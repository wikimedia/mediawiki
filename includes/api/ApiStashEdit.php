<?php
/**
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

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\PageEditStash;

/**
 * Prepare an edit in shared cache so that it can be reused on edit
 *
 * This endpoint can be called via AJAX as the user focuses on the edit
 * summary box. By the time of submission, the parse may have already
 * finished, and can be immediately used on page save. Certain parser
 * functions like {{REVISIONID}} or {{CURRENTTIME}} may cause the cache
 * to not be used on edit. Template and files used are check for changes
 * since the output was generated. The cache TTL is also kept low for sanity.
 *
 * @ingroup API
 * @since 1.25
 */
class ApiStashEdit extends ApiBase {
	const ERROR_NONE = PageEditStash::ERROR_NONE; // b/c
	const ERROR_PARSE = PageEditStash::ERROR_PARSE; // b/c
	const ERROR_CACHE = PageEditStash::ERROR_CACHE; // b/c
	const ERROR_UNCACHEABLE = PageEditStash::ERROR_UNCACHEABLE; // b/c
	const ERROR_BUSY = PageEditStash::ERROR_BUSY; // b/c

	public function execute() {
		$user = $this->getUser();
		$params = $this->extractRequestParams();

		if ( $user->isBot() ) { // sanity
			$this->dieWithError( 'apierror-botsnotsupported' );
		}

		$editStash = MediaWikiServices::getInstance()->getPageEditStash();
		$page = $this->getTitleOrPageId( $params );
		$title = $page->getTitle();

		if ( !ContentHandler::getForModelID( $params['contentmodel'] )
			->isSupportedFormat( $params['contentformat'] )
		) {
			$this->dieWithError(
				[ 'apierror-badformat-generic', $params['contentformat'], $params['contentmodel'] ],
				'badmodelformat'
			);
		}

		$this->requireOnlyOneParameter( $params, 'stashedtexthash', 'text' );

		$text = null;
		$textHash = null;
		if ( $params['stashedtexthash'] !== null ) {
			// Load from cache since the client indicates the text is the same as last stash
			$textHash = $params['stashedtexthash'];
			if ( !preg_match( '/^[0-9a-f]{40}$/', $textHash ) ) {
				$this->dieWithError( 'apierror-stashedit-missingtext', 'missingtext' );
			}
			$text = $editStash->fetchInputText( $textHash );
			if ( !is_string( $text ) ) {
				$this->dieWithError( 'apierror-stashedit-missingtext', 'missingtext' );
			}
		} else {
			// 'text' was passed.  Trim and fix newlines so the key SHA1's
			// match (see WebRequest::getText())
			$text = rtrim( str_replace( "\r\n", "\n", $params['text'] ) );
			$textHash = sha1( $text );
		}

		$textContent = ContentHandler::makeContent(
			$text, $title, $params['contentmodel'], $params['contentformat'] );

		$page = WikiPage::factory( $title );
		if ( $page->exists() ) {
			// Page exists: get the merged content with the proposed change
			$baseRev = Revision::newFromPageId( $page->getId(), $params['baserevid'] );
			if ( !$baseRev ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $params['baserevid'] ] );
			}
			$currentRev = $page->getRevision();
			if ( !$currentRev ) {
				$this->dieWithError( [ 'apierror-missingrev-pageid', $page->getId() ], 'missingrev' );
			}
			// Merge in the new version of the section to get the proposed version
			$editContent = $page->replaceSectionAtRev(
				$params['section'],
				$textContent,
				$params['sectiontitle'],
				$baseRev->getId()
			);
			if ( !$editContent ) {
				$this->dieWithError( 'apierror-sectionreplacefailed', 'replacefailed' );
			}
			if ( $currentRev->getId() == $baseRev->getId() ) {
				// Base revision was still the latest; nothing to merge
				$content = $editContent;
			} else {
				// Merge the edit into the current version
				$baseContent = $baseRev->getContent();
				$currentContent = $currentRev->getContent();
				if ( !$baseContent || !$currentContent ) {
					$this->dieWithError( [ 'apierror-missingcontent-pageid', $page->getId() ], 'missingrev' );
				}
				$handler = ContentHandler::getForModelID( $baseContent->getModel() );
				$content = $handler->merge3( $baseContent, $editContent, $currentContent );
			}
		} else {
			// New pages: use the user-provided content model
			$content = $textContent;
		}

		if ( !$content ) { // merge3() failed
			$this->getResult()->addValue( null,
				$this->getModuleName(), [ 'status' => 'editconflict' ] );
			return;
		}

		if ( $user->pingLimiter( 'stashedit' ) ) {
			$status = 'ratelimited';
		} else {
			$status = $editStash->parseAndCache( $page, $content, $user, $params['summary'] );
			$editStash->stashInputText( $text, $textHash );
		}

		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
		$stats->increment( "editstash.cache_stores.$status" );

		$ret = [ 'status' => $status ];
		// If we were rate-limited, we still return the pre-existing valid hash if one was passed
		if ( $status !== 'ratelimited' || $params['stashedtexthash'] !== null ) {
			$ret['texthash'] = $textHash;
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $ret );
	}

	/**
	 * @param WikiPage $page
	 * @param Content $content Edit content
	 * @param User $user
	 * @param string $summary Edit summary
	 * @return string ApiStashEdit::ERROR_* constant
	 * @since 1.25
	 * @deprecated Since 1.34
	 */
	public function parseAndStash( WikiPage $page, Content $content, User $user, $summary ) {
		$editStash = MediaWikiServices::getInstance()->getPageEditStash();

		return $editStash->parseAndCache( $page, $content, $user, $summary );
	}

	public function getAllowedParams() {
		return [
			'title' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			],
			'section' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'sectiontitle' => [
				ApiBase::PARAM_TYPE => 'string'
			],
			'text' => [
				ApiBase::PARAM_TYPE => 'text',
				ApiBase::PARAM_DFLT => null
			],
			'stashedtexthash' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_DFLT => null
			],
			'summary' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'contentmodel' => [
				ApiBase::PARAM_TYPE => ContentHandler::getContentModels(),
				ApiBase::PARAM_REQUIRED => true
			],
			'contentformat' => [
				ApiBase::PARAM_TYPE => ContentHandler::getAllContentFormats(),
				ApiBase::PARAM_REQUIRED => true
			],
			'baserevid' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true
			]
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function isInternal() {
		return true;
	}
}
