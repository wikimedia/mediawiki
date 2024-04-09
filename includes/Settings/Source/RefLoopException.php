<?php

namespace MediaWiki\Settings\Source;

use RuntimeException;

/**
 * Thrown when resolving references in a JSONSchema results in an infinite loop
 *
 * @since 1.42
 */
class RefLoopException extends RuntimeException {

}
