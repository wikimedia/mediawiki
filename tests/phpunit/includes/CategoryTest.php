<?php

/**
 * @group Database
 * @group Category
 */
class CategoryTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgAllowUserJs' => false,
			'wgDefaultLanguageVariant' => false,
			'wgMetaNamespace' => 'Project',
		] );
		$this->setUserLang( 'en' );
		$this->setContentLang( 'en' );
	}

	/**
	 * @covers Category::initialize()
	 */
	public function testInitialize_idNotExist() {
		$category = Category::newFromID( -1 );
		$this->assertFalse( $category->getName() );
	}

	public function provideInitializeVariants() {
		return [
			// Existing title
			[ 'newFromName', 'Example', 'getID', 1 ],
			[ 'newFromName', 'Example', 'getName', 'Example' ],
			[ 'newFromName', 'Example', 'getPageCount', 3 ],
			[ 'newFromName', 'Example', 'getSubcatCount', 4 ],
			[ 'newFromName', 'Example', 'getFileCount', 5 ],

			// Non-existing title
			[ 'newFromName', 'NoExample', 'getID', 0 ],
			[ 'newFromName', 'NoExample', 'getName', 'NoExample' ],
			[ 'newFromName', 'NoExample', 'getPageCount', 0 ],
			[ 'newFromName', 'NoExample', 'getSubcatCount', 0 ],
			[ 'newFromName', 'NoExample', 'getFileCount', 0 ],

			// Existing ID
			[ 'newFromID', 1, 'getID', 1 ],
			[ 'newFromID', 1, 'getName', 'Example' ],
			[ 'newFromID', 1, 'getPageCount', 3 ],
			[ 'newFromID', 1, 'getSubcatCount', 4 ],
			[ 'newFromID', 1, 'getFileCount', 5 ]
		];
	}

	/**
	 * @covers Category::initialize()
	 * @dataProvider provideInitializeVariants
	 */
	public function testInitialize( $createFunction, $createParam, $testFunction, $expected ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'category',
			[
				[
					'cat_id' => 1,
					'cat_title' => 'Example',
					'cat_pages' => 3,
					'cat_subcats' => 4,
					'cat_files' => 5
				]
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$category = Category::{$createFunction}( $createParam );
		$this->assertEquals( $expected, $category->{$testFunction}() );

		$dbw->delete( 'category', '*', __METHOD__ );
	}

	/**
	 * @covers Category::newFromName()
	 * @covers Category::getName()
	 */
	public function testNewFromName_validTitle() {
		$category = Category::newFromName( 'Example' );
		$this->assertSame( 'Example', $category->getName() );
	}

	/**
	 * @covers Category::newFromName()
	 */
	public function testNewFromName_invalidTitle() {
		$this->assertFalse( Category::newFromName( '#' ) );
	}

	/**
	 * @covers Category::newFromTitle()
	 */
	public function testNewFromTitle() {
		$title = Title::newFromText( 'Category:Example' );
		$category = Category::newFromTitle( $title );
		$this->assertSame( 'Example', $category->getName() );
	}

	/**
	 * @covers Category::newFromID()
	 * @covers Category::getID()
	 */
	public function testNewFromID() {
		$category = Category::newFromID( 5 );
		$this->assertSame( 5, $category->getID() );
	}

	/**
	 * @covers Category::newFromRow()
	 */
	public function testNewFromRow_found() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'category',
			[
				[
					'cat_id' => 1,
					'cat_title' => 'Example',
					'cat_pages' => 3,
					'cat_subcats' => 4,
					'cat_files' => 5
				]
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$category = Category::newFromRow( $dbw->selectRow(
			'category',
			[ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ],
			[ 'cat_id' => 1 ],
			__METHOD__
		) );

		$this->assertEquals( 1, $category->getID() );

		$dbw->delete( 'category', '*', __METHOD__ );
	}

	/**
	 * @covers Category::newFromRow()
	 */
	public function testNewFromRow_notFoundWithoutTitle() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'category',
			[
				[
					'cat_id' => 1,
					'cat_title' => 'Example',
					'cat_pages' => 3,
					'cat_subcats' => 4,
					'cat_files' => 5
				]
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$row = $dbw->selectRow(
			'category',
			[ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ],
			[ 'cat_id' => 1 ],
			__METHOD__
		);
		$row->cat_title = null;

		$this->assertFalse( Category::newFromRow( $row ) );

		$dbw->delete( 'category', '*', __METHOD__ );
	}

	/**
	 * @covers Category::newFromRow()
	 */
	public function testNewFromRow_notFoundWithTitle() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'category',
			[
				[
					'cat_id' => 1,
					'cat_title' => 'Example',
					'cat_pages' => 3,
					'cat_subcats' => 4,
					'cat_files' => 5
				]
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$row = $dbw->selectRow(
			'category',
			[ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ],
			[ 'cat_id' => 1 ],
			__METHOD__
		);
		$row->cat_title = null;

		$category = Category::newFromRow(
			$row,
			Title::newFromText( NS_CATEGORY, 'Example' )
		);

		$this->assertFalse( $category->getID() );

		$dbw->delete( 'category', '*', __METHOD__ );
	}

	/**
	 * @covers Category::getPageCount()
	 */
	public function testGetPageCount() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'category',
			[
				[
					'cat_id' => 1,
					'cat_title' => 'Example',
					'cat_pages' => 3,
					'cat_subcats' => 4,
					'cat_files' => 5
				]
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$category = Category::newFromID( 1 );
		$this->assertEquals( 3, $category->getPageCount() );

		$dbw->delete( 'category', '*', __METHOD__ );
	}

	/**
	 * @covers Category::getSubcatCount()
	 */
	public function testGetSubcatCount() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'category',
			[
				[
					'cat_id' => 1,
					'cat_title' => 'Example',
					'cat_pages' => 3,
					'cat_subcats' => 4,
					'cat_files' => 5
				]
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$category = Category::newFromID( 1 );
		$this->assertEquals( 4, $category->getSubcatCount() );

		$dbw->delete( 'category', '*', __METHOD__ );
	}

	/**
	 * @covers Category::getFileCount()
	 */
	public function testGetFileCount() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert( 'category',
			[
				[
					'cat_id' => 1,
					'cat_title' => 'Example',
					'cat_pages' => 3,
					'cat_subcats' => 4,
					'cat_files' => 5
				]
			],
			__METHOD__,
			[ 'IGNORE' ]
		);

		$category = Category::newFromID( 1 );
		$this->assertEquals( 5, $category->getFileCount() );

		$dbw->delete( 'category', '*', __METHOD__ );
	}
}
