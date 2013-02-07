<?php
/**
 *
 *
 * Created on Feb 6, 2013
 *
 * Copyright © 2013 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * @file
 *
 * These tests validate basic functionality of the api query module
 *
 * @group API
 * @group Database
 * @group medium
 */
class ApiQueryBasicTest extends ApiTestCase {

	/**
	 * Create a set of pages. These must not change, otherwise the tests might give wrong results.
	 * @see MediaWikiTestCase::addDBData()
	 */
	function addDBData() {
		try {
			if ( Title::newFromText( 'AQBT-All' )->exists() ) {
				return;
			}

			// Ordering is important, as it will be returned in the same order as stored in the index
			$this->editPage( 'AQBT-All', '[[Category:AQBT-Cat]] [[AQBT-Links]] {{AQBT-T}}' );
			$this->editPage( 'AQBT-Categories', '[[Category:AQBT-Cat]]' );
			$this->editPage( 'AQBT-Links', '[[AQBT-All]] [[AQBT-Categories]] [[AQBT-Templates]]' );
			$this->editPage( 'AQBT-Templates', '{{AQBT-T}}' );
			$this->editPage( 'AQBT-T', 'Content', '', NS_TEMPLATE );

			// Refresh due to the bug with listing transclusions as links if they don't exist
			$this->editPage( 'AQBT-All', '[[Category:AQBT-Cat]] [[AQBT-Links]] {{AQBT-T}}' );
			$this->editPage( 'AQBT-Templates', '{{AQBT-T}}' );
		} catch ( Exception $e ) {
			$this->exceptionFromAddDBData = $e;
		}
	}

	static $links = array(
		array( 'prop' => 'links', 'titles' => 'AQBT-All' ),
		array( 'pages' => array(
			'1' => array(
				'pageid' => 1,
				'ns' => 0,
				'title' => 'AQBT-All',
				'links' => array(
					array( 'ns' => 0, 'title' => 'AQBT-Links' ),
	) ) ) ) );

	static $templates = array(
		array( 'prop' => 'templates', 'titles' => 'AQBT-All' ),
		array( 'pages' => array(
			'1' => array(
				'pageid' => 1,
				'ns' => 0,
				'title' => 'AQBT-All',
				'templates' => array(
					array( 'ns' => 10, 'title' => 'Template:AQBT-T' ),
	) ) ) ) );

	static $categories = array(
		array( 'prop' => 'categories', 'titles' => 'AQBT-All' ),
		array( 'pages' => array(
			'1' => array(
				'pageid' => 1,
				'ns' => 0,
				'title' => 'AQBT-All',
				'categories' => array(
					array( 'ns' => 14, 'title' => 'Category:AQBT-Cat' ),
	) ) ) ) );

	static $allpages = array(
		array( 'list' => 'allpages', 'apprefix' => 'AQBT-' ),
		array( 'allpages' => array(
			array( 'pageid' => 1, 'ns' => 0, 'title' => 'AQBT-All' ),
			array( 'pageid' => 2, 'ns' => 0, 'title' => 'AQBT-Categories' ),
			array( 'pageid' => 3, 'ns' => 0, 'title' => 'AQBT-Links' ),
			array( 'pageid' => 4, 'ns' => 0, 'title' => 'AQBT-Templates' ),
	) ) );

	static $alllinks = array(
		array( 'list' => 'alllinks', 'alprefix' => 'AQBT-' ),
		array( 'alllinks' => array(
			array( 'ns' => 0, 'title' => 'AQBT-All' ),
			array( 'ns' => 0, 'title' => 'AQBT-Categories' ),
			array( 'ns' => 0, 'title' => 'AQBT-Links' ),
			array( 'ns' => 0, 'title' => 'AQBT-Templates' ),
	) ) );

	static $alltransclusions = array(
		array( 'list' => 'alltransclusions', 'atprefix' => 'AQBT-' ),
		array( 'alltransclusions' => array(
			array( 'ns' => 10, 'title' => 'Template:AQBT-T' ),
			array( 'ns' => 10, 'title' => 'Template:AQBT-T' ),
	) ) );

