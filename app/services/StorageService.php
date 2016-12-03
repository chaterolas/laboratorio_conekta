<?php

/**
 *
 */
class StorageService {

  /**
   * Guarda el objeto de datos $data en memoria asociandolo al
   * usuario $user
   */
  public function store( /*stdClass*/ $data, User $user) {
    $redis = Redis::connection();

    // Uses bcrypt by default
    $identifier = Hash::make($user->email);

    // Uses  AES encryption via the mcrypt PHP
    $data  = Crypt::encrypt($data);

    // Reading timeout from configurations
    $timeout = Config::get('storage.sec_time_out');

    // Writes on redis
    $redis->set($identifier , $data);
    $redis->expire($identifier, $timeout);

    return $identifier;
  }

  /**
   * Función para obtener la información
   */
  public function get(/* String */ $identifier, User $user) {
    $data  = $this->getPlain($identifier, $user);
    return $data ? Crypt::decrypt($data) : null;
  }

  /*
   * Función auxiliar que ayuda a probar algunos features deseados
   * dle código
   */
  public function getPlain(/* String */ $identifier, User $user) {
    $redis = Redis::connection();

    if( !Hash::check($user->email, $identifier) ) {
      return null;
    }

    $data_found = false;
    if( ($data = $redis->get($identifier)) ) {
      $data_found = true;
    }

    return $data_found ? $data : null;
  }
}