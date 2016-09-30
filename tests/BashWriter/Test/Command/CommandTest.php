<?php

namespace BashWriter\Test\Command;

use BashWriter\Command\Command;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    protected function escapeShell($string)
    {
        return \str_replace('^[', chr(27), $string);
    }

    public function testSimpleNewCommand()
    {
        $cmd = new Command('cd $HOME');

        $result = <<<'SCRIPT'
echo -e "cd $HOME"
cd $HOME

SCRIPT;

        $this->assertSame($result, $cmd->getScript());
    }

    public function testNewCommandWhitoutPrint()
    {
        $cmd = new Command('cd $HOME', ['print' => false]);

        $result = <<<'SCRIPT'
cd $HOME

SCRIPT;

        $this->assertSame($result, $cmd->getScript());
    }

    public function testNoNewLine()
    {
        $cmd = new Command('cd $HOME', ['print' => false, 'nl' => false]);

        $result = <<<'SCRIPT'
cd $HOME
SCRIPT;

        $this->assertSame($result, $cmd->getScript());
    }

    public function testNewCommandWithSpecificLabel()
    {
        $cmd = new Command('cd $HOME', ['print' => 'Go to home dir']);

        $result = <<<'SCRIPT'
echo -e "Go to home dir"
cd $HOME

SCRIPT;

        $this->assertSame($result, $cmd->getScript());
    }

    public function testNewCommandWithStyle()
    {
        $cmd = new Command('<comment>cd $HOME</comment>');

        $result = <<<'SCRIPT'
echo -e "^[[33mcd $HOME^[[39m"
cd $HOME

SCRIPT;

        $this->assertSame('cd $HOME', $cmd->getCommand());
        $this->assertSame($this->escapeShell($result), $cmd->getScript());
    }

    public function testToString()
    {
        $cmd = new Command('cd $HOME');

        $this->assertSame((string) $cmd, $cmd->getScript());
    }
}
