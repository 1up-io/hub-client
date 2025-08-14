<?php

declare(strict_types=1);

namespace App\Network;

use phpseclib3\Crypt\PublicKeyLoader;

class AuthentificationFactory
{
    public function getHeaders(): array
    {
        $key = $this->getKeyLocation();

        $message = $this->createMessage();
        $signature = $this->getSignature($key, $message);
        $fingerprint = $this->getFingerPrint($key);

        $legacyKey = $_ENV['HUB_CLIENT_LEGACY_AUTHENTICATION_KEY'];

        $headers = [
            'Message' => $message,
            'Fingerprint' => $fingerprint,
            'Signature' => $signature,
        ];

        if ($legacyKey) {
            $headers['Authorization'] = $legacyKey;
        }

        return $headers;
    }

    private function getFingerprint(string $keyLocation): string
    {
        $publicKey = $this->getPublicKey($keyLocation);
        $key = explode(' ', $publicKey)[1];

        return hash('md5', base64_decode($key));
    }

    private function getSignature(string $keyLocation, string $message): string
    {
        $key = PublicKeyLoader::loadPrivateKey($this->getPrivateKey($keyLocation));

        return base64_encode($key->sign($message));
    }

    private function getPublicKey(string $keyLocation): string
    {
        return (string) file_get_contents($keyLocation . '.pub');
    }

    private function getPrivateKey(string $keyLocation): string
    {
        return (string) file_get_contents($keyLocation);
    }

    private function getKeyLocation(): string
    {
        $explicitKey = $_ENV['HUB_CLIENT_SIGNING_KEY'];

        if ($explicitKey) {
            return $explicitKey;
        }

        $home = getenv('HOME');

        $keyLocations = [
            $home . '/.ssh/id_ed25519',
            $home . '/.ssh/id_rsa',
        ];

        foreach ($keyLocations as $keyLocation) {
            if (file_exists($keyLocation) && is_readable($keyLocation)) {
                return $keyLocation;
            }
        }

        throw new \RuntimeException('Unable to locate key location');
    }

    private function createMessage(): string
    {
        return sprintf('%d / %s / HubClient', time(), bin2hex(random_bytes(16)));
    }
}
