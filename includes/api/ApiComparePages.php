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

	public function execute() {
		$params = $this->extractRequestParams();

		$rev1 = $this->revisionOrTitleOrId( $params['fromrev'], $params['fromtitle'], $params['fromid'] );
		$rev2 = $this->revisionOrTitleOrId( $params['torev'], $params['totitle'], $params['toid'] );

		$revision = Revision::newFromId( $rev1 );

		if ( !$revision ) {
			$this->dieUsage( 'The diff cannot be retrieved, ' .
				'one revision does not exist or you do not have permission to view it.', 'baddiff' );
		}

		$contentHandler = $revision->getContentHandler();
		$de = $contentHandler->createDifferenceEngine( $this->getContext(),
			$rev1,
			$rev2,
			null, // rcid
			true,
			false );

		$vals = array();
		if ( isset( $params['fromtitle'] ) ) {
			$vals['fromtitle'] = $params['fromtitle'];
		}
		if ( isset( $params['fromid'] ) ) {
			$vals['fromid'] = $params['fromid'];
		}
		$vals['fromrevid'] = $rev1;
		if ( isset( $params['totitle'] ) ) {
			$vals['totitle'] = $params['totitle'];
		}
		if ( isset( $params['toid'] ) ) {
			$vals['toid'] = $params['toid'];
		}
		$vals['torevid'] = $rev2;

		$difftext = $de->getDiffBody();

		if ( $difftext === false ) {
			$this->dieUsage( 'The diff cannot be retrieved. ' .
				'Maybe one or both revisions do not exist or you do not have permission to view them.', 'baddiff' );
		} else {
			ApiResult::setContent( $vals, $difftext );
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $vals );
	}

	/**
	 * @param $revision int
	 * @param $titleText string
	 * @param $titleId int
	 * @return int
	 */
	private function revisionOrTitleOrId( $revision, $titleText, $titleId ) {
		if ( $revision ) {
			return $revision;
		} elseif ( $titleText ) {
			$title = Title::newFromText( $titleText );
			if ( !$title || $title->isExternal() ) {
				$this->dieUsageMsg( array( 'invalidtitle', $titleText ) );
			}
			return $title->getLatestRevID();
		} elseif ( $titleId ) {
			$title = Title::newFromID( $titleId );
			if ( !$title ) {
				$this->dieUsageMsg( array( 'nosuchpageid', $titleId ) );
			}
			return $title->getLatestRevID();
		}
		$this->dieUsage( 'inputneeded', 'A title, a page ID, or a revision number is needed for both the from and the to parameters' );
	}

	public function getAllowedParams() {
		return array(
			'fromtitle' => null,
			'fromid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'fromrev' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'totitle' => null,
			'toid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'torev' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
		);
	}

	public function getParamDescription() {
		return array(
			'fromtitle' => 'First title to compare',
			'fromid' => 'First page ID to compare',
			'fromrev' => 'First revision to compare',
			'totitle' => 'Second title to compare',
			'toid' => 'Second page ID to compare',
			'torev' => 'Second revision to compare',
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'fromtitle' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'fromrevid' => 'integer',
				'totitle' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'torevid' => 'integer',
				'*' => 'string'
			)
		);
	}

	public function getDescription() {
		return array(
			'Get the difference between 2 pages',
			'You must pass a revision number or a page title or a page ID id for each part (1 and 2)'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'inputneeded', 'info' => 'A title or a revision is needed' ),
			array( 'invalidtitle', 'title' ),
			array( 'nosuchpageid', 'pageid' ),
			array( 'code' => 'baddiff', 'info' => 'The diff cannot be retrieved. Maybe one or both revisions do not exist or you do not have permission to view them.' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=compare&fromrev=1&torev=2' => 'Create a diff between revision 1 and 2',
		);
	}
}
