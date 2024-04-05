<?php

namespace MediaWiki\Settings\Source;

use RuntimeException;

/**
 * Thrown during processing a JSON Schema when a reference is not found
 *
 * @since 1.42
 */
class RefNotFoundException extends RuntimeException {

}
