<?php

namespace Test;

/**
 * Class RegistrationTest
 */
class RegistrationTest extends \UnitTestCase
{
    public function testAjaxRegistrationCase()
    {
      $mockData = new \stdClass();
      $mockData->first_name = 'Sean';
      $mockData->last_name = 'Jorgensen';
      $mockData->email_address = 'jorgensean@gmail.com';
      $mockData->phone = '8880008080';
      $mockData->address = '1555 Boring St.';
      $mockData->square_footage = '1444';

      $response = new \Phalcon\Http\Response();
      $response->setHeader('Content-Type', 'application/json');
      $this->getDI()->set('response', $mockData);

      $this->assertEquals(
        "200",
        "200",
        "Lead Registration works!"
      );
  }

}
