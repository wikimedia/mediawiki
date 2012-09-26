<?php
/**
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
 */

require_once 'ApiQueryContinueTestBase.php';

/**
 * These tests validate the new continue functionality of the api query module by
 * doing multiple requests with varying parameters, merging the results, and checking
 * that the result matches the full data received in one no-limits call.
 *
 * @group API
 * @group Database
 * @group medium
 */
class ApiQueryContinueTest extends ApiQueryContinueTestBase {
	/**
	 * Create a set of pages. These must not change, otherwise the tests might give wrong results.
	 * @see MediaWikiTestCase::addDBData()
	 */
	function addDBData() {
		try {
			$this->editPage( 'Template:AQCT-T1', '**Template:AQCT-T1**' );
			$this->editPage( 'Template:AQCT-T2', '**Template:AQCT-T2**' );
			$this->editPage( 'Template:AQCT-T3', '**Template:AQCT-T3**' );
			$this->editPage( 'Template:AQCT-T4', '**Template:AQCT-T4**' );
			$this->editPage( 'Template:AQCT-T5', '**Template:AQCT-T5**' );

			$this->editPage( 'AQCT-1', '**AQCT-1** {{AQCT-T2}} {{AQCT-T3}} {{AQCT-T4}} {{AQCT-T5}}' );
			$this->editPage( 'AQCT-2', '[[AQCT-1]] **AQCT-2** {{AQCT-T3}} {{AQCT-T4}} {{AQCT-T5}}' );
			$this->editPage( 'AQCT-3', '[[AQCT-1]] [[AQCT-2]] **AQCT-3** {{AQCT-T4}} {{AQCT-T5}}' );
			$this->editPage( 'AQCT-4', '[[AQCT-1]] [[AQCT-2]] [[AQCT-3]] **AQCT-4** {{AQCT-T5}}' );
			$this->editPage( 'AQCT-5', '[[AQCT-1]] [[AQCT-2]] [[AQCT-3]] [[AQCT-4]] **AQCT-5**' );
		} catch ( Exception $e ) {
			$this->exceptionFromAddDBData = $e;
		}
	}

	/**
	 * Test smart continue - list=allpages
	 * @medium
	 */
	public function test1List() {
		$this->mVerbose = false;
		$mk = function ( $l ) {
			return array(
				'list' => 'allpages',
				'apprefix' => 'AQCT-',
				'aplimit' => "$l",
			);
		};
		$data = $this->query( $mk( 99 ), 1, '1L', false );

		// 1 list
		$this->checkC( $data, $mk( 1 ), 5, '1L-1' );
		$this->checkC( $data, $mk( 2 ), 3, '1L-2' );
		$this->checkC( $data, $mk( 3 ), 2, '1L-3' );
		$this->checkC( $data, $mk( 4 ), 2, '1L-4' );
		$this->checkC( $data, $mk( 5 ), 1, '1L-5' );
	}

	/**
	 * Test smart continue - list=allpages|alltransclusions
	 * @medium
	 */
	public function test2Lists() {
		$this->mVerbose = false;
		$mk = function ( $l1, $l2 ) {
			return array(
				'list' => 'allpages|alltransclusions',
				'apprefix' => 'AQCT-',
				'atprefix' => 'AQCT-',
				'atunique' => '',
				'aplimit' => "$l1",
				'atlimit' => "$l2",
			);
		};
		// 2 lists
		$data = $this->query( $mk( 99, 99 ), 1, '2L', false );
		$this->checkC( $data, $mk( 1, 1 ), 5, '2L-11' );
		$this->checkC( $data, $mk( 2, 2 ), 3, '2L-22' );
		$this->checkC( $data, $mk( 3, 3 ), 2, '2L-33' );
		$this->checkC( $data, $mk( 4, 4 ), 2, '2L-44' );
		$this->checkC( $data, $mk( 5, 5 ), 1, '2L-55' );
	}

