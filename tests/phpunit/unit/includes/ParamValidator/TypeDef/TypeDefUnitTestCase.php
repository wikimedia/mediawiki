<?php

namespace MediaWiki\Tests\ParamValidator\TypeDef;

use MediaWikiUnitTestCase;
use Wikimedia\Tests\ParamValidator\TypeDef\TypeDefTestCaseTrait;

abstract class TypeDefUnitTestCase extends MediaWikiUnitTestCase {
	use TypeDefTestCaseTrait;

	/** Standard "$ret" array for provideCheckSettings */
	protected const STDRET =
		[ 'issues' => [ 'X' ], 'allowedKeys' => [ 'Y' ], 'messages' => [] ];
}
