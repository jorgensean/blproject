<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
      $random = new \Phalcon\Security\Random();

      $this->session->set("session-id", $random->uuid());
      $this->assets->addCss("css/registration.css");
      $this->assets->addJs("js/jquery.js");
      $this->assets->addJs("js/form.js");
    }

    public function successAction()
    {
      $this->assets->addCss("css/success.css");
    }

}

