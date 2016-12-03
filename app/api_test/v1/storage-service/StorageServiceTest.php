<?php

class StorageServiceTest extends TestCase {

  //private $storage_service;

  public function __contruct(StorageService $storage_service) {
    $this->storage_service = $storage_service;
  }

  /**
   * A basic functional test example.
   *
   * @return void
   */
  public function testStore()
  {
    // Binding data 
    $data = "HWD!";
    $user = "HWY!";

    $this->assertEquals("Hello World!", $this->storage_service($data, $user));
  }

}
