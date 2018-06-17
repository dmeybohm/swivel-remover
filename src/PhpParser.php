<?php

namespace Best;

use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use PhpParser\PrettyPrinter;

class PhpParser
{
	/**
	 * @var \PhpParser\Node[]
	 */
	private $parsed;

	/**
	 * @var \PhpParser\Node[]
	 */
	private $oldStmts;

	/**
	 * @var integer[]
	 */
	private $oldTokens;

	/**
	 * Parse the code.
	 *
	 * @param string $code
	 * @return \PhpParser\Node[]
	 */
	public function parse($code)
	{
		$lexer = new Lexer\Emulative([
			'usedAttributes' => [
				'comments',
				'startLine', 'endLine',
				'startTokenPos', 'endTokenPos',
			],
		]);
		$parser = new Parser\Php7($lexer);

		$traverser = new NodeTraverser();
		$traverser->addVisitor(new NodeVisitor\CloningVisitor());

		$this->oldStmts = $parser->parse($code);
		$this->oldTokens = $lexer->getTokens();

		$this->parsed = $traverser->traverse($this->oldStmts);
		return $this->parsed;
	}

	/**
	 * Get the parsed code.
	 *
	 * @return \PhpParser\Node[]
	 */
	public function getParsed()
	{
		return $this->parsed;
	}

	/**
	 * Print the code while preserving the format.
	 *
	 * @return string
	 */
	public function printFormatPreserving()
	{
		$printer = new PrettyPrinter\Standard();
		return $printer->printFormatPreserving($this->parsed, $this->oldStmts, $this->oldTokens);
	}
}
