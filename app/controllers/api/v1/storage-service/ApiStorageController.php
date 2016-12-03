<?php

/*
 | Controlador par amanejar la información de manera persistente
 */
class ApiStorageController extends BaseController {

  public function __construct(StorageService $storage_service) {
    $this->storage_service = $storage_service;
  }

  public function store() {

    $data = "HW";
    $user = "USER";

    $found = false;

    if( ($data = $this->storage_service->store($data, $user)) ) {
      $found = true;
    }

    return json_encode([
          'found' => $found,
          'data' => $data
      ]);
  }

}