	static $allcategories = array(
		array( 'list' => 'allcategories', 'acprefix' => 'AQBT-' ),
		array( 'allcategories' => array(
			array( '*' => 'AQBT-Cat' ),
	) ) );

	static $backlinks = array(
		array( 'list' => 'backlinks', 'bltitle' => 'AQBT-Links' ),
		array( 'backlinks' => array(
			array( 'pageid' => 1, 'ns' => 0, 'title' => 'AQBT-All' ),
	) ) );

	static $embeddedin = array(
		array( 'list' => 'embeddedin', 'eititle' => 'Template:AQBT-T' ),
		array( 'embeddedin' => array(
			array( 'pageid' => 1, 'ns' => 0, 'title' => 'AQBT-All' ),
			array( 'pageid' => 4, 'ns' => 0, 'title' => 'AQBT-Templates' ),
	) ) );

	static $categorymembers = array(
		array( 'list' => 'categorymembers', 'cmtitle' => 'Category:AQBT-Cat' ),
		array( 'categorymembers' => array(
			array( 'pageid' => 1, 'ns' => 0, 'title' => 'AQBT-All' ),
			array( 'pageid' => 2, 'ns' => 0, 'title' => 'AQBT-Categories' ),
	) ) );

	static $generatorAllpages = array(
		array( 'generator' => 'allpages', 'gapprefix' => 'AQBT-' ),
		array( 'pages' => array(
			'1' => array(
				'pageid' => 1,
				'ns' => 0,
				'title' => 'AQBT-All' ),
			'2' => array(
				'pageid' => 2,
				'ns' => 0,
				'title' => 'AQBT-Categories' ),
			'3' => array(
				'pageid' => 3,
				'ns' => 0,
				'title' => 'AQBT-Links' ),
			'4' => array(
				'pageid' => 4,
				'ns' => 0,
				'title' => 'AQBT-Templates' ),
	) ) );

	static $generatorLinks = array(
		array( 'generator' => 'links', 'titles' => 'AQBT-Links' ),
		array( 'pages' => array(
			'1' => array(
				'pageid' => 1,
				'ns' => 0,
				'title' => 'AQBT-All' ),
			'2' => array(
				'pageid' => 2,
				'ns' => 0,
				'title' => 'AQBT-Categories' ),
			'4' => array(
				'pageid' => 4,
				'ns' => 0,
				'title' => 'AQBT-Templates' ),
	) ) );

	static $generatorLinksPropLinks = array(
		array( 'prop' => 'links' ),
		array( 'pages' => array(
			'1' => array( 'links' => array(
				array( 'ns' => 0, 'title' => 'AQBT-Links' ),
	) ) ) ) );

	static $generatorLinksPropTemplates = array(
		array( 'prop' => 'templates' ),
		array( 'pages' => array(
			'1' => array( 'templates' => array(
				array( 'ns' => 10, 'title' => 'Template:AQBT-T' ) ) ),
			'4' => array( 'templates' => array(
				array( 'ns' => 10, 'title' => 'Template:AQBT-T' ) ) ),
		) ) );

	/**
	 * Test basic props
	 */
	public function testProps() {
		$this->check( self::$links );
		$this->check( self::$templates );
		$this->check( self::$categories );
	}

	/**
	 * Test basic lists
	 */
	public function testLists() {
		$this->check( self::$allpages );
		$this->check( self::$alllinks );
		$this->check( self::$alltransclusions );
		$this->check( self::$allcategories );
		$this->check( self::$backlinks );
		$this->check( self::$embeddedin );
		$this->check( self::$categorymembers );
	}

	/**
	 * Test basic lists
	 */
	public function testAllTogether() {

		// All props together
		$this->check( $this->merge(
			self::$links,
			self::$templates,
			self::$categories
		) );

		// All lists together
		$this->check( $this->merge(
			self::$allpages,
			self::$alllinks,
			self::$alltransclusions,
			self::$allcategories,
			self::$backlinks,
			self::$embeddedin,
			self::$categorymembers
		) );

		// All props+lists together
		$this->check( $this->merge(
			self::$links,
			self::$templates,
			self::$categories,
			self::$allpages,
			self::$alllinks,
			self::$alltransclusions,
			self::$allcategories,
			self::$backlinks,
			self::$embeddedin,
			self::$categorymembers
		) );
	}

