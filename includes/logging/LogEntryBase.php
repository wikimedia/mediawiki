<?php
/**
 * Contains a class for dealing with individual log entries
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 * @since 1.19
 */

namespace MediaWiki\Logging;

/**
 * Extends the LogEntry Interface with some basic functionality
 *
 * @since 1.19
 */
abstract class LogEntryBase implements LogEntry {

	/** @inheritDoc */
	public function getFullType() {
		return $this->getType() . '/' . $this->getSubtype();
	}

	/** @inheritDoc */
	public function isDeleted( $field ) {
		return ( $this->getDeleted() & $field ) === $field;
	}

	/**
	 * Whether the parameters for this log are stored in new or
	 * old format.
	 *
	 * @return bool
	 */
	public function isLegacy() {
		return false;
	}

	/**
	 * Create a blob from a parameter array
	 *
	 * @since 1.26
	 * @param array $params
	 * @return string
	 */
	public static function makeParamBlob( $params ) {
		return serialize( (array)$params );
	}

	/**
	 * Extract a parameter array from a blob
	 *
	 * @since 1.26
	 * @param string $blob
	 * @return array|false
	 */
	public static function extractParams( $blob ) {
		return unserialize( $blob );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LogEntryBase::class, 'LogEntryBase' );
