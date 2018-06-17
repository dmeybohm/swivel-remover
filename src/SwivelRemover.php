<?php declare(strict_types=1);

namespace Best\SwivelRemover;

use Best\SwivelRemover\PhpParser\ParsedCode;

class SwivelRemover
{
	/**
	 * Remove the swivel from code
	 */
	public function remove(ParsedCode $code, string $swivelToRemove, bool $removedValue = true): void
	{
		$returnValueRemover = new ReturnValueRemover($swivelToRemove, $removedValue);
		$code->addVisitor($returnValueRemover);
		$code->traverse();
	}
}
