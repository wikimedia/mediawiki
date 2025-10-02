<?php
/**
 * Exception representing a failure to look up a revision.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Revision;

/**
 * Exception raised in response to an audience check when attempting to
 * access suppressed information without permission.
 *
 * @newable
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\SuppressedDataException
 */
class SuppressedDataException extends RevisionAccessException {

}
