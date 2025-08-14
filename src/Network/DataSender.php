<?php

declare(strict_types=1);

namespace App\Network;

use Symfony\Component\HttpClient\HttpClient;

class DataSender
{
    public function __construct(
        private readonly AuthentificationFactory $authentificationFactory,
    ) {
    }

    public function send(array $data): void
    {
        $endpoint = $_ENV['HUB_CLIENT_ENDPOINT'];

        $client = HttpClient::create();
        $client->request('POST', $endpoint, [
            'headers' => $this->authentificationFactory->getHeaders(),
            'json' => $data,
        ]);
    }
}
