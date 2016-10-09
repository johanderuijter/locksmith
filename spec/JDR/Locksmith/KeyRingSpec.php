<?php

namespace spec\JDR\Locksmith;

use JDR\Locksmith\KeyPair;
use JDR\Locksmith\KeyRing;
use JDR\Locksmith\PrivateKey;
use JDR\Locksmith\PublicKey;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class KeyRingSpec extends ObjectBehavior
{
    function let(KeyPair $keyPair, PrivateKey $private, PublicKey $public)
    {
        $keyPair->getPrivateKey()->willReturn($private);
        $keyPair->getPublicKey()->willReturn($public);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(KeyRing::class);
    }

    function it_should_be_possible_to_add_a_key_pair(KeyPair $keyPair)
    {
        $this->addKeyPair(Argument::type('string'), $keyPair);
    }

    function it_should_be_possible_to_add_a_public_key(PublicKey $public)
    {
        $this->addPublicKey(Argument::type('string'), $public);
    }

    function it_should_be_possible_to_retrieve_a_key_pair(KeyPair $keyPair)
    {
        $this->addKeyPair(Argument::type('string'), $keyPair);
        $this->getKeyPair(Argument::type('string'))->shouldReturn($keyPair);
    }

    function it_should_be_possible_to_retrieve_a_private_key(KeyPair $keyPair)
    {
        $this->addKeyPair(Argument::type('string'), $keyPair);
        $this->getPrivateKey(Argument::type('string'))->shouldReturnAnInstanceOf(PrivateKey::class);
    }

    function it_should_be_possible_to_retrieve_a_public_key(PublicKey $public)
    {
        $this->addPublicKey(Argument::type('string'), $public);
        $this->getPublicKey(Argument::type('string'))->shouldReturnAnInstanceOf(PublicKey::class);
    }

    function it_should_be_possible_to_retrieve_a_public_key_from_a_key_pair(KeyPair $keyPair)
    {
        $this->addKeyPair(Argument::type('string'), $keyPair);
        $this->getPublicKey(Argument::type('string'))->shouldReturnAnInstanceOf(PublicKey::class);
    }

    function it_should_be_possible_to_override_a_public_key_with_its_matching_key_pair(KeyPair $keyPair, PublicKey $public)
    {
        $this->addPublicKey(Argument::type('string'), $public);
        $this->addKeyPair(Argument::type('string'), $keyPair);
    }

    function it_should_not_be_possible_to_override_a_key_pair(KeyPair $keyPair)
    {
        $this->addKeyPair(Argument::type('string'), $keyPair);
        $this->shouldThrow('\InvalidArgumentException')->during('addKeyPair', [Argument::type('string'), $keyPair]);
    }

    function it_should_not_be_possible_to_override_a_public_key(PublicKey $public)
    {
        $this->addPublicKey(Argument::type('string'), $public);
        $this->shouldThrow('\InvalidArgumentException')->during('addPublicKey', [Argument::type('string'), $public]);
    }
}
