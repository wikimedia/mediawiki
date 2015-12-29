<?php

/**
 * @group Cache
 */
class LinkCacheTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideGetFieldsData
	 */
	public function testGetFields( $contentHandlerUseDB, $pageLanguageUseDB, array $expected ) {
		$this->setMwGlobals( [
			'wgContentHandlerUseDB' => $contentHandlerUseDB,
			'wgPageLanguageUseDB' => $pageLanguageUseDB,
		] );

		$this->assertEquals( $expected, LinkCache::getSelectFields() );
	}

	public function provideGetFieldsData() {
		return [
			// wgContentHandlerUseDB    wgPageLanguageUseDB    expected
			[ true, true, [
					'page_namespace', 'page_title', 'page_id',
					'page_len', 'page_is_redirect', 'page_latest',
					'page_content_model', 'page_lang',
				]
			],
			[ false, true, [
					'page_namespace', 'page_title', 'page_id',
					'page_len', 'page_is_redirect', 'page_latest',
					'page_lang',
				]
			],
			[ true, false, [
					'page_namespace', 'page_title', 'page_id',
					'page_len', 'page_is_redirect', 'page_latest',
					'page_content_model',
				]
			],
			[ false, false, [
					'page_namespace', 'page_title', 'page_id',
					'page_len', 'page_is_redirect', 'page_latest',
				]
			]
		];
	}
}
