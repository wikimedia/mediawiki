<?php

use MediaWiki\Page\PageHandlerFactory;

/**
 * @covers MediaWiki\Page\PageHandlerFactory
 */
class PageHandlerFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected function getFactoryClass() {
		return PageHandlerFactory::class;
	}

	protected function getInstanceClass() {
		return MovePage::class;
	}

	protected static function getExtraClassArgCount() {
		// $to and $from
		return 2;
	}
}