	/**
	 * Test smart continue - generator=allpages, prop=links
	 * @medium
	 */
	public function testGen1Prop() {
		$this->mVerbose = false;
		$mk = function ( $g, $p ) {
			return array(
				'generator' => 'allpages',
				'gapprefix' => 'AQCT-',
				'gaplimit' => "$g",
				'prop' => 'links',
				'pllimit' => "$p",
			);
		};
		// generator + 1 prop
		$data = $this->query( $mk( 99, 99 ), 1, 'G1P', false );
		$this->checkC( $data, $mk( 1, 1 ), 11, 'G1P-11' );
		$this->checkC( $data, $mk( 2, 2 ), 6, 'G1P-22' );
		$this->checkC( $data, $mk( 3, 3 ), 4, 'G1P-33' );
		$this->checkC( $data, $mk( 4, 4 ), 3, 'G1P-44' );
		$this->checkC( $data, $mk( 5, 5 ), 2, 'G1P-55' );
	}

	/**
	 * Test smart continue - generator=allpages, prop=links|templates
	 * @medium
	 */
	public function testGen2Prop() {
		$this->mVerbose = false;
		$mk = function ( $g, $p1, $p2 ) {
			return array(
				'generator' => 'allpages',
				'gapprefix' => 'AQCT-',
				'gaplimit' => "$g",
				'prop' => 'links|templates',
				'pllimit' => "$p1",
				'tllimit' => "$p2",
			);
		};
		// generator + 2 props
		$data = $this->query( $mk( 99, 99, 99 ), 1, 'G2P', false );
		$this->checkC( $data, $mk( 1, 1, 1 ), 16, 'G2P-111' );
		$this->checkC( $data, $mk( 2, 2, 2 ), 9, 'G2P-222' );
		$this->checkC( $data, $mk( 3, 3, 3 ), 6, 'G2P-333' );
		$this->checkC( $data, $mk( 4, 4, 4 ), 4, 'G2P-444' );
		$this->checkC( $data, $mk( 5, 5, 5 ), 2, 'G2P-555' );
		$this->checkC( $data, $mk( 5, 1, 1 ), 10, 'G2P-511' );
		$this->checkC( $data, $mk( 4, 2, 2 ), 7, 'G2P-422' );
		$this->checkC( $data, $mk( 2, 3, 3 ), 7, 'G2P-233' );
		$this->checkC( $data, $mk( 2, 4, 4 ), 5, 'G2P-244' );
		$this->checkC( $data, $mk( 1, 5, 5 ), 5, 'G2P-155' );
	}

	/**
	 * Test smart continue - generator=allpages, prop=links, list=alltransclusions
	 * @medium
	 */
	public function testGen1Prop1List() {
		$this->mVerbose = false;
		$mk = function ( $g, $p, $l ) {
			return array(
				'generator' => 'allpages',
				'gapprefix' => 'AQCT-',
				'gaplimit' => "$g",
				'prop' => 'links',
				'pllimit' => "$p",
				'list' => 'alltransclusions',
				'atprefix' => 'AQCT-',
				'atunique' => '',
				'atlimit' => "$l",
			);
		};
		// generator + 1 prop + 1 list
		$data = $this->query( $mk( 99, 99, 99 ), 1, 'G1P1L', false );
		$this->checkC( $data, $mk( 1, 1, 1 ), 11, 'G1P1L-111' );
		$this->checkC( $data, $mk( 2, 2, 2 ), 6, 'G1P1L-222' );
		$this->checkC( $data, $mk( 3, 3, 3 ), 4, 'G1P1L-333' );
		$this->checkC( $data, $mk( 4, 4, 4 ), 3, 'G1P1L-444' );
		$this->checkC( $data, $mk( 5, 5, 5 ), 2, 'G1P1L-555' );
		$this->checkC( $data, $mk( 5, 5, 1 ), 4, 'G1P1L-551' );
		$this->checkC( $data, $mk( 5, 5, 2 ), 2, 'G1P1L-552' );
	}

