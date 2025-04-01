<?php
/**
 * Contains a class for dealing with manual log entries
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
 * @license GPL-2.0-or-later
 * @since 1.19
 */

namespace MediaWiki\Logging;

use InvalidArgumentException;
use MediaWiki\ChangeTags\Taggable;
use MediaWiki\Context\RequestContext;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use RuntimeException;
use UnexpectedValueException;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IDatabase;

/**
 * Class for creating new log entries and inserting them into the database.
 *
 * @newable
 * @note marked as newable in 1.35 for lack of a better alternative,
 *       but should be changed to use the builder pattern or the
 *       command pattern.
 * @since 1.19
 * @see https://www.mediawiki.org/wiki/Manual:Logging_to_Special:Log
 */
class ManualLogEntry extends LogEntryBase implements Taggable {
	/** @var string Type of log entry */
	protected $type;

	/** @var string Sub type of log entry */
	protected $subtype;

	/** @var array Parameters for log entry */
	protected $parameters = [];

	/** @var array */
	protected $relations = [];

	/** @var UserIdentity Performer of the action for the log entry */
	protected $performer;

	/** @var Title Target title for the log entry */
	protected $target;

	/** @var string Timestamp of creation of the log entry */
	protected $timestamp;

	/** @var string Comment for the log entry */
	protected $comment = '';

	/** @var int A rev id associated to the log entry */
	protected $revId = 0;

	/** @var string[] Change tags add to the log entry */
	protected $tags = [];

	/** @var int|null Deletion state of the log entry */
	protected $deleted;

	/** @var int ID of the log entry */
	protected $id;

	/** @var bool Can this log entry be patrolled? */
	protected $isPatrollable = false;

	/** @var bool Whether this is a legacy log entry */
	protected $legacy = false;

	/** @var bool|null The bot flag in the recent changes will be set to this value */
	protected $forceBotFlag = null;

	/**
	 * @stable to call
	 * @since 1.19
	 * @param string $type Log type. Should match $wgLogTypes.
	 * @param string $subtype Log subtype (action). Should match $wgLogActions or
	 *   (together with $type) $wgLogActionsHandlers.
	 * @note
	 */
	public function __construct( $type, $subtype ) {
		$this->type = $type;
		$this->subtype = $subtype;
	}

	/**
	 * Set extra log parameters.
	 *
	 * Takes an array in a parameter name => parameter value format. The array
	 * will be converted to string via serialize() and stored in the log_params
	 * database field. (If you want to store parameters in such a way that they
	 * can be targeted by DB queries, use setRelations() instead.)
	 *
	 * You can pass these parameters to the log action message by prefixing the
	 * keys with a number and optional type, using colons to separate the fields.
	 * The numbering should start with number 4 (matching the $4 message
	 * parameter), as the first three parameters are hardcoded for every message
	 * ($1 is a link to the username and user talk page of the performing user,
	 * $2 is just the username (for determining gender), $3 is a link to the
	 * target page).
	 *
	 * If you want to store stuff that should not be available in messages, don't
	 * prefix the array key with a number and don't use the colons. (Note that
	 * such parameters will still be publicly viewable via the API.)
	 *
	 * Example:
	 *   $entry->setParameters( [
	 *     // store and use in messages as $4
	 *     '4::color' => 'blue',
	 *     // store as is, use in messages as $5 with Message::numParam()
	 *     '5:number:count' => 3000,
	 *     // store but do not use in messages
	 *     'animal' => 'dog'
	 *   ] );
	 *
	 * Typically, these parameters will be used in the logentry-<type>-<subtype>
	 * message, but custom formatters, declared via $wgLogActionsHandlers, can
	 * override that.
	 *
	 * @since 1.19
	 * @param array $parameters Associative array
	 * @see LogFormatter::formatParameterValue() for valid parameter types and
	 *   their meanings.
	 * @see self::setRelations() for storing parameters in a way that can be searched.
	 * @see LogFormatter::getMessageKey() for determining which message these
	 *   parameters will be used in.
	 */
	public function setParameters( $parameters ) {
		$this->parameters = $parameters;
	}

