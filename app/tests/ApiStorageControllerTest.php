<?php


class ApiStorageControllerTest extends \TestCase {

  protected static $storage_service;

  /**
   * Inject dependency from application context.
   * TODO: MOCKUP StorageService
   */ 
  public static function setUpBeforeClass() {
    //self::$storage_service = App::make('StorageService');
  }

  /**
   *
   */
  public function setUp() {
    parent::setUp();

    $this->data = "Hola Mundo!";

    $this->user = new User;
    $this->user->email = "alain.chevanier@gmail.com";

    $this->be($this->user);
  }


  /**
   * Functional test for STORE ACTION
   *
   * @test
   */
  public function testStore() {
    $response = $this->action('GET', 'ApiStorageController@store', 
        ['source_data' => $this->data]);

    $json_data = $response->getContent();
    $this->assertNotNull($json_data);

    $decoded = json_decode($json_data);

    $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    $this->assertTrue($decoded->success);
    $this->assertNotNull($decoded->identifier);
    
    return $decoded->identifier;
  }

  /**
   * Functional test for GET ACTION
   *
   * @test
   * @depends testStore
   */
  public function testGet($identifier) {
    $response = $this->action('GET', 'ApiStorageController@get', 
        ['identifier' => $identifier]);
    
    $json_data = $response->getContent();
    $this->assertNotNull($json_data);

    $decoded = json_decode($json_data);

    $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    $this->assertTrue($decoded->success);
    $this->assertEquals($this->data, $decoded->data);
  }
}
