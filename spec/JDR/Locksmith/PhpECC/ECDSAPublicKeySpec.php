<?php

namespace spec\JDR\Locksmith\PhpECC;

use JDR\Locksmith\ECDSA;
use JDR\Locksmith\PhpECC\ECDSAPublicKey;
use JDR\Locksmith\PublicKey;
use PhpSpec\ObjectBehavior;

class ECDSAPublicKeySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(file_get_contents('var/keys/ecdsa/public.pem'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ECDSAPublicKey::class);
    }

    function it_implements_public_key()
    {
        $this->shouldImplement(PublicKey::class);
    }

    function it_implements_ecdsa()
    {
        $this->shouldImplement(ECDSA::class);
    }

    function it_should_store_the_public_key_contents()
    {
        $this->getContent()->shouldReturn(file_get_contents('var/keys/ecdsa/public.pem'));
    }

    function it_throws_an_exception_when_key_is_invalid()
    {
        $this->beConstructedWith('-----BEGIN PUBLIC KEY----- key -----END PUBLIC KEY-----');
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }
}
