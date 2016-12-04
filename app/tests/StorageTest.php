<?php

class StorageTest extends \TestCase {

  public function setUp() {
    parent::setUp();

    $this->data = new stdClass();
    $this->data->sensitive_information = "Hola Mundo!";

    $this->user = new User;
    $this->user->email = "alain.chevanier@gmail.com";

    $this->timeout = Config::get('storage.sec_time_out');

    $this->robustness_size =  Config::get('storage.robustness_size');

    // Mocking Redis facade
  }

  /**
   * Functional test for STORE
   * @test
   */
  public function testStore()
  {
    // Binding data 
    $data = $this->data;
    $user = $this->user;

    $identifier = Storage::store($data, $user);
    $this->assertNotNull($identifier);

    return $identifier;
  }

  /**
   * Funcitonal test for GET. Data must not be saved plain
   * @test
   * @depends testStore
   */
  public  function testGetNotPlain($identifier) {
    $user = $this->user;

    $data = Storage::getPlain($identifier, $user);
    $this->assertNotEquals($this->data, $data);
  }

  /**
   * Functional test for GET
   * @test
   * @depends testStore
   */
  public function testGet($identifier) {
    $user = $this->user;

    $data = Storage::get($identifier, $user);
    $this->assertEquals($this->data, $data);
  }

  /**
   * ErrorCondition test for GET. Valid Identifier, Not same user.
   * @test
   * @depends testStore
   */
  public function testGetDifferentUser($identifier) {
    $user = new User;
    $user->email = 'alain.chevanier_2@gmail.com';
    $data = Storage::get($identifier, $user);
    $this->assertNull($data);
  }

  /**
   * ErrorCondition test for GET. Invalid Identifier, Same user.
   * @test
   * @depends testStore
   */
  public function testGetInvalidIdendifier($identifier) {
    $user = $this->user;
    $identifier = str_random( count($identifier) );
    $data = Storage::get($identifier, $user);
    $this->assertNull($data);
  }

  /**
   * Functional test for STORE timeout
   * @test
   * @depends testStore
   */
  public function testStoreTimeout($identifier) {
    $user = $this->user;
    sleep($this->timeout);
    $data = Storage::get($identifier, $user);
    $this->assertNull($data);
  }

  /**
   * Robustness test. Create several users with data and every thing should works
   * @test
   */
  public function testRobustness() {
    $test_users = [];
    $test_data = [];

    for($i=0; $i<$this->robustness_size; $i++) {
      $test_users[$i] = new User;
      $test_users[$i]->email = str_random(20)."@".str_random(20).".com";

      $test_data[$i] = new stdClass;
      $test_data[$i]->data = str_random(200);
      $test_data[$i]->identifier = 
        Storage::store($test_data[$i]->data, $test_users[$i]);

      $data = Storage::get($test_data[$i]->identifier, $test_users[$i]);
      $this->assertEquals($test_data[$i]->data, $data);
    }

    sleep($this->timeout);

    for($i=0; $i<$this->robustness_size; $i++) {
      $data = Storage::get($test_data[$i]->identifier, $test_users[$i]);
      $this->assertNull($data);
    }
  }
}