	/**
	 * Add a parameter to the list already set.
	 *
	 * @see setParameters
	 * @since 1.44
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function addParameter( $name, $value ) {
		$this->parameters[$name] = $value;
	}

	/**
	 * Declare arbitrary tag/value relations to this log entry.
	 * These will be stored in the log_search table and can be used
	 * to filter log entries later on.
	 *
	 * @param array $relations Map of (tag => (list of values|value)); values must be string.
	 *   When an array of values is given, a separate DB row will be created for each value.
	 * @since 1.22
	 */
	public function setRelations( array $relations ) {
		$this->relations = $relations;
	}

	/**
	 * Set the user that performed the action being logged.
	 *
	 * @since 1.19
	 * @param UserIdentity $performer
	 */
	public function setPerformer( UserIdentity $performer ) {
		$this->performer = $performer;
	}

	/**
	 * Set the title of the object changed.
	 *
	 * @param LinkTarget|PageReference $target calling with LinkTarget
	 *   is deprecated since 1.37
	 * @since 1.19
	 */
	public function setTarget( $target ) {
		if ( $target instanceof PageReference ) {
			$this->target = Title::newFromPageReference( $target );
		} elseif ( $target instanceof LinkTarget ) {
			$this->target = Title::newFromLinkTarget( $target );
		} else {
			throw new InvalidArgumentException( "Invalid target provided" );
		}
	}

