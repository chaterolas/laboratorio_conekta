<?php

class RequestStorageTest extends TestCase {

  /**
   * Prueba que la ruta /api/v1/storage esté disponible  bajo el método GET
   *
   * @return void
   */
  public function testAvailableURL()
  {
    //$crawler = $this->client->request('GET', '/api/v1/storage');
    //$this->assertTrue($this->client->getResponse()->isOk());

    $this->call('GET', '/api/v1/storage');
    $this->assertResponseOk();
  }

}
