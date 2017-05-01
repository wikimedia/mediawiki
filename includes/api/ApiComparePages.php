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
			$this->dieWithError( 'apierror-baddiff' );
		}

		$contentHandler = $revision->getContentHandler();
		$de = $contentHandler->createDifferenceEngine( $this->getContext(),
			$rev1,
			$rev2,
			null, // rcid
			true,
			false );

		$vals = [];
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
			$this->dieWithError( 'apierror-baddiff' );
		}

		ApiResult::setContentValue( $vals, 'body', $difftext );

		$this->getResult()->addValue( null, $this->getModuleName(), $vals );
	}

	/**
	 * @param int $revision
	 * @param string $titleText
	 * @param int $titleId
	 * @return int
	 */
	private function revisionOrTitleOrId( $revision, $titleText, $titleId ) {
		if ( $revision ) {
			return $revision;
		} elseif ( $titleText ) {
			$title = Title::newFromText( $titleText );
			if ( !$title || $title->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $titleText ) ] );
			}

			return $title->getLatestRevID();
		} elseif ( $titleId ) {
			$title = Title::newFromID( $titleId );
			if ( !$title ) {
				$this->dieWithError( [ 'apierror-nosuchpageid', $titleId ] );
			}

			return $title->getLatestRevID();
		}
		$this->dieWithError( 'apierror-compare-inputneeded', 'inputneeded' );
	}

	public function getAllowedParams() {
		return [
			'fromtitle' => null,
			'fromid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'fromrev' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'totitle' => null,
			'toid' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'torev' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=compare&fromrev=1&torev=2'
				=> 'apihelp-compare-example-1',
		];
	}
}
