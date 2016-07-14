<?php

namespace MediaWiki\Storage;

use OutOfBoundsException;

/**
 * Thrown to indicate that a requested storage entity (such as a blob or a revision) was not found.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class NotFoundException extends OutOfBoundsException {

}
