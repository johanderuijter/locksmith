<?php declare(strict_types = 1);

namespace JDR\Locksmith;

use InvalidArgumentException;

class KeyPair
{
    /**
     * @var PrivateKey
     */
    private $private;

    /**
     * @var PublicKey
     */
    private $public;

    /**
     * Instantiate a new KeyPair from a private and public key.
     *
     * @param PrivateKey $private
     * @param PublicKey $public
     *
     * @throws InvalidArgumentException When private and public key do not match.
     */
    public function __construct(PrivateKey $private, PublicKey $public = null)
    {
        $this->validateKeyPair($private, $public);
        $this->private = $private;
        $this->public = $public;
    }

    /**
     * Get the private key.
     *
     * @return PrivateKey
     */
    public function getPrivateKey(): PrivateKey
    {
        return $this->private;
    }

    /**
     * Get the public key.
     *
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey
    {
        if ($this->public === null) {
            $this->public = $this->private->getPublicKey();
        }

        return $this->public;
    }

    /**
     * Validate the Key Pair.
     *
     * @param PrivateKey $private
     * @param PublicKey $public
     *
     * @throws InvalidArgumentException When private and public key do not match.
     */
    private function validateKeyPair(PrivateKey $private, PublicKey $public = null)
    {
        if (!$this->isValidKeyPair($private, $public)) {
            throw new InvalidArgumentException('KeyPair could not be created: private and public key do not match.');
        }
    }

    /**
     * Check whether the $private key matches the public key.
     *
     * @param PrivateKey $private
     * @param PublicKey $public
     *
     * @return bool
     */
    private function isValidKeyPair(PrivateKey $private, PublicKey $public = null): bool
    {
        // If no public key was supplied, the public key will be extracted from the private key.
        if ($public === null) {
            return true;
        }

        // If the public key extracted from the private key matches the supplied public key, the pair must be valid.
        if ($private->getPublicKey() == $public) {
            return true;
        }

        return false;
    }
}
