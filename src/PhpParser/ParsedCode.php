<?php declare(strict_types=1);

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
	 * @var NodeTraverser|null
	 */
	private $traverser = null;

	public function addVisitor(NodeVisitor $visitor): void
	{
		if ($this->traverser === null) {
			$this->traverser = new NodeTraverser();
		}
		$this->traverser->addVisitor($visitor);
	}

	/**
	 * Traverse a node list.
	 */
	public function traverse(NodeVisitor $visitor = null): void
	{
		if ($visitor !== null) {
			$this->addVisitor($visitor);
		}

		$this->nodes = $this->getTraverser()->traverse($this->nodes);
	}

	private function getTraverser(): NodeTraverser
	{
		if ($this->traverser === null) {
			$this->traverser = new NodeTraverser();
		}
		return $this->traverser;
	}
}
