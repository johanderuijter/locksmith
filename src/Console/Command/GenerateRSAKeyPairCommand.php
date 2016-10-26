<?php declare(strict_types = 1);

namespace JDR\Locksmith\Console\Command;

use JDR\Locksmith\OpenSSL\RSAKeyGenerator;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class GenerateRSAKeyPairCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('generate:keys:rsa')
            ->setDescription('Generate a new key pair.')

            ->setDefinition([
                new InputOption('bits', 'b', InputOption::VALUE_REQUIRED, 'Amount of bits used to generate the private key. Supported sizes are: 2048, 4096', 4096),
                new InputOption('passphrase', 'p', InputOption::VALUE_REQUIRED, 'Passphrase used to generate the private key.'),
            ])

            ->setHelp("Generate a new key pair")
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!in_array($input->getOption('bits'), [2048, 4096])) {
            if ($input->getOption('no-interaction')) {
                $io = new SymfonyStyle($input, $output);
                $io->error('Invalid key size. Size MUST be either 2048 or 4096.');
            }
            $input->setOption('bits', 4096);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Generate a new RSA key pair');

        $size = $io->choice(
            'Please specify how many bits should be used to generate the private key',
            [2048, 4096],
            $input->getOption('bits')
        );
        $input->setOption('bits', $size);

        if ($io->confirm('Do you want to use a passphrase?', true)) {
            $passphrase = $io->askHidden('Please specify the passphrase to use', function ($answer) {
                if (empty($answer)) {
                    throw new RuntimeException('Passphrase cannot be empty.');
                }

                return $answer;
            });
            $input->setOption('passphrase', $passphrase);
        }
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
            $generator = new RSAKeyGenerator();
            $keyPair = $generator->generate((int) $input->getOption('bits'), $input->getOption('passphrase'));
            file_put_contents(getcwd().'/private_key.pem', $keyPair->getPrivateKey()->getContent());
            file_put_contents(getcwd().'/public_key.pem', $keyPair->getPublicKey()->getContent());
        } catch (Throwable $exception) {
            $io->error($exception->getMessage());
        }
    }

    private function outputSummary(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $size = $input->getOption('bits');

        if ($input->getOption('passphrase')) {
            $io->text(sprintf('You are about to generate a %d bit key with a passphrase.', $size));

            return;
        }

        $io->text(sprintf('You are about to generate a %d bit key without a passphrase.', $size));
    }
}
