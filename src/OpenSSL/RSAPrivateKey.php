<?php declare(strict_types = 1);

namespace JDR\Locksmith\OpenSSL;

use InvalidArgumentException;
use JDR\Locksmith\PrivateKey;
use JDR\Locksmith\PublicKey;
use JDR\Locksmith\RSA;

class RSAPrivateKey implements PrivateKey, RSA
{
    /**
     * Instantiate a new RSA Private Key.
     *
     * @param string $content
     * @param string $passphrase
     *
     * @throws InvalidArgumentException When the key is invalid.
     */
    public function __construct(string $content, string $passphrase)
    {
        $this->validateKey($content, $passphrase);
        $this->content = $content;
        $this->passphrase = $passphrase;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * Get passphrase.
     *
     * @return string
     */
    public function getPassphrase() : string
    {
        return $this->passphrase;
    }

    /**
     * Get public key.
     *
     * @return PublicKey
     */
    public function getPublicKey() : PublicKey
    {
        $resource = openssl_pkey_get_private($this->content, $this->passphrase);
        $details = openssl_pkey_get_details($resource);

        return new RSAPublicKey($details['key']);
    }

    /**
     * Validate the key.
     *
     * @param string $content
     * @param string $passphrase
     *
     * @throws InvalidArgumentException When the key is invalid.
     */
    private function validateKey(string $content, string $passphrase)
    {
        if (!$this->isValidKey($content, $passphrase)) {
            throw new InvalidArgumentException('Private key could not be created: Key is invalid.');
        }
    }

    /**
     * Check whether or not the key is valid.
     *
     * @param string $content
     * @param string $passphrase
     *
     * @return bool
     */
    private function isValidKey(string $content, string $passphrase) : bool
    {
        return openssl_pkey_get_private($content, $passphrase) !== false;
    }
}
