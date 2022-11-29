<?php

namespace MediaWiki\Storage;

/**
 * Exception thrown when a blob has the "bad" content address schema, or has
 * "error" in its old_flags, meaning it is permanently missing.
 */
class BadBlobException extends BlobAccessException {

}
