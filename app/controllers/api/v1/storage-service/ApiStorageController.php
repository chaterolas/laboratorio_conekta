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
    $user = Auth::user();

    $source_data = Input::get('source_data');

    $success = false;
    if( ($identifier = Storage::store($source_data, $user)) ) {
      $success = true;
    }

    return Response::json([
          'success' => $success,
          'identifier' => $identifier
      ]);
  }

  /**
   * Regresa la información asociada al identificador
   */
  public function get($identifier) {
    $user = Auth::user();

    $success = false;
    if( ($data = Storage::get($identifier, $user)) ) {
      $success = true;
    }

    return Response::json([
          'success' => $success,
          'data' => $data
      ]);
  }
}