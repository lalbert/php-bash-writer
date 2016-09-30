<?php

namespace BashWriter\Command;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatter;

class EchoCommand extends Command
{
    private $formatter;

    /**
     * Retrieve current output formatter.
     *
     * @return \Symfony\Component\Console\Formatter\OutputFormatterInterface
     */
    public function getFormatter()
    {
        if (!$this->formatter) {
            $styles = isset($this->options['styles']) ? $this->options['styles'] : [];
            $this->formatter = new OutputFormatter(true, $styles);
        }

        // Always activate decoration
        $this->formatter->setDecorated(true);

        return $this->formatter;
    }

    /**
     * Set output formatter.
     *
     * @param OutputFormatterInterface $outputFormatter
     */
    public function setFormatter(OutputFormatterInterface $outputFormatter)
    {
        $this->formatter = $outputFormatter;
    }

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

        return implode(' ', $script).Command::COMMAND_EOL;
    }
}
