<?php
/**
 * Class for creating log entries manually, to inject them into the database.
 *
 * This is how I see the log system history:
 * - appending to plain wiki pages
 * - formatting log entries based on database fields
 * - user is now part of the action message
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
 * @author Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @since 1.19
 */

/**
 * Class for creating log entries manually, to inject them into the database.
 *
 * @since 1.19
 */
class ManualLogEntry extends LogEntryBase {
	/** @var string Type of log entry */
	protected $type;

	/** @var string Sub type of log entry */
	protected $subtype;

	/** @var array Parameters for log entry */
	protected $parameters = [];

	/** @var array */
	protected $relations = [];

	/** @var User Performer of the action for the log entry */
	protected $performer;

	/** @var Title Target title for the log entry */
	protected $target;

	/** @var string Timestamp of creation of the log entry */
	protected $timestamp;

	/** @var string Comment for the log entry */
	protected $comment = '';

	/** @var int A rev id associated to the log entry */
	protected $revId = 0;

	/** @var array Change tags add to the log entry */
	protected $tags = null;

	/** @var int Deletion state of the log entry */
	protected $deleted;

	/** @var int ID of the log entry */
	protected $id;

	/** @var bool Can this log entry be patrolled? */
	protected $isPatrollable = false;

	/** @var bool Whether this is a legacy log entry */
	protected $legacy = false;

	/**
	 * @since 1.19
	 * @param string $type
	 * @param string $subtype
	 */
	public function __construct( $type, $subtype ) {
		$this->type = $type;
		$this->subtype = $subtype;
	}

	/**
	 * Set extra log parameters.
	 *
	 * You can pass params to the log action message by prefixing the keys with
	 * a number and optional type, using colons to separate the fields. The
	 * numbering should start with number 4, the first three parameters are
	 * hardcoded for every message.
	 *
	 * If you want to store stuff that should not be available in messages, don't
	 * prefix the array key with a number and don't use the colons.
	 *
	 * Example:
	 *   $entry->setParameters(
	 *     '4::color' => 'blue',
	 *     '5:number:count' => 3000,
	 *     'animal' => 'dog'
	 *   );
	 *
	 * @since 1.19
	 * @param array $parameters Associative array
	 */
	public function setParameters( $parameters ) {
		$this->parameters = $parameters;
	}

	/**
	 * Declare arbitrary tag/value relations to this log entry.
	 * These can be used to filter log entries later on.
	 *
	 * @param array $relations Map of (tag => (list of values|value))
	 * @since 1.22
	 */
	public function setRelations( array $relations ) {
		$this->relations = $relations;
	}

	/**
	 * Set the user that performed the action being logged.
	 *
	 * @since 1.19
	 * @param User $performer
	 */
	public function setPerformer( User $performer ) {
		$this->performer = $performer;
	}

	/**
	 * Set the title of the object changed.
	 *
	 * @since 1.19
	 * @param Title $target
	 */
	public function setTarget( Title $target ) {
		$this->target = $target;
	}

	/**
	 * Set the timestamp of when the logged action took place.
	 *
	 * @since 1.19
	 * @param string $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	/**
	 * Set a comment associated with the action being logged.
	 *
	 * @since 1.19
	 * @param string $comment
	 */
	public function setComment( $comment ) {
		$this->comment = $comment;
	}

	/**
	 * Set an associated revision id.
	 *
	 * For example, the ID of the revision that was inserted to mark a page move
	 * or protection, file upload, etc.
	 *
	 * @since 1.27
	 * @param int $revId
	 */
	public function setAssociatedRevId( $revId ) {
		$this->revId = $revId;
	}

	/**
	 * Set change tags for the log entry.
	 *
	 * @since 1.27
	 * @param string|string[] $tags
	 */
	public function setTags( $tags ) {
		if ( is_string( $tags ) ) {
			$tags = [ $tags ];
		}
		$this->tags = $tags;
	}

	/**
	 * Set whether this log entry should be made patrollable
	 * This shouldn't depend on config, only on whether there is full support
	 * in the software for patrolling this log entry.
	 * False by default
	 *
	 * @since 1.27
	 * @param bool $patrollable
	 */
	public function setIsPatrollable( $patrollable ) {
		$this->isPatrollable = (bool)$patrollable;
	}

	/**
	 * Set the 'legacy' flag
	 *
	 * @since 1.25
	 * @param bool $legacy
	 */
	public function setLegacy( $legacy ) {
		$this->legacy = $legacy;
	}

	/**
	 * Set the 'deleted' flag.
	 *
	 * @since 1.19
	 * @param int $deleted One of LogPage::DELETED_* bitfield constants
	 */
	public function setDeleted( $deleted ) {
		$this->deleted = $deleted;
	}

