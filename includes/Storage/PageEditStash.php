<?php
/**
 * Predictive edit preparation system for MediaWiki page.
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

namespace MediaWiki\Storage;

use ActorMigration;
use BagOStuff;
use Content;
use Hooks;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use ParserOutput;
use Psr\Log\LoggerInterface;
use stdClass;
use Title;
use User;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\ScopedCallback;
use WikiPage;

/**
 * Class for managing stashed edits used by the page updater classes
 *
 * @since 1.34
 */
class PageEditStash {
	/** @var BagOStuff */
	private $cache;
	/** @var ILoadBalancer */
	private $lb;
	/** @var LoggerInterface */
	private $logger;
	/** @var StatsdDataFactoryInterface */
	private $stats;
	/** @var int */
	private $initiator;

	const ERROR_NONE = 'stashed';
	const ERROR_PARSE = 'error_parse';
	const ERROR_CACHE = 'error_cache';
	const ERROR_UNCACHEABLE = 'uncacheable';
	const ERROR_BUSY = 'busy';

	const PRESUME_FRESH_TTL_SEC = 30;
	const MAX_CACHE_TTL = 300; // 5 minutes
	const MAX_SIGNATURE_TTL = 60;

	const MAX_CACHE_RECENT = 2;

	const INITIATOR_USER = 1;
	const INITIATOR_JOB_OR_CLI = 2;

	/**
	 * @param BagOStuff $cache
	 * @param ILoadBalancer $lb
	 * @param LoggerInterface $logger
	 * @param StatsdDataFactoryInterface $stats
	 * @param int $initiator Class INITIATOR__* constant
	 */
	public function __construct(
		BagOStuff $cache,
		ILoadBalancer $lb,
		LoggerInterface $logger,
		StatsdDataFactoryInterface $stats,
		$initiator
	) {
		$this->cache = $cache;
		$this->lb = $lb;
		$this->logger = $logger;
		$this->stats = $stats;
		$this->initiator = $initiator;
	}

