<?php

class CategoriesRdfTest extends MediaWikiLangTestCase {
	public function getCategoryIterator() {
		return [
			// batch 1
			[
				(object)[ 'page_title' => 'Category One', 'page_id' => 1 ],
				(object)[ 'page_title' => '2 Category Two', 'page_id' => 2 ],
			],
			// batch 2
			[
				(object)[ 'page_title' => 'Третья категория', 'page_id' => 3 ],
			]
		];
	}

	public function getCategoryLinksIterator( $dbr, array $ids ) {
		$res = [];
		foreach ( $ids as $pageid ) {
			$res[] = (object)[ 'cl_from' => $pageid, 'cl_to' => "Parent of $pageid" ];
		}
		return $res;
	}

	public function testCategoriesDump() {
		$dumpScript =
			$this->getMockBuilder( CategoriesRDF::class )
				->setMethods( [ 'getCategoryIterator', 'getCategoryLinksIterator' ] )
				->getMock();

		$dumpScript->expects( $this->once() )
			->method( 'getCategoryIterator' )
			->willReturn( $this->getCategoryIterator() );

		$dumpScript->expects( $this->any() )
			->method( 'getCategoryLinksIterator' )
			->willReturnCallback( [ $this, 'getCategoryLinksIterator' ] );

		$this->setMwGlobals( [
			'wgServer' => 'http://acme.test',
			'wgCanonicalServer' => 'http://acme.test',
		] );

		/** @var CategoriesRdf  $dumpScript */
		$logFileName = tempnam( sys_get_temp_dir(), "Categories-DumpRdfTest" );
		$outFileName = tempnam( sys_get_temp_dir(), "Categories-DumpRdfTest" );

		$dumpScript->loadParamsAndArgs(
			null,
			[
				'log' => $logFileName,
				'output' => $outFileName,
				'format' => 'nt',
			]
		);

		$dumpScript->execute();
		$actualOut = file_get_contents( $outFileName );

		$outFile = __DIR__ . '/data/categoriesRdf-out.txt';
		if ( !file_exists( $outFile ) ) {
			file_put_contents( $outFile, $actualOut );
			$this->markTestSkipped( 'Data file does not exist' );
		}

		$expectedOut = file_get_contents( $outFile );

		$this->assertEquals(
			$this->fixLineEndings( $expectedOut ),
			$this->fixLineEndings( $actualOut )
		);
	}

	private function fixLineEndings( $string ) {
		return preg_replace( '~(*BSR_ANYCRLF)\R~', "\n", $string );
	}
}