	/**
	 * Insert the entry into the `logging` table.
	 *
	 * @param IDatabase $dbw
	 * @return int ID of the log entry
	 * @throws MWException
	 */
	public function insert( IDatabase $dbw = null ) {
		$dbw = $dbw ?: wfGetDB( DB_MASTER );

		if ( $this->timestamp === null ) {
			$this->timestamp = wfTimestampNow();
		}

		// Trim spaces on user supplied text
		$comment = trim( $this->getComment() );

		$params = $this->getParameters();
		$relations = $this->relations;

		// Additional fields for which there's no space in the database table schema
		$revId = $this->getAssociatedRevId();
		if ( $revId ) {
			$params['associated_rev_id'] = $revId;
			$relations['associated_rev_id'] = $revId;
		}

		$data = [
			'log_type' => $this->getType(),
			'log_action' => $this->getSubtype(),
			'log_timestamp' => $dbw->timestamp( $this->getTimestamp() ),
			'log_user' => $this->getPerformer()->getId(),
			'log_user_text' => $this->getPerformer()->getName(),
			'log_namespace' => $this->getTarget()->getNamespace(),
			'log_title' => $this->getTarget()->getDBkey(),
			'log_page' => $this->getTarget()->getArticleID(),
			'log_params' => LogEntryBase::makeParamBlob( $params ),
		];
		if ( isset( $this->deleted ) ) {
			$data['log_deleted'] = $this->deleted;
		}
		$data += CommentStore::newKey( 'log_comment' )->insert( $dbw, $comment );

		$dbw->insert( 'logging', $data, __METHOD__ );
		$this->id = $dbw->insertId();

		$rows = [];
		foreach ( $relations as $tag => $values ) {
			if ( !strlen( $tag ) ) {
				throw new MWException( "Got empty log search tag." );
			}

			if ( !is_array( $values ) ) {
				$values = [ $values ];
			}

			foreach ( $values as $value ) {
				$rows[] = [
					'ls_field' => $tag,
					'ls_value' => $value,
					'ls_log_id' => $this->id
				];
			}
		}
		if ( count( $rows ) ) {
			$dbw->insert( 'log_search', $rows, __METHOD__, 'IGNORE' );
		}

		return $this->id;
	}

	/**
	 * Get a RecentChanges object for the log entry
	 *
	 * @param int $newId
	 * @return RecentChange
	 * @since 1.23
	 */
	public function getRecentChange( $newId = 0 ) {
		$formatter = LogFormatter::newFromEntry( $this );
		$context = RequestContext::newExtraneousContext( $this->getTarget() );
		$formatter->setContext( $context );

		$logpage = SpecialPage::getTitleFor( 'Log', $this->getType() );
		$user = $this->getPerformer();
		$ip = "";
		if ( $user->isAnon() ) {
			// "MediaWiki default" and friends may have
			// no IP address in their name
			if ( IP::isIPAddress( $user->getName() ) ) {
				$ip = $user->getName();
			}
		}

		return RecentChange::newLogEntry(
			$this->getTimestamp(),
			$logpage,
			$user,
			$formatter->getPlainActionText(),
			$ip,
			$this->getType(),
			$this->getSubtype(),
			$this->getTarget(),
			$this->getComment(),
			LogEntryBase::makeParamBlob( $this->getParameters() ),
			$newId,
			$formatter->getIRCActionComment(), // Used for IRC feeds
			$this->getAssociatedRevId(), // Used for e.g. moves and uploads
			$this->getIsPatrollable()
		);
	}

	/**
	 * Publish the log entry.
	 *
	 * @param int $newId Id of the log entry.
	 * @param string $to One of: rcandudp (default), rc, udp
	 */
	public function publish( $newId, $to = 'rcandudp' ) {
		DeferredUpdates::addCallableUpdate(
			function () use ( $newId, $to ) {
				$log = new LogPage( $this->getType() );
				if ( !$log->isRestricted() ) {
					$rc = $this->getRecentChange( $newId );

					if ( $to === 'rc' || $to === 'rcandudp' ) {
						// save RC, passing tags so they are applied there
						$tags = $this->getTags();
						if ( is_null( $tags ) ) {
							$tags = [];
						}
						$rc->addTags( $tags );
						$rc->save( 'pleasedontudp' );
					}

					if ( $to === 'udp' || $to === 'rcandudp' ) {
						$rc->notifyRCFeeds();
					}

					// Log the autopatrol if the log entry is patrollable
					if ( $this->getIsPatrollable() &&
						$rc->getAttribute( 'rc_patrolled' ) === 1
					) {
						PatrolLog::record( $rc, true, $this->getPerformer() );
					}
				}
			},
			DeferredUpdates::POSTSEND,
			wfGetDB( DB_MASTER )
		);
	}

	public function getType() {
		return $this->type;
	}

	public function getSubtype() {
		return $this->subtype;
	}

	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @return User
	 */
	public function getPerformer() {
		return $this->performer;
	}

	/**
	 * @return Title
	 */
	public function getTarget() {
		return $this->target;
	}

	public function getTimestamp() {
		$ts = $this->timestamp !== null ? $this->timestamp : wfTimestampNow();

		return wfTimestamp( TS_MW, $ts );
	}

	public function getComment() {
		return $this->comment;
	}

	/**
	 * @since 1.27
	 * @return int
	 */
	public function getAssociatedRevId() {
		return $this->revId;
	}

	/**
	 * @since 1.27
	 * @return array
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * Whether this log entry is patrollable
	 *
	 * @since 1.27
	 * @return bool
	 */
	public function getIsPatrollable() {
		return $this->isPatrollable;
	}

	/**
	 * @since 1.25
	 * @return bool
	 */
	public function isLegacy() {
		return $this->legacy;
	}

	public function getDeleted() {
		return (int)$this->deleted;
	}
}