	/**
	 * @param WikiPage $page
	 * @param Content $content Edit content
	 * @param User $user
	 * @param string $summary Edit summary
	 * @return string Class ERROR_* constant
	 */
	public function parseAndCache( WikiPage $page, Content $content, User $user, $summary ) {
		$logger = $this->logger;

		$title = $page->getTitle();
		$key = $this->getStashKey( $title, $this->getContentHash( $content ), $user );
		$fname = __METHOD__;

		// Use the master DB to allow for fast blocking locks on the "save path" where this
		// value might actually be used to complete a page edit. If the edit submission request
		// happens before this edit stash requests finishes, then the submission will block until
		// the stash request finishes parsing. For the lock acquisition below, there is not much
		// need to duplicate parsing of the same content/user/summary bundle, so try to avoid
		// blocking at all here.
		$dbw = $this->lb->getConnectionRef( DB_MASTER );
		if ( !$dbw->lock( $key, $fname, 0 ) ) {
			// De-duplicate requests on the same key
			return self::ERROR_BUSY;
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$unlocker = new ScopedCallback( function () use ( $dbw, $key, $fname ) {
			$dbw->unlock( $key, $fname );
		} );

		$cutoffTime = time() - self::PRESUME_FRESH_TTL_SEC;

		// Reuse any freshly build matching edit stash cache
		$editInfo = $this->getStashValue( $key );
		if ( $editInfo && wfTimestamp( TS_UNIX, $editInfo->timestamp ) >= $cutoffTime ) {
			$alreadyCached = true;
		} else {
			$format = $content->getDefaultFormat();
			$editInfo = $page->prepareContentForEdit( $content, null, $user, $format, false );
			$editInfo->output->setCacheTime( $editInfo->timestamp );
			$alreadyCached = false;
		}

		$context = [ 'cachekey' => $key, 'title' => $title->getPrefixedText() ];

		if ( $editInfo && $editInfo->output ) {
			// Let extensions add ParserOutput metadata or warm other caches
			Hooks::run( 'ParserOutputStashForEdit',
				[ $page, $content, $editInfo->output, $summary, $user ] );

			if ( $alreadyCached ) {
				$logger->debug( "Parser output for key '{cachekey}' already cached.", $context );

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
				$logger->debug( "Cached parser output for key '{cachekey}'.", $context );

				return self::ERROR_NONE;
			} elseif ( $code === 'uncacheable' ) {
				$logger->info(
					"Uncacheable parser output for key '{cachekey}' [{code}].",
					$context + [ 'code' => $code ]
				);

				return self::ERROR_UNCACHEABLE;
			} else {
				$logger->error(
					"Failed to cache parser output for key '{cachekey}'.",
					$context + [ 'code' => $code ]
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
	 * @param Title $title
	 * @param Content $content
	 * @param User $user User to get parser options from
	 * @return stdClass|bool Returns edit stash object or false on cache miss
	 */
	public function checkCache( Title $title, Content $content, User $user ) {
		if (
			// The context is not an HTTP POST request
			!$user->getRequest()->wasPosted() ||
			// The context is a CLI script or a job runner HTTP POST request
			$this->initiator !== self::INITIATOR_USER ||
			// The editor account is a known bot
			$user->isBot()
		) {
			// Avoid wasted queries and statsd pollution
			return false;
		}

		$logger = $this->logger;

		$key = $this->getStashKey( $title, $this->getContentHash( $content ), $user );
		$context = [
			'key' => $key,
			'title' => $title->getPrefixedText(),
			'user' => $user->getName()
		];

		$editInfo = $this->getAndWaitForStashValue( $key );
		if ( !is_object( $editInfo ) || !$editInfo->output ) {
			$this->incrStatsByContent( 'cache_misses.no_stash', $content );
			if ( $this->recentStashEntryCount( $user ) > 0 ) {
				$logger->info( "Empty cache for key '{key}' but not for user.", $context );
			} else {
				$logger->debug( "Empty cache for key '{key}'.", $context );
			}

			return false;
		}

		$age = time() - (int)wfTimestamp( TS_UNIX, $editInfo->output->getCacheTime() );
		$context['age'] = $age;

		$isCacheUsable = true;
		if ( $age <= self::PRESUME_FRESH_TTL_SEC ) {
			// Assume nothing changed in this time
			$this->incrStatsByContent( 'cache_hits.presumed_fresh', $content );
			$logger->debug( "Timestamp-based cache hit for key '{key}'.", $context );
		} elseif ( $user->isAnon() ) {
			$lastEdit = $this->lastEditTime( $user );
			$cacheTime = $editInfo->output->getCacheTime();
			if ( $lastEdit < $cacheTime ) {
				// Logged-out user made no local upload/template edits in the meantime
				$this->incrStatsByContent( 'cache_hits.presumed_fresh', $content );
				$logger->debug( "Edit check based cache hit for key '{key}'.", $context );
			} else {
				$isCacheUsable = false;
				$this->incrStatsByContent( 'cache_misses.proven_stale', $content );
				$logger->info( "Stale cache for key '{key}' due to outside edits.", $context );
			}
		} else {
			if ( $editInfo->edits === $user->getEditCount() ) {
				// Logged-in user made no local upload/template edits in the meantime
				$this->incrStatsByContent( 'cache_hits.presumed_fresh', $content );
				$logger->debug( "Edit count based cache hit for key '{key}'.", $context );
			} else {
				$isCacheUsable = false;
				$this->incrStatsByContent( 'cache_misses.proven_stale', $content );
				$logger->info( "Stale cache for key '{key}'due to outside edits.", $context );
			}
		}

		if ( !$isCacheUsable ) {
			return false;
		}

		if ( $editInfo->output->getFlag( 'vary-revision' ) ) {
			// This can be used for the initial parse, e.g. for filters or doEditContent(),
			// but a second parse will be triggered in doEditUpdates() no matter what
			$logger->info(
				"Cache for key '{key}' has vary-revision; post-insertion parse inevitable.",
				$context
			);
		} else {
			static $flagsMaybeReparse = [
				// Similar to the above if we didn't guess the ID correctly
				'vary-revision-id',
				// Similar to the above if we didn't guess the timestamp correctly
				'vary-revision-timestamp',
				// Similar to the above if we didn't guess the content correctly
				'vary-revision-sha1',
				// Similar to the above if we didn't guess page ID correctly
				'vary-page-id'
			];
			foreach ( $flagsMaybeReparse as $flag ) {
				if ( $editInfo->output->getFlag( $flag ) ) {
					$logger->debug(
						"Cache for key '{key}' has $flag; post-insertion parse possible.",
						$context
					);
				}
			}
		}

		return $editInfo;
	}

	/**
	 * @param string $subkey
	 * @param Content $content
	 */
	private function incrStatsByContent( $subkey, Content $content ) {
		$this->stats->increment( 'editstash.' . $subkey ); // overall for b/c
		$this->stats->increment( 'editstash_by_model.' . $content->getModel() . '.' . $subkey );
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
			// Skip this logic if there no master connection in case this method
			// is called on an HTTP GET request for some reason.
			$dbw = $this->lb->getAnyOpenConnection( $this->lb->getWriterIndex() );
			if ( $dbw && $dbw->lock( $key, __METHOD__, 30 ) ) {
				$editInfo = $this->getStashValue( $key );
				$dbw->unlock( $key, __METHOD__ );
			}

			$timeMs = 1000 * max( 0, microtime( true ) - $start );
			$this->stats->timing( 'editstash.lock_wait_time', $timeMs );
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
	 * @param User $user
	 * @return string|null TS_MW timestamp or null
	 */
	private function lastEditTime( User $user ) {
		$db = $this->lb->getConnectionRef( DB_REPLICA );

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
	 * @param Title $title
	 * @param string $contentHash Result of getContentHash()
	 * @param User $user User to get parser options from
	 * @return string
	 */
	private function getStashKey( Title $title, $contentHash, User $user ) {
		return $this->cache->makeKey(
			'stashedit-info-v1',
			md5( $title->getPrefixedDBkey() ),
			// Account for the edit model/text
			$contentHash,
			// Account for user name related variables like signatures
			md5( $user->getId() . "\n" . $user->getName() )
		);
	}

	/**
	 * @param string $key
	 * @return stdClass|bool Object map (pstContent,output,outputID,timestamp,edits) or false
	 */
	private function getStashValue( $key ) {
		$stashInfo = $this->cache->get( $key );
		if ( is_object( $stashInfo ) && $stashInfo->output instanceof ParserOutput ) {
			return $stashInfo;
		}

		return false;
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
	 * @param User $user
	 * @return string|bool True or an error code
	 */
	private function storeStashValue(
		$key,
		Content $pstContent,
		ParserOutput $parserOutput,
		$timestamp,
		User $user
	) {
		// If an item is renewed, mind the cache TTL determined by config and parser functions.
		// Put an upper limit on the TTL for sanity to avoid extreme template/file staleness.
		$age = time() - (int)wfTimestamp( TS_UNIX, $parserOutput->getCacheTime() );
		$ttl = min( $parserOutput->getCacheExpiry() - $age, self::MAX_CACHE_TTL );
		// Avoid extremely stale user signature timestamps (T84843)
		if ( $parserOutput->getFlag( 'user-signature' ) ) {
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
			'edits'      => $user->getEditCount()
		];

		$ok = $this->cache->set( $key, $stashInfo, $ttl, BagOStuff::WRITE_ALLOW_SEGMENTS );
		if ( $ok ) {
			// These blobs can waste slots in low cardinality memcached slabs
			$this->pruneExcessStashedEntries( $user, $key );
		}

		return $ok ? true : 'store_error';
	}

	/**
	 * @param User $user
	 * @param string $newKey
	 */
	private function pruneExcessStashedEntries( User $user, $newKey ) {
		$key = $this->cache->makeKey( 'stash-edit-recent', sha1( $user->getName() ) );

		$keyList = $this->cache->get( $key ) ?: [];
		if ( count( $keyList ) >= self::MAX_CACHE_RECENT ) {
			$oldestKey = array_shift( $keyList );
			$this->cache->delete( $oldestKey, BagOStuff::WRITE_PRUNE_SEGMENTS );
		}

		$keyList[] = $newKey;
		$this->cache->set( $key, $keyList, 2 * self::MAX_CACHE_TTL );
	}

	/**
	 * @param User $user
	 * @return int
	 */
	private function recentStashEntryCount( User $user ) {
		$key = $this->cache->makeKey( 'stash-edit-recent', sha1( $user->getName() ) );

		return count( $this->cache->get( $key ) ?: [] );
	}
}
