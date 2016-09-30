<?php

namespace BashWriter\Test\Command;

use BashWriter\Command\EchoCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Formatter\OutputFormatter;

class EchoCommandTest extends \PHPUnit_Framework_TestCase
{
    protected function escapeShell($string)
    {
        return \str_replace('^[', chr(27), $string);
    }

    public function testSimple()
    {
        $echo = new EchoCommand('Hello world !');

        $result = <<<'SCRIPT'
echo -e "Hello world !"

SCRIPT;

        $this->assertSame($result, $echo->getScript());
    }

    public function testDefaultStyle()
    {
        $echo = new EchoCommand('<info>info</info> <comment>comment</comment> <question>question</question> <error>error</error>');

        $result = <<<'SCRIPT'
echo -e "^[[32minfo^[[39m ^[[33mcomment^[[39m ^[[30;46mquestion^[[39;49m ^[[37;41merror^[[39;49m"

SCRIPT;

        $this->assertSame($this->escapeShell($result), $echo->getScript());
        $this->assertSame('info comment question error', $echo->getCommand());
    }

    public function testInlineCustomStyle()
    {
        $echo = new EchoCommand('<bg=yellow;bg=cyan;options=bold>Hello world !</>');

        $result = <<<'SCRIPT'
echo -e "^[[46;1mHello world !^[[49;22m"

SCRIPT;
        $this->assertSame($this->escapeShell($result), $echo->getScript());
    }

    public function testCustomOptionStyle()
    {
        $echo = new EchoCommand('<fire>Hello world !</fire>', [
            'styles' => [
                'fire' => new OutputFormatterStyle('red', 'yellow', array('bold', 'blink')),
            ], ]);

        $result = <<<'SCRIPT'
echo -e "^[[31;43;1;5mHello world !^[[39;49;22;25m"

SCRIPT;
        $this->assertSame($this->escapeShell($result), $echo->getScript());
    }

    public function testCustomOutputFormater()
    {
        $echo = new EchoCommand('<fire>Hello world !</fire>');

        $outputFormatter = new OutputFormatter();
        $fire = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
        $outputFormatter->setStyle('fire', $fire);

        $echo->setFormatter($outputFormatter);

        $result = <<<'SCRIPT'
echo -e "^[[31;43;1;5mHello world !^[[39;49;22;25m"

SCRIPT;
        $this->assertSame($this->escapeShell($result), $echo->getScript());
    }
}
