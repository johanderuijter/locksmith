<?php

namespace spec\JDR\Locksmith\PhpECC;

use JDR\Locksmith\ECDSA;
use JDR\Locksmith\PhpECC\ECDSAPrivateKey;
use JDR\Locksmith\PrivateKey;
use JDR\Locksmith\PublicKey;
use PhpSpec\ObjectBehavior;

class ECDSAPrivateKeySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(file_get_contents('var/keys/ecdsa/private.pem'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ECDSAPrivateKey::class);
    }

    function it_implements_private_key()
    {
        $this->shouldImplement(PrivateKey::class);
    }

    function it_implements_ecdsa()
    {
        $this->shouldImplement(ECDSA::class);
    }

    function it_should_store_the_private_key_contents()
    {
        $this->getContent()->shouldReturn(file_get_contents('var/keys/ecdsa/private.pem'));
    }

    function it_should_store_the_private_key_passphrase()
    {
        $this->shouldThrow('\Exception')->during('getPassphrase');
    }

    function it_should_be_able_to_extract_the_public_key()
    {
        $this->getPublicKey()->shouldReturnAnInstanceOf(PublicKey::class);
    }

    function it_throws_an_exception_when_key_is_invalid()
    {
        $this->beConstructedWith('-----BEGIN EC PRIVATE KEY----- key -----END EC PRIVATE KEY-----');
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }
}
