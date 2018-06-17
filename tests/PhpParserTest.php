<?php

namespace Best\SwivelRemover\Test;

use Best\SwivelRemover\PhpParser;

class PhpParserTest extends \PHPUnit\Framework\TestCase {

	public function testParsing() {
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
		$parser->parse($code);
		$this->assertEquals($code, $parser->printFormatPreserving());
	}

	public function testParsingOfThisFile() {
		$file = file_get_contents(__FILE__);
		$parser = new PhpParser();
		$parser->parse($file);
		$this->assertEquals($file, $parser->printFormatPreserving());
	}

}
