<?php

namespace MediaWiki\Revision;

/**
 * Exception raised when the text of a revision is permanently missing or
 * corrupt. This wraps BadBlobException which is thrown by the Storage layer.
 * To mark a revision as permanently missing, use findBadBlobs.php.
 */
class BadRevisionException extends RevisionAccessException {

}
