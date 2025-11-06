<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logging;

use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;

/**
 * An individual log entry.
 *
 * This is the basis for methods that all log entries support.
 *
 * Must not be implemented directly by extensions, extend LogEntryBase instead.
 *
 * @stable to type
 * @since 1.19
 * @author Niklas Laxström
 */
interface LogEntry {

	/**
	 * The main log type.
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * The log subtype.
	 *
	 * @return string
	 */
	public function getSubtype();

	/**
	 * The full logtype in format maintype/subtype.
	 *
	 * @return string
	 */
	public function getFullType();

	/**
	 * Get the extra parameters stored for this message.
	 * This will be in the same format as setParameters(), ie. the array keys
	 * might include message formatting prefixes.
	 *
	 * @return array
	 * @see ManualLogEntry::setParameters() for message formatting prefixes.
	 */
	public function getParameters();

	/**
	 * @since 1.36
	 * @return UserIdentity
	 */
	public function getPerformerIdentity(): UserIdentity;

	/**
	 * Get the target page of this action.
	 *
	 * @return Title
	 */
	public function getTarget();

	/**
	 * Get the timestamp when the action was executed.
	 *
	 * @return string TS_MW timestamp, a string with 14 digits
	 */
	public function getTimestamp();

	/**
	 * Get the user provided comment.
	 *
	 * @return string
	 */
	public function getComment();

	/**
	 * Get the access restriction.
	 *
	 * @return int
	 */
	public function getDeleted();

	/**
	 * @param int $field One of LogPage::DELETED_* bitfield constants
	 * @return bool
	 */
	public function isDeleted( $field );
}

/** @deprecated class alias since 1.44 */
class_alias( LogEntry::class, 'LogEntry' );
