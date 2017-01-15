<?php declare(strict_types = 1);

namespace JDR\Locksmith\Console\Command;

use JDR\Locksmith\PhpECC\ECDSAKeyGenerator;
use Mdanter\Ecc\Curves\NistCurve;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class GenerateECDSAKeyPairCommand extends Command
{
    const CURVES = [
        NistCurve::NAME_P192,
        NistCurve::NAME_P224,
        NistCurve::NAME_P256,
        NistCurve::NAME_P384,
        NistCurve::NAME_P521,
    ];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate:keys:ecdsa')
            ->setDescription('Generate a new key pair (ECDSA).')

            ->setDefinition([
                new InputOption('curve', 'c', InputOption::VALUE_REQUIRED, 'Curve used to generate the private key. Supported curves are: '.implode(', ', self::CURVES), NistCurve::NAME_P256),
            ])

            ->setHelp("Generate a new key pair")
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!in_array($input->getOption('curve'), self::CURVES)) {
            if ($input->getOption('no-interaction')) {
                $io = new SymfonyStyle($input, $output);
                $io->error('Invalid curve. Using default curve ("'.NistCurve::NAME_P256.'") instead.');
            }
            $input->setOption('curve', NistCurve::NAME_P256);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Generate a new ECDSA key pair');

        $size = $io->choice(
            'Please specify which curve should be used to generate the private key',
            self::CURVES,
            $input->getOption('curve')
        );
        $input->setOption('curve', $size);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->outputSummary($input, $output);

        $io = new SymfonyStyle($input, $output);
        if (!$io->confirm('Continue with this action?', true)) {
            return;
        }

        try {
            $generator = new ECDSAKeyGenerator();
            $keyPair = $generator->generate($input->getOption('curve'));
            file_put_contents(getcwd().'/private_key.pem', $keyPair->getPrivateKey()->getContent());
            file_put_contents(getcwd().'/public_key.pem', $keyPair->getPublicKey()->getContent());
        } catch (Throwable $exception) {
            $io->error($exception->getMessage());
        }
    }

    private function outputSummary(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $curve = $input->getOption('curve');

        $io->text(sprintf('You are about to generate a key on the %s curve.', $curve));
    }
}
