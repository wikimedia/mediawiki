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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\RevisionSlots;
use Wikimedia\ScopedCallback;

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
	const ERROR_NONE = 'stashed';
	const ERROR_PARSE = 'error_parse';
	const ERROR_CACHE = 'error_cache';
	const ERROR_UNCACHEABLE = 'uncacheable';
	const ERROR_BUSY = 'busy';

	const PRESUME_FRESH_TTL_SEC = 30;
	const MAX_CACHE_TTL = 300; // 5 minutes
	const MAX_SIGNATURE_TTL = 60;

	public function execute() {
		$user = $this->getUser();
		$params = $this->extractRequestParams();

		if ( $user->isBot() ) { // sanity
			$this->dieWithError( 'apierror-botsnotsupported' );
		}

		$cache = ObjectCache::getLocalClusterInstance();
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

		$this->requireAtLeastOneParameter( $params, 'stashedtexthash', 'text' );

		$text = null;
		$textHash = null;
		if ( strlen( $params['stashedtexthash'] ) ) {
			// Load from cache since the client indicates the text is the same as last stash
			$textHash = $params['stashedtexthash'];
			if ( !preg_match( '/^[0-9a-f]{40}$/', $textHash ) ) {
				$this->dieWithError( 'apierror-stashedit-missingtext', 'missingtext' );
			}
			$textKey = $cache->makeKey( 'stashedit', 'text', $textHash );
			$text = $cache->get( $textKey );
			if ( !is_string( $text ) ) {
				$this->dieWithError( 'apierror-stashedit-missingtext', 'missingtext' );
			}
		} elseif ( $params['text'] !== null ) {
			// Trim and fix newlines so the key SHA1's match (see WebRequest::getText())
			$text = rtrim( str_replace( "\r\n", "\n", $params['text'] ) );
			$textHash = sha1( $text );
		} else {
			$this->dieWithError( [
				'apierror-missingparam-at-least-one-of',
				Message::listParam( [ '<var>stashedtexthash</var>', '<var>text</var>' ] ),
				2,
			], 'missingparam' );
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

		// The user will abort the AJAX request by pressing "save", so ignore that
		ignore_user_abort( true );

		if ( $user->pingLimiter( 'stashedit' ) ) {
			$status = 'ratelimited';
		} else {
			$status = self::parseAndStash( $page, $content, $user, $params['summary'] );
			$textKey = $cache->makeKey( 'stashedit', 'text', $textHash );
			$cache->set( $textKey, $text, self::MAX_CACHE_TTL );
		}

		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
		$stats->increment( "editstash.cache_stores.$status" );

		$this->getResult()->addValue(
			null,
			$this->getModuleName(),
			[
				'status' => $status,
				'texthash' => $textHash
			]
		);
	}

	/**
	 * @param WikiPage $page
	 * @param Content $content Edit content
	 * @param User $user
	 * @param string $summary Edit summary
	 * @return string ApiStashEdit::ERROR_* constant
	 * @since 1.25
	 */
	public static function parseAndStash( WikiPage $page, Content $content, User $user, $summary ) {
		$stash = MediaWikiServices::getInstance()->getPreparedEditStash(); // TODO: inject

		// FIXME: check whether stashing is enabled at all

		$key = $stash->getStashKey( $page->getTitle(), $content, $user );

		// Use the master DB to allow for fast blocking locks on the "save path" where this
		// value might actually be used to complete a page edit. If the edit submission request
		// happens before this edit stash requests finishes, then the submission will block until
		// the stash request finishes parsing. For the lock acquisition below, there is not much
		// need to duplicate parsing of the same content/user/summary bundle, so try to avoid
		// blocking at all here.
		$dbw = wfGetDB( DB_MASTER );
		if ( !$dbw->lock( $key, __METHOD__, 0 ) ) {
			// De-duplicate requests on the same key
			return self::ERROR_BUSY;
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$unlocker = new ScopedCallback( function () use ( $dbw, $key ) {
			$dbw->unlock( $key, __METHOD__ );
		} );


		return $stash->parseAndStash( $page, [ 'main' => $content ], $user, $summary );
	}

	/**
	 * Check that a prepared edit is in cache and still up-to-date
	 *
	 * This method blocks if the prepared edit is already being rendered,
	 * waiting until rendering finishes before doing final validity checks.
	 *
	 * The cache is rejected if template or file changes are detected.
	 * Note that foreign template or file transclusions are not checked.
	 *
	 * The result is a map (pstContent,output,timestamp) with fields
	 * extracted directly from WikiPage::prepareContentForEdit().
	 *
	 * @param Title $title
	 * @param Content $content
	 * @param User $user User to get parser options from
	 * @return stdClass|bool Returns false on cache miss
	 */
	public static function checkCache( Title $title, Content $content, User $user ) {
		$stash = MediaWikiServices::getInstance()->getPreparedEditStash(); // TODO: inject
		return $stash->checkCache( $title, [ 'main' => $content ], $user );
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
