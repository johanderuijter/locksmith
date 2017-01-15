<?php

namespace spec\JDR\Locksmith\PhpECC;

use JDR\Locksmith\ECDSAKeyGenerator as KeyGenerator;
use JDR\Locksmith\KeyPair;
use JDR\Locksmith\PhpECC\ECDSAKeyGenerator;
use PhpSpec\ObjectBehavior;

class ECDSAKeyGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ECDSAKeyGenerator::class);
    }

    function it_implements_key_generator()
    {
        $this->shouldImplement(KeyGenerator::class);
    }

    function it_can_generate_a_valid_ecdsa_key_pair()
    {
        $this->generate('nistp256')->shouldReturnAnInstanceOf(KeyPair::class);
    }

    function it_should_throw_a_runtime_exception_when_using_an_unknown_curve()
    {
        $this->shouldThrow('\RuntimeException')->during('generate', ['unknown']);
    }
}
