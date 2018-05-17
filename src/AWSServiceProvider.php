<?php

namespace Rayjun\AWS;

use Illuminate\Support\ServiceProvider;

class AWSServiceProvider extends ServiceProvider{

    public function boot() {
        $this->publishes([
            __DIR__ . '/config/aws.php' => config_path('aws.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/aws.php', 'aws'
        );
    }
}