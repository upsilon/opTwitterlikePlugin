<?php

class opTwitterComponents extends sfComponents
{
  public function executeTwitter()
  {
    $this->activities = Doctrine::getTable('ActivityData')
      ->getFriendActivityListPager(null)
      ->getQuery()
      ->andWhere('source = \'opTwitterlikePlugin\'')
      ->execute();
    $this->form = new opTweetForm();
  }
}
