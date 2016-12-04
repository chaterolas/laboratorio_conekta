<?php


class ApiStorageControllerTest extends \TestCase {

  /**
   *
   */
  public function setUp() {
    parent::setUp();

    $this->data = "Hola Mundo!";

    $this->user = new User;
    $this->user->email = "alain.chevanier@gmail.com";

    $this->identifier = "IDENTIFIER";

    // Simulates login
    $this->be($this->user);

    // Mocking storage facade
    Storage::shouldReceive('store')->once()
      ->with($this->data, $this->user)
      ->andReturn($this->identifier);

    Storage::shouldReceive('get')->once()
      ->with($this->identifier, $this->user)
      ->andReturn($this->data);
  }


  /**
   * Functional test for STORE ACTION
   *
   * @test
   */
  public function testStore() {
    $response = $this->action('POST', 'ApiStorageController@store', 
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
        ['identifier' => $this->identifier]);
    
    $json_data = $response->getContent();
    $this->assertNotNull($json_data);

    $decoded = json_decode($json_data);

    $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    $this->assertTrue($decoded->success);
    $this->assertEquals($this->data, $decoded->data);
  }
}