	/**
	 * Test smart continue - generator=allpages, prop=links|templates,
	 *                       list=alllinks|alltransclusions, meta=siteinfo
	 * @medium
	 */
	public function testGen2Prop2List1Meta() {
		$this->mVerbose = false;
		$mk = function ( $g, $p1, $p2, $l1, $l2 ) {
			return array(
				'generator' => 'allpages',
				'gapprefix' => 'AQCT-',
				'gaplimit' => "$g",
				'prop' => 'links|templates',
				'pllimit' => "$p1",
				'tllimit' => "$p2",
				'list' => 'alllinks|alltransclusions',
				'alprefix' => 'AQCT-',
				'alunique' => '',
				'allimit' => "$l1",
				'atprefix' => 'AQCT-',
				'atunique' => '',
				'atlimit' => "$l2",
				'meta' => 'siteinfo',
				'siprop' => 'namespaces',
			);
		};
		// generator + 1 prop + 1 list
		$data = $this->query( $mk( 99, 99, 99, 99, 99 ), 1, 'G2P2L1M', false );
		$this->checkC( $data, $mk( 1, 1, 1, 1, 1 ), 16, 'G2P2L1M-11111' );
		$this->checkC( $data, $mk( 2, 2, 2, 2, 2 ), 9, 'G2P2L1M-22222' );
		$this->checkC( $data, $mk( 3, 3, 3, 3, 3 ), 6, 'G2P2L1M-33333' );
		$this->checkC( $data, $mk( 4, 4, 4, 4, 4 ), 4, 'G2P2L1M-44444' );
		$this->checkC( $data, $mk( 5, 5, 5, 5, 5 ), 2, 'G2P2L1M-55555' );
		$this->checkC( $data, $mk( 5, 5, 5, 1, 1 ), 4, 'G2P2L1M-55511' );
		$this->checkC( $data, $mk( 5, 5, 5, 2, 2 ), 2, 'G2P2L1M-55522' );
		$this->checkC( $data, $mk( 5, 1, 1, 5, 5 ), 10, 'G2P2L1M-51155' );
		$this->checkC( $data, $mk( 5, 2, 2, 5, 5 ), 5, 'G2P2L1M-52255' );
	}

	/**
	 * Test smart continue - generator=templates, prop=templates
	 * @medium
	 */
	public function testSameGenAndProp() {
		$this->mVerbose = false;
		$mk = function ( $g, $gDir, $p, $pDir ) {
			return array(
				'titles' => 'AQCT-1',
				'generator' => 'templates',
				'gtllimit' => "$g",
				'gtldir' => $gDir ? 'ascending' : 'descending',
				'prop' => 'templates',
				'tllimit' => "$p",
				'tldir' => $pDir ? 'ascending' : 'descending',
			);
		};
		// generator + 1 prop
		$data = $this->query( $mk( 99, true, 99, true ), 1, 'G=P', false );

		$this->checkC( $data, $mk( 1, true, 1, true ), 4, 'G=P-1t1t' );
		$this->checkC( $data, $mk( 2, true, 2, true ), 2, 'G=P-2t2t' );
		$this->checkC( $data, $mk( 3, true, 3, true ), 2, 'G=P-3t3t' );
		$this->checkC( $data, $mk( 1, true, 3, true ), 4, 'G=P-1t3t' );
		$this->checkC( $data, $mk( 3, true, 1, true ), 2, 'G=P-3t1t' );

		$this->checkC( $data, $mk( 1, true, 1, false ), 4, 'G=P-1t1f' );
		$this->checkC( $data, $mk( 2, true, 2, false ), 2, 'G=P-2t2f' );
		$this->checkC( $data, $mk( 3, true, 3, false ), 2, 'G=P-3t3f' );
		$this->checkC( $data, $mk( 1, true, 3, false ), 4, 'G=P-1t3f' );
		$this->checkC( $data, $mk( 3, true, 1, false ), 2, 'G=P-3t1f' );

		$this->checkC( $data, $mk( 1, false, 1, true ), 4, 'G=P-1f1t' );
		$this->checkC( $data, $mk( 2, false, 2, true ), 2, 'G=P-2f2t' );
		$this->checkC( $data, $mk( 3, false, 3, true ), 2, 'G=P-3f3t' );
		$this->checkC( $data, $mk( 1, false, 3, true ), 4, 'G=P-1f3t' );
		$this->checkC( $data, $mk( 3, false, 1, true ), 2, 'G=P-3f1t' );

		$this->checkC( $data, $mk( 1, false, 1, false ), 4, 'G=P-1f1f' );
		$this->checkC( $data, $mk( 2, false, 2, false ), 2, 'G=P-2f2f' );
		$this->checkC( $data, $mk( 3, false, 3, false ), 2, 'G=P-3f3f' );
		$this->checkC( $data, $mk( 1, false, 3, false ), 4, 'G=P-1f3f' );
		$this->checkC( $data, $mk( 3, false, 1, false ), 2, 'G=P-3f1f' );
	}

