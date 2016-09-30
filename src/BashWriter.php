<?php

namespace BashWriter;

use BashWriter\Command\Command;
use BashWriter\Command\EchoCommand;

class BashWriter implements ScripterInterface
{
    private $commands = [];
    private $script;

    /**
     * Add new command.
     *
     * @param string|ScripterInterface $command
     * @param array                    $options
     *
     * @return \BashWriter\BashWriter
     */
    public function addCommand($command, array $options = [])
    {
        $command = $this->prepareCommand($command, $options);
        $this->commands[] = $command;
        $this->script = null;

        return $this;
    }

    /**
     * @param string|ScripterInterface $command
     * @param array                    $options
     *
     * @throws \InvalidArgumentException
     *
     * @return \BashWriter\ScripterInterface|\BashWriter\Command\Command
     */
    protected function prepareCommand($command, array $options = [])
    {
        if (\is_string($command)) {
            $command = new Command($command, $options);
        }

        if (!\is_object($command)) {
            throw new \InvalidArgumentException(\sprintf('$command must be a string or an object; %s type given', \gettype($command)));
        }

        if (!$command instanceof ScripterInterface) {
            throw new \InvalidArgumentException(\sprintf('$command must be implements %s', ScripterInterface::class));
        }

        return $command;
    }

    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Add an echo command.
     *
     * @param string $message If $message is empty, print an empty line
     * @param array  $options
     *
     * @return \BashWriter\BashWriter
     */
    public function output($message = null, array $options = [])
    {
        if (\is_null($message) || empty($message)) {
            return $this->addCommand(new Command('echo', ['print' => false]));
        }

        $options += ['print' => false];

        return $this->addCommand(new EchoCommand($message, $options));
    }

    /**
     * Add empty line in file or in output script (use echo "").
     *
     * @param bool $echo
     *
     * @return \BashWriter\BashWriter
     */
    public function newLine($echo = false)
    {
        if ($echo) {
            return $this->output();
        }

        return $this->addCommand(new Command(Command::COMMAND_EOL, ['print' => false, 'nl' => false]));
    }

    /**
     * Get final script
     * {@inheritdoc}
     *
     * @see \BashWriter\ScripterInterface::getScript()
     */
    public function getScript()
    {
        if (!$this->script) {
            $this->script = '';
            foreach ($this->getCommands() as $command) {
                $this->script .= $command->getScript();
            }
        }

        return $this->script;
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->getScript();
    }
}
