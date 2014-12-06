<?php

class LinkCacheTest extends MediaWikiTestCase {

	/**
	 * @covers LinkCache::addBadLinkObj
	 */
	public function testAddBadLinkObj() {
		$lc = new LinkCache;
		$tv = new TitleValue( 1, 'Test' );
		$lc->addBadLinkObj( $tv );
		$this->assertTrue( $lc->isBadLink( $tv ) );
		// Old calling style
		$title = Title::newFromText( 'Talk:Test 2' );
		$lc->addBadLinkObj( $title );
		$this->assertTrue( $lc->isBadLink( $title->getTitleValue() ) );
	}

	/**
	 * @covers LinkCache::isBadLink
	 */
	public function testIsBadLink() {
		$lc = new LinkCache;
		$tv = new TitleValue( 1, 'Test' );
		$lc->addBadLinkObj( $tv );
		$this->assertTrue( $lc->isBadLink( $tv ) );
		// Old calling style
		$this->assertTrue( $lc->isBadLink( 'Talk:Test' ) );
	}

	/**
	 * @covers LinkCache::clearBadLinkObj
	 */
	public function testClearBadLinkObj() {
		$lc = new LinkCache;
		$tv = new TitleValue( 1, 'Test' );
		$lc->addBadLinkObj( $tv );
		$this->assertTrue( $lc->isBadLink( $tv ) );
		$lc->clearBadLinkObj( $tv );
		$this->assertFalse( $lc->isBadLink( $tv ) );
	}

	public static function provideGetGoodLinkFieldObj() {
		return array(
			array(
				array( 1, 'Test' ),
				array(
					'page_id' => 10,
					'page_len' => 15,
					'page_is_redirect' => 1,
					'page_latest' => 123456789,
					'page_content_model' => 'wikitext',
				),
				array(
					'length' => 15,
					'redirect' => 1,
					'revision' => 123456789,
					'model' => 'wikitext'
				)
			),
			array(
				array( 2, 'Test' ),
				array(
					'page_id' => '3',
					'page_len' => '18',
					'page_is_redirect' => '0',
					'page_latest' => 7567545656,
					'page_content_model' => 'wikitext',
				),
				array(
					'redirect' => 0,
					'revision' => 7567545656,
				)
			),
			array(
				array( 3, 'Test' ),
				array(
					'page_id' => 786,
					'page_len' => 87,
					'page_is_redirect' => 0,
					'page_latest' => 1279,
					'page_content_model' => 'json',
				),
				array(
					'model' => 'json',
					'length' => 87,
				)
			),
		);
	}

	/**
	 * @covers LinkCache::getGoodLinkFieldObj
	 * @dataProvider provideGetGoodLinkFieldObj
	 */
	public function testGetGoodLinkFieldObj( $titleInfo, $row, $expected ) {
		$lc = new LinkCache;
		$tv = new TitleValue( $titleInfo[0], $titleInfo[1] );
		$lc->addGoodLinkObjFromRow( $tv, (object)$row );
		foreach ( $expected as $field => $value ) {
			$this->assertEquals( $value, $lc->getGoodLinkFieldObj( $tv, $field ) );
		}
	}
}