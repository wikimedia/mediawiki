<?php

use MediaWiki\Page\MovePageFactory;

/**
 * @covers MediaWiki\Page\MovePageFactory
 */
class MovePageFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected function getFactoryClass() {
		return MovePageFactory::class;
	}

	protected function getInstanceClass() {
		return MovePage::class;
	}

	protected static function getExtraClassArgCount() {
		// $to and $from
		return 2;
	}
}
