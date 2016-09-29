<?php

namespace BashWriter\Command;

class EchoCommand extends Command
{
    /**
     * {@inheritdoc}
     *
     * @see \BashWriter\Command\Command::getScript()
     */
    public function getScript()
    {
        $script = ['echo'];
        $script[] = '-e';

        $script[] = '"'.\addcslashes($this->getFormatter()->format($this->getRawCommand()), '"').'"';
        $script[] = Command::COMMAND_EOL;

        return implode(' ', $script);
    }
}
