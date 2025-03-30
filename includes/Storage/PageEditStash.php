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

namespace MediaWiki\Storage;

use MediaWiki\Content\Content;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\Hook\ParserOutputStashForEditHook;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use stdClass;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\ScopedCallback;
use Wikimedia\Stats\StatsFactory;

/**
 * Manage the pre-emptive page parsing for edits to wiki pages.
 *
 * This is written to by ApiStashEdit, and consumed by ApiEditPage
 * and EditPage (via PageUpdaterFactory and DerivedPageDataUpdater).
 *
 * See also mediawiki.action.edit/stash.js.
 *
 * @since 1.34
 * @ingroup Page
 */
class PageEditStash {
	/** @var BagOStuff */
	private $cache;
	/** @var IConnectionProvider */
	private $dbProvider;
	/** @var LoggerInterface */
	private $logger;
	/** @var StatsFactory */
	private $stats;
	/** @var ParserOutputStashForEditHook */
	private $hookRunner;
	/** @var UserEditTracker */
	private $userEditTracker;
	/** @var UserFactory */
	private $userFactory;
	/** @var WikiPageFactory */
	private $wikiPageFactory;
	/** @var int */
	private $initiator;

	public const ERROR_NONE = 'stashed';
	public const ERROR_PARSE = 'error_parse';
	public const ERROR_CACHE = 'error_cache';
	public const ERROR_UNCACHEABLE = 'uncacheable';
	public const ERROR_BUSY = 'busy';

	public const PRESUME_FRESH_TTL_SEC = 30;
	public const MAX_CACHE_TTL = 300; // 5 minutes
	public const MAX_SIGNATURE_TTL = 60;

	private const MAX_CACHE_RECENT = 2;

	public const INITIATOR_USER = 1;
	public const INITIATOR_JOB_OR_CLI = 2;

	/**
	 * @param BagOStuff $cache
	 * @param IConnectionProvider $dbProvider
	 * @param LoggerInterface $logger
	 * @param StatsFactory $stats
	 * @param UserEditTracker $userEditTracker
	 * @param UserFactory $userFactory
	 * @param WikiPageFactory $wikiPageFactory
	 * @param HookContainer $hookContainer
	 * @param int $initiator Class INITIATOR__* constant
	 */
	public function __construct(
		BagOStuff $cache,
		IConnectionProvider $dbProvider,
		LoggerInterface $logger,
		StatsFactory $stats,
		UserEditTracker $userEditTracker,
		UserFactory $userFactory,
		WikiPageFactory $wikiPageFactory,
		HookContainer $hookContainer,
		$initiator
	) {
		$this->cache = $cache;
		$this->dbProvider = $dbProvider;
		$this->logger = $logger;
		$this->stats = $stats;
		$this->userEditTracker = $userEditTracker;
		$this->userFactory = $userFactory;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->initiator = $initiator;
	}

