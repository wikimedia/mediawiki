<?php
/**
 * Copyright © 2013 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
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
 */

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQuery
 */
class ApiQueryContinue2Test extends ApiQueryContinueTestBase {
	protected $exceptionFromAddDBData;

	/**
	 * Create a set of pages. These must not change, otherwise the tests might give wrong results.
	 *
*@see MediaWikiTestCase::addDBDataOnce()
	 */
	function addDBDataOnce() {
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
		$mk = function ( $g, $p, $gDir ) {
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
