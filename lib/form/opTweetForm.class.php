<?php

class opTweetForm extends sfForm
{
  public function configure()
  {
    $this->setWidget('body', new sfWidgetFormTextarea());
    $this->setValidator('body', new opValidatorString(array('required' => true, 'trim' => true)));

    $this->setWidget('twitter', new sfWidgetFormInputCheckbox());
    $this->setValidator('twitter', new sfValidatorBoolean());
    $this->widgetSchema->setLabel('twitter', 'Twitterにも送信する');

    $this->widgetSchema->setNameFormat('tweet[%s]');
  }

  public function save()
  {
    $memberId = sfContext::getInstance()->getUser()->getMemberId();
    $body = $this->getValue('body');
    $body = $this->shortenText($body);

    return Doctrine::getTable('ActivityData')->updateActivity($memberId, $body, array('source' => 'opTwitterlikePlugin'));
  }

  protected function shortenText($text)
  {
    return preg_replace_callback(
      '|https?:\/\/[0-9a-zA-Z\-_.!~\*\'()@\?\+,\$\/#%;:&=]{14,}|',
      array($this, 'shortenUrl'),
      $text
    );
  }

  protected function shortenUrl($matches)
  {
    $longUrl = $matches[0];
    $url = 'http://api.bit.ly/v3/shorten'
         . '?login=upsilon'
         . '&apiKey=R_e227b5e19ad3a778f88902763da0d344'
         . '&uri='.urlencode($longUrl)
         . '&format=txt';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $shortUrl = curl_exec($curl);
    curl_close($curl);

    return trim($shortUrl);
  }
}
