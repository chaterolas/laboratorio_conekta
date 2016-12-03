<?php

/*
 | Controlador par amanejar la información de manera persistente
 */
class ApiStorageController extends BaseController {

  public function __construct(StorageService $storage_service) {
    $this->storage_service = $storage_service;

    /* make sure the user is autheticated prior to use any functionality*/
    $this->beforeFilter('auth');
  }

  public function store() {
    $user = Auth::user();

    $found = false;
    if( ($identifier = $this->storage_service->store($source_data, $user)) ) {
      $found = true;
    }

    return Response::json([
          'found' => $found,
          'identifier' => $identifier
      ]);
  }

  public function get() {
    //$user = 
  }

}