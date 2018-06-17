<?php

namespace Best\SwivelRemover;

use Best\SwivelRemover\PhpParser\ParsedCode;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class SwivelRemover
{
	/**
	 * Remove the swivel a file.
	 *
	 * @param ParsedCode $code
	 * @param string $swivelToRemove
	 * @param bool $removedValue
	 * @return void
	 */
	public function remove(ParsedCode $code, string $swivelToRemove, bool $removedValue = true) : void
	{
		$code->traverse(new class($swivelToRemove, $removedValue) extends NodeVisitorAbstract {

			const METHOD = 'returnValue';

			public $swivelToRemove;
			public $removedValue;

			public function __construct(string $swivelToRemove, bool $removedValue) {
				$this->swivelToRemove = $swivelToRemove;
				$this->removedValue = $removedValue;
			}

			public function leaveNode(Node $node) {
				if (!($node instanceof Node\Expr\MethodCall)) {
					return null;
				}
				if ($node->name->name !== self::METHOD) {
					return null;
				}
				if (!($node->var instanceof Node\Expr\PropertyFetch)) {
					return null;
				}
				if (!($node->var->name instanceof Node\Identifier) || $node->var->name->name !== 'Swivel') {
					return null;
				}
				if (!($node->var->var instanceof Node\Expr\Variable)) {
					return null;
				}
				if ($node->var->var->name !== 'this') {
					return null;
				}

				if (count($node->args) !== 3) {
					return null;
				}

				if (!($node->args[0]->value instanceof Node\Scalar\String_)) {
					return null;
				}
				if ($node->args[0]->value->value !== $this->swivelToRemove) {
					return null;
				}

				// Replace the method call with one of its arguments:
				return $this->removedValue ? $node->args[1]->value : $node->args[2]->value;
			}

		});
	}
}
