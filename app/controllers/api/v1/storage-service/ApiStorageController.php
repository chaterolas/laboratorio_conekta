<?php

/*
 | Controlador par amanejar la información de manera persistente
 */
class ApiStorageController extends BaseController {

  public function __construct(StorageService $storage_service) {
    $this->storage_service = $storage_service;
  }

  /**
   * Prueba para ejecutar 
   */
  public function store() {
    $user = Auth::user();

    $source_data = Input::get('source_data');

    $success = false;
    if( ($identifier = $this->storage_service->store($source_data, $user)) ) {
      $success = true;
    }

    return Response::json([
          'success' => $success,
          'identifier' => $identifier
      ]);
  }

  /**
   * 
   */
  public function get() {
    $user = Auth::user();

    $identifier = Input::get('identifier');

    $success = false;
    if( ($data = $this->storage_service->get($identifier, $user)) ) {
      $success = true;
    }

    return Response::json([
          'success' => $success,
          'data' => $data
      ]);
  }
}