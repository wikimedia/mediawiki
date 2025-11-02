<?php

namespace MediaWiki\Exception;

/**
 * Exception representing a failure to serialize or unserialize a content object.
 *
 * @newable
 * @ingroup Content
 */
class MWContentSerializationException extends MWException {
}

/** @deprecated class alias since 1.44 */
class_alias( MWContentSerializationException::class, 'MWContentSerializationException' );
