<?php

namespace MediaWiki\ParamValidator\TypeDef;

use MediaWikiUnitTestCase;
use Wikimedia\ParamValidator\TypeDef\TypeDefTestCaseTrait;

abstract class TypeDefUnitTestCase extends MediaWikiUnitTestCase {
	use TypeDefTestCaseTrait;

	/** Standard "$ret" array for provideCheckSettings */
	protected const STDRET =
		[ 'issues' => [ 'X' ], 'allowedKeys' => [ 'Y' ], 'messages' => [] ];
}
