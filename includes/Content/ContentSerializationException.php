<?php

namespace MediaWiki\Content;

use MediaWiki\Exception\MWException;

/**
 * Exception representing a failure to serialize or unserialize a content object.
 *
 * @newable
 * @ingroup Content
 */
class ContentSerializationException extends MWException {
}

/** @deprecated class alias since 1.44 */
class_alias( ContentSerializationException::class, 'MWContentSerializationException' );
/** @deprecated class alias since 1.46 */
class_alias( ContentSerializationException::class, 'MediaWiki\\Exception\\MWContentSerializationException' );
