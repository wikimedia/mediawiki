<?php

use MediaWiki\Site\MediaWikiPageNameNormalizer;

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
class MediaWikiPageNameNormalizerTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		parent::setUp();

		static $connectivity = null;

		if ( $connectivity === null ) {
			// Check whether we have (reasonable fast) connectivity
			$res = Http::get(
				'https://www.wikidata.org/w/api.php?action=query&meta=siteinfo&format=json',
				[ 'timeout' => 3 ],
				__METHOD__
			);

			if ( $res === false || strpos( $res, '"sitename":"Wikidata"' ) === false ) {
				$connectivity = false;
			} else {
				$connectivity = true;
			}
		}

		if ( !$connectivity ) {
			$this->markTestSkipped( 'MediaWikiPageNameNormalizerTest needs internet connectivity.' );
		}
	}

	/**
	 * @dataProvider normalizePageTitleProvider
	 */
	public function testNormalizePageTitle( $expected, $pageName ) {
		$normalizer = new MediaWikiPageNameNormalizer();

		$this->assertSame(
			$expected,
			$normalizer->normalizePageName( $pageName, 'https://www.wikidata.org/w/api.php' )
		);
	}

	public function normalizePageTitleProvider() {
		// Note: This makes (very conservative) assumptions about pages on Wikidata
		// existing or not.
		return [
			'universe (Q1)' => [
				'Q1', 'Q1'
			],
			'Q404 redirects to Q395' => [
				'Q395', 'Q404'
			],
			'there is no Q0' => [
				false, 'Q0'
			]
		];
	}

}
