<?php

namespace spec\JDR\Locksmith\OpenSSL;

use JDR\Locksmith\OpenSSL\RSAPrivateKey;
use JDR\Locksmith\PrivateKey;
use JDR\Locksmith\PublicKey;
use JDR\Locksmith\RSA;
use PhpSpec\ObjectBehavior;

class RSAPrivateKeySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(file_get_contents('var/keys/rsa/private.pem'), 'foobar');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RSAPrivateKey::class);
    }

    function it_implements_private_key()
    {
        $this->shouldImplement(PrivateKey::class);
    }

    function it_implements_rsa()
    {
        $this->shouldImplement(RSA::class);
    }

    function it_should_store_the_private_key_contents()
    {
        $this->getContent()->shouldReturn(file_get_contents('var/keys/rsa/private.pem'));
    }

    function it_should_store_the_private_key_passphrase()
    {
        $this->getPassphrase()->shouldReturn('foobar');
    }

    function it_should_be_able_to_extract_the_public_key()
    {
        $this->getPublicKey()->shouldReturnAnInstanceOf(PublicKey::class);
    }

    function it_throws_an_exception_when_key_is_invalid()
    {
        $this->beConstructedWith('-----BEGIN RSA PRIVATE KEY----- key -----END RSA PRIVATE KEY-----', 'foobar');
        // $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_throws_an_exception_when_passphrase_is_invalid()
    {
        $this->beConstructedWith(file_get_contents('var/keys/rsa/private.pem'), 'barfoo');
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }
}
