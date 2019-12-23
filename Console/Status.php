<?php

namespace Fsw\ErrorSieve\Console;

use Fsw\ErrorSieve\Model\Status as ModelStatus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Status extends Command
{
    /** @var ModelStatus */
    protected $status;

    /**
     * State constructor.
     * @param ModelStatus $status
     * @param string|null $name
     */
    public function __construct(ModelStatus $status, string $name = null)
    {
        $this->status = $status;
        parent::__construct($name);
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setName('fsw:errorsieve:status')->setDescription('');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->status->getStatus() as $key => $value) {
            echo "$key: $value\n";
        }
    }

}