<?php declare(strict_types = 1);

namespace JDR\Locksmith\PhpECC;

use Exception;
use InvalidArgumentException;
use JDR\Locksmith\ECDSA;
use JDR\Locksmith\PublicKey;
use Mdanter\Ecc\Serializer\PublicKey\DerPublicKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\PemPublicKeySerializer;

class ECDSAPublicKey implements PublicKey, ECDSA
{
    /**
     * Instantiate a new ECDSA Public Key.
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
     * Validate the key.
     *
     * @param string $content
     *
     * @throws InvalidArgumentException When the key is invalid.
     */
    private function validateKey(string $content)
    {
        if (!$this->isValidKey($content)) {
            throw new InvalidArgumentException('Public key could not be created: Key is invalid.');
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
            $publicKeySerializer = new PemPublicKeySerializer(new DerPublicKeySerializer());
            $publicKeySerializer->parse($content);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
