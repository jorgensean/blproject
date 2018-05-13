<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
      $this->assets->addCss("css/registration.css");
    }

    public function successAction()
    {
      $this->assets->addCss("css/success.css");
    }

}

