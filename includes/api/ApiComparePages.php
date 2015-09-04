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

		if ( !is_null( $params['fromtitle'] ) ) {
			$fromTitle = Title::newFromText( $params['fromtitle'] );
			if ( !$fromTitle->exists() ) {
				$fromContent = ContentHandler::getForModelID( $fromTitle->getContentModel() )->makeEmptyContent();
			}
		}
		$fromRevision = Revision::newFromId( $rev1 );
		if ( $fromRevision ) {
			$fromTitle = $fromRevision->getTitle();
			$fromContent = $fromRevision->getContent( Revision::FOR_THIS_USER, $this->getUser() );
			$section = isset( $params['section'] ) ? $params['section'] : false;
			if ( $fromContent && $section !== false ) {
				$fromContent = $fromContent->getSection( $section, false );
				if ( !$fromContent ) {
					$this->dieUsage(
						"There is no section {$section} in r" . $fromRevision->getId(),
						'nosuchsection'
					);
				}
			}
		}

		if ( !$fromContent ) {
			$this->dieUsage( 'The diff cannot be retrieved, ' .
				'one revision does not exist or you do not have permission to view it.', 'baddiff' );
		}

		$toRevision = Revision::newFromId( $rev2 );
		if ( $toRevision ) {
			$toContent = $toRevision->getContent( Revision::FOR_THIS_USER, $this->getUser() );
			if ( $toContent && $section !== false ) {
				$toContent = $toContent->getSection( $section, false );
				if ( !$toContent ) {
					$this->dieUsage(
						"There is no section {$section} in r" . $toRevision->getId(),
						'nosuchsection'
					);
				}
			}
		} else if ( !is_null( $params['totext'] ) ) {
			$toContent = ContentHandler::makeContent(
				$params['totext'],
				$fromTitle,
				$fromTitle->getContentModel(),
				$fromRevision ? $fromRevision->getContentFormat() : $fromContent->getContentHandler()->getDefaultFormat()
			);
			$popts = ParserOptions::newFromUserAndLang( $this->getUser(), $fromContent->getContentHandler()->getPageViewLanguage( $fromTitle ) );
	        $toContent = $toContent->preSaveTransform( $fromTitle, $this->getUser(), $popts );
		}

		if ( ( $fromContent && !$fromContent->isEmpty() ) || ( $toContent && !$toContent->isEmpty() ) ) {
			if ( !$fromContent ) {
				$fromContent = $toContent->getContentHandler()->makeEmptyContent();
			}
			if ( !$toContent ) {
				$toContent = $fromContent->getContentHandler()->makeEmptyContent();
			}
		} else {
			$this->dieUsage( 'The diff cannot be retrieved, ' .
				'one revision does not exist or you do not have permission to view it.', 'baddiff' );
		}

		$de = $fromContent->getContentHandler()->createDifferenceEngine( $this->getContext(), $rev1, $rev2 );
		$de->setContent( $fromContent, $toContent );

		$vals = array();
		if ( isset( $params['fromtitle'] ) ) {
			$vals['fromtitle'] = $params['fromtitle'];
		}
		if ( isset( $params['fromid'] ) ) {
			$vals['fromid'] = $params['fromid'];
		}
		if ( $rev1 ) {
			$vals['fromrevid'] = $rev1;
		}

		if ( isset( $params['totitle'] ) ) {
			$vals['totitle'] = $params['totitle'];
		}
		if ( isset( $params['toid'] ) ) {
			$vals['toid'] = $params['toid'];
		}
		if ( $rev2 ) {
			$vals['torevid'] = $rev2;
		}
		$difftext = $de->getDiffBody();

		if ( $difftext === false ) {
			$this->dieUsage(
				'The diff cannot be retrieved. Maybe one or both revisions do ' .
					'not exist or you do not have permission to view them.',
				'baddiff'
			);
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
		#$this->dieUsage(
		#	'A title, a page ID, or a revision number is needed for both the from and the to parameters',
		#	'inputneeded'
		#);
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
			'totext' => null,
			'section' => array(
				ApiBase::PARAM_DFLT => null,
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=compare&fromrev=1&torev=2'
				=> 'apihelp-compare-example-1',
		);
	}
}
