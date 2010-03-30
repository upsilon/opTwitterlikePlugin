<?php

class opTwitterlikePluginFrontendRouteCollection extends sfRouteCollection
{
  public function __construct(array $options)
  {
    parent::__construct($options);

    $this->routes = array(
      'twitter_post' => new sfRequestRoute(
        '/twitter/post',
        array('module' => 'twitter', 'action' => 'post'),
        array('sf_method' => array('post'))
      ),
      // no default
      'twitter_nodefaults' => new sfRoute(
        '/twitter/*',
        array('module' => 'default', 'action' => 'error')
      ),
    );
  } 
}
