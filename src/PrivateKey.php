<?php declare(strict_types = 1);

namespace JDR\Locksmith;

interface PrivateKey
{
    /**
     * Get content.
     *
     * @return string
     */
    public function getContent() : string;

    /**
     * Get passphrase.
     *
     * @return string
     */
    public function getPassphrase() : string;

    /**
     * Get public key.
     *
     * @return PublicKey
     */
    public function getPublicKey() : PublicKey;
}
