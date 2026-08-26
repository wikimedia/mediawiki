<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logging;

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\Page\PageReference;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Assert\Assert;
use Wikimedia\Timestamp\TimestampException;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * Immutable value object representing an entry in the log of user actions.
 *
 * Corresponds to a row in the logging table.
 *
 * Unlike DatabaseLogEntry, this class is not bound to the database schema and
 * does not rely on global state, which makes it suitable for passing around
 * freely, e.g. as part of a domain event.
 *
 * @note In the future, instances of this class are expected to be provided by a
 *       LogStore service. For now, they can be obtained from a DatabaseLogEntry,
 *       see DatabaseLogEntry::getLogRecord().
 *
 * @unstable
 * @since 1.47
 */
class LogRecord implements WikiAwareEntity {
	use WikiAwareEntityTrait;

	private readonly ?int $id;

	private readonly string $timestamp;

	private readonly ?int $associatedRevId;

	/**
	 * @param ?int $id The log_id of this entry, or null if the log doesn't exist in the DB.
	 * @param string $type The main log type, e.g. "delete".
	 * @param string $subtype The log subtype (also known as the action), e.g. "restore".
	 * @param UserIdentity $performer The user who performed the logged action.
	 * @param PageReference $target The page the logged action applies to. This may be
	 *        a pseudo-page, see ::getTarget().
	 * @param string $timestamp The time the action was performed, in any format
	 *        supported by MWTimestamp.
	 * @param string $comment The comment supplied by the performer, unformatted and
	 *        without regard for $deleted. See ::isDeleted().
	 * @param array $params Additional parameters, see ManualLogEntry::setParameters().
	 * @param int $deleted Bitfield of LogPage::DELETED_* constants.
	 * @param int|null $associatedRevId The ID of the revision associated with the logged
	 *        action, see ManualLogEntry::setAssociatedRevId(). Both null and 0 mean
	 *        that there is no associated revision.
	 * @param bool $legacy Whether $params is in the old, positional format.
	 * @param string|false $wikiId The wiki this entry belongs to, or self::LOCAL.
	 * @throws TimestampException
	 */
	public function __construct(
		?int $id,
		private readonly string $type,
		private readonly string $subtype,
		private readonly UserIdentity $performer,
		private readonly PageReference $target,
		string $timestamp,
		private readonly string $comment = '',
		private readonly array $params = [],
		private readonly int $deleted = 0,
		?int $associatedRevId = null,
		private readonly bool $legacy = false,
		private readonly string|false $wikiId = self::LOCAL
	) {
		$this->assertWikiIdParam( $wikiId );
		Assert::parameter( $id >= 0, '$id', 'must not be negative' );
		Assert::parameter(
			$performer->getWikiId() === $wikiId,
			'$performer',
			'must belong to the same wiki as the log record'
		);
		Assert::parameter(
			$target->getWikiId() === $wikiId,
			'$target',
			'must belong to the same wiki as the log record'
		);

		// The log id can be 0 if the log doesn't exist.
		// This happens if it hasn't been inserted into the db yet (ManualLogEntry)
		// or the LogRecord was created from a recent change that doesn't have
		// an associated log entry (RCDatabaseLogEntry)
		// TODO: If LogRecord is supposed to represent a row from the DB,
		//		maybe it should throw an error?
		$this->id = $id ?: null;

		// RCDatabaseLogEntry::getAssociatedRevId grabs rc_this_oldid
		// which returns 0 instead of null when there is no associated revision
		// See https://www.mediawiki.org/wiki/Manual:Recentchanges_table#rc_this_oldid
		$this->associatedRevId = $associatedRevId ?: null;

		$this->timestamp = ( new MWTimestamp( $timestamp ) )->getTimestamp( TS::MW );
	}

	/**
	 * The log_id of this entry, or null if the log doesn't exist in the database.
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 */
	public function getId( string|false $wikiId = self::LOCAL ): ?int {
		$this->assertWiki( $wikiId );
		return $this->id;
	}

	/**
	 * The main log type, e.g. "delete".
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * The log subtype, also known as the action, e.g. "restore".
	 */
	public function getSubtype(): string {
		return $this->subtype;
	}

	/**
	 * The full log type, in the format maintype/subtype.
	 */
	public function getFullType(): string {
		return $this->type . '/' . $this->subtype;
	}

	/**
	 * The user who performed the logged action.
	 */
	public function getPerformer(): UserIdentity {
		return $this->performer;
	}

	/**
	 * The page the logged action applies to.
	 *
	 * Note that this might not be a valid page.
	 * For example, AutoBlockTarget::getLogPage returns an invalid page title #1234
	 *
	 * See https://www.mediawiki.org/wiki/Manual:Logging_table#log_title
	 */
	public function getTarget(): PageReference {
		return $this->target;
	}

	/**
	 * The time the logged action was performed.
	 *
	 * @return string TS::MW timestamp, a string with 14 digits
	 */
	public function getTimestamp(): string {
		return $this->timestamp;
	}

	/**
	 * The comment supplied by the performer.
	 *
	 * This is the raw comment, it may have been marked as deleted,
	 * see ::isDeleted().
	 */
	public function getComment(): string {
		return $this->comment;
	}

	/**
	 * Additional parameters of the logged action.
	 *
	 * The array keys may include message formatting prefixes,
	 * see ManualLogEntry::setParameters(). For legacy entries (see ::isLegacy()),
	 * the parameters are a positional list without such prefixes.
	 */
	public function getParameters(): array {
		return $this->params;
	}

	/**
	 * The ID of the revision associated with the logged action, or null if there
	 * is none.
	 *
	 * For example, the ID of the revision that was inserted to mark a page move
	 * or protection, file upload, etc.
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 */
	public function getAssociatedRevId( string|false $wikiId = self::LOCAL ): ?int {
		$this->assertWiki( $wikiId );
		return $this->associatedRevId;
	}

	/**
	 * The visibility of this entry, as a bitfield of LogPage::DELETED_* constants.
	 */
	public function getDeleted(): int {
		return $this->deleted;
	}

	/**
	 * Whether the given part of this entry has been marked as deleted.
	 *
	 * @param int $field One of the LogPage::DELETED_* constants
	 */
	public function isDeleted( int $field ): bool {
		return ( $this->deleted & $field ) === $field;
	}

	/**
	 * Whether the parameters of this entry are stored in the old, positional format.
	 */
	public function isLegacy(): bool {
		return $this->legacy;
	}

	/** @inheritDoc */
	public function getWikiId() {
		return $this->wikiId;
	}
}