	/**
	 * Test basic lists
	 */
	public function testGenerator() {
		// generator=allpages
		$this->check( self::$generatorAllpages );
		// generator=allpages & list=allpages
		$this->check( $this->merge(
			self::$generatorAllpages,
			self::$allpages ) );
		// generator=links
		$this->check( self::$generatorLinks );
		// generator=links & prop=links
		$this->check( $this->merge(
			self::$generatorLinks,
			self::$generatorLinksPropLinks ) );
		// generator=links & prop=templates
		$this->check( $this->merge(
			self::$generatorLinks,
			self::$generatorLinksPropTemplates ) );
		// generator=links & prop=links|templates
		$this->check( $this->merge(
			self::$generatorLinks,
			self::$generatorLinksPropLinks,
			self::$generatorLinksPropTemplates ) );
		// generator=links & prop=links|templates & list=allpages|...
		$this->check( $this->merge(
			self::$generatorLinks,
			self::$generatorLinksPropLinks,
			self::$generatorLinksPropTemplates,
			self::$allpages,
			self::$alllinks,
			self::$alltransclusions,
			self::$allcategories,
			self::$backlinks,
			self::$embeddedin,
			self::$categorymembers ) );
	}

	/**
	 * Merges all requests (parameter arrays) into one
	 * @return array
	 */
	private function merge( /*...*/ ) {
		$request = array();
		$expected = array();
		foreach	( func_get_args() as $v ) {
			$request = array_merge_recursive( $request, $v[0] );
			$this->mergeExpected( $expected, $v[1] );
		}
		return array( $request, $expected );
	}

	/**
	 * Recursively merges the expected values in the $item into the $all
	 */
	private function mergeExpected( &$all, $item ) {
		foreach ( $item as $k => $v ) {
			if ( array_key_exists( $k, $all ) ) {
				if ( is_array ( $all[$k] ) ) {
					$this->mergeExpected( $all[$k], $v );
				} else {
					$this->assertEquals( $all[$k], $v );
				}
			} else {
				$all[$k] = $v;
			}
		}
	}

	/**
	 * Checks that the request's result matches the expected results.
	 * @param $values array is a two element array( request, expected_results )
	 * @throws Exception
	 */
	private function check( $values ) {
		$request = $values[0];
		$expected = $values[1];
		if ( !array_key_exists( 'action', $request ) ) {
			$request['action'] = 'query';
		}
		foreach ( $request as &$val ) {
			if ( is_array( $val ) ) {
				$val = implode( '|', array_unique( $val ) );
			}
		}
		$result = $this->doApiRequest( $request );
		$result = $result[0];
		$expected = array( 'query' => $expected );
		try {
			$this->assertQueryResults( $expected, $result );
		} catch (Exception $e) {
			print("\nRequest:\n");
			print_r( $request );
			print("\nExpected:\n");
			print_r( $expected );
			print("\nResult:\n");
			print_r( $result );
			throw $e; // rethrow it
		}
	}

	/**
	 * Recursively compare arrays, ignoring mismatches in numeric key and pageids.
	 * @param $expected array expected values
	 * @param $result array returned values
	 */
	private function assertQueryResults( $expected, $result ) {
		reset( $expected );
		reset( $result );
		while ( true ) {
			$e = each( $expected );
			$r = each( $result );
			// If either of the arrays is shorter, abort. If both are done, success.
			$this->assertEquals( (bool) $e, (bool) $r );
			if ( !$e ) {
				break; // done
			}
			// continue only if keys are identical or both keys are numeric
			$this->assertTrue( $e['key'] === $r['key'] || ( is_numeric( $e['key'] ) && is_numeric( $r['key'] ) ) );
			// don't compare pageids
			if ( $e['key'] !== 'pageid' ) {
				// If values are arrays, compare recursively, otherwise compare with ===
				if ( is_array( $e['value'] ) && is_array( $r['value'] ) ) {
					$this->assertQueryResults( $e['value'], $r['value'] );
				} else {
					$this->assertEquals( $e['value'], $r['value'] );
				}
			}
		}
	}
}
