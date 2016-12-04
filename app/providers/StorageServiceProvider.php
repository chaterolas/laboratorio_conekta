<?php

class StorageServiceProvider extends Illuminate\Support\ServiceProvider {

    public function register()
    {
        $this->app->singleton('StorageService', function()
        {
            return new Services\StorageService;
        });
    }

    public function setSettings(
        Services\StorageService $storageService
      ) {
      dd($storageService);
    }

}