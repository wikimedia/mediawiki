<?php

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;

/**
 * @group Cache
 */
class CacheKeyHelperTest extends MediaWikiUnitTestCase {

	public function provideKeyForPage() {
		// NOTE: code changes that break these test cases
		//       will result in incompatible cache keys when deployed!

		yield [ new PageReferenceValue( NS_USER, 'Yulduz', PageReference::LOCAL ), 'ns2:Yulduz' ];
		yield [ new PageIdentityValue( 7, NS_USER, 'Yulduz', PageReference::LOCAL ), 'ns2:Yulduz' ];
		yield [ Title::makeTitle( NS_USER, 'Yulduz' ), 'ns2:Yulduz' ];
		yield [ new TitleValue( NS_USER, 'Yulduz' ), 'ns2:Yulduz' ];
	}

	/**
	 * @dataProvider provideKeyForPage
	 * @covers MediaWiki\Cache\CacheKeyHelper::getKeyForPage
	 */
	public function testKeyForPage( $page, $key ) {
		$this->assertSame( $key, CacheKeyHelper::getKeyForPage( $page ) );
	}
}
