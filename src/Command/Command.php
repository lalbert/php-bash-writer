<?php

namespace BashWriter\Command;

class Command implements CommandInterface
{
    const COMMAND_EOL = "\n";

    private $command;

    protected $options;
    protected $print = true;
    protected $nl = true;

    /**
     * @param string $command
     * @param array  $options
     */
    public function __construct($command, array $options = [])
    {
        $this->command = $command;
        $this->options = $options;

        if (isset($options['print'])) {
            $this->setPrint($options['print']);
        }

        if (isset($options['nl'])) {
            $this->setNl($options['nl']);
        }
    }

    /**
     * Get cleanup command.
     *
     * @return string
     */
    public function getCommand()
    {
        return \strip_tags($this->command);
    }

    /**
     * Get raw command.
     *
     * @return string
     */
    public function getRawCommand()
    {
        return $this->command;
    }

    /**
     * Define if command must be show (use echo).
     *
     * @param bool|string $flag
     *
     * @return \BashWriter\Command\Command
     */
    public function setPrint($flag = true)
    {
        $this->print = $flag;

        return $this;
    }

    /**
     * @return bool|string
     */
    public function getPrint()
    {
        return $this->print;
    }

    /**
     * Define if a new line must be add at end of command script.
     *
     * @param bool $flag
     *
     * @return \BashWriter\Command\Command
     */
    public function setNl($flag = true)
    {
        $this->nl = (bool) $flag;

        return $this;
    }

    /**
     * @return bool
     */
    public function getNl()
    {
        return $this->nl;
    }

    /**
     * {@inheritdoc}
     *
     * @see \BashWriter\ScripterInterface::getScript()
     */
    public function getScript()
    {
        $script = '';
        if (true === $this->getPrint() || \is_string($this->getPrint())) {
            $print = \is_bool($this->getPrint()) ? $this->getRawCommand() : $this->getPrint();
            $script = (new EchoCommand($print))->getScript();
        }

        $script .= $this->getCommand();
        $script .= $this->getNl() ? self::COMMAND_EOL : '';

        return $script;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getScript();
    }
}
