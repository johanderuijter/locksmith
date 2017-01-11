<?php declare(strict_types = 1);

namespace JDR\Locksmith;

use JDR\Locksmith\KeyPair;
use RuntimeException;

interface RSAKeyGenerator
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
    public function generate(int $size, string $passphrase = null): KeyPair;
}
