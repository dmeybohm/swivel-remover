<?php declare(strict_types=1);

namespace Best\SwivelRemover\PhpParser;

use PhpParser\Lexer;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use PhpParser\PrettyPrinter;

class PhpParser
{
	/**
	 * Parse the code.
	 */
	public function parse(string $code) : ParsedCode
	{
		$lexer = new Lexer\Emulative([
			'usedAttributes' => [
				'comments',
				'startLine', 'endLine',
				'startTokenPos', 'endTokenPos',
			],
		]);
		$parser = new Parser\Php7($lexer);

		$parsedCode = new ParsedCode();
		$parsedCode->oldStatements = $parser->parse($code);
		$parsedCode->oldTokens = $lexer->getTokens();
		$parsedCode->nodes = $parsedCode->oldStatements;
		$parsedCode->traverse(new NodeVisitor\CloningVisitor());

		return $parsedCode;
	}

	/**
	 * Print the code while preserving the format.
	 */
	public function output(ParsedCode $parsedCode) : string
	{
		$printer = new PrettyPrinter\Standard();
		return $printer->printFormatPreserving($parsedCode->nodes, $parsedCode->oldStatements, $parsedCode->oldTokens);
	}

}
