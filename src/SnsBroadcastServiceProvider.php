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
                    $parameters = [
                        'version' => 'latest',
                        'region' => $config['aws_region'],
                    ];

                    if ($this->hasAWSEnvConfigured() === false) {
                        $parameters['credentials'] = [
                            'key' => $config['aws_key'],
                            'secret' => $config['aws_secret'],
                        ];
                    }

                    return new SnsBroadcaster(SnsClient::factory($parameters));
                }
            );
    }

    public function register()
    {
    }

    private function hasAWSEnvConfigured()
    {
        if (getenv('AWS_ACCESS_KEY_ID') !== false && getenv('AWS_SECRET_ACCESS_KEY') !== false) {
            return true;
        }

        return getenv('AWS_SESSION_TOKEN') !== false;
    }
}
