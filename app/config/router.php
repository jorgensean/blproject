<?php

$router = $di->getRouter();

// Define your routes here
$router->add("/success", array(
  'controller' => 'index',
  'action' => 'success',
));
$router->handle();
