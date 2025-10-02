<?php
/**
 * Copyright © 2013 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Api\Query;

use Exception;
use MediaWiki\Api\ApiQueryBase;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Language\RawMessage;
use MediaWiki\Title\Title;

/**
 * These tests validate basic functionality of the api query module
 *
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiQuery
 */
class ApiQueryBasicTest extends ApiQueryTestBase {
	/** @var Exception|null */
	protected $exceptionFromAddDBData;

	/**
	 * Create a set of pages. These must not change, otherwise the tests might give wrong results.
	 *
	 * @see MediaWikiIntegrationTestCase::addDBDataOnce()
	 */
	public function addDBDataOnce() {
		try {
			if ( Title::makeTitle( NS_MAIN, 'AQBT-All' )->exists() ) {
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
			$this->runJobs();
		} catch ( Exception $e ) {
			$this->exceptionFromAddDBData = $e;
		}
	}

	private const LINKS = [
		[ 'prop' => 'links', 'titles' => 'AQBT-All' ],
		[ 'pages' => [
			'1' => [
				'pageid' => 1,
				'ns' => NS_MAIN,
				'title' => 'AQBT-All',
				'links' => [
					[ 'ns' => NS_MAIN, 'title' => 'AQBT-Links' ],
				]
			]
		] ]
	];

	private const TEMPLATES = [
		[ 'prop' => 'templates', 'titles' => 'AQBT-All' ],
		[ 'pages' => [
			'1' => [
				'pageid' => 1,
				'ns' => NS_MAIN,
				'title' => 'AQBT-All',
				'templates' => [
					[ 'ns' => NS_TEMPLATE, 'title' => 'Template:AQBT-T' ],
				]
			]
		] ]
	];

	private const CATEGORIES = [
		[ 'prop' => 'categories', 'titles' => 'AQBT-All' ],
		[ 'pages' => [
			'1' => [
				'pageid' => 1,
				'ns' => NS_MAIN,
				'title' => 'AQBT-All',
				'categories' => [
					[ 'ns' => NS_CATEGORY, 'title' => 'Category:AQBT-Cat' ],
				]
			]
		] ]
	];

	private const ALLPAGES = [
		[ 'list' => 'allpages', 'apprefix' => 'AQBT-' ],
		[ 'allpages' => [
			[ 'pageid' => 1, 'ns' => NS_MAIN, 'title' => 'AQBT-All' ],
			[ 'pageid' => 2, 'ns' => NS_MAIN, 'title' => 'AQBT-Categories' ],
			[ 'pageid' => 3, 'ns' => NS_MAIN, 'title' => 'AQBT-Links' ],
			[ 'pageid' => 4, 'ns' => NS_MAIN, 'title' => 'AQBT-Templates' ],
		] ]
	];

	private const ALLLINKS = [
		[ 'list' => 'alllinks', 'alprefix' => 'AQBT-' ],
		[ 'alllinks' => [
			[ 'ns' => NS_MAIN, 'title' => 'AQBT-Links' ],
			[ 'ns' => NS_MAIN, 'title' => 'AQBT-All' ],
			[ 'ns' => NS_MAIN, 'title' => 'AQBT-Categories' ],
			[ 'ns' => NS_MAIN, 'title' => 'AQBT-Templates' ],
		] ]
	];

	private const ALLTRANSCLUSIONS = [
		[ 'list' => 'alltransclusions', 'atprefix' => 'AQBT-' ],
		[ 'alltransclusions' => [
			[ 'ns' => NS_TEMPLATE, 'title' => 'Template:AQBT-T' ],
			[ 'ns' => NS_TEMPLATE, 'title' => 'Template:AQBT-T' ],
		] ]
	];

	/** Although this appears to have no use it is used by testLists() */
	private const ALLCATEGORIES = [
		[ 'list' => 'allcategories', 'acprefix' => 'AQBT-' ],
		[ 'allcategories' => [
			[ 'category' => 'AQBT-Cat' ],
		] ]
	];

	private const BACKLINKS = [
		[ 'list' => 'backlinks', 'bltitle' => 'AQBT-Links' ],
		[ 'backlinks' => [
			[ 'pageid' => 1, 'ns' => NS_MAIN, 'title' => 'AQBT-All' ],
		] ]
	];

	private const EMBEDDEDIN = [
		[ 'list' => 'embeddedin', 'eititle' => 'Template:AQBT-T' ],
		[ 'embeddedin' => [
			[ 'pageid' => 1, 'ns' => NS_MAIN, 'title' => 'AQBT-All' ],
			[ 'pageid' => 4, 'ns' => NS_MAIN, 'title' => 'AQBT-Templates' ],
		] ]
	];

	private const CATEGORYMEMBERS = [
		[ 'list' => 'categorymembers', 'cmtitle' => 'Category:AQBT-Cat' ],
		[ 'categorymembers' => [
			[ 'pageid' => 1, 'ns' => NS_MAIN, 'title' => 'AQBT-All' ],
			[ 'pageid' => 2, 'ns' => NS_MAIN, 'title' => 'AQBT-Categories' ],
		] ]
	];

	private const GENERATOR_ALLPAGES = [
		[ 'generator' => 'allpages', 'gapprefix' => 'AQBT-' ],
		[ 'pages' => [
			'1' => [
				'pageid' => 1,
				'ns' => NS_MAIN,
				'title' => 'AQBT-All' ],
			'2' => [
				'pageid' => 2,
				'ns' => NS_MAIN,
				'title' => 'AQBT-Categories' ],
			'3' => [
				'pageid' => 3,
				'ns' => NS_MAIN,
				'title' => 'AQBT-Links' ],
			'4' => [
				'pageid' => 4,
				'ns' => NS_MAIN,
				'title' => 'AQBT-Templates' ],
		] ]
	];

	private const GENERATOR_LINKS = [
		[ 'generator' => 'links', 'titles' => 'AQBT-Links' ],
		[ 'pages' => [
			'1' => [
				'pageid' => 1,
				'ns' => NS_MAIN,
				'title' => 'AQBT-All' ],
			'2' => [
				'pageid' => 2,
				'ns' => NS_MAIN,
				'title' => 'AQBT-Categories' ],
			'4' => [
				'pageid' => 4,
				'ns' => NS_MAIN,
				'title' => 'AQBT-Templates' ],
		] ]
	];

	private const GENERATOR_LINKS_PROP_LINKS = [
		[ 'prop' => 'links' ],
		[ 'pages' => [
			'1' => [ 'links' => [
				[ 'ns' => NS_MAIN, 'title' => 'AQBT-Links' ],
			] ]
		] ]
	];

	private const GENERATOR_LINKS_PROP_TEMPLATES = [
		[ 'prop' => 'templates' ],
		[ 'pages' => [
			'1' => [ 'templates' => [
				[ 'ns' => NS_TEMPLATE, 'title' => 'Template:AQBT-T' ] ] ],
			'4' => [ 'templates' => [
				[ 'ns' => NS_TEMPLATE, 'title' => 'Template:AQBT-T' ] ] ],
		] ]
	];

	/**
	 * Test basic props
	 */
	public function testProps() {
		$this->check( self::LINKS );
		$this->check( self::TEMPLATES );
		$this->check( self::CATEGORIES );
	}

	/**
	 * Test basic lists
	 */
	public function testLists() {
		$this->check( self::ALLPAGES );
		$this->check( self::ALLLINKS );
		$this->check( self::ALLTRANSCLUSIONS );
		$this->check( self::ALLCATEGORIES );
		$this->check( self::BACKLINKS );
		$this->check( self::EMBEDDEDIN );
		$this->check( self::CATEGORYMEMBERS );
	}

	/**
	 * Test basic lists
	 */
	public function testAllTogether() {
		// All props together
		$this->check( $this->merge(
			self::LINKS,
			self::TEMPLATES,
			self::CATEGORIES
		) );

		// All lists together
		$this->check( $this->merge(
			self::ALLPAGES,
			self::ALLLINKS,
			self::ALLTRANSCLUSIONS,
			// This test is temporarily disabled until a sqlite bug is fixed
			// self::ALLCATEGORIES,
			self::BACKLINKS,
			self::EMBEDDEDIN,
			self::CATEGORYMEMBERS
		) );

		// All props+lists together
		$this->check( $this->merge(
			self::LINKS,
			self::TEMPLATES,
			self::CATEGORIES,
			self::ALLPAGES,
			self::ALLLINKS,
			self::ALLTRANSCLUSIONS,
			// This test is temporarily disabled until a sqlite bug is fixed
			// self::ALLCATEGORIES,
			self::BACKLINKS,
			self::EMBEDDEDIN,
			self::CATEGORYMEMBERS
		) );
	}

	/**
	 * Test basic lists
	 */
	public function testGenerator() {
		// generator=allpages
		$this->check( self::GENERATOR_ALLPAGES );
		// generator=allpages & list=allpages
		$this->check( $this->merge(
			self::GENERATOR_ALLPAGES,
			self::ALLPAGES ) );
		// generator=links
		$this->check( self::GENERATOR_LINKS );
		// generator=links & prop=links
		$this->check( $this->merge(
			self::GENERATOR_LINKS,
			self::GENERATOR_LINKS_PROP_LINKS ) );
		// generator=links & prop=templates
		$this->check( $this->merge(
			self::GENERATOR_LINKS,
			self::GENERATOR_LINKS_PROP_TEMPLATES ) );
		// generator=links & prop=links|templates
		$this->check( $this->merge(
			self::GENERATOR_LINKS,
			self::GENERATOR_LINKS_PROP_LINKS,
			self::GENERATOR_LINKS_PROP_TEMPLATES ) );
		// generator=links & prop=links|templates & list=allpages|...
		$this->check( $this->merge(
			self::GENERATOR_LINKS,
			self::GENERATOR_LINKS_PROP_LINKS,
			self::GENERATOR_LINKS_PROP_TEMPLATES,
			self::ALLPAGES,
			self::ALLLINKS,
			self::ALLTRANSCLUSIONS,
			// This test is temporarily disabled until a sqlite bug is fixed
			// self::ALLCATEGORIES,
			self::BACKLINKS,
			self::EMBEDDEDIN,
			self::CATEGORYMEMBERS ) );
	}

	/**
	 * Test T53821
	 */
	public function testGeneratorRedirects() {
		$this->editPage( 'AQBT-Target', 'test' );
		$this->editPage( 'AQBT-Redir', '#REDIRECT [[AQBT-Target]]' );
		$this->check( [
			[ 'generator' => 'backlinks', 'gbltitle' => 'AQBT-Target', 'redirects' => '1' ],
			[
				'redirects' => [
					[
						'from' => 'AQBT-Redir',
						'to' => 'AQBT-Target',
					]
				],
				'pages' => [
					'6' => [
						'pageid' => 6,
						'ns' => NS_MAIN,
						'title' => 'AQBT-Target',
					]
				],
			]
		] );
	}

	public function testApiQueryCheckCanExecute() {
		$this->setTemporaryHook( 'ApiQueryCheckCanExecute',
			function ( $modules, $authority, &$message ) {
				$moduleNames = array_map( static fn ( ApiQueryBase $module ) => $module->getModuleName(), $modules );
				$this->assertArrayEquals( [ 'links', 'templates', 'categories' ], $moduleNames );
				$message = new RawMessage( 'Prevented by hook' );
				return false;
			}
		);
		$e = null;
		try {
			$this->doApiRequest( [
				'action' => 'query',
				'prop' => 'links|templates|categories',
				'titles' => 'Main Page',
			] );
		} catch ( ApiUsageException $e ) {
			$this->assertSame( 'Prevented by hook', $e->getMessage() );
		}
	}
}
