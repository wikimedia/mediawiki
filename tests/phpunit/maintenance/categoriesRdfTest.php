<?php

namespace MediaWiki\Tests\Maintenance;

use DumpCategoriesAsRdf;
use MediaWikiLangTestCase;

/**
 * @covers CategoriesRdf
 * @covers DumpCategoriesAsRdf
 */
class CategoriesRdfTest extends MediaWikiLangTestCase {
	public function getCategoryIterator() {
		return [
			// batch 1
			[
				(object)[
					'page_title' => 'Category One',
					'page_id' => 1,
					'pp_propname' => null,
					'cat_pages' => '20',
					'cat_subcats' => '10',
					'cat_files' => '3'
				],
				(object)[
					'page_title' => '2 Category Two',
					'page_id' => 2,
					'pp_propname' => 'hiddencat',
					'cat_pages' => 20,
					'cat_subcats' => 0,
					'cat_files' => 3
				],
			],
			// batch 2
			[
				(object)[
					'page_title' => 'Третья категория',
					'page_id' => 3,
					'pp_propname' => null,
					'cat_pages' => '0',
					'cat_subcats' => '0',
					'cat_files' => '0'
				],
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
		$this->setMwGlobals( [
			'wgServer' => 'http://acme.test',
			'wgCanonicalServer' => 'http://acme.test',
			'wgArticlePath' => '/wiki/$1',
			'wgRightsUrl' => 'https://creativecommons.org/licenses/by-sa/3.0/',
		] );

		$dumpScript =
			$this->getMockBuilder( DumpCategoriesAsRdf::class )
				->setMethods( [ 'getCategoryIterator', 'getCategoryLinksIterator' ] )
				->getMock();

		$dumpScript->expects( $this->once() )
			->method( 'getCategoryIterator' )
			->willReturn( $this->getCategoryIterator() );

		$dumpScript->expects( $this->any() )
			->method( 'getCategoryLinksIterator' )
			->willReturnCallback( [ $this, 'getCategoryLinksIterator' ] );

		/** @var DumpCategoriesAsRdf  $dumpScript */
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
		$actualOut = preg_replace(
			'|<http://acme.test/wiki/Special:CategoryDump> <http://schema.org/dateModified> "[^"]+?"|',
			'<http://acme.test/wiki/Special:CategoryDump> <http://schema.org/dateModified> "{DATE}"',
			$actualOut
		);

		$outFile = __DIR__ . '/../data/categoriesrdf/categoriesRdf-out.nt';
		$this->assertFileContains( $outFile, $actualOut );
	}

}
