<?php declare(strict_types = 1);

namespace JDR\Locksmith\Console;

use JDR\Locksmith\Console\Command\GenerateRSAKeyPairCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

class Application extends ConsoleApplication
{
    const NAME = 'Locksmith';
    const VERSION = '0.0.1';

    public function __construct()
    {
        parent::__construct(self::NAME, self::VERSION);
        $this->add(new GenerateRSAKeyPairCommand());
    }
}
