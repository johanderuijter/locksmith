<?php declare(strict_types = 1);

namespace JDR\Locksmith\OpenSSL;

use RuntimeException;
use JDR\Locksmith\KeyGenerator;
use JDR\Locksmith\KeyPair;

class RSAKeyGenerator implements KeyGenerator
{
    /**
     * Generate a KeyPair.
     *
     * @param int $size
     * @param string|null $passphrase
     *
     * @return KeyPair
     *
     * @throws RuntimeException When the key could not be generated.
     */
    public function generate(int $size, string $passphrase = null) : KeyPair
    {
        // When the passphrase is an empty string, the key won't work. Use null instead.
        if ($passphrase === '') {
            $passphrase = null;
        }

        // Create the keypair
        $resource = openssl_pkey_new([
            'private_key_bits' => $size,
        ]);

        if (!openssl_pkey_export($resource, $privatekey, $passphrase)) {
            throw new RuntimeException("OpenSSL failed to generate key. Please try again.");
        }

        $private = new RSAPrivateKey($privatekey, (string) $passphrase);

        return new KeyPair($private, $private->getPublicKey());
    }
}
