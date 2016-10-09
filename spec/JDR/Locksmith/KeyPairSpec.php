<?php

namespace spec\JDR\Locksmith;

use JDR\Locksmith\KeyPair;
use JDR\Locksmith\PrivateKey;
use JDR\Locksmith\PublicKey;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class KeyPairSpec extends ObjectBehavior
{
    function let(PrivateKey $private, PublicKey $public)
    {
        $this->beConstructedWith($private, $public);
        $private->getPublicKey()->willReturn($public);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(KeyPair::class);
    }

    function it_should_contain_a_private_key()
    {
        $this->getPrivateKey()->shouldReturnAnInstanceOf(PrivateKey::class);
    }

    function it_should_contain_a_public_key()
    {
        $this->getPublicKey()->shouldReturnAnInstanceOf(PublicKey::class);
    }

    function it_can_be_constucted_with_just_a_private_key(PrivateKey $private)
    {
        $this->beConstructedWith($private);

        $this->shouldHaveType(KeyPair::class);
    }

    function it_should_contain_a_public_key_even_when_constucted_with_just_a_private_key(PrivateKey $private)
    {
        $this->beConstructedWith($private);

        $this->getPublicKey()->shouldReturnAnInstanceOf(PublicKey::class);
    }
}
