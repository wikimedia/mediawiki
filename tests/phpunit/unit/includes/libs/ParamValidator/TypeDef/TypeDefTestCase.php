<?php

namespace Wikimedia\ParamValidator\TypeDef;

/**
 * Test case infrastructure for TypeDef subclasses
 *
 * Generally you'll only need to implement self::getInstance() and
 * data providers methods.
 */
abstract class TypeDefTestCase extends \PHPUnit\Framework\TestCase {
	use TypeDefTestCaseTrait;

	/** Standard "$ret" array for provideCheckSettings */
	protected const STDRET =
		[ 'issues' => [ 'X' ], 'allowedKeys' => [ 'Y' ], 'messages' => [] ];
}
