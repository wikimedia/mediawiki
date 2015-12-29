<?php

/**
 * @group Cache
 */
class LinkCacheTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideGetFieldsData
	 */
	public function testGetFields( $contentHandlerUseDB, $pageLanguageUseDB, array $expected ) {
		$this->setMwGlobals( array(
			'wgContentHandlerUseDB' => $contentHandlerUseDB,
			'wgPageLanguageUseDB' => $pageLanguageUseDB,
		) );

		$this->assertEquals( $expected, LinkCache::getFields() );
	}

	public function provideGetFieldsData() {
		return array(
			// wgContentHandlerUseDB    wgPageLanguageUseDB    expected
			array( true, true, array(
					'page_namespace', 'page_title', 'page_id',
					'page_len', 'page_is_redirect', 'page_latest',
					'page_content_model', 'page_lang',
				)
			),
			array( false, true, array(
					'page_namespace', 'page_title', 'page_id',
					'page_len', 'page_is_redirect', 'page_latest',
					'page_lang',
				)
			),
			array( true, false, array(
					'page_namespace', 'page_title', 'page_id',
					'page_len', 'page_is_redirect', 'page_latest',
					'page_content_model',
				)
			),
			array( false, false, array(
					'page_namespace', 'page_title', 'page_id',
					'page_len', 'page_is_redirect', 'page_latest',
				)
			)
		);
	}
}
