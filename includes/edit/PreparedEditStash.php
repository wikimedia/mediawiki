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

namespace MediaWiki\Edit;

use BagOStuff;
use Content;
use Hooks;
use Language;
use MapCacheLRU;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionSlots;
use ParserOptions;
use ParserOutput;
use Revision;
use Title;
use User;
use WikiPage;

/**
 * Service for preparing content while saving an edit, and caching and stashing prepared edits.
 *
 * @since 1.31
 */
class PreparedEditStash {

	/**
	 * @var BagOStuff|null
	 */
	private $stash;

	/**
	 * @var MapCacheLRU
	 */
	private $localCache;

	/**
	 * @var Language
	 */
	private $contentLanguage;

	/**
	 * @param Language $contentLanguage
	 * @param BagOStuff $stash
	 * @param int $localCacheCap maximum capacity of the in-process LRU cache
	 */
	public function __construct(
		Language $contentLanguage,
		BagOStuff $stash = null,
		$localCacheCap = 20
	) {
		$this->stash = $stash;
		$this->localCache = new MapCacheLRU( $localCacheCap );
		$this->contentLanguage = $contentLanguage;
		//$logger = LoggerFactory::getInstance( 'StashEdit' );
	}


	/**
	 * Prepare content which is about to be saved.
	 *
	 * Prior to 1.30, this returned a stdClass object with the same class
	 * members.
	 *
	 * @param Content[] $newContent
	 * @param RevisionRecord|null $revision Revision object. For backwards compatibility, a
	 *        revision ID is also accepted, but this is deprecated.
	 * @param User|null $user
	 * @param bool $useStash Check shared prepared edit stash
	 *
	 * @return PreparedEdit
	 */
	public function prepareContentForEdit(
		WikiPage $wikiPage, array $newContent, RevisionRecord $revision = null, User $user,
		$useStash = true
	) {
		$title = $wikiPage->getTitle();
		$revid = $revision->getId();

		$newContentSlots = new RevisionSlots( $newContent );
		$sig = PreparedEdit::makeSignature( $title, $newContentSlots, $user, $revision );

		$preparedEdit = $this->localCache->get( $sig );
		if ( $preparedEdit ) {
			return $preparedEdit;
		}

		// The edit may have already been prepared via api.php?action=stashedit
		$cachedEdit = ( $useStash && ( $this->stash !== null ) )
			? $this->checkStash( $title, $newContentSlots, $user )
			: false;

		$userPopts = ParserOptions::newFromUserAndLang( $user, $this->contentLanguage );
		Hooks::run( 'ArticlePrepareTextForEdit', [ $wikiPage, $userPopts ] );

		if ( $cachedEdit ) {
			$pstContent = $cachedEdit->pstContent;
		} else {
			// FIXME: apply PST to all slots!
			$pstContent = $content->preSaveTransform( $this->mTitle, $user, $userPopts );
		}

		$canonPopts = $wikiPage->makeParserOptions( 'canonical' );
		if ( $cachedEdit ) {
			$output = $cachedEdit->output;
		} else {
			if ( $revision ) {
				// We get here if vary-revision is set. This means that this page references
				// itself (such as via self-transclusion). In this case, we need to make sure
				// that any such self-references refer to the newly-saved revision, and not
				// to the previous one, which could otherwise happen due to replica DB lag.
				$oldCallback = $canonPopts->getCurrentRevisionCallback();
				$canonPopts->setCurrentRevisionCallback(
					function ( Title $parserTitle, $parser = false ) use ( $title, $revision, &$oldCallback ) {
						if ( $parserTitle->equals( $title ) ) {
							return new Revision( $revision );
						} else {
							return call_user_func( $oldCallback, $parserTitle, $parser );
						}
					}
				);
			} else {
				// Try to avoid a second parse if {{REVISIONID}} is used
				$dbIndex = $wikiPage->wasLoadedFrom( WikiPage::READ_LATEST )
					? DB_MASTER // use the best possible guess
					: DB_REPLICA; // T154554

				$canonPopts->setSpeculativeRevIdCallback(
					function () use ( $dbIndex ) {
						return 1 + (int)$this->loadBalancer->getConnection( $dbIndex )->selectField(
							'revision',
							'MAX(rev_id)',
							[ ],
							__METHOD__
						);
					}
				);
			}

			// TODO: create combined parser output for all slots!
			$output = $pstContent
				? $pstContent->getParserOutput( $this->mTitle, $revid, $canonPopts )
				: null;
		}

		if ( $output ) {
			$output->setCacheTime( wfTimestampNow() );
		}

		$pstContentSlots = new RevisionSlots( [ 'main' => $pstContent ] );

		// Process cache the result
		$edit = new PreparedEdit(
			$newContentSlots,
			$pstContentSlots,
			$canonPopts,
			$output,
			$revision
		);

		return $edit;
	}


	/**
	 * @param WikiPage $page
	 * @param Content $content Edit content
	 * @param User $user
	 * @param string $summary Edit summary
	 * @return string ApiStashEdit::ERROR_* constant
	 * @since 1.25
	 */
	public function parseAndStash( WikiPage $page, Content $content, User $user, $summary ) {

		$title = $page->getTitle();
		$key = self::getStashKey( $title, self::getContentHash( $content ), $user );

		$cutoffTime = time() - self::PRESUME_FRESH_TTL_SEC;

		// Reuse any freshly build matching edit stash cache
		$stashedInfo = $cache->get( $key );
		if ( $stashedInfo && wfTimestamp( TS_UNIX, $stashedInfo->timestamp ) >= $cutoffTime ) {
			$alreadyCached = true;
		} else {
			$format = $content->getDefaultFormat();
			$editInfo = $page->prepareContentForEdit( $content, null, $user, $format, false );
			$alreadyCached = false;
			if ( $editInfo ) {
				$stashedInfo = (object)[
					'pstContent' => $editInfo->getTransformedContentSlots()->getContent( 'main' ),
					'output'     => $editInfo->getParserOutput(),
					'timestamp'  => wfTimestampNow(),
				];
			}
		}

		if ( $stashedInfo && $stashedInfo->output ) {
			// Let extensions add ParserOutput metadata or warm other caches
			Hooks::run( 'ParserOutputStashForEdit',
			            [ $page, $content, $stashedInfo->output, $summary, $user ] );

			$titleStr = (string)$title;
			if ( $alreadyCached ) {
				$logger->debug( "Already cached parser output for key '{cachekey}' ('{title}').",
				                [ 'cachekey' => $key, 'title' => $titleStr ] );
				return self::ERROR_NONE;
			}

			list( $stashInfo, $ttl, $code ) = self::buildStashValue(
				$stashedInfo->pstContent,
				$stashedInfo->output,
				$stashedInfo->timestamp,
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
		$time = wfGetDB( DB_REPLICA )->selectField(
			'recentchanges',
			'MAX(rc_timestamp)',
			[ 'rc_user_text' => $user->getName() ],
			__METHOD__
		);

		return wfTimestampOrNull( TS_MW, $time );
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
	public function getStashKey( Title $title, RevisionSlots $content, User $user ) {
		// FIXME: align with PreparedEdit::makeSignature
		return $this->stash->makeKey(
			'prepared-edit',
			md5( $title->getPrefixedDBkey() ),
			// Account for the edit model/text
			$content->computeSha1(), // FIXME: cache the hash!
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

}
