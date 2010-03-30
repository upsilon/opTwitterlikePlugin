<?php

class opTwitterlikePluginActions extends sfActions
{
  public function executePost(sfWebRequest $request)
  {
    $form = new opTweetForm();
    $form->bind($request->getParameter('tweet'));
    if ($form->isValid())
    {
      $form->save();
    }
    $this->redirect('@homepage');
  }
}
