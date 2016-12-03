<?php

class StorageServiceProvider extends  Illuminate\Support\ServiceProvider {

    public function register()
    {
        $this->app->singleton('storageService', function()
        {
            return new \StorageService;
        });
    }

    public function setSettings(
        \StorageService $storageService
      ) {
      dd($storageService);
    }

}