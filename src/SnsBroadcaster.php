<?php

namespace Ringtrail\LaravelSns;

use Aws\Sns\SnsClient;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Contracts\Config\Repository;

class SnsBroadcaster implements Broadcaster
{
    /**
     * @var SnsClient
     */
    protected $client;

    /**
     * @var Repository
     */
    protected $config;

    public function __construct(
        SnsClient $client,
        Repository $config
    ) {
        $this->client = $client;
        $this->config = $config;
    }

    public function broadcast(array $channels, $event, array $payload = [])
    {
        $payload['tenant'] = $this->config->get('lapis.tenant', '');

        foreach ($channels as $channel) {
            $this
                ->client
                ->publish([
                    'TopicArn' => $channel,
                    'Message' => json_encode($payload),
                    'Subject' => $event,
                ]
            );
        }
    }
}
