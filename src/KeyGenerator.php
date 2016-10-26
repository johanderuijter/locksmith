<?php declare(strict_types = 1);

namespace JDR\Locksmith;

use RuntimeException;
use JDR\Locksmith\KeyPair;

interface KeyGenerator
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
    public function generate(int $size, string $passphrase = null) : KeyPair;
}
