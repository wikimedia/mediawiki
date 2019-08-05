<?php

use Wikimedia\Rdbms\IDatabase;

/**
 * Tests for CategoryChangesAsRdf recent changes exporter.
 * @covers CategoryChangesAsRdf
 */
class CategoryChangesAsRdfTest extends MediaWikiLangTestCase {

	public function setUp() {
		parent::setUp();
		$this->setMwGlobals( [
			'wgServer' => 'http://acme.test',
			'wgCanonicalServer' => 'http://acme.test',
			'wgArticlePath' => '/wiki/$1',
		] );
	}

	public function provideCategoryData() {
		return [
			'delete category' => [
				__DIR__ . "/../data/categoriesrdf/delete.sparql",
				'getDeletedCatsIterator',
				'handleDeletes',
				[
					(object)[ 'rc_title' => 'Test', 'rc_cur_id' => 1, '_processed' => 1 ],
					(object)[ 'rc_title' => 'Test 2', 'rc_cur_id' => 2, '_processed' => 2 ],
				],
			],
			'move category' => [
				__DIR__ . "/../data/categoriesrdf/move.sparql",
				'getMovedCatsIterator',
				'handleMoves',
				[
					(object)[
						'rc_title' => 'Test',
						'rc_cur_id' => 4,
						'page_title' => 'MovedTo',
						'page_namespace' => NS_CATEGORY,
						'_processed' => 4,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'MovedTo',
						'rc_cur_id' => 4,
						'page_title' => 'MovedAgain',
						'page_namespace' => NS_CATEGORY,
						'pp_propname' => 'hiddencat',
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'Test 2',
						'rc_cur_id' => 5,
						'page_title' => 'AlsoMoved',
						'page_namespace' => NS_CATEGORY,
						'_processed' => 5,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'Test 3',
						'rc_cur_id' => 6,
						'page_title' => 'MovedOut',
						'page_namespace' => NS_MAIN,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'Test 4',
						'rc_cur_id' => 7,
						'page_title' => 'Already Done',
						'page_namespace' => NS_CATEGORY,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
				],
				[ 7 => true ],
			],
			'restore deleted category' => [
				__DIR__ . "/../data/categoriesrdf/restore.sparql",
				'getRestoredCatsIterator',
				'handleRestores',
				[
					(object)[
						'rc_title' => 'Restored cat',
						'rc_cur_id' => 10,
						'_processed' => 10,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'Restored again',
						'rc_cur_id' => 10,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'Already seen',
						'rc_cur_id' => 11,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
				],
				[ 11 => true ],
			],
			'new page' => [
				__DIR__ . "/../data/categoriesrdf/new.sparql",
				'getNewCatsIterator',
				'handleAdds',
				[
					(object)[
						'rc_title' => 'New category',
						'rc_cur_id' => 20,
						'_processed' => 20,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'Новая категория 😃',
						'rc_cur_id' => 21,
						'_processed' => 21,
						'pp_propname' => 'hiddencat',
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'Processed already',
						'rc_cur_id' => 22,
					],
				],
				[ 22 => true ],
			],
			'edit category' => [
				__DIR__ . "/../data/categoriesrdf/edit.sparql",
				'getChangedCatsIterator',
				'handleEdits',
				[
					(object)[
						'rc_title' => 'Changed category',
						'rc_cur_id' => 30,
						'_processed' => 30,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'Changed again',
						'rc_cur_id' => 30,
						'pp_propname' => null,
						'cat_pages' => 12,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
					(object)[
						'rc_title' => 'Processed already',
						'rc_cur_id' => 31,
						'pp_propname' => null,
						'cat_pages' => 10,
						'cat_subcats' => 2,
						'cat_files' => 1,
					],
				],
				[ 31 => true ],
			],
			// TODO: not sure how to test categorization changes, it uses the database select...
		];
	}

	/**
	 * Mock category links iterator.
	 * @param IDatabase $dbr
	 * @param array $ids
	 * @return array
	 */
	public function getCategoryLinksIterator( $dbr, array $ids ) {
		$res = [];
		foreach ( $ids as $pageid ) {
			$res[] = (object)[ 'cl_from' => $pageid, 'cl_to' => "Parent of $pageid" ];
		}
		return $res;
	}

	/**
	 * @dataProvider provideCategoryData
	 * @param string $testFileName Name of the test, defines filename with expected results.
	 * @param string $iterator Iterator method name to mock
	 * @param string $handler Handler method to call
	 * @param array $result Result to be returned from mock iterator
	 * @param array $preProcessed List of pre-processed items
	 */
	public function testSparqlUpdate( $testFileName, $iterator, $handler, $result,
			array $preProcessed = [] ) {
		$dumpScript =
			$this->getMockBuilder( CategoryChangesAsRdf::class )
				->setMethods( [ $iterator, 'getCategoryLinksIterator' ] )
				->getMock();

		$dumpScript->expects( $this->any() )
			->method( 'getCategoryLinksIterator' )
			->willReturnCallback( [ $this, 'getCategoryLinksIterator' ] );

		$dumpScript->expects( $this->once() )
			->method( $iterator )
			->willReturn( [ $result ] );

		$ref = new ReflectionObject( $dumpScript );
		$processedProperty = $ref->getProperty( 'processed' );
		$processedProperty->setAccessible( true );
		$processedProperty->setValue( $dumpScript, $preProcessed );

		$output = fopen( "php://memory", "w+b" );
		$dbr = wfGetDB( DB_REPLICA );
		/** @var CategoryChangesAsRdf $dumpScript */
		$dumpScript->initialize();
		$dumpScript->getRdf();
		$dumpScript->$handler( $dbr, $output );

		rewind( $output );
		$sparql = stream_get_contents( $output );
		$this->assertFileContains( $testFileName, $sparql );

		$processed = $processedProperty->getValue( $dumpScript );
		$expectedProcessed = array_keys( $preProcessed );
		foreach ( $result as $row ) {
			if ( isset( $row->_processed ) ) {
				$this->assertArrayHasKey( $row->_processed, $processed,
					"ID {$row->_processed} was not processed!" );
				$expectedProcessed[] = $row->_processed;
			}
		}
		$this->assertSame( $expectedProcessed, array_keys( $processed ),
			'Processed array has wrong items' );
	}

	public function testUpdateTs() {
		$dumpScript = new CategoryChangesAsRdf();
		$dumpScript->initialize();
		$update = $dumpScript->updateTS( 1503620949 );
		$outFile = __DIR__ . '/../data/categoriesrdf/updatets.txt';
		$this->assertFileContains( $outFile, $update );
	}

}
