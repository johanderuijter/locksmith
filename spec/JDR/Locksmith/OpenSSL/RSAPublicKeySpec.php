<?php

namespace spec\JDR\Locksmith\OpenSSL;

use JDR\Locksmith\OpenSSL\RSAPublicKey;
use JDR\Locksmith\PublicKey;
use JDR\Locksmith\RSA;
use PhpSpec\ObjectBehavior;

class RSAPublicKeySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(file_get_contents('var/keys/rsa/public.pem'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RSAPublicKey::class);
    }

    function it_implements_public_key()
    {
        $this->shouldImplement(PublicKey::class);
    }

    function it_implements_rsa()
    {
        $this->shouldImplement(RSA::class);
    }

    function it_should_store_the_public_key_contents()
    {
        $this->getContent()->shouldReturn(file_get_contents('var/keys/rsa/public.pem'));
    }

    function it_throws_an_exception_when_key_is_invalid()
    {
        $this->beConstructedWith('-----BEGIN PUBLIC KEY----- key -----END PUBLIC KEY-----');
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }
}
