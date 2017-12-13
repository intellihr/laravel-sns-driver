<?php

namespace Ringtrail\LaravelSns;

use Aws\Sns\SnsClient;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;

class SnsBroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this
            ->app
            ->make(BroadcastManager::class)
            ->extend(
                'sns',
                function ($app, $config) {
                    $parameters = [
                        'version' => 'latest',
                        'region' => $config['region'],
                    ];

                    if ($this->hasKeyAndSecret($config)) {
                        $parameters['credentials'] = [
                            'key' => $config['key'],
                            'secret' => $config['secret'],
                        ];
                    }

                    $config = $this->app->make(Repository::class);

                    return new SnsBroadcaster(
                        SnsClient::factory($parameters),
                        $config
                    );
                }
            );
    }

    public function register()
    {
    }

    private function hasKeyAndSecret(array $config)
    {
        return array_key_exists('key', $config) && array_key_exists('secret', $config);
    }
}
