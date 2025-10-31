<?php

/**
 * Copyright © 2013 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Tests\Api\Query;

use Exception;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiQuery
 */
class ApiQueryContinue2Test extends ApiQueryContinueTestBase {
	/** @var Exception|null */
	protected $exceptionFromAddDBData;

	/**
	 * Create a set of pages. These must not change, otherwise the tests might give wrong results.
	 *
	 * @see MediaWikiIntegrationTestCase::addDBDataOnce()
	 */
	public function addDBDataOnce() {
		try {
			$this->editPage( 'AQCT73462-A', '**AQCT73462-A**  [[AQCT73462-B]] [[AQCT73462-C]]' );
			$this->editPage( 'AQCT73462-B', '[[AQCT73462-A]]  **AQCT73462-B** [[AQCT73462-C]]' );
			$this->editPage( 'AQCT73462-C', '[[AQCT73462-A]]  [[AQCT73462-B]] **AQCT73462-C**' );
			$this->editPage( 'AQCT73462-A', '**AQCT73462-A**  [[AQCT73462-B]] [[AQCT73462-C]]' );
			$this->editPage( 'AQCT73462-B', '[[AQCT73462-A]]  **AQCT73462-B** [[AQCT73462-C]]' );
			$this->editPage( 'AQCT73462-C', '[[AQCT73462-A]]  [[AQCT73462-B]] **AQCT73462-C**' );
		} catch ( Exception $e ) {
			$this->exceptionFromAddDBData = $e;
		}
	}

	/**
	 * @group medium
	 */
	public function testA() {
		$this->mVerbose = false;
		$mk = static function ( $g, $p, $gDir ) {
			return [
				'generator' => 'allpages',
				'gapprefix' => 'AQCT73462-',
				'prop' => 'links',
				'gaplimit' => "$g",
				'pllimit' => "$p",
				'gapdir' => $gDir ? "ascending" : "descending",
			];
		};
		// generator + 1 prop + 1 list
		$data = $this->query( $mk( 99, 99, true ), 1, 'g1p', false ) +
			[ 'batchcomplete' => true ];
		$this->checkC( $data, $mk( 1, 1, true ), 6, 'g1p-11t' );
		$this->checkC( $data, $mk( 2, 2, true ), 3, 'g1p-22t' );
		$this->checkC( $data, $mk( 1, 1, false ), 6, 'g1p-11f' );
		$this->checkC( $data, $mk( 2, 2, false ), 3, 'g1p-22f' );
	}
}
