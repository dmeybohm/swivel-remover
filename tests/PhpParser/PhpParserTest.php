<?php

namespace Best\SwivelRemover\Test\PhpParser;

use Best\SwivelRemover\PhpParser\PhpParser;

class PhpParserTest extends \PHPUnit\Framework\TestCase {

	public function testParsing()
	{
		$parser = new PhpParser();
		$code = <<< 'EOD'
<?php
// This is a comment:

/** 
 *
   * This is another comment 
 */
$foo = 'bar';
EOD;
		$parsed = $parser->parse($code);
		$this->assertSame($code, $parser->output($parsed));
	}

	public function testParsingOfThisFile()
	{
		$file = file_get_contents(__FILE__);
		$parser = new PhpParser();
		$parsed = $parser->parse($file);
		$this->assertSame($file, $parser->output($parsed));
	}

}
