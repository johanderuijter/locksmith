<?php declare(strict_types = 1);

namespace JDR\Locksmith;

interface PublicKey
{
    /**
     * Get content.
     *
     * @return string
     */
    public function getContent() : string;
}
