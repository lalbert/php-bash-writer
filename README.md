# php-bash-writer
Write quickly and easily bash scripts with PHP.

PHP Bash Writer is not designed to run bash script but only to facilitate writing scripts by adding styles (color, bold, ...) on the output.

Internally it uses [symfony/console](https://github.com/symfony/console) to manage the color and styles (see [OutputFormatter](https://github.com/symfony/console/blob/master/Formatter/OutputFormatter.php)).

## Installation

The best way to install PHP Bash Writer it use composer

    composer require lalbert/bash-writer
    
## Usage

```php
$sh = new BashWriter();

$sh->addCommand('#!/bin/bash', ['print' => false]);

$sh->newLine(); // Add new blank line only in file, not on output
$sh->newLine(true); // Add new line on output (write "echo")

$sh->addCommand('cd $HOME'); // print 'cd /home/user' and run command
$sh->addCommand('ls -la', ['print' => 'List files in <comment>`(pwd)`</comment> folder']); // print 'List files in `(pwd)` folder' whith result of pwd in yellow, and run command

$sh->addCommand('touch <bg=yellow;options=bold>file.txt</>'); // print 'touch file.txt' with 'file.txt' in yellow and bold, and run 'touch file.txt'
$sh->addCommand('echo "content file" > file.txt', ['print' => false]); // add content in file.txt, shows nothing

$sh->newLine(true);
$sh->echo('<info>Done</info>'); // print "Done" in green text

// save script in script.sh file
file_put_contents('script.sh', $sh);

```

Now, the file `script.sh` contains the following script:

```shell
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
```