	/**
	 * Set the timestamp of when the logged action took place.
	 *
	 * @since 1.19
	 * @param string $timestamp Can be in any format accepted by ConvertibleTimestamp
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
	public function setComment( string $comment ) {
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
	 * Add change tags for the log entry
	 *
	 * @since 1.33
	 * @param string|string[]|null $tags Tags to apply
	 */
	public function addTags( $tags ) {
		if ( $tags === null ) {
			return;
		}

		if ( is_string( $tags ) ) {
			$tags = [ $tags ];
		}
		Assert::parameterElementType( 'string', $tags, 'tags' );
		$this->tags = array_unique( array_merge( $this->tags, $tags ) );
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
	 * Set the bot flag in the recent changes to this value.
	 *
	 * @since 1.40
	 * @param bool $forceBotFlag
	 */
	public function setForceBotFlag( bool $forceBotFlag ): void {
		$this->forceBotFlag = $forceBotFlag;
	}

	/**
	 * Insert the entry into the `logging` table.
	 *
	 * @param IDatabase|null $dbw
	 * @return int ID of the log entry
	 */
	public function insert( ?IDatabase $dbw = null ) {
		$services = MediaWikiServices::getInstance();
		$dbw = $dbw ?: $services->getConnectionProvider()->getPrimaryDatabase();

		$this->timestamp ??= wfTimestampNow();
		$actorId = $services->getActorStore()->acquireActorId( $this->getPerformerIdentity(), $dbw );

		// Trim spaces on user supplied text
		$comment = trim( $this->getComment() ?? '' );

		$params = $this->getParameters();
		$relations = $this->relations;

		// Additional fields for which there's no space in the database table schema
		$revId = $this->getAssociatedRevId();
		if ( $revId ) {
			$params['associated_rev_id'] = $revId;
			$relations['associated_rev_id'] = $revId;
		}

		$row = [
			'log_type' => $this->getType(),
			'log_action' => $this->getSubtype(),
			'log_timestamp' => $dbw->timestamp( $this->getTimestamp() ),
			'log_actor' => $actorId,
			'log_namespace' => $this->getTarget()->getNamespace(),
			'log_title' => $this->getTarget()->getDBkey(),
			'log_page' => $this->getTarget()->getArticleID(),
			'log_params' => LogEntryBase::makeParamBlob( $params ),
		];
		if ( $this->deleted !== null ) {
			$row['log_deleted'] = $this->deleted;
		}
		$row += $services->getCommentStore()->insert( $dbw, 'log_comment', $comment );

		$dbw->newInsertQueryBuilder()
			->insertInto( 'logging' )
			->row( $row )
			->caller( __METHOD__ )
			->execute();
		$this->id = $dbw->insertId();

		$rows = [];
		foreach ( $relations as $tag => $values ) {
			if ( $tag === '' ) {
				throw new UnexpectedValueException( "Got empty log search tag." );
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
			$dbw->newInsertQueryBuilder()
				->insertInto( 'log_search' )
				->ignore()
				->rows( $rows )
				->caller( __METHOD__ )
				->execute();
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
		$formatter = MediaWikiServices::getInstance()->getLogFormatterFactory()->newFromEntry( $this );
		$context = RequestContext::newExtraneousContext( $this->getTarget() );
		$formatter->setContext( $context );

		$logpage = SpecialPage::getTitleFor( 'Log', $this->getType() );

		return RecentChange::newLogEntry(
			$this->getTimestamp(),
			$logpage,
			$this->getPerformerIdentity(),
			$formatter->getPlainActionText(),
			'',
			$this->getType(),
			$this->getSubtype(),
			$this->getTarget(),
			$this->getComment(),
			LogEntryBase::makeParamBlob( $this->getParameters() ),
			$newId,
			$formatter->getIRCActionComment(), // Used for IRC feeds
			$this->getAssociatedRevId(), // Used for e.g. moves and uploads
			$this->getIsPatrollable(),
			$this->forceBotFlag
		);
	}

	/**
	 * Publish the log entry.
	 *
	 * @param int $newId Id of the log entry.
	 * @param string $to One of: rcandudp (default), rc, udp
	 */
	public function publish( $newId, $to = 'rcandudp' ) {
		$canAddTags = true;
		// FIXME: this code should be removed once all callers properly call publish()
		if ( $to === 'udp' && !$newId && !$this->getAssociatedRevId() ) {
			\MediaWiki\Logger\LoggerFactory::getInstance( 'logging' )->warning(
				'newId and/or revId must be set when calling ManualLogEntry::publish()',
				[
					'newId' => $newId,
					'to' => $to,
					'revId' => $this->getAssociatedRevId(),
					// pass a new exception to register the stack trace
					'exception' => new RuntimeException()
				]
			);
			$canAddTags = false;
		}

		$log = new LogPage( $this->getType() );
		if ( !$log->isRestricted() ) {
			// We need to generate a RecentChanges object now so that we can have the rc_bot attribute set based
			// on any temporary user rights assigned to the user as part of the creation of this log entry.
			// We do not attempt to save it to the DB until POSTSEND to avoid writes blocking a response (T127852).
			( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
				->onManualLogEntryBeforePublish( $this );
			$rc = $this->getRecentChange( $newId );

			DeferredUpdates::addCallableUpdate(
				function () use ( $newId, $to, $canAddTags, $rc ) {
					if ( $to === 'rc' || $to === 'rcandudp' ) {
						// save RC, passing tags so they are applied there
						$rc->addTags( $this->getTags() );
						$rc->save( $rc::SEND_NONE );
					} else {
						$tags = $this->getTags();
						if ( $tags && $canAddTags ) {
							$revId = $this->getAssociatedRevId();
							MediaWikiServices::getInstance()->getChangeTagsStore()->addTags(
								$tags,
								null,
								$revId > 0 ? $revId : null,
								$newId > 0 ? $newId : null
							);
						}
					}

					if ( $to === 'udp' || $to === 'rcandudp' ) {
						$rc->notifyRCFeeds();
					}
				},
				DeferredUpdates::POSTSEND,
				MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase()
			);
		}
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getSubtype() {
		return $this->subtype;
	}

	/**
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}

	public function getPerformerIdentity(): UserIdentity {
		return $this->performer;
	}

	/**
	 * @return Title
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @return string|false TS_MW timestamp, a string with 14 digits
	 */
	public function getTimestamp() {
		$ts = $this->timestamp ?? wfTimestampNow();

		return wfTimestamp( TS_MW, $ts );
	}

	/**
	 * @return string
	 */
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
	 * @return string[]
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

	/**
	 * @return int
	 */
	public function getDeleted() {
		return (int)$this->deleted;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ManualLogEntry::class, 'ManualLogEntry' );
