<?php declare(strict_types = 1);

namespace JDR\Locksmith;

use InvalidArgumentException;

class KeyRing
{
    /**
     * @var KeyPair[]
     */
    private $keyPairs = [];

    /**
     * @var PublicKey[]
     */
    private $publicKeys = [];

    /**
     * Constructor.
     *
     * @param KeyPair[] $keyPairs
     * @param PublicKey[] $publicKeys
     */
    public function __construct(array $keyPairs = [], array $publicKeys = [])
    {
        foreach ($keyPairs as $identifier => $keyPair) {
            $this->addKeyPair($identifier, $keyPair);
        }

        foreach ($publicKeys as $identifier => $publicKey) {
            $this->addPublicKey($identifier, $publicKey);
        }
    }

    public function addKeyPair(string $identifier, KeyPair $keyPair)
    {
        if (array_key_exists($identifier, $this->keyPairs)) {
            throw new InvalidArgumentException(sprintf(
                'A key pair with identifier "%s" already exists. Please use a unique identifier.',
                $identifier
            ));
        }

        if (array_key_exists($identifier, $this->publicKeys) && $this->getPublicKey($identifier) !== $keyPair->getPublicKey()) {
            throw new InvalidArgumentException(sprintf(
                'A public key with identifier "%s" already exists and does not match. Please use a unique identifier.',
                $identifier
            ));
        }

        $this->keyPairs[$identifier] = $keyPair;
        if (array_key_exists($identifier, $this->publicKeys)) {
            unset($this->publicKeys[$identifier]);
        }
    }

    public function addPublicKey(string $identifier, PublicKey $publicKey)
    {
        if (array_key_exists($identifier, $this->keyPairs)) {
            throw new InvalidArgumentException(sprintf(
                'A key pair with identifier "%s" already exists. Please use a unique identifier.',
                $identifier
            ));
        }

        if (array_key_exists($identifier, $this->publicKeys)) {
            throw new InvalidArgumentException(sprintf(
                'A public key with identifier "%s" already exists. Please use a unique identifier.',
                $identifier
            ));
        }

        $this->publicKeys[$identifier] = $publicKey;
    }

    public function getKeyPair(string $identifier): KeyPair
    {
        if (!array_key_exists($identifier, $this->keyPairs)) {
            throw new InvalidArgumentException(sprintf(
                'KeyPair "%s" not found. The following keys are available: %s',
                $identifier,
                implode(array_keys($this->keyPairs), ', ')
            ));
        }

        return $this->keyPairs[$identifier];
    }

    public function getPrivateKey(string $identifier): PrivateKey
    {
        if (!array_key_exists($identifier, $this->keyPairs)) {
            throw new InvalidArgumentException(sprintf(
                'PrivateKey "%s" not found. The following keys are available: %s',
                $identifier,
                implode(array_keys($this->keyPairs), ', ')
            ));
        }

        return $this->keyPairs[$identifier]->getPrivateKey();
    }

    public function getPublicKey(string $identifier): PublicKey
    {
        if (!array_key_exists($identifier, $this->publicKeys) && !array_key_exists($identifier, $this->keyPairs)) {
            throw new InvalidArgumentException(sprintf(
                'PublicKey "%s" not found. The following keys are available: %s',
                $identifier,
                implode(array_merge(array_keys($this->publicKeys), array_keys($this->keyPairs)), ', ')
            ));
        }

        if (array_key_exists($identifier, $this->publicKeys)) {
            return $this->publicKeys[$identifier];
        }

        return $this->keyPairs[$identifier]->getPublicKey();
    }
}
