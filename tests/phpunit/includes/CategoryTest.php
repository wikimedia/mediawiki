<?php

/**
 * @group Database
 * @group Category
 */
class CategoryTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgAllowUserJs' => false,
			'wgDefaultLanguageVariant' => false,
			'wgMetaNamespace' => 'Project',
		] );
		$this->setUserLang( 'en' );
		$this->setContentLang( 'en' );
		$this->tablesUsed[] = 'category';
	}

	public function addDBData() {
		// Add a row to the 'category' table
		$this->db->insert(
			'category',
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
			[ 'newFromName', 'Example', 'getMemberCount', 3 ],
			[ 'newFromName', 'Example', 'getSubcatCount', 4 ],
			[ 'newFromName', 'Example', 'getFileCount', 5 ],

			// Non-existing title
			[ 'newFromName', 'NoExample', 'getID', 0 ],
			[ 'newFromName', 'NoExample', 'getName', 'NoExample' ],
			[ 'newFromName', 'NoExample', 'getMemberCount', 0 ],
			[ 'newFromName', 'NoExample', 'getSubcatCount', 0 ],
			[ 'newFromName', 'NoExample', 'getFileCount', 0 ],

			// Existing ID
			[ 'newFromID', 1, 'getID', 1 ],
			[ 'newFromID', 1, 'getName', 'Example' ],
			[ 'newFromID', 1, 'getMemberCount', 3 ],
			[ 'newFromID', 1, 'getSubcatCount', 4 ],
			[ 'newFromID', 1, 'getFileCount', 5 ]
		];
	}

	/**
	 * @covers Category::initialize()
	 * @dataProvider provideInitializeVariants
	 */
	public function testInitialize( $createFunction, $createParam, $testFunction, $expected ) {
		$category = Category::{$createFunction}( $createParam );
		$this->assertEquals( $expected, $category->{$testFunction}() );
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
		$this->assertTrue( $title->isSamePageAs( $category->getPage() ) );
		$this->assertTrue( $title->isSamePageAs( $category->getTitle() ) );
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
		$dbw = wfGetDB( DB_PRIMARY );

		$category = Category::newFromRow( $dbw->selectRow(
			'category',
			[ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ],
			[ 'cat_id' => 1 ],
			__METHOD__
		) );

		$this->assertSame( '1', $category->getID() );
	}

	/**
	 * @covers Category::newFromRow()
	 */
	public function testNewFromRow_notFoundWithoutTitle() {
		$dbw = wfGetDB( DB_PRIMARY );

		$row = $dbw->selectRow(
			'category',
			[ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ],
			[ 'cat_id' => 1 ],
			__METHOD__
		);
		$row->cat_title = null;

		$this->assertFalse( Category::newFromRow( $row ) );
	}

	/**
	 * @covers Category::newFromRow()
	 */
	public function testNewFromRow_notFoundWithTitle() {
		$dbw = wfGetDB( DB_PRIMARY );

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
	}

	/**
	 * @covers Category::getMemberCount()
	 * @covers Category::getSubcatCount()
	 * @covers Category::getFileCount()
	 */
	public function testGetCounts() {
		// See data set in addDBDataOnce
		$category = Category::newFromID( 1 );
		$this->assertEquals( 3, $category->getMemberCount() );
		$this->assertEquals( 4, $category->getSubcatCount() );
		$this->assertEquals( 5, $category->getFileCount() );
	}

}
