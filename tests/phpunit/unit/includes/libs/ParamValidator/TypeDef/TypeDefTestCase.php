<?php

namespace Wikimedia\Tests\ParamValidator\TypeDef;

use PHPUnit\Framework\TestCase;

/**
 * Test case infrastructure for TypeDef subclasses
 *
 * Generally, you'll only need to implement self::getInstance() and
 * data providers methods.
 */
abstract class TypeDefTestCase extends TestCase {
	use TypeDefTestCaseTrait;

	/** Standard "$ret" array for provideCheckSettings */
	protected const STDRET =
		[ 'issues' => [ 'X' ], 'allowedKeys' => [ 'Y' ], 'messages' => [] ];
}
