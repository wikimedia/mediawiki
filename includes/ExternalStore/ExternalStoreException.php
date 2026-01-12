<?php

namespace MediaWiki\ExternalStore;

use Exception;

/**
 * @newable
 * @ingroup ExternalStorage
 */
class ExternalStoreException extends Exception {

}

/** @deprecated class alias since 1.46 */
class_alias( ExternalStoreException::class, 'ExternalStoreException' );
