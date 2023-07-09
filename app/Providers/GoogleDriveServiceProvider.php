<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('google', function ($app, $config) {
            $client = new \Google_Client();
            $client->setClientId($config['414479438284-vp4pct4n2lpjcnrdbvssa3s527ldg0po.apps.googleusercontent.com']);
            $client->setClientSecret($config['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']);
            $client->refreshToken($config['1//04U5vJALoQQuUCgYIARAAGAQSNwF-L9IrAe4aNKsF__Ri02ykxvA8MfSWOVARH8VXZwYCjExcyn-23nIIG5joWd2c8L8BUwQli5U']);
            $service = new \Google_Service_Drive($client);
            $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $config['1OPK18VNGeoO_TxpGlmqAEIAamFm_Rej3']);

            return new \League\Flysystem\Filesystem($adapter);
        });
    }
}
