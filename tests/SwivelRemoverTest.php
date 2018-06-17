<?php

namespace Best\SwivelRemover\Test;

use Best\SwivelRemover\PhpParser\PhpParser;
use Best\SwivelRemover\SwivelRemover;

class SwivelRemoverTest extends \PHPUnit\Framework\TestCase
{
	public function testRemoveReturnValueWithThreeArgs()
	{
		$parser = new PhpParser();
		$code = <<< 'EOD'
<?php
$x = $this->Swivel->returnValue('FooBar.Baz', true, false);
$y = $this->Swivel->returnValue('FooBar.Baz.Poo', true, false);
EOD;
		$parsed = $parser->parse($code);
		$swivelRemover = new SwivelRemover();
		$swivelRemover->remove($parsed,'FooBar.Baz');
		$expected = <<< 'EOD'
<?php
$x = true;
$y = $this->Swivel->returnValue('FooBar.Baz.Poo', true, false);
EOD;
		$this->assertEquals($expected, $parser->output($parsed));
	}

	public function testRemoveReturnValueWithTwoArgs()
	{
		$parser = new PhpParser();
		$code = <<< 'EOD'
<?php
$x = $this->Swivel->returnValue('FooBar.Baz', true);
$y = $this->Swivel->returnValue('FooBar.Baz.Poo', true);
EOD;
		$parsed = $parser->parse($code);
		$swivelRemover = new SwivelRemover();
		$swivelRemover->remove($parsed,'FooBar.Baz');
		$expected = <<< 'EOD'
<?php
$x = true;
$y = $this->Swivel->returnValue('FooBar.Baz.Poo', true);
EOD;
		$this->assertEquals($expected, $parser->output($parsed));

		$code = <<< 'EOD'
<?php
$x = $this->Swivel->returnValue('FooBar.Baz', true);
$y = $this->Swivel->returnValue('FooBar.Baz.Poo', true);
EOD;

		$parsed = $parser->parse($code);
		$swivelRemover = new SwivelRemover();
		$swivelRemover->remove($parsed, 'FooBar.Baz', false);
		$expected = <<< 'EOD'
<?php
$x = null;
$y = $this->Swivel->returnValue('FooBar.Baz.Poo', true);
EOD;

		$this->assertEquals($expected, $parser->output($parsed));
	}

}
