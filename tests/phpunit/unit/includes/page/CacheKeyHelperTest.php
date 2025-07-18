<?php

use MediaWiki\Page\CacheKeyHelper;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;

/**
 * @group Page
 * @covers \MediaWiki\Page\CacheKeyHelper
 */
class CacheKeyHelperTest extends MediaWikiUnitTestCase {

	public static function provideKeyForPage() {
		// NOTE: code changes that break these test cases
		//       will result in incompatible cache keys when deployed!

		yield [ PageReferenceValue::localReference( NS_USER, 'Yulduz' ), 'ns2:Yulduz' ];
		yield [ PageIdentityValue::localIdentity( 7, NS_USER, 'Yulduz' ), 'ns2:Yulduz' ];
		yield [ Title::makeTitle( NS_USER, 'Yulduz' ), 'ns2:Yulduz' ];
		yield [ new TitleValue( NS_USER, 'Yulduz' ), 'ns2:Yulduz' ];
	}

	/**
	 * @dataProvider provideKeyForPage
	 */
	public function testKeyForPage( $page, $key ) {
		$this->assertSame( $key, CacheKeyHelper::getKeyForPage( $page ) );
	}
}
