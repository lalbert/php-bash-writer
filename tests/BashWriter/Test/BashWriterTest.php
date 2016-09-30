<?php

namespace BashWriter\Test;

use BashWriter\BashWriter;

class BashWriterTest extends \PHPUnit_Framework_TestCase
{
    protected function escapeShell($string)
    {
        return \str_replace('^[', chr(27), $string);
    }

    public function testBashWriter()
    {
        $sh = new BashWriter();

        $sh->addCommand('#!/bin/bash', ['print' => false]);

        $sh->newLine(); // Add new blank line only in file, not on output
        $sh->newLine(true); // Add new line on output (write "echo")

        $sh->addCommand('cd $HOME'); // print 'cd /home/user' and run command
        $sh->addCommand('ls -la', ['print' => 'List files in <comment>`(pwd)`</comment> folder']); // print 'List files in `(pwd)` folder' whith result of pwd in yellow, and run command

        $sh->addCommand('touch <bg=yellow;options=bold>file.txt</>'); // print 'touch file.txt' with 'file.txt' in yellow and bold, and run 'touch file.txt'
        $sh->addCommand('echo "content file" > file.txt', ['print' => false]); // add content in file.txt, shows nothing

        $sh->newLine(true);
        $sh->print('<info>Done</info>'); // print "Done" in green text

        $result = <<<'SCRIPT'
#!/bin/bash

echo
echo -e "cd $HOME"
cd $HOME
echo -e "List files in ^[[33m`(pwd)`^[[39m folder"
ls -la
echo -e "touch ^[[43;1mfile.txt^[[49;22m"
touch file.txt
echo "content file" > file.txt
echo
echo -e "^[[32mDone^[[39m"

SCRIPT;

        $this->assertSame($this->escapeShell($result), $sh->getScript());
        $this->assertSame((string) $sh, $sh->getScript());
    }

    /**
     * @expectedException InvalidArgumentException
     * @@expectedExceptionMessageRegExp /^\$command must be a string or an object; \w+ type given$/
     */
    public function testNotObjectException()
    {
        $sh = new BashWriter();

        $sh->addCommand(['cmd']);
    }

    /**
     * @expectedException InvalidArgumentException
     * @@expectedExceptionMessage $command must be implements BashWriter\ScripterInterface
     */
    public function testBadInterfaceException()
    {
        $sh = new BashWriter();

        $sh->addCommand(new \stdClass('cmd'));
    }
}