	/**
	 * Test smart continue - generator=allpages, list=allpages
	 * @medium
	 */
	public function testSameGenList() {
		$this->mVerbose = false;
		$mk = function ( $g, $gDir, $l, $pDir ) {
			return array(
				'generator' => 'allpages',
				'gapprefix' => 'AQCT-',
				'gaplimit' => "$g",
				'gapdir' => $gDir ? 'ascending' : 'descending',
				'list' => 'allpages',
				'apprefix' => 'AQCT-',
				'aplimit' => "$l",
				'apdir' => $pDir ? 'ascending' : 'descending',
			);
		};
		// generator + 1 list
		$data = $this->query( $mk( 99, true, 99, true ), 1, 'G=L', false );

		$this->checkC( $data, $mk( 1, true, 1, true ), 5, 'G=L-1t1t' );
		$this->checkC( $data, $mk( 2, true, 2, true ), 3, 'G=L-2t2t' );
		$this->checkC( $data, $mk( 3, true, 3, true ), 2, 'G=L-3t3t' );
		$this->checkC( $data, $mk( 1, true, 3, true ), 5, 'G=L-1t3t' );
		$this->checkC( $data, $mk( 3, true, 1, true ), 5, 'G=L-3t1t' );
		$this->checkC( $data, $mk( 1, true, 1, false ), 5, 'G=L-1t1f' );
		$this->checkC( $data, $mk( 2, true, 2, false ), 3, 'G=L-2t2f' );
		$this->checkC( $data, $mk( 3, true, 3, false ), 2, 'G=L-3t3f' );
		$this->checkC( $data, $mk( 1, true, 3, false ), 5, 'G=L-1t3f' );
		$this->checkC( $data, $mk( 3, true, 1, false ), 5, 'G=L-3t1f' );
		$this->checkC( $data, $mk( 1, false, 1, true ), 5, 'G=L-1f1t' );
		$this->checkC( $data, $mk( 2, false, 2, true ), 3, 'G=L-2f2t' );
		$this->checkC( $data, $mk( 3, false, 3, true ), 2, 'G=L-3f3t' );
		$this->checkC( $data, $mk( 1, false, 3, true ), 5, 'G=L-1f3t' );
		$this->checkC( $data, $mk( 3, false, 1, true ), 5, 'G=L-3f1t' );
		$this->checkC( $data, $mk( 1, false, 1, false ), 5, 'G=L-1f1f' );
		$this->checkC( $data, $mk( 2, false, 2, false ), 3, 'G=L-2f2f' );
		$this->checkC( $data, $mk( 3, false, 3, false ), 2, 'G=L-3f3f' );
		$this->checkC( $data, $mk( 1, false, 3, false ), 5, 'G=L-1f3f' );
		$this->checkC( $data, $mk( 3, false, 1, false ), 5, 'G=L-3f1f' );
	}
}
