<?php

class opTwitterlikePluginActions extends sfActions
{
  public function executePost(sfWebRequest $request)
  {
    $form = new opTweetForm();
    $form->bind($request->getParameter('tweet'));
    if ($form->isValid())
    {
      $activity = $form->save();
      if ($form->getValue('twitter'))
      {
        $request->setParameter('id', $activity->id);
        $this->forward('twitter', 'login');
      }
    }
    $this->redirect('@homepage');
  }

  public function executeLogin(sfWebRequest $request)
  {
    $user = $this->getUser();
    $state = $user->getAttribute('twitter_oauth_state', 0);

    if (1 === $state)
    {
      $token = $request->getParameter('oauth_token');
      if (isset($token) || $token !== $user->getAttribute('twitter_oauth_token'))
      {
        $user->setAttribute('twitter_oauth_state', 0);
      }
    }

    try
    {
      $consumerKey = sfConfig::get('app_twitter_consumer_key');
      $consumerSecret = sfConfig::get('app_twitter_consumer_secret');

      if (0 === $state)
      {
        $oauth = new TwitterOAuth($consumerKey, $consumerSecret);
        
        $activityId = $request->getParameter('id');
        $callbackUrl = $this->getController()->genUrl('@twitter_login', true).'?id='.$activityId;

        $requestTokenInfo = $oauth->getRequestToken($callbackUrl);        
        $user->setAttribute('twitter_oauth_token', $requestTokenInfo['oauth_token']);
        $user->setAttribute('twitter_oauth_secret', $requestTokenInfo['oauth_token_secret']);
        $user->setAttribute('twitter_oauth_state', 1);

        $redirectUrl = $oauth->getAuthorizeURL($requestTokenInfo['oauth_token']);

        $this->redirect($redirectUrl);
      }
      elseif (1 === $state)
      {
        $token = $request->getParameter('oauth_token');
        $secret = $user->getAttribute('twitter_oauth_secret');
        $oauth = new TwitterOAuth($consumerKey, $consumerSecret, $token, $secret);

        $accessTokenInfo = $oauth->getAccessToken($request->getParameter('oauth_verifier'));

        $user->setAttribute('twitter_oauth_state', 2);
        $user->setAttribute('twitter_oauth_token', $accessTokenInfo['oauth_token']);
        $user->setAttribute('twitter_oauth_secret', $accessTokenInfo['oauth_token_secret']);

        $this->forward('twitter', 'twitterPost');
      }
      elseif (2 === $state)
      {
        $this->forward('twitter', 'twitterPost');
      }
    }
    catch (OAuthException $e)
    {
      $user->setAttribute('twitter_oauth_state', 0);
      throw $e;
      //return sfView::ERROR;
    }
  }

  public function executeTwitterPost(sfWebRequest $request)
  {
    $activityId = $request->getParameter('id');
    $activity = Doctrine::getTable('ActivityData')->findOneById($activityId);

    $user = $this->getUser();
    $state = $user->getAttribute('twitter_oauth_state', 0);

    if (2 !== $state)
    {
      $this->forward('twitter', 'login');
    }

    try
    {
      $consumerKey = sfConfig::get('app_twitter_consumer_key');
      $consumerSecret = sfConfig::get('app_twitter_consumer_secret');
      $token = $user->getAttribute('twitter_oauth_token');
      $secret = $user->getAttribute('twitter_oauth_secret');

      $oauth = new TwitterOAuth($consumerKey, $consumerSecret, $token, $secret);

      $oauth->oAuthRequest('statuses/update', 'POST', array('status' => $activity->body));

      $this->redirect('@homepage');
    }
    catch (OAuthException $e)
    {
      $user->setAttribute('twitter_oauth_state', 0);
      throw $e;
      //$this->forward('twitter', 'login');
    }
  }
}
