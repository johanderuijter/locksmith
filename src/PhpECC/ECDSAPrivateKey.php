<?php declare(strict_types = 1);

namespace JDR\Locksmith\PhpECC;

use Exception;
use InvalidArgumentException;
use JDR\Locksmith\ECDSA;
use JDR\Locksmith\PrivateKey;
use JDR\Locksmith\PublicKey;
use Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\DerPublicKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\PemPublicKeySerializer;

class ECDSAPrivateKey implements PrivateKey, ECDSA
{
    /**
     * Instantiate a new ECDSA Private Key.
     *
     * @param string $content
     *
     * @throws InvalidArgumentException When the key is invalid.
     */
    public function __construct(string $content)
    {
        $this->validateKey($content);
        $this->content = $content;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get passphrase.
     *
     * @return string
     */
    public function getPassphrase(): string
    {
        throw new Exception('ECDSA keys do not have a passphrase.');
    }

    /**
     * Get public key.
     *
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey
    {
        $privateKeySerializer = new PemPrivateKeySerializer(new DerPrivateKeySerializer());
        $privateEccKey = $privateKeySerializer->parse($this->content);

        $publicKeySerializer = new PemPublicKeySerializer(new DerPublicKeySerializer());
        $publicKey = $publicKeySerializer->serialize($privateEccKey->getPublicKey());

        return new ECDSAPublicKey($publicKey);
    }

    /**
     * Validate the key.
     *
     * @param string $content
     *
     * @throws InvalidArgumentException When the key is invalid.
     */
    private function validateKey(string $content)
    {
        if (!$this->isValidKey($content)) {
            throw new InvalidArgumentException('Private key could not be created: Key is invalid.');
        }
    }

    /**
     * Check whether or not the key is valid.
     *
     * @param string $content
     *
     * @return bool
     */
    private function isValidKey(string $content): bool
    {
        try {
            $privateKeySerializer = new PemPrivateKeySerializer(new DerPrivateKeySerializer());
            $privateKeySerializer->parse($content);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
