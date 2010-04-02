<?php

class opTweetForm extends sfForm
{
  public function configure()
  {
    $this->setWidget('body', new sfWidgetFormTextarea());
    $this->setValidator('body', new opValidatorString(array('max_length' => 140, 'required' => true, 'trim' => true)));

    $this->setWidget('twitter', new sfWidgetFormInputCheckbox());
    $this->setValidator('twitter', new sfValidatorBoolean());
    $this->widgetSchema->setLabel('twitter', 'Twitterにも送信する');

    $this->widgetSchema->setNameFormat('tweet[%s]');
  }

  public function save()
  {
    $memberId = sfContext::getInstance()->getUser()->getMemberId();
    $body = $this->getValue('body');

    return Doctrine::getTable('ActivityData')->updateActivity($memberId, $body, array('source' => 'opTwitterlikePlugin'));
  }
}
