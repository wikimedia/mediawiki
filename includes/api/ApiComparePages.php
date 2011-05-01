<?php
/**
 *
 * Created on May 1, 2011
 *
 * Copyright © 2011 Sam Reed
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
 *
 * @file
 */

class ApiComparePages extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$rev1 = $this->revisionOrTitle( $params['fromrev'], $params['fromtitle'] );
		$rev2 = $this->revisionOrTitle( $params['torev'], $params['totitle'] );

		$de = new DifferenceEngine( null,
			$rev1,
			$rev2,
			null, // rcid
			true,
			false );

		$vals = array();
		if ( isset( $params['fromtitle'] ) ) {
			$vals['fromtitle'] = $params['fromtitle'];
		}
		$vals['fromrevid'] = $rev1;
		if ( isset( $params['totitle'] ) ) {
			$vals['totitle'] = $params['totitle'];
		}
		$vals['torevid'] = $rev2;

		$difftext = $de->getDiffBody();
		ApiResult::setContent( $vals, $difftext );

		$this->getResult()->addValue( null, $this->getModuleName(), $vals );
	}

	/**
	 * @param $revision int
	 * @param $title string
	 * @return int
	 */
	private function revisionOrTitle( $revision, $title ) {
		if( $revision ){
			return $revision;
		} elseif( $title ) {
			$title = Title::newFromText( $title );
			if( !$title ){
				$this->dieUsageMsg( array( 'invalidtitle', $title ) );
			}
			return $title->getLatestRevID();
		}
		$this->dieUsage( 'inputneeded', 'A title or a revision number is needed for both the from and the to parameters' );
	}

	public function getAllowedParams() {
		return array(
			'fromtitle' => null,
			'fromrev' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'totitle' => null,
			'torev' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
		);
	}

	public function getParamDescription() {
		return array(
			'title1' => 'First title to compare',
			'rev1' => 'First revision to compare',
			'title2' => 'Second title to compare',
			'rev2' => 'Second revision to compare',
		);
	}
	public function getDescription() {
		return array(
			'Get the difference between 2 pages',
			'You must pass a revision number or a page title for each part (1 and 2)'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'inputneeded', 'info' => 'A title or a revision is needed' ),
			array( 'invalidtitle', 'title' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=compare&rev1=1&rev2=2',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
