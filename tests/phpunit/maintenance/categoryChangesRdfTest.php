<?php

/**
 * Tests for CategoryChangesAsRdf recent changes exporter.
 *  @covers CategoryChangesAsRdf
 */
class CategoryChangesRdfTest extends MediaWikiLangTestCase {

	public function setUp() {
		parent::setUp();
		$this->setMwGlobals( [
			'wgServer' => 'http://acme.test',
			'wgCanonicalServer' => 'http://acme.test',
			'wgArticlePath' => '/wiki/$1',
		] );
	}

	public function getCategoryData() {
		return [
			'delete' => [
				'delete',
				'getDeletedCatsIterator',
				'handleDeletes',
				[
					(object)[ 'rc_title' => 'Test', 'rc_cur_id' => 1, '_processed' => 1 ],
					(object)[ 'rc_title' => 'Test 2', 'rc_cur_id' => 2, '_processed' => 2 ],
				],
			],
			'move' => [
				'move',
				'getMovedCatsIterator',
				'handleMoves',
				[
					(object)[
						'rc_title' => 'Test',
						'rc_cur_id' => 4,
						'page_title' => 'MovedTo',
						'page_namespace' => NS_CATEGORY,
						'_processed' => 4
					],
					(object)[
						'rc_title' => 'MovedTo',
						'rc_cur_id' => 4,
						'page_title' => 'MovedAgain',
						'page_namespace' => NS_CATEGORY,
					],
					(object)[
						'rc_title' => 'Test 2',
						'rc_cur_id' => 5,
						'page_title' => 'AlsoMoved',
						'page_namespace' => NS_CATEGORY,
						'_processed' => 5
					],
					(object)[
						'rc_title' => 'Test 3',
						'rc_cur_id' => 6,
						'page_title' => 'MovedOut',
						'page_namespace' => NS_MAIN,
					],
					(object)[
						'rc_title' => 'Test 4',
						'rc_cur_id' => 7,
						'page_title' => 'Already Done',
						'page_namespace' => NS_CATEGORY,
					],
				],
				[ 7 => true ],
			],
			'restore' => [
				'restore',
				'getRestoredCatsIterator',
				'handleRestores',
				[
					(object)[
						'rc_title' => 'Restored cat',
						'rc_cur_id' => 10,
						'_processed' => 10
					],
					(object)[
						'rc_title' => 'Restored again',
						'rc_cur_id' => 10,
					],
					(object)[
						'rc_title' => 'Already seen',
						'rc_cur_id' => 11,
					],
				],
				[ 11 => true ],
			],
			'new' => [
				'new',
				'getNewCatsIterator',
				'handleAdds',
				[
					(object)[
						'rc_title' => 'New category',
						'rc_cur_id' => 20,
						'_processed' => 20
					],
					(object)[
						'rc_title' => 'Новая категория 😃',
						'rc_cur_id' => 21,
						'_processed' => 21
					],
					(object)[
						'rc_title' => 'Processed already',
						'rc_cur_id' => 22,
					],
				],
				[ 22 => true ],
			],
			'change' => [
				'change',
				'getChangedCatsIterator',
				'handleChanges',
				[
					(object)[
						'rc_title' => 'Changed category',
						'rc_cur_id' => 30,
						'_processed' => 30
					],
					(object)[
						'rc_title' => 'Changed again',
						'rc_cur_id' => 30,
					],
					(object)[
						'rc_title' => 'Processed already',
						'rc_cur_id' => 31,
					],
				],
				[ 31 => true ],
			],

		];
	}

	public function getCategoryLinksIterator( $dbr, array $ids ) {
		$res = [];
		foreach ( $ids as $pageid ) {
			$res[] = (object)[ 'cl_from' => $pageid, 'cl_to' => "Parent of $pageid" ];
		}
		return $res;
	}

	/**
	 * @dataProvider getCategoryData
	 * @param $testName
	 * @param $iterator
	 * @param $handler
	 * @param $result
	 * @param array $preProcessed
	 */
	function testSparqlUpdate( $testName, $iterator, $handler, $result, $preProcessed = [] ) {
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
		$dbr = wfGetDB( DB_SLAVE );
		/** @var CategoryChangesAsRdf $dumpScript */
		$dumpScript->getRdf();
		$dumpScript->$handler( $dbr, $output );

		rewind( $output );
		$sparql = stream_get_contents( $output );
		$outFile = __DIR__ . "/../data/categoriesrdf/$testName.sparql";
		$this->assertFileContains( $outFile, $sparql );

		$processed = $processedProperty->getValue( $dumpScript );
		$expectedProcessed = $preProcessed;
		foreach ( $result as $row ) {
			if ( isset( $row->_processed ) ) {
				$this->assertArrayHasKey( $row->_processed, $processed,
					"ID {$row->_processed} was not processed!" );
				$expectedProcessed[] = $row->_processed;
			}
		}
		$this->assertArrayEquals( $expectedProcessed, array_keys( $processed ),
			'Processed array has wrong items' );
	}

	public function testUpdateTs() {
		$dumpScript = new CategoryChangesAsRdf();
		$update = $dumpScript->updateTS( 1503620949 );
		$outFile = __DIR__ . '/../data/categoriesrdf/updatets.txt';
		$this->assertFileContains( $outFile, $update );
	}

}
