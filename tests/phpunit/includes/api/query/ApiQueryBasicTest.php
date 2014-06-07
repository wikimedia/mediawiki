<?php
/**
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
 */

require_once 'ApiQueryTestBase.php';

/**
 * These tests validate basic functionality of the api query module
 *
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQuery
 */
class ApiQueryBasicTest extends ApiQueryTestBase {
	protected $exceptionFromAddDBData;

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

	private static $links = array(
		array( 'prop' => 'links', 'titles' => 'AQBT-All' ),
		array( 'pages' => array(
			'1' => array(
				'pageid' => 1,
				'ns' => 0,
				'title' => 'AQBT-All',
				'links' => array(
					array( 'ns' => 0, 'title' => 'AQBT-Links' ),
				)
			)
		) )
	);

	private static $templates = array(
		array( 'prop' => 'templates', 'titles' => 'AQBT-All' ),
		array( 'pages' => array(
			'1' => array(
				'pageid' => 1,
				'ns' => 0,
				'title' => 'AQBT-All',
				'templates' => array(
					array( 'ns' => 10, 'title' => 'Template:AQBT-T' ),
				)
			)
		) )
	);

	private static $categories = array(
		array( 'prop' => 'categories', 'titles' => 'AQBT-All' ),
		array( 'pages' => array(
			'1' => array(
				'pageid' => 1,
				'ns' => 0,
				'title' => 'AQBT-All',
				'categories' => array(
					array( 'ns' => 14, 'title' => 'Category:AQBT-Cat' ),
				)
			)
		) )
	);

	private static $allpages = array(
		array( 'list' => 'allpages', 'apprefix' => 'AQBT-' ),
		array( 'allpages' => array(
			array( 'pageid' => 1, 'ns' => 0, 'title' => 'AQBT-All' ),
			array( 'pageid' => 2, 'ns' => 0, 'title' => 'AQBT-Categories' ),
			array( 'pageid' => 3, 'ns' => 0, 'title' => 'AQBT-Links' ),
			array( 'pageid' => 4, 'ns' => 0, 'title' => 'AQBT-Templates' ),
		) )
	);

	private static $alllinks = array(
		array( 'list' => 'alllinks', 'alprefix' => 'AQBT-' ),
		array( 'alllinks' => array(
			array( 'ns' => 0, 'title' => 'AQBT-All' ),
			array( 'ns' => 0, 'title' => 'AQBT-Categories' ),
			array( 'ns' => 0, 'title' => 'AQBT-Links' ),
			array( 'ns' => 0, 'title' => 'AQBT-Templates' ),
		) )
	);

	private static $alltransclusions = array(
		array( 'list' => 'alltransclusions', 'atprefix' => 'AQBT-' ),
		array( 'alltransclusions' => array(
			array( 'ns' => 10, 'title' => 'Template:AQBT-T' ),
			array( 'ns' => 10, 'title' => 'Template:AQBT-T' ),
		) )
	);

	// Although this appears to have no use it is used by testLists()
	private static $allcategories = array(
		array( 'list' => 'allcategories', 'acprefix' => 'AQBT-' ),
		array( 'allcategories' => array(
			array( '*' => 'AQBT-Cat' ),
		) )
	);

	private static $backlinks = array(
		array( 'list' => 'backlinks', 'bltitle' => 'AQBT-Links' ),
		array( 'backlinks' => array(
			array( 'pageid' => 1, 'ns' => 0, 'title' => 'AQBT-All' ),
		) )
	);

	private static $embeddedin = array(
		array( 'list' => 'embeddedin', 'eititle' => 'Template:AQBT-T' ),
		array( 'embeddedin' => array(
			array( 'pageid' => 1, 'ns' => 0, 'title' => 'AQBT-All' ),
			array( 'pageid' => 4, 'ns' => 0, 'title' => 'AQBT-Templates' ),
		) )
	);

	private static $categorymembers = array(
		array( 'list' => 'categorymembers', 'cmtitle' => 'Category:AQBT-Cat' ),
		array( 'categorymembers' => array(
			array( 'pageid' => 1, 'ns' => 0, 'title' => 'AQBT-All' ),
			array( 'pageid' => 2, 'ns' => 0, 'title' => 'AQBT-Categories' ),
		) )
	);

	private static $generatorAllpages = array(
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
		) )
	);

	private static $generatorLinks = array(
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
		) )
	);

	private static $generatorLinksPropLinks = array(
		array( 'prop' => 'links' ),
		array( 'pages' => array(
			'1' => array( 'links' => array(
				array( 'ns' => 0, 'title' => 'AQBT-Links' ),
			) )
		) )
	);

	private static $generatorLinksPropTemplates = array(
		array( 'prop' => 'templates' ),
		array( 'pages' => array(
			'1' => array( 'templates' => array(
				array( 'ns' => 10, 'title' => 'Template:AQBT-T' ) ) ),
			'4' => array( 'templates' => array(
				array( 'ns' => 10, 'title' => 'Template:AQBT-T' ) ) ),
		) )
	);

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
		// This test is temporarily disabled until a sqlite bug is fixed
		// Confirmed still broken 15-nov-2013
		// $this->check( self::$allcategories );
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
			// This test is temporarily disabled until a sqlite bug is fixed
			// self::$allcategories,
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
			// This test is temporarily disabled until a sqlite bug is fixed
			// self::$allcategories,
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
			// This test is temporarily disabled until a sqlite bug is fixed
			// self::$allcategories,
			self::$backlinks,
			self::$embeddedin,
			self::$categorymembers ) );
	}

	/**
	 * Test bug 51821
	 */
	public function testGeneratorRedirects() {
		$this->editPage( 'AQBT-Target', 'test' );
		$this->editPage( 'AQBT-Redir', '#REDIRECT [[AQBT-Target]]' );
		$this->check( array(
			array( 'generator' => 'backlinks', 'gbltitle' => 'AQBT-Target', 'redirects' => '1' ),
			array(
				'redirects' => array(
					array(
						'from' => 'AQBT-Redir',
						'to' => 'AQBT-Target',
					)
				),
				'pages' => array(
					'6' => array(
						'pageid' => 6,
						'ns' => 0,
						'title' => 'AQBT-Target',
					)
				),
			)
		) );
	}
}
