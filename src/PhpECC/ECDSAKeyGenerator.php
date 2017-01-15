<?php declare(strict_types = 1);

namespace JDR\Locksmith\PhpECC;

use JDR\Locksmith\ECDSAKeyGenerator as KeyGenerator;
use JDR\Locksmith\KeyPair;
use Mdanter\Ecc\Curves\CurveFactory;
use Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use RuntimeException;

class ECDSAKeyGenerator implements KeyGenerator
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
    public function generate(string $curve): KeyPair
    {
        $generator = CurveFactory::getGeneratorByName($curve);

        $privateKeySerializer = new PemPrivateKeySerializer(new DerPrivateKeySerializer());
        $privateKey = $privateKeySerializer->serialize($generator->createPrivateKey());

        $private = new ECDSAPrivateKey($privateKey);

        return new KeyPair($private, $private->getPublicKey());
    }
}
