<?php

/*
 | Controlador para manejar las peticiones guardar y traer información
 | desde y hacia la base de datos que vive en memoria
 */
class ApiStorageController extends BaseController {

  public function __construct() {
    
  }

  /**
   * Guarda la información contenida en source_data.
   */
  public function store() {
    $source_data = Input::get('source_data');

    try {
      $user = Auth::user();

      $success = false;
      if( ($identifier = Storage::store($source_data, $user)) ) {
        $success = true;
      }

      $response = [ 
        'success' => $success, 
        'identifier' => $identifier
      ];
      $status = 200;
    }
    catch(Exception $e) {
      $status = 500;
      $response = [
          'success' => false,
          'message' => 'An error ocurred.',
          'trace' => $e->getMessage()
        ];
    }

    return Response::json($response, $status);
  }

  /**
   * Regresa la información asociada al identificador
   */
  public function get($identifier) {

    try {
      $user = Auth::user();

      $success = false;
      if( ($data = Storage::get($identifier, $user)) ) {
        $success = true;
      }

      $response = [ 
        'success' => $success, 
        'data' => $data
      ];
      $status = 200;
    }
    catch(Exception $e) {
      $status = 500;
      $response = [
          'success' => false,
          'message' => 'An error ocurred.',
          'trace' => $e->getMessage()
        ];
    }
   

    return Response::json($response, $status);
  }
}