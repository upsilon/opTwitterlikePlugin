<?php

class opTwitterComponents extends sfComponents
{
  public function executeTwitter()
  {
    $this->activities = Doctrine::getTable('ActivityData')
      ->getFriendActivityListPager(null)
      ->getQuery()
      ->andWhere('source = \'opTwitterlikePlugin\'')
//      ->limit($this->gadget->getConfig('row'))
      ->execute();
    $this->form = new opTweetForm();
  }
}
