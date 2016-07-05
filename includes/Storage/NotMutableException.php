<?php

namespace MediaWiki\Storage;

/**
 * Exception indicating that a content could not be written to a given slot, because that
 * slot is not mutable (only slots for derived data are mutable, primary and virtual slots arre not).
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class NotMutableException extends StorageException {

}
