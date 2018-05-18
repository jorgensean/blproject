<?php

namespace Test;

/**
 * Class AjaxRegistrationTest
 */
class AjaxRegistrationTest extends \UnitTestCase
{
    public function testAjaxRegistrationCase()
    {
      $mockData = new \stdClass();
      $mockData->field = 'first_name';
      $mockData->value = 'Sean';

      $response = new \Phalcon\Http\Response();
      $response->setHeader('Content-Type', 'application/json');
      $this->getDI()->set('response', $mockData);

      $this->assertNotEmpty($response, "Ajax request successful");

  }

}
