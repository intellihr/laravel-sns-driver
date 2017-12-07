<?php

namespace Ringtrail\LaravelSns;

use Aws\Sns\SnsClient;
use Illuminate\Broadcasting\BroadcastManager;
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
                    return new SnsBroadcaster(
                        SnsClient::factory(
                            [
                                'credentials' => [
                                    'key' => $config['aws_key'],
                                    'secret' => $config['aws_secret'],
                                ],
                                'version' => 'latest',
                                'region' => $config['aws_region']
                            ]
                        )
                    );
                }
            );
    }

    public function register()
    {
    }
}
