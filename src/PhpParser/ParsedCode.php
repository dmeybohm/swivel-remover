<?php

namespace Best\SwivelRemover\PhpParser;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;

class ParsedCode
{
	/**
	 * @var \PhpParser\Node[]
	 */
	public $nodes;

	/**
	 * @var \PhpParser\Node\Stmt[]
	 */
	public $oldStatements;

	/**
	 * @var integer[]
	 */
	public $oldTokens;

	/**
	 * Traverse a node list.
	 *
	 * @param \PhpParser\NodeVisitor $visitor
	 * @return void
	 */
	public function traverse(NodeVisitor $visitor)
	{
		$traverser = new NodeTraverser();
		$traverser->addVisitor($visitor);

		$this->nodes = $traverser->traverse($this->nodes);
	}
}
