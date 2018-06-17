<?php declare(strict_types=1);

namespace Best\SwivelRemover;

use Best\SwivelRemover\PhpParser\ParsedCode;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class SwivelRemover
{
	/**
	 * Remove the swivel from code
	 */
	public function remove(ParsedCode $code, string $swivelToRemove, bool $removedValue = true): void
	{
		$code->traverse(new class($swivelToRemove, $removedValue) extends NodeVisitorAbstract {

			private const METHOD = 'returnValue';

			public $swivelToRemove;
			public $removedValue;

			public function __construct(string $swivelToRemove, bool $removedValue)
			{
				$this->swivelToRemove = $swivelToRemove;
				$this->removedValue = $removedValue;
			}

			public function leaveNode(Node $node)
			{
				if (!($node instanceof Node\Expr\MethodCall)) {
					return null;
				}
				if (!($node->name instanceof Node\Identifier) || $node->name->name !== self::METHOD) {
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

				$argCount = count($node->args);
				if ($argCount < 2 || $argCount > 3) {
					return null;
				}
				if (!($node->args[0]->value instanceof Node\Scalar\String_)) {
					return null;
				}
				if ($node->args[0]->value->value !== $this->swivelToRemove) {
					return null;
				}

				return $argCount === 3 ? $this->handleThreeArgs($node) : $this->handleTwoArgs($node);
			}

			/**
			 * Handle three args.
			 */
			private function handleThreeArgs(Node\Expr\MethodCall $method): Node
			{
				// Replace the method call with one of its arguments:
				return $this->removedValue ? $method->args[1]->value : $method->args[2]->value;
			}

			/**
			 * Handle two args.
			 */
			private function handleTwoArgs(Node\Expr\MethodCall $method): Node
			{
				// Replace the method call with one of its arguments:
				if ($this->removedValue) {
					return $method->args[1]->value;
				}
				return new Node\Expr\ConstFetch(new Node\Name('null'));
			}
		});
	}
}
