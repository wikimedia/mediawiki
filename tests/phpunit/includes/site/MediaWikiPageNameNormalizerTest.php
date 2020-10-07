<?php

use MediaWiki\Site\MediaWikiPageNameNormalizer;
use PHPUnit\Framework\Assert;

/**
 * @covers MediaWiki\Site\MediaWikiPageNameNormalizer
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.27
 *
 * @group Site
 * @group medium
 *
 * @author Marius Hoch
 */
class MediaWikiPageNameNormalizerTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider normalizePageTitleProvider
	 */
	public function testNormalizePageTitle( $expected, $pageName, $getResponse ) {
		MediaWikiPageNameNormalizerTestMockHttp::$response = $getResponse;

		$normalizer = new MediaWikiPageNameNormalizer(
			new MediaWikiPageNameNormalizerTestMockHttp()
		);

		$this->assertSame(
			$expected,
			$normalizer->normalizePageName( $pageName, 'https://www.wikidata.org/w/api.php' )
		);
	}

	public function normalizePageTitleProvider() {
		// Response are taken from wikidata and kkwiki using the following API request
		// api.php?action=query&prop=info&redirects=1&converttitles=1&format=json&titles=…
		return [
			'universe (Q1)' => [
				'Q1',
				'Q1',
				'{"batchcomplete":"","query":{"pages":{"129":{"pageid":129,"ns":0,'
				. '"title":"Q1","contentmodel":"wikibase-item","pagelanguage":"en",'
				. '"pagelanguagehtmlcode":"en","pagelanguagedir":"ltr",'
				. '"touched":"2016-06-23T05:11:21Z","lastrevid":350004448,"length":58001}}}}'
			],
			'Q404 redirects to Q395' => [
				'Q395',
				'Q404',
				'{"batchcomplete":"","query":{"redirects":[{"from":"Q404","to":"Q395"}],"pages"'
				. ':{"601":{"pageid":601,"ns":0,"title":"Q395","contentmodel":"wikibase-item",'
				. '"pagelanguage":"en","pagelanguagehtmlcode":"en","pagelanguagedir":"ltr",'
				. '"touched":"2016-06-23T08:00:20Z","lastrevid":350021914,"length":60108}}}}'
			],
			'D converted to Д (Latin to Cyrillic) (taken from kkwiki)' => [
				'Д',
				'D',
				'{"batchcomplete":"","query":{"converted":[{"from":"D","to":"\u0414"}],'
				. '"pages":{"510541":{"pageid":510541,"ns":0,"title":"\u0414",'
				. '"contentmodel":"wikitext","pagelanguage":"kk","pagelanguagehtmlcode":"kk",'
				. '"pagelanguagedir":"ltr","touched":"2015-11-22T09:16:18Z",'
				. '"lastrevid":2373618,"length":3501}}}}'
			],
			'there is no Q0' => [
				false,
				'Q0',
				'{"batchcomplete":"","query":{"pages":{"-1":{"ns":0,"title":"Q0",'
				. '"missing":"","contentmodel":"wikibase-item","pagelanguage":"en",'
				. '"pagelanguagehtmlcode":"en","pagelanguagedir":"ltr"}}}}'
			],
			'invalid title' => [
				false,
				'{{',
				'{"batchcomplete":"","query":{"pages":{"-1":{"title":"{{",'
				. '"invalidreason":"The requested page title contains invalid '
				. 'characters: \"{\".","invalid":""}}}}'
			],
			'error on get' => [ false, 'ABC', false ]
		];
	}

}

/**
 * @private
 * @see Http
 */
class MediaWikiPageNameNormalizerTestMockHttp extends Http {

	/**
	 * @var mixed
	 */
	public static $response;

	public static function get( $url, array $options = [], $caller = __METHOD__ ) {
		Assert::assertIsString( $url );
		Assert::assertIsString( $caller );

		return self::$response;
	}
}
