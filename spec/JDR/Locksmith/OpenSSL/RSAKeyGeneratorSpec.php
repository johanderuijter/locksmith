<?php

namespace spec\JDR\Locksmith\OpenSSL;

use JDR\Locksmith\KeyGenerator;
use JDR\Locksmith\KeyPair;
use JDR\Locksmith\OpenSSL\RSAKeyGenerator;
use PhpSpec\ObjectBehavior;

class RSAKeyGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RSAKeyGenerator::class);
    }

    function it_implements_key_generator()
    {
        $this->shouldImplement(KeyGenerator::class);
    }

    function it_can_generate_a_valid_rsa_key_pair()
    {
        $this->generate(2048, 'foobar')->shouldReturnAnInstanceOf(KeyPair::class);
    }

    function it_can_generate_a_valid_rsa_key_pair_without_a_passphrase()
    {
        $this->generate(2048, '')->shouldReturnAnInstanceOf(KeyPair::class);
    }
}
