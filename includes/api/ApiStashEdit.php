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
		$cache = ObjectCache::getLocalClusterInstance();
		$logger = LoggerFactory::getInstance( 'StashEdit' );

		$title = $page->getTitle();
		$key = self::getStashKey( $title, self::getContentHash( $content ), $user );

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

		$cutoffTime = time() - self::PRESUME_FRESH_TTL_SEC;

		// Reuse any freshly build matching edit stash cache
		$editInfo = $cache->get( $key );
		if ( $editInfo && wfTimestamp( TS_UNIX, $editInfo->timestamp ) >= $cutoffTime ) {
			$alreadyCached = true;
		} else {
			$format = $content->getDefaultFormat();
			$editInfo = $page->prepareContentForEdit( $content, null, $user, $format, false );
			$alreadyCached = false;
		}

		if ( $editInfo && $editInfo->output ) {
			// Let extensions add ParserOutput metadata or warm other caches
			Hooks::run( 'ParserOutputStashForEdit',
				[ $page, $content, $editInfo->output, $summary, $user ] );

			$titleStr = (string)$title;
			if ( $alreadyCached ) {
				$logger->debug( "Already cached parser output for key '{cachekey}' ('{title}').",
					[ 'cachekey' => $key, 'title' => $titleStr ] );
				return self::ERROR_NONE;
			}

			list( $stashInfo, $ttl, $code ) = self::buildStashValue(
				$editInfo->pstContent,
				$editInfo->output,
				$editInfo->timestamp,
				$user
			);

			if ( $stashInfo ) {
				$ok = $cache->set( $key, $stashInfo, $ttl );
				if ( $ok ) {
					$logger->debug( "Cached parser output for key '{cachekey}' ('{title}').",
						[ 'cachekey' => $key, 'title' => $titleStr ] );
					return self::ERROR_NONE;
				} else {
					$logger->error( "Failed to cache parser output for key '{cachekey}' ('{title}').",
						[ 'cachekey' => $key, 'title' => $titleStr ] );
					return self::ERROR_CACHE;
				}
			} else {
				$logger->info( "Uncacheable parser output for key '{cachekey}' ('{title}') [{code}].",
					[ 'cachekey' => $key, 'title' => $titleStr, 'code' => $code ] );
				return self::ERROR_UNCACHEABLE;
			}
		}

		return self::ERROR_PARSE;
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
		if ( $user->isBot() ) {
			return false; // bots never stash - don't pollute stats
		}

		$cache = ObjectCache::getLocalClusterInstance();
		$logger = LoggerFactory::getInstance( 'StashEdit' );
		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();

		$key = self::getStashKey( $title, self::getContentHash( $content ), $user );
		$editInfo = $cache->get( $key );
		if ( !is_object( $editInfo ) ) {
			$start = microtime( true );
			// We ignore user aborts and keep parsing. Block on any prior parsing
			// so as to use its results and make use of the time spent parsing.
			// Skip this logic if there no master connection in case this method
			// is called on an HTTP GET request for some reason.
			$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
			$dbw = $lb->getAnyOpenConnection( $lb->getWriterIndex() );
			if ( $dbw && $dbw->lock( $key, __METHOD__, 30 ) ) {
				$editInfo = $cache->get( $key );
				$dbw->unlock( $key, __METHOD__ );
			}

			$timeMs = 1000 * max( 0, microtime( true ) - $start );
			$stats->timing( 'editstash.lock_wait_time', $timeMs );
		}

		if ( !is_object( $editInfo ) || !$editInfo->output ) {
			$stats->increment( 'editstash.cache_misses.no_stash' );
			$logger->debug( "Empty cache for key '$key' ('$title'); user '{$user->getName()}'." );
			return false;
		}

		$age = time() - wfTimestamp( TS_UNIX, $editInfo->output->getCacheTime() );
		if ( $age <= self::PRESUME_FRESH_TTL_SEC ) {
			// Assume nothing changed in this time
			$stats->increment( 'editstash.cache_hits.presumed_fresh' );
			$logger->debug( "Timestamp-based cache hit for key '$key' (age: $age sec)." );
		} elseif ( isset( $editInfo->edits ) && $editInfo->edits === $user->getEditCount() ) {
			// Logged-in user made no local upload/template edits in the meantime
			$stats->increment( 'editstash.cache_hits.presumed_fresh' );
			$logger->debug( "Edit count based cache hit for key '$key' (age: $age sec)." );
		} elseif ( $user->isAnon()
			&& self::lastEditTime( $user ) < $editInfo->output->getCacheTime()
		) {
			// Logged-out user made no local upload/template edits in the meantime
			$stats->increment( 'editstash.cache_hits.presumed_fresh' );
			$logger->debug( "Edit check based cache hit for key '$key' (age: $age sec)." );
		} else {
			// User may have changed included content
			$editInfo = false;
		}

		if ( !$editInfo ) {
			$stats->increment( 'editstash.cache_misses.proven_stale' );
			$logger->info( "Stale cache for key '$key'; old key with outside edits. (age: $age sec)" );
		} elseif ( $editInfo->output->getFlag( 'vary-revision' ) ) {
			// This can be used for the initial parse, e.g. for filters or doEditContent(),
			// but a second parse will be triggered in doEditUpdates(). This is not optimal.
			$logger->info( "Cache for key '$key' ('$title') has vary_revision." );
		} elseif ( $editInfo->output->getFlag( 'vary-revision-id' ) ) {
			// Similar to the above if we didn't guess the ID correctly.
			$logger->info( "Cache for key '$key' ('$title') has vary_revision_id." );
		}

		return $editInfo;
	}

	/**
	 * @param User $user
	 * @return string|null TS_MW timestamp or null
	 */
	private static function lastEditTime( User $user ) {
		$db = wfGetDB( DB_REPLICA );
		$actorQuery = ActorMigration::newMigration()->getWhere( $db, 'rc_user', $user, false );
		$time = $db->selectField(
			[ 'recentchanges' ] + $actorQuery['tables'],
			'MAX(rc_timestamp)',
			[ $actorQuery['conds'] ],
			__METHOD__,
			[],
			$actorQuery['joins']
		);

		return wfTimestampOrNull( TS_MW, $time );
	}

	/**
	 * Get hash of the content, factoring in model/format
	 *
	 * @param Content $content
	 * @return string
	 */
	private static function getContentHash( Content $content ) {
		return sha1( implode( "\n", [
			$content->getModel(),
			$content->getDefaultFormat(),
			$content->serialize( $content->getDefaultFormat() )
		] ) );
	}

	/**
	 * Get the temporary prepared edit stash key for a user
	 *
	 * This key can be used for caching prepared edits provided:
	 *   - a) The $user was used for PST options
	 *   - b) The parser output was made from the PST using cannonical matching options
	 *
	 * @param Title $title
	 * @param string $contentHash Result of getContentHash()
	 * @param User $user User to get parser options from
	 * @return string
	 */
	private static function getStashKey( Title $title, $contentHash, User $user ) {
		return ObjectCache::getLocalClusterInstance()->makeKey(
			'prepared-edit',
			md5( $title->getPrefixedDBkey() ),
			// Account for the edit model/text
			$contentHash,
			// Account for user name related variables like signatures
			md5( $user->getId() . "\n" . $user->getName() )
		);
	}

	/**
	 * Build a value to store in memcached based on the PST content and parser output
	 *
	 * This makes a simple version of WikiPage::prepareContentForEdit() as stash info
	 *
	 * @param Content $pstContent Pre-Save transformed content
	 * @param ParserOutput $parserOutput
	 * @param string $timestamp TS_MW
	 * @param User $user
	 * @return array (stash info array, TTL in seconds, info code) or (null, 0, info code)
	 */
	private static function buildStashValue(
		Content $pstContent, ParserOutput $parserOutput, $timestamp, User $user
	) {
		// If an item is renewed, mind the cache TTL determined by config and parser functions.
		// Put an upper limit on the TTL for sanity to avoid extreme template/file staleness.
		$since = time() - wfTimestamp( TS_UNIX, $parserOutput->getTimestamp() );
		$ttl = min( $parserOutput->getCacheExpiry() - $since, self::MAX_CACHE_TTL );

		// Avoid extremely stale user signature timestamps (T84843)
		if ( $parserOutput->getFlag( 'user-signature' ) ) {
			$ttl = min( $ttl, self::MAX_SIGNATURE_TTL );
		}

		if ( $ttl <= 0 ) {
			return [ null, 0, 'no_ttl' ];
		}

		// Only store what is actually needed
		$stashInfo = (object)[
			'pstContent' => $pstContent,
			'output'     => $parserOutput,
			'timestamp'  => $timestamp,
			'edits'      => $user->getEditCount()
		];

		return [ $stashInfo, $ttl, 'ok' ];
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
