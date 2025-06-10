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

namespace MediaWiki\ChangeTags;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Status\Status;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\RawSQLValue;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Read-write access to the change_tags table.
 *
 * @since 1.41
 * @ingroup ChangeTags
 */
class ChangeTagsStore {

	/**
	 * Name of change_tag table
	 */
	private const CHANGE_TAG = 'change_tag';

	/**
	 * Name of change_tag_def table
	 */
	private const CHANGE_TAG_DEF = 'change_tag_def';

	public const DISPLAY_TABLE_ALIAS = 'changetagdisplay';

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::SoftwareTags,
		MainConfigNames::UseTagFilter,
	];

	/**
	 * A list of tags defined and used by MediaWiki itself.
	 */
	private const DEFINED_SOFTWARE_TAGS = [
		'mw-contentmodelchange',
		'mw-new-redirect',
		'mw-removed-redirect',
		'mw-changed-redirect-target',
		'mw-blank',
		'mw-replace',
		'mw-recreated',
		'mw-rollback',
		'mw-undo',
		'mw-manual-revert',
		'mw-reverted',
		'mw-server-side-upload',
	];

	private IConnectionProvider $dbProvider;
	private LoggerInterface $logger;
	private ServiceOptions $options;
	private NameTableStore $changeTagDefStore;
	private WANObjectCache $wanCache;
	private HookRunner $hookRunner;
	private UserFactory $userFactory;
	private HookContainer $hookContainer;

	public function __construct(
		IConnectionProvider $dbProvider,
		NameTableStore $changeTagDefStore,
		WANObjectCache $wanCache,
		HookContainer $hookContainer,
		LoggerInterface $logger,
		UserFactory $userFactory,
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->dbProvider = $dbProvider;
		$this->logger = $logger;
		$this->options = $options;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->wanCache = $wanCache;
		$this->hookContainer = $hookContainer;
		$this->userFactory = $userFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Loads defined core tags, checks for invalid types (if not array),
	 * and filters for supported and enabled (if $all is false) tags only.
	 *
	 * @param bool $all If true, return all valid defined tags. Otherwise, return only enabled ones.
	 * @return array Array of all defined/enabled tags.
	 */
	public function getSoftwareTags( $all = false ): array {
		$coreTags = $this->options->get( MainConfigNames::SoftwareTags );
		if ( !is_array( $coreTags ) ) {
			$this->logger->warning( 'wgSoftwareTags should be associative array of enabled tags.
			Please refer to documentation for the list of tags you can enable' );
			return [];
		}

		$availableSoftwareTags = !$all ?
			array_keys( array_filter( $coreTags ) ) :
			array_keys( $coreTags );

		return array_intersect(
			$availableSoftwareTags,
			self::DEFINED_SOFTWARE_TAGS
		);
	}

	/**
	 * Expose the codebase-level defined software tags.
	 * No filtering is available for this function.
	 *
	 * @return array Array of all core-defined tags
	 */
	public function getCoreDefinedTags(): array {
		return self::DEFINED_SOFTWARE_TAGS;
	}

	/**
	 * Return all the tags associated with the given recent change ID,
	 * revision ID, and/or log entry ID, along with any data stored with the tag.
	 *
	 * @param IReadableDatabase $db the database to query
	 * @param int|null $rc_id
	 * @param int|null $rev_id
	 * @param int|null $log_id
	 * @return string[] Tag name => data. Data format is tag-specific.
	 * @since 1.41
	 */
	public function getTagsWithData(
		IReadableDatabase $db, $rc_id = null, $rev_id = null, $log_id = null
	): array {
		if ( !$rc_id && !$rev_id && !$log_id ) {
			throw new InvalidArgumentException(
				'At least one of: RCID, revision ID, and log ID MUST be ' .
				'specified when loading tags from a change!' );
		}

		$conds = array_filter(
			[
				'ct_rc_id' => $rc_id,
				'ct_rev_id' => $rev_id,
				'ct_log_id' => $log_id,
			]
		);
		$result = $db->newSelectQueryBuilder()
			->select( [ 'ct_tag_id', 'ct_params' ] )
			->from( self::CHANGE_TAG )
			->where( $conds )
			->caller( __METHOD__ )
			->fetchResultSet();

		$tags = [];
		foreach ( $result as $row ) {
			$tagName = $this->changeTagDefStore->getName( (int)$row->ct_tag_id );
			$tags[$tagName] = $row->ct_params;
		}

		return $tags;
	}

	/**
	 * Make the tag summary subquery based on the given tables and return it.
	 *
	 * @param string|array $tables Table names, see Database::select
	 *
	 * @return string tag summary subqeury
	 */
	public function makeTagSummarySubquery( $tables ) {
		// Normalize to arrays
		$tables = (array)$tables;

		// Figure out which ID field to use
		if ( in_array( 'recentchanges', $tables ) ) {
			$join_cond = 'ct_rc_id=rc_id';
		} elseif ( in_array( 'logging', $tables ) ) {
			$join_cond = 'ct_log_id=log_id';
		} elseif ( in_array( 'revision', $tables ) ) {
			$join_cond = 'ct_rev_id=rev_id';
		} elseif ( in_array( 'archive', $tables ) ) {
			$join_cond = 'ct_rev_id=ar_rev_id';
		} else {
			throw new InvalidArgumentException( 'Unable to determine appropriate JOIN condition for tagging.' );
		}

		return $this->dbProvider->getReplicaDatabase()
			->newSelectQueryBuilder()
			->table( self::CHANGE_TAG )
			->join( self::CHANGE_TAG_DEF, null, 'ct_tag_id=ctd_id' )
			->field( 'ctd_name' )
			->where( $join_cond )
			->buildGroupConcatField( ',' );
	}

	/**
	 * Set ctd_user_defined = 1 in change_tag_def without checking that the tag name is valid.
	 * Extensions should NOT use this function; they can use the ListDefinedTags
	 * hook instead.
	 *
	 * @param string $tag Tag to create
	 * @since 1.41
	 */
	public function defineTag( $tag ) {
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->newInsertQueryBuilder()
			->insertInto( self::CHANGE_TAG_DEF )
			->row( [
				'ctd_name' => $tag,
				'ctd_user_defined' => 1,
				'ctd_count' => 0
			] )
			->onDuplicateKeyUpdate()
			->uniqueIndexFields( [ 'ctd_name' ] )
			->set( [ 'ctd_user_defined' => 1 ] )
			->caller( __METHOD__ )->execute();

		// clear the memcache of defined tags
		$this->purgeTagCacheAll();
	}

	/**
	 * Update ctd_user_defined = 0 field in change_tag_def.
	 * The tag may remain in use by extensions, and may still show up as 'defined'
	 * if an extension is setting it from the ListDefinedTags hook.
	 *
	 * @param string $tag Tag to remove
	 * @since 1.41
	 */
	public function undefineTag( $tag ) {
		$dbw = $this->dbProvider->getPrimaryDatabase();

		$dbw->newUpdateQueryBuilder()
			->update( self::CHANGE_TAG_DEF )
			->set( [ 'ctd_user_defined' => 0 ] )
			->where( [ 'ctd_name' => $tag ] )
			->caller( __METHOD__ )->execute();

		$dbw->newDeleteQueryBuilder()
			->deleteFrom( self::CHANGE_TAG_DEF )
			->where( [ 'ctd_name' => $tag, 'ctd_count' => 0 ] )
			->caller( __METHOD__ )->execute();

		// clear the memcache of defined tags
		$this->purgeTagCacheAll();
	}

	/**
	 * Writes a tag action into the tag management log.
	 *
	 * @param string $action
	 * @param string $tag
	 * @param string $reason
	 * @param UserIdentity $user Who to attribute the action to
	 * @param int|null $tagCount For deletion only, how many usages the tag had before
	 * it was deleted.
	 * @param array $logEntryTags Change tags to apply to the entry
	 * that will be created in the tag management log
	 * @return int ID of the inserted log entry
	 * @since 1.41
	 */
	public function logTagManagementAction( string $action, string $tag, string $reason,
		UserIdentity $user, $tagCount = null, array $logEntryTags = []
	) {
		$dbw = $this->dbProvider->getPrimaryDatabase();

		$logEntry = new ManualLogEntry( 'managetags', $action );
		$logEntry->setPerformer( $user );
		// target page is not relevant, but it has to be set, so we just put in
		// the title of Special:Tags
		$logEntry->setTarget( Title::newFromText( 'Special:Tags' ) );
		$logEntry->setComment( $reason );

		$params = [ '4::tag' => $tag ];
		if ( $tagCount !== null ) {
			$params['5:number:count'] = $tagCount;
		}
		$logEntry->setParameters( $params );
		$logEntry->setRelations( [ 'Tag' => $tag ] );
		$logEntry->addTags( $logEntryTags );

		$logId = $logEntry->insert( $dbw );
		$logEntry->publish( $logId );
		return $logId;
	}

	/**
	 * Permanently removes all traces of a tag from the DB. Good for removing
	 * misspelt or temporary tags.
	 *
	 * This function should be directly called by maintenance scripts only, never
	 * by user-facing code. See deleteTagWithChecks() for functionality that can
	 * safely be exposed to users.
	 *
	 * @param string $tag Tag to remove
	 * @return Status The returned status will be good unless a hook changed it
	 * @since 1.41
	 */
	public function deleteTagEverywhere( $tag ) {
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->startAtomic( __METHOD__ );

		// fetch tag id, this must be done before calling undefineTag(), see T225564
		$tagId = $this->changeTagDefStore->getId( $tag );

		// set ctd_user_defined = 0
		$this->undefineTag( $tag );

		// delete from change_tag
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( self::CHANGE_TAG )
			->where( [ 'ct_tag_id' => $tagId ] )
			->caller( __METHOD__ )->execute();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( self::CHANGE_TAG_DEF )
			->where( [ 'ctd_name' => $tag ] )
			->caller( __METHOD__ )->execute();
		$dbw->endAtomic( __METHOD__ );

		// give extensions a chance
		$status = Status::newGood();
		$this->hookRunner->onChangeTagAfterDelete( $tag, $status );
		// let's not allow error results, as the actual tag deletion succeeded
		if ( !$status->isOK() ) {
			$this->logger->debug( 'ChangeTagAfterDelete error condition downgraded to warning' );
			$status->setOK( true );
		}

		// clear the memcache of defined tags
		$this->purgeTagCacheAll();

		return $status;
	}

	/**
	 * Invalidates the short-term cache of defined tags used by the
	 * list*DefinedTags functions, as well as the tag statistics cache.
	 * @since 1.41
	 */
	public function purgeTagCacheAll() {
		$this->wanCache->touchCheckKey( $this->wanCache->makeKey( 'active-tags' ) );
		$this->wanCache->touchCheckKey( $this->wanCache->makeKey( 'valid-tags-db' ) );
		$this->wanCache->touchCheckKey( $this->wanCache->makeKey( 'valid-tags-hook' ) );
		$this->wanCache->touchCheckKey( $this->wanCache->makeKey( 'tags-usage-statistics' ) );

		$this->changeTagDefStore->reloadMap();
	}

	/**
	 * Returns a map of any tags used on the wiki to number of edits
	 * tagged with them, ordered descending by the hitcount.
	 * This does not include tags defined somewhere that have never been applied.
	 * @return array Array of string => int
	 */
	public function tagUsageStatistics(): array {
		$fname = __METHOD__;
		$dbProvider = $this->dbProvider;

		return $this->wanCache->getWithSetCallback(
			$this->wanCache->makeKey( 'tags-usage-statistics' ),
			WANObjectCache::TTL_HOUR,
			static function ( $oldValue, &$ttl, array &$setOpts ) use ( $fname, $dbProvider ) {
				$dbr = $dbProvider->getReplicaDatabase();
				$res = $dbr->newSelectQueryBuilder()
					->select( [ 'ctd_name', 'ctd_count' ] )
					->from( self::CHANGE_TAG_DEF )
					->orderBy( 'ctd_count', SelectQueryBuilder::SORT_DESC )
					->caller( $fname )
					->fetchResultSet();

				$out = [];
				foreach ( $res as $row ) {
					$out[$row->ctd_name] = $row->ctd_count;
				}

				return $out;
			},
			[
				'checkKeys' => [ $this->wanCache->makeKey( 'tags-usage-statistics' ) ],
				'lockTSE' => WANObjectCache::TTL_HOUR,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Lists tags explicitly defined in the `change_tag_def` table of the database.
	 *
	 * Tries memcached first.
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public function listExplicitlyDefinedTags() {
		$fname = __METHOD__;
		$dbProvider = $this->dbProvider;

		return $this->wanCache->getWithSetCallback(
			$this->wanCache->makeKey( 'valid-tags-db' ),
			WANObjectCache::TTL_HOUR,
			static function ( $oldValue, &$ttl, array &$setOpts ) use ( $fname, $dbProvider ) {
				$dbr = $dbProvider->getReplicaDatabase();
				$setOpts += Database::getCacheSetOptions( $dbr );
				$tags = $dbr->newSelectQueryBuilder()
					->select( 'ctd_name' )
					->from( self::CHANGE_TAG_DEF )
					->where( [ 'ctd_user_defined' => 1 ] )
					->caller( $fname )
					->fetchFieldValues();

				return array_unique( $tags );
			},
			[
				'checkKeys' => [ $this->wanCache->makeKey( 'valid-tags-db' ) ],
				'lockTSE' => WANObjectCache::TTL_HOUR,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Lists tags defined by core or extensions using the ListDefinedTags hook.
	 * Extensions need only define those tags they deem to be in active use.
	 *
	 * Tries memcached first.
	 *
	 * @return string[] Array of strings: tags
	 * @since 1.25
	 */
	public function listSoftwareDefinedTags() {
		// core defined tags
		$tags = $this->getSoftwareTags( true );
		if ( !$this->hookContainer->isRegistered( 'ListDefinedTags' ) ) {
			return $tags;
		}
		$hookRunner = $this->hookRunner;
		$dbProvider = $this->dbProvider;
		return $this->wanCache->getWithSetCallback(
			$this->wanCache->makeKey( 'valid-tags-hook' ),
			WANObjectCache::TTL_HOUR,
			static function ( $oldValue, &$ttl, array &$setOpts ) use ( $tags, $hookRunner, $dbProvider ) {
				$setOpts += Database::getCacheSetOptions( $dbProvider->getReplicaDatabase() );
				$hookRunner->onListDefinedTags( $tags );
				return array_unique( $tags );
			},
			[
				'checkKeys' => [ $this->wanCache->makeKey( 'valid-tags-hook' ) ],
				'lockTSE' => WANObjectCache::TTL_HOUR,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Return all the tags associated with the given recent change ID,
	 * revision ID, and/or log entry ID.
	 *
	 * @param IReadableDatabase $db the database to query
	 * @param int|null $rc_id
	 * @param int|null $rev_id
	 * @param int|null $log_id
	 * @return string[]
	 */
	public function getTags( IReadableDatabase $db, $rc_id = null, $rev_id = null, $log_id = null ) {
		return array_keys( $this->getTagsWithData( $db, $rc_id, $rev_id, $log_id ) );
	}

	/**
	 * Basically lists defined tags which count even if they aren't applied to anything.
	 * It returns a union of the results of listExplicitlyDefinedTags() and
	 * listSoftwareDefinedTags()
	 *
	 * @return string[] Array of strings: tags
	 */
	public function listDefinedTags() {
		$tags1 = $this->listExplicitlyDefinedTags();
		$tags2 = $this->listSoftwareDefinedTags();
		return array_values( array_unique( array_merge( $tags1, $tags2 ) ) );
	}

	/**
	 * Add and remove tags to/from a change given its rc_id, rev_id and/or log_id,
	 * without verifying that the tags exist or are valid. If a tag is present in
	 * both $tagsToAdd and $tagsToRemove, it will be removed.
	 *
	 * This function should only be used by extensions to manipulate tags they
	 * have registered using the ListDefinedTags hook. When dealing with user
	 * input, call updateTagsWithChecks() instead.
	 *
	 * @param string|array|null $tagsToAdd Tags to add to the change
	 * @param string|array|null $tagsToRemove Tags to remove from the change
	 * @param int|null &$rc_id The rc_id of the change to add the tags to.
	 * Pass a variable whose value is null if the rc_id is not relevant or unknown.
	 * @param int|null &$rev_id The rev_id of the change to add the tags to.
	 * Pass a variable whose value is null if the rev_id is not relevant or unknown.
	 * @param int|null &$log_id The log_id of the change to add the tags to.
	 * Pass a variable whose value is null if the log_id is not relevant or unknown.
	 * @param string|null $params Params to put in the ct_params field of table
	 * 'change_tag' when adding tags
	 * @param RecentChange|null $rc Recent change being tagged, in case the tagging accompanies
	 * the action
	 * @param UserIdentity|null $user Tagging user, in case the tagging is subsequent to the tagged action
	 *
	 * @return array Index 0 is an array of tags actually added, index 1 is an
	 * array of tags actually removed, index 2 is an array of tags present on the
	 * revision or log entry before any changes were made
	 */
	public function updateTags( $tagsToAdd, $tagsToRemove, &$rc_id = null,
		&$rev_id = null, &$log_id = null, $params = null, ?RecentChange $rc = null,
		?UserIdentity $user = null
	) {
		$tagsToAdd = array_filter(
			(array)$tagsToAdd, // Make sure we're submitting all tags...
			static function ( $value ) {
				return ( $value ?? '' ) !== '';
			}
		);
		$tagsToRemove = array_filter(
			(array)$tagsToRemove,
			static function ( $value ) {
				return ( $value ?? '' ) !== '';
			}
		);

		if ( !$rc_id && !$rev_id && !$log_id ) {
			throw new InvalidArgumentException( 'At least one of: RCID, revision ID, and log ID MUST be ' .
				'specified when adding or removing a tag from a change!' );
		}

		$dbw = $this->dbProvider->getPrimaryDatabase();

		// Might as well look for rcids and so on.
		if ( !$rc_id ) {
			// Info might be out of date, somewhat fractionally, on replica DB.
			// LogEntry/LogPage and WikiPage match rev/log/rc timestamps,
			// so use that relation to avoid full table scans.
			if ( $log_id ) {
				$rc_id = $dbw->newSelectQueryBuilder()
					->select( 'rc_id' )
					->from( 'logging' )
					->join( 'recentchanges', null, [
						'rc_timestamp = log_timestamp',
						'rc_logid = log_id'
					] )
					->where( [ 'log_id' => $log_id ] )
					->caller( __METHOD__ )
					->fetchField();
			} elseif ( $rev_id ) {
				$rc_id = $dbw->newSelectQueryBuilder()
					->select( 'rc_id' )
					->from( 'revision' )
					->join( 'recentchanges', null, [
						'rc_this_oldid = rev_id'
					] )
					->where( [ 'rev_id' => $rev_id ] )
					->caller( __METHOD__ )
					->fetchField();
			}
		} elseif ( !$log_id && !$rev_id ) {
			// Info might be out of date, somewhat fractionally, on replica DB.
			$log_id = $dbw->newSelectQueryBuilder()
				->select( 'rc_logid' )
				->from( 'recentchanges' )
				->where( [ 'rc_id' => $rc_id ] )
				->caller( __METHOD__ )
				->fetchField();
			$rev_id = $dbw->newSelectQueryBuilder()
				->select( 'rc_this_oldid' )
				->from( 'recentchanges' )
				->where( [ 'rc_id' => $rc_id ] )
				->caller( __METHOD__ )
				->fetchField();
		}

		if ( $log_id && !$rev_id ) {
			$rev_id = $dbw->newSelectQueryBuilder()
				->select( 'ls_value' )
				->from( 'log_search' )
				->where( [ 'ls_field' => 'associated_rev_id', 'ls_log_id' => $log_id ] )
				->caller( __METHOD__ )
				->fetchField();
		} elseif ( !$log_id && $rev_id ) {
			$log_id = $dbw->newSelectQueryBuilder()
				->select( 'ls_log_id' )
				->from( 'log_search' )
				->where( [ 'ls_field' => 'associated_rev_id', 'ls_value' => (string)$rev_id ] )
				->caller( __METHOD__ )
				->fetchField();
		}

		$prevTags = $this->getTags( $dbw, $rc_id, $rev_id, $log_id );

		// add tags
		$tagsToAdd = array_values( array_diff( $tagsToAdd, $prevTags ) );
		$newTags = array_unique( array_merge( $prevTags, $tagsToAdd ) );

		// remove tags
		$tagsToRemove = array_values( array_intersect( $tagsToRemove, $newTags ) );
		$newTags = array_values( array_diff( $newTags, $tagsToRemove ) );

		sort( $prevTags );
		sort( $newTags );
		if ( $prevTags == $newTags ) {
			return [ [], [], $prevTags ];
		}

		// insert a row into change_tag for each new tag
		if ( count( $tagsToAdd ) ) {
			$changeTagMapping = [];
			foreach ( $tagsToAdd as $tag ) {
				$changeTagMapping[$tag] = $this->changeTagDefStore->acquireId( $tag );
			}
			$fname = __METHOD__;
			// T207881: update the counts at the end of the transaction
			$dbw->onTransactionPreCommitOrIdle( static function () use ( $dbw, $tagsToAdd, $fname ) {
				$dbw->newUpdateQueryBuilder()
					->update( self::CHANGE_TAG_DEF )
					->set( [ 'ctd_count' => new RawSQLValue( 'ctd_count + 1' ) ] )
					->where( [ 'ctd_name' => $tagsToAdd ] )
					->caller( $fname )->execute();
			}, $fname );

			$tagsRows = [];
			foreach ( $tagsToAdd as $tag ) {
				// Filter so we don't insert NULLs as zero accidentally.
				// Keep in mind that $rc_id === null means "I don't care/know about the
				// rc_id, just delete $tag on this revision/log entry". It doesn't
				// mean "only delete tags on this revision/log WHERE rc_id IS NULL".
				$tagsRows[] = array_filter(
					[
						'ct_rc_id' => $rc_id,
						'ct_log_id' => $log_id,
						'ct_rev_id' => $rev_id,
						'ct_params' => $params,
						'ct_tag_id' => $changeTagMapping[$tag] ?? null,
					]
				);

			}

			$dbw->newInsertQueryBuilder()
				->insertInto( self::CHANGE_TAG )
				->ignore()
				->rows( $tagsRows )
				->caller( __METHOD__ )->execute();
		}

		// delete from change_tag
		if ( count( $tagsToRemove ) ) {
			$fname = __METHOD__;
			foreach ( $tagsToRemove as $tag ) {
				$conds = array_filter(
					[
						'ct_rc_id' => $rc_id,
						'ct_log_id' => $log_id,
						'ct_rev_id' => $rev_id,
						'ct_tag_id' => $this->changeTagDefStore->getId( $tag ),
					]
				);
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( self::CHANGE_TAG )
					->where( $conds )
					->caller( __METHOD__ )->execute();
				if ( $dbw->affectedRows() ) {
					// T207881: update the counts at the end of the transaction
					$dbw->onTransactionPreCommitOrIdle( static function () use ( $dbw, $tag, $fname ) {
						$dbw->newUpdateQueryBuilder()
							->update( self::CHANGE_TAG_DEF )
							->set( [ 'ctd_count' => new RawSQLValue( 'ctd_count - 1' ) ] )
							->where( [ 'ctd_name' => $tag ] )
							->caller( $fname )->execute();

						$dbw->newDeleteQueryBuilder()
							->deleteFrom( self::CHANGE_TAG_DEF )
							->where( [ 'ctd_name' => $tag, 'ctd_count' => 0, 'ctd_user_defined' => 0 ] )
							->caller( $fname )->execute();
					}, $fname );
				}
			}
		}

		$userObj = $user ? $this->userFactory->newFromUserIdentity( $user ) : null;
		$this->hookRunner->onChangeTagsAfterUpdateTags(
			$tagsToAdd, $tagsToRemove, $prevTags, $rc_id, $rev_id, $log_id, $params, $rc, $userObj );

		return [ $tagsToAdd, $tagsToRemove, $prevTags ];
	}

	/**
	 * Add tags to a change given its rc_id, rev_id and/or log_id
	 *
	 * @param string|string[] $tags Tags to add to the change
	 * @param int|null $rc_id The rc_id of the change to add the tags to
	 * @param int|null $rev_id The rev_id of the change to add the tags to
	 * @param int|null $log_id The log_id of the change to add the tags to
	 * @param string|null $params Params to put in the ct_params field of table 'change_tag'
	 * @param RecentChange|null $rc Recent change, in case the tagging accompanies the action
	 * (this should normally be the case)
	 *
	 * @return bool False if no changes are made, otherwise true
	 */
	public function addTags( $tags, $rc_id = null, $rev_id = null,
		$log_id = null, $params = null, ?RecentChange $rc = null
	) {
		$result = $this->updateTags( $tags, null, $rc_id, $rev_id, $log_id, $params, $rc );
		return (bool)$result[0];
	}

	/**
	 * Lists those tags which core or extensions report as being "active".
	 *
	 * @return array
	 * @since 1.41
	 */
	public function listSoftwareActivatedTags() {
		// core active tags
		$tags = $this->getSoftwareTags();
		if ( !$this->hookContainer->isRegistered( 'ChangeTagsListActive' ) ) {
			return $tags;
		}
		$hookRunner = $this->hookRunner;
		$dbProvider = $this->dbProvider;

		return $this->wanCache->getWithSetCallback(
			$this->wanCache->makeKey( 'active-tags' ),
			WANObjectCache::TTL_HOUR,
			static function ( $oldValue, &$ttl, array &$setOpts ) use ( $tags, $hookRunner, $dbProvider ) {
				$setOpts += Database::getCacheSetOptions( $dbProvider->getReplicaDatabase() );

				// Ask extensions which tags they consider active
				$hookRunner->onChangeTagsListActive( $tags );
				return $tags;
			},
			[
				'checkKeys' => [ $this->wanCache->makeKey( 'active-tags' ) ],
				'lockTSE' => WANObjectCache::TTL_HOUR,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	/**
	 * Applies all tags-related changes to a query.
	 * Handles selecting tags, and filtering.
	 * Needs $tables to be set up properly, so we can figure out which join conditions to use.
	 *
	 * WARNING: If $filter_tag contains more than one tag and $exclude is false, this function
	 * will add DISTINCT, which may cause performance problems for your query unless you put
	 * the ID field of your table at the end of the ORDER BY, and set a GROUP BY equal to the
	 * ORDER BY. For example, if you had ORDER BY foo_timestamp DESC, you will now need
	 * GROUP BY foo_timestamp, foo_id ORDER BY foo_timestamp DESC, foo_id DESC.
	 *
	 * @deprecated since 1.41 use ChangeTagsStore::modifyDisplayQueryBuilder instead
	 *
	 * @param string|array &$tables Table names, see Database::select
	 * @param string|array &$fields Fields used in query, see Database::select
	 * @param string|array &$conds Conditions used in query, see Database::select
	 * @param array &$join_conds Join conditions, see Database::select
	 * @param string|array &$options Options, see Database::select
	 * @param string|array|false|null $filter_tag Tag(s) to select on (OR)
	 * @param bool $exclude If true, exclude tag(s) from $filter_tag (NOR)
	 *
	 */
	public function modifyDisplayQuery( &$tables, &$fields, &$conds,
		&$join_conds, &$options, $filter_tag = '', bool $exclude = false
	) {
		$useTagFilter = $this->options->get( MainConfigNames::UseTagFilter );

		// Normalize to arrays
		$tables = (array)$tables;
		$fields = (array)$fields;
		$conds = (array)$conds;
		$options = (array)$options;

		$fields['ts_tags'] = $this->makeTagSummarySubquery( $tables );
		// We use an alias and qualify the conditions in case there are
		// multiple joins to this table.
		// In particular for compatibility with the RC filters that extension Translate does.

		// Figure out which ID field to use
		if ( in_array( 'recentchanges', $tables ) ) {
			$join_cond = self::DISPLAY_TABLE_ALIAS . '.ct_rc_id=rc_id';
		} elseif ( in_array( 'logging', $tables ) ) {
			$join_cond = self::DISPLAY_TABLE_ALIAS . '.ct_log_id=log_id';
		} elseif ( in_array( 'revision', $tables ) ) {
			$join_cond = self::DISPLAY_TABLE_ALIAS . '.ct_rev_id=rev_id';
		} elseif ( in_array( 'archive', $tables ) ) {
			$join_cond = self::DISPLAY_TABLE_ALIAS . '.ct_rev_id=ar_rev_id';
		} else {
			throw new InvalidArgumentException( 'Unable to determine appropriate JOIN condition for tagging.' );
		}

		if ( !$useTagFilter ) {
			return;
		}

		if ( !is_array( $filter_tag ) ) {
			// some callers provide false or null
			$filter_tag = (string)$filter_tag;
		}

		if ( $filter_tag !== [] && $filter_tag !== '' ) {
			// Somebody wants to filter on a tag.
			// Add an INNER JOIN on change_tag
			$filterTagIds = [];
			foreach ( (array)$filter_tag as $filterTagName ) {
				try {
					$filterTagIds[] = $this->changeTagDefStore->getId( $filterTagName );
				} catch ( NameTableAccessException ) {
				}
			}

			if ( $exclude ) {
				if ( $filterTagIds !== [] ) {
					$tables[self::DISPLAY_TABLE_ALIAS] = self::CHANGE_TAG;
					$join_conds[self::DISPLAY_TABLE_ALIAS] = [
						'LEFT JOIN',
						[ $join_cond, self::DISPLAY_TABLE_ALIAS . '.ct_tag_id' => $filterTagIds ]
					];
					$conds[self::DISPLAY_TABLE_ALIAS . '.ct_tag_id'] = null;
				}
			} else {
				$tables[self::DISPLAY_TABLE_ALIAS] = self::CHANGE_TAG;
				$join_conds[self::DISPLAY_TABLE_ALIAS] = [ 'JOIN', $join_cond ];
				if ( $filterTagIds !== [] ) {
					$conds[self::DISPLAY_TABLE_ALIAS . '.ct_tag_id'] = $filterTagIds;
				} else {
					// all tags were invalid, return nothing
					$conds[] = '0=1';
				}

				if (
					is_array( $filter_tag ) && count( $filter_tag ) > 1 &&
					!in_array( 'DISTINCT', $options )
				) {
					$options[] = 'DISTINCT';
				}
			}
		}
	}

	/**
	 * Applies all tags-related changes to a query builder object.
	 *
	 * Handles selecting tags, and filtering.
	 *
	 * WARNING: If $filter_tag contains more than one tag and $exclude is false, this function
	 * will add DISTINCT, which may cause performance problems for your query unless you put
	 * the ID field of your table at the end of the ORDER BY, and set a GROUP BY equal to the
	 * ORDER BY. For example, if you had ORDER BY foo_timestamp DESC, you will now need
	 * GROUP BY foo_timestamp, foo_id ORDER BY foo_timestamp DESC, foo_id DESC.
	 *
	 * @param SelectQueryBuilder $queryBuilder Query builder to add the join
	 * @param string $table Table name. Must be either of 'recentchanges', 'logging', 'revision', or 'archive'
	 * @param string|array|false|null $filter_tag Tag(s) to select on (OR)
	 * @param bool $exclude If true, exclude tag(s) from $filter_tag (NOR)
	 *
	 */
	public function modifyDisplayQueryBuilder(
		SelectQueryBuilder $queryBuilder,
		$table,
		$filter_tag = '',
		bool $exclude = false
	) {
		$useTagFilter = $this->options->get( MainConfigNames::UseTagFilter );
		$queryBuilder->field( $this->makeTagSummarySubquery( [ $table ] ), 'ts_tags' );

		// We use an alias and qualify the conditions in case there are
		// multiple joins to this table.
		// In particular for compatibility with the RC filters that extension Translate does.
		// Figure out which ID field to use
		if ( $table === 'recentchanges' ) {
			$join_cond = self::DISPLAY_TABLE_ALIAS . '.ct_rc_id=rc_id';
		} elseif ( $table === 'logging' ) {
			$join_cond = self::DISPLAY_TABLE_ALIAS . '.ct_log_id=log_id';
		} elseif ( $table === 'revision' ) {
			$join_cond = self::DISPLAY_TABLE_ALIAS . '.ct_rev_id=rev_id';
		} elseif ( $table === 'archive' ) {
			$join_cond = self::DISPLAY_TABLE_ALIAS . '.ct_rev_id=ar_rev_id';
		} else {
			throw new InvalidArgumentException( 'Unable to determine appropriate JOIN condition for tagging.' );
		}

		if ( !$useTagFilter ) {
			return;
		}

		if ( !is_array( $filter_tag ) ) {
			// some callers provide false or null
			$filter_tag = (string)$filter_tag;
		}

		if ( $filter_tag !== [] && $filter_tag !== '' ) {
			// Somebody wants to filter on a tag.
			// Add an INNER JOIN on change_tag
			$filterTagIds = [];
			foreach ( (array)$filter_tag as $filterTagName ) {
				try {
					$filterTagIds[] = $this->changeTagDefStore->getId( $filterTagName );
				} catch ( NameTableAccessException ) {
				}
			}

			if ( $exclude ) {
				if ( $filterTagIds !== [] ) {
					$queryBuilder->leftJoin(
						self::CHANGE_TAG,
						self::DISPLAY_TABLE_ALIAS,
						[ $join_cond, self::DISPLAY_TABLE_ALIAS . '.ct_tag_id' => $filterTagIds ]
					);
					$queryBuilder->where( [ self::DISPLAY_TABLE_ALIAS . '.ct_tag_id' => null ] );
				}
			} else {
				$queryBuilder->join(
					self::CHANGE_TAG,
					self::DISPLAY_TABLE_ALIAS,
					$join_cond
				);
				if ( $filterTagIds !== [] ) {
					$queryBuilder->where( [ self::DISPLAY_TABLE_ALIAS . '.ct_tag_id' => $filterTagIds ] );
				} else {
					// all tags were invalid, return nothing
					$queryBuilder->where( '0=1' );
				}

				if (
					is_array( $filter_tag ) && count( $filter_tag ) > 1
				) {
					$queryBuilder->distinct();
				}
			}
		}
	}
}
