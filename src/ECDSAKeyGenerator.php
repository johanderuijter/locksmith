<?php declare(strict_types = 1);

namespace JDR\Locksmith;

use JDR\Locksmith\KeyPair;
use RuntimeException;

interface ECDSAKeyGenerator
{
    /**
     * Generate a KeyPair.
     *
     * @param string $curve
     *
     * @return KeyPair
     *
     * @throws RuntimeException When the key could not be generated.
     */
    public function generate(string $curve): KeyPair;
}
