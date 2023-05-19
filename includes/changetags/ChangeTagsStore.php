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
 * @ingroup Change tagging
 */

namespace MediaWiki\ChangeTags;

use BadMethodCallException;
use InvalidArgumentException;
use ManualLogEntry;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use Status;
use WANObjectCache;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Gateway class for change_tags table
 *
 * @since 1.41
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

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::SoftwareTags,
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

	public function __construct(
		IConnectionProvider $dbProvider,
		NameTableStore $changeTagDefStore,
		WANObjectCache $wanCache,
		HookContainer $hookContainer,
		LoggerInterface $logger,
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->dbProvider = $dbProvider;
		$this->logger = $logger;
		$this->options = $options;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->wanCache = $wanCache;
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

		$softwareTags = array_intersect(
			$availableSoftwareTags,
			self::DEFINED_SOFTWARE_TAGS
		);

		return $softwareTags;
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
			throw new BadMethodCallException(
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

		$tagTables = [ self::CHANGE_TAG, self::CHANGE_TAG_DEF ];
		$join_cond_ts_tags = [ self::CHANGE_TAG_DEF => [ 'JOIN', 'ct_tag_id=ctd_id' ] ];
		$field = 'ctd_name';

		return $this->dbProvider->getReplicaDatabase()
			->buildGroupConcatField( ',', $tagTables, $field, $join_cond, $join_cond_ts_tags );
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
		$tagDef = [
			'ctd_name' => $tag,
			'ctd_user_defined' => 1,
			'ctd_count' => 0
		];
		$dbw->upsert(
			self::CHANGE_TAG_DEF,
			$tagDef,
			'ctd_name',
			[ 'ctd_user_defined' => 1 ],
			__METHOD__
		);

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

		$dbw->update(
			self::CHANGE_TAG_DEF,
			[ 'ctd_user_defined' => 0 ],
			[ 'ctd_name' => $tag ],
			__METHOD__
		);

		$dbw->delete(
			self::CHANGE_TAG_DEF,
			[ 'ctd_name' => $tag, 'ctd_count' => 0 ],
			__METHOD__
		);

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
		$dbw->delete( self::CHANGE_TAG, [ 'ct_tag_id' => $tagId ], __METHOD__ );
		$dbw->delete( self::CHANGE_TAG_DEF, [ 'ctd_name' => $tag ], __METHOD__ );
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
			WANObjectCache::TTL_MINUTE * 5,
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
				'lockTSE' => WANObjectCache::TTL_MINUTE * 5,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}
}
