<?php
/**
 * Exception representing a failure to look up a revision.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Revision;

/**
 * Exception throw when trying to access undefined fields on an incomplete RevisionRecord.
 *
 * @newable
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\IncompleteRevisionException
 */
class IncompleteRevisionException extends RevisionAccessException {

}
