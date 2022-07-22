<?php

namespace MediaWiki\ParamValidator\TypeDef;

use MediaWikiIntegrationTestCase;
use Wikimedia\ParamValidator\TypeDef\TypeDefTestCaseTrait;

abstract class TypeDefIntegrationTestCase extends MediaWikiIntegrationTestCase {
	use TypeDefTestCaseTrait;

	/** Standard "$ret" array for provideCheckSettings */
	protected const STDRET =
		[ 'issues' => [ 'X' ], 'allowedKeys' => [ 'Y' ], 'messages' => [] ];
}
