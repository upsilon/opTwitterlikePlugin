<?php

class opValidatorTweet extends opValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('shortenUrl', true);
  }

  protected function doClean($value)
  {
    if ($this->getOption('shortenUrl'))
    {
      $value = $this->shortenText($value);
    }

    return parent::doClean($value);
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