	/**
	 * @param PageUpdater $pageUpdater (a WikiPage instance is also supported but deprecated)
	 * @param Content $content Edit content
	 * @param UserIdentity $user
	 * @param string $summary Edit summary
	 * @return string Class ERROR_* constant
	 */
	public function parseAndCache( $pageUpdater, Content $content, UserIdentity $user, string $summary ) {
		$logger = $this->logger;

		if ( $pageUpdater instanceof WikiPage ) {
			wfDeprecated( __METHOD__ . ' with WikiPage instance', '1.42' );
			$pageUpdater = $pageUpdater->newPageUpdater( $user );
		}

		$page = $pageUpdater->getPage();
		$key = $this->getStashKey( $page, $this->getContentHash( $content ), $user );
		$fname = __METHOD__;

		// Use the primary DB to allow for fast blocking locks on the "save path" where this
		// value might actually be used to complete a page edit. If the edit submission request
		// happens before this edit stash requests finishes, then the submission will block until
		// the stash request finishes parsing. For the lock acquisition below, there is not much
		// need to duplicate parsing of the same content/user/summary bundle, so try to avoid
		// blocking at all here.
		$dbw = $this->dbProvider->getPrimaryDatabase();
		if ( !$dbw->lock( $key, $fname, 0 ) ) {
			// De-duplicate requests on the same key
			return self::ERROR_BUSY;
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$unlocker = new ScopedCallback( static function () use ( $dbw, $key, $fname ) {
			$dbw->unlock( $key, $fname );
		} );

		$cutoffTime = time() - self::PRESUME_FRESH_TTL_SEC;

		// Reuse any freshly build matching edit stash cache
		$editInfo = $this->getStashValue( $key );
		if ( $editInfo && (int)wfTimestamp( TS_UNIX, $editInfo->timestamp ) >= $cutoffTime ) {
			$alreadyCached = true;
		} else {
			$pageUpdater->setContent( SlotRecord::MAIN, $content );

			$update = $pageUpdater->prepareUpdate( EDIT_INTERNAL ); // applies pre-safe transform
			$output = $update->getCanonicalParserOutput(); // causes content to be parsed
			$output->setCacheTime( $update->getRevision()->getTimestamp() );

			// emulate a cache value that kind of looks like a PreparedEdit, for use below
			$editInfo = (object)[
				'pstContent' => $update->getRawContent( SlotRecord::MAIN ),
				'output'     => $output,
				'timestamp'  => $output->getCacheTime()
			];

			$alreadyCached = false;
		}

		$logContext = [ 'cachekey' => $key, 'title' => (string)$page ];

		if ( $editInfo->output ) {
			// Let extensions add ParserOutput metadata or warm other caches
			$legacyUser = $this->userFactory->newFromUserIdentity( $user );
			$legacyPage = $this->wikiPageFactory->newFromTitle( $page );
			$this->hookRunner->onParserOutputStashForEdit(
				$legacyPage, $content, $editInfo->output, $summary, $legacyUser );

			if ( $alreadyCached ) {
				$logger->debug( "Parser output for key '{cachekey}' already cached.", $logContext );

				return self::ERROR_NONE;
			}

			$code = $this->storeStashValue(
				$key,
				$editInfo->pstContent,
				$editInfo->output,
				$editInfo->timestamp,
				$user
			);

			if ( $code === true ) {
				$logger->debug( "Cached parser output for key '{cachekey}'.", $logContext );

				return self::ERROR_NONE;
			} elseif ( $code === 'uncacheable' ) {
				$logger->info(
					"Uncacheable parser output for key '{cachekey}' [{code}].",
					$logContext + [ 'code' => $code ]
				);

				return self::ERROR_UNCACHEABLE;
			} else {
				$logger->error(
					"Failed to cache parser output for key '{cachekey}'.",
					$logContext + [ 'code' => $code ]
				);

				return self::ERROR_CACHE;
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
	 * This returns an object with the following fields:
	 *   - pstContent: the Content after pre-save-transform
	 *   - output: the ParserOutput instance
	 *   - timestamp: the timestamp of the parse
	 *   - edits: author edit count if they are logged in or NULL otherwise
	 *
	 * @param PageIdentity $page
	 * @param Content $content
	 * @param UserIdentity $user to get parser options from
	 * @return stdClass|false Returns edit stash object or false on cache miss
	 */
	public function checkCache( PageIdentity $page, Content $content, UserIdentity $user ) {
		$legacyUser = $this->userFactory->newFromUserIdentity( $user );
		if (
			// The context is not an HTTP POST request
			!$legacyUser->getRequest()->wasPosted() ||
			// The context is a CLI script or a job runner HTTP POST request
			$this->initiator !== self::INITIATOR_USER ||
			// The editor account is a known bot
			$legacyUser->isBot()
		) {
			// Avoid wasted queries and statsd pollution
			return false;
		}

		$logger = $this->logger;

		$key = $this->getStashKey( $page, $this->getContentHash( $content ), $user );
		$logContext = [
			'key' => $key,
			'title' => (string)$page,
			'user' => $user->getName()
		];

		$editInfo = $this->getAndWaitForStashValue( $key );
		if ( !is_object( $editInfo ) || !$editInfo->output ) {
			$this->incrCacheReadStats( 'miss', 'no_stash', $content );
			if ( $this->recentStashEntryCount( $user ) > 0 ) {
				$logger->info( "Empty cache for key '{key}' but not for user.", $logContext );
			} else {
				$logger->debug( "Empty cache for key '{key}'.", $logContext );
			}

			return false;
		}

		$age = time() - (int)wfTimestamp( TS_UNIX, $editInfo->output->getCacheTime() );
		$logContext['age'] = $age;

		$isCacheUsable = true;
		if ( $age <= self::PRESUME_FRESH_TTL_SEC ) {
			// Assume nothing changed in this time
			$this->incrCacheReadStats( 'hit', 'presumed_fresh', $content );
			$logger->debug( "Timestamp-based cache hit for key '{key}'.", $logContext );
		} elseif ( !$user->isRegistered() ) {
			$lastEdit = $this->lastEditTime( $user );
			$cacheTime = $editInfo->output->getCacheTime();
			if ( $lastEdit < $cacheTime ) {
				// Logged-out user made no local upload/template edits in the meantime
				$this->incrCacheReadStats( 'hit', 'presumed_fresh', $content );
				$logger->debug( "Edit check based cache hit for key '{key}'.", $logContext );
			} else {
				$isCacheUsable = false;
				$this->incrCacheReadStats( 'miss', 'proven_stale', $content );
				$logger->info( "Stale cache for key '{key}' due to outside edits.", $logContext );
			}
		} else {
			if ( $editInfo->edits === $this->userEditTracker->getUserEditCount( $user ) ) {
				// Logged-in user made no local upload/template edits in the meantime
				$this->incrCacheReadStats( 'hit', 'presumed_fresh', $content );
				$logger->debug( "Edit count based cache hit for key '{key}'.", $logContext );
			} else {
				$isCacheUsable = false;
				$this->incrCacheReadStats( 'miss', 'proven_stale', $content );
				$logger->info( "Stale cache for key '{key}'due to outside edits.", $logContext );
			}
		}

		if ( !$isCacheUsable ) {
			return false;
		}

		if ( $editInfo->output->getOutputFlag( ParserOutputFlags::VARY_REVISION ) ) {
			// This can be used for the initial parse, e.g. for filters or doUserEditContent(),
			// but a second parse will be triggered in doEditUpdates() no matter what
			$logger->info(
				"Cache for key '{key}' has vary-revision; post-insertion parse inevitable.",
				$logContext
			);
		} else {
			static $flagsMaybeReparse = [
				// Similar to the above if we didn't guess the ID correctly
				ParserOutputFlags::VARY_REVISION_ID,
				// Similar to the above if we didn't guess the timestamp correctly
				ParserOutputFlags::VARY_REVISION_TIMESTAMP,
				// Similar to the above if we didn't guess the content correctly
				ParserOutputFlags::VARY_REVISION_SHA1,
				// Similar to the above if we didn't guess page ID correctly
				ParserOutputFlags::VARY_PAGE_ID,
			];
			foreach ( $flagsMaybeReparse as $flag ) {
				if ( $editInfo->output->getOutputFlag( $flag ) ) {
					$logger->debug(
						"Cache for key '{key}' has $flag; post-insertion parse possible.",
						$logContext
					);
				}
			}
		}

		return $editInfo;
	}

	/**
	 * @param string $result
	 * @param string $reason
	 * @param Content $content
	 */
	private function incrCacheReadStats( $result, $reason, Content $content ) {
		static $subtypeByResult = [ 'miss' => 'cache_misses', 'hit' => 'cache_hits' ];
		$this->stats->getCounter( "editstash_cache_checks_total" )
			->setLabel( 'reason', $reason )
			->setLabel( 'result', $result )
			->setLabel( 'model', $content->getModel() )
			->copyToStatsdAt( [
				'editstash.' . $subtypeByResult[ $result ] . '.' . $reason,
				'editstash_by_model.' . $content->getModel() . '.' . $subtypeByResult[ $result ] . '.' . $reason ] )
			->increment();
	}

	/**
	 * @param string $key
	 * @return bool|stdClass
	 */
	private function getAndWaitForStashValue( $key ) {
		$editInfo = $this->getStashValue( $key );

		if ( !$editInfo ) {
			$start = microtime( true );
			// We ignore user aborts and keep parsing. Block on any prior parsing
			// so as to use its results and make use of the time spent parsing.
			$dbw = $this->dbProvider->getPrimaryDatabase();
			if ( $dbw->lock( $key, __METHOD__, 30 ) ) {
				$editInfo = $this->getStashValue( $key );
				$dbw->unlock( $key, __METHOD__ );
			}

			$timeMs = 1000 * max( 0, microtime( true ) - $start );
			$this->stats->getTiming( 'editstash_lock_wait_seconds' )
				->copyToStatsdAt( 'editstash.lock_wait_time' )
				->observe( $timeMs );
		}

		return $editInfo;
	}

	/**
	 * @param string $textHash
	 * @return string|bool Text or false if missing
	 */
	public function fetchInputText( $textHash ) {
		$textKey = $this->cache->makeKey( 'stashedit', 'text', $textHash );

		return $this->cache->get( $textKey );
	}

	/**
	 * @param string $text
	 * @param string $textHash
	 * @return bool Success
	 */
	public function stashInputText( $text, $textHash ) {
		$textKey = $this->cache->makeKey( 'stashedit', 'text', $textHash );

		return $this->cache->set(
			$textKey,
			$text,
			self::MAX_CACHE_TTL,
			BagOStuff::WRITE_ALLOW_SEGMENTS
		);
	}

	/**
	 * @param UserIdentity $user
	 * @return string|null TS_MW timestamp or null
	 */
	private function lastEditTime( UserIdentity $user ) {
		$time = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( 'MAX(rc_timestamp)' )
			->from( 'recentchanges' )
			->join( 'actor', null, 'actor_id=rc_actor' )
			->where( [ 'actor_name' => $user->getName() ] )
			->caller( __METHOD__ )
			->fetchField();

		return wfTimestampOrNull( TS_MW, $time );
	}

	/**
	 * Get hash of the content, factoring in model/format
	 *
	 * @param Content $content
	 * @return string
	 */
	private function getContentHash( Content $content ) {
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
	 * @param PageIdentity $page
	 * @param string $contentHash Result of getContentHash()
	 * @param UserIdentity $user User to get parser options from
	 * @return string
	 */
	private function getStashKey( PageIdentity $page, $contentHash, UserIdentity $user ) {
		return $this->cache->makeKey(
			'stashedit-info-v2',
			md5( "{$page->getNamespace()}\n{$page->getDBkey()}" ),
			// Account for the edit model/text
			$contentHash,
			// Account for user name related variables like signatures
			md5( "{$user->getId()}\n{$user->getName()}" )
		);
	}

	/**
	 * @param string $key
	 * @return stdClass|bool Object map (pstContent,output,outputID,timestamp,edits) or false
	 */
	private function getStashValue( $key ) {
		$serial = $this->cache->get( $key );

		return $this->unserializeStashInfo( $serial );
	}

	/**
	 * Build a value to store in memcached based on the PST content and parser output
	 *
	 * This makes a simple version of WikiPage::prepareContentForEdit() as stash info
	 *
	 * @param string $key
	 * @param Content $pstContent Pre-Save transformed content
	 * @param ParserOutput $parserOutput
	 * @param string $timestamp TS_MW
	 * @param UserIdentity $user
	 * @return string|bool True or an error code
	 */
	private function storeStashValue(
		$key,
		Content $pstContent,
		ParserOutput $parserOutput,
		$timestamp,
		UserIdentity $user
	) {
		// If an item is renewed, mind the cache TTL determined by config and parser functions.
		// Put an upper limit on the TTL to avoid extreme template/file staleness.
		$age = time() - (int)wfTimestamp( TS_UNIX, $parserOutput->getCacheTime() );
		$ttl = min( $parserOutput->getCacheExpiry() - $age, self::MAX_CACHE_TTL );
		// Avoid extremely stale user signature timestamps (T84843)
		if ( $parserOutput->getOutputFlag( ParserOutputFlags::USER_SIGNATURE ) ) {
			$ttl = min( $ttl, self::MAX_SIGNATURE_TTL );
		}

		if ( $ttl <= 0 ) {
			return 'uncacheable'; // low TTL due to a tag, magic word, or signature?
		}

		// Store what is actually needed and split the output into another key (T204742)
		$stashInfo = (object)[
			'pstContent' => $pstContent,
			'output'     => $parserOutput,
			'timestamp'  => $timestamp,
			'edits'      => $this->userEditTracker->getUserEditCount( $user ),
		];
		$serial = $this->serializeStashInfo( $stashInfo );
		if ( $serial === false ) {
			return 'store_error';
		}

		$ok = $this->cache->set( $key, $serial, $ttl, BagOStuff::WRITE_ALLOW_SEGMENTS );
		if ( $ok ) {
			// These blobs can waste slots in low cardinality memcached slabs
			$this->pruneExcessStashedEntries( $user, $key );
		}

		return $ok ? true : 'store_error';
	}

	/**
	 * @param UserIdentity $user
	 * @param string $newKey
	 */
	private function pruneExcessStashedEntries( UserIdentity $user, $newKey ) {
		$key = $this->cache->makeKey( 'stash-edit-recent', sha1( $user->getName() ) );

		$keyList = $this->cache->get( $key ) ?: [];
		if ( count( $keyList ) >= self::MAX_CACHE_RECENT ) {
			$oldestKey = array_shift( $keyList );
			$this->cache->delete( $oldestKey, BagOStuff::WRITE_ALLOW_SEGMENTS );
		}

		$keyList[] = $newKey;
		$this->cache->set( $key, $keyList, 2 * self::MAX_CACHE_TTL );
	}

	/**
	 * @param UserIdentity $user
	 * @return int
	 */
	private function recentStashEntryCount( UserIdentity $user ) {
		$key = $this->cache->makeKey( 'stash-edit-recent', sha1( $user->getName() ) );

		return count( $this->cache->get( $key ) ?: [] );
	}

	/** @return string|false */
	private function serializeStashInfo( stdClass $stashInfo ) {
		// @todo: use JSON with ParserOutput and Content
		return serialize( $stashInfo );
	}

	/**
	 * @param mixed $serial
	 * @return stdClass|false
	 */
	private function unserializeStashInfo( $serial ) {
		if ( is_string( $serial ) ) {
			// @todo: use JSON with ParserOutput and Content
			$stashInfo = unserialize( $serial );
			if ( is_object( $stashInfo ) && $stashInfo->output instanceof ParserOutput ) {
				return $stashInfo;
			}
		}

		return false;
	}
}
