<?php
// This file is loaded by composer.json#autoload.files instead of autoload.php,
// because PHP's class loader does not support autoloading an alias for a class that
// isn't already loaded. See also AutoLoaderTest and ClassCollector.

// By using an autoload file, this will trigger directly at runtime outside any class
// loading context. This file will then register the alias and, as class_alias() does
// by default, it will trigger a plain autoload for the destination class.

// The below uses string concatenation for the alias to avoid being seen by ClassCollector,
// which would insist on adding it to autoload.php, after which AutoLoaderTest will
// complain about class_alias() not being in the target class file.

/**
 * @deprecated since 1.29
 * @since 1.20
 */
class_alias( Wikimedia\Timestamp\TimestampException::class, 'Timestamp' . 'Exception' );
