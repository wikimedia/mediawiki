<?php

/**
 * Container out of bound error.
 */
class OutOfBounds extends PEAR_Error {}

/**
 * Unable to locate some data somewhere.
 * 
 * This kind of exception was introduced by tof template context.
 */
class NameError extends PEAR_Error {}

/**
 * Mismatched data type.
 */
class TypeError extends PEAR_Error {}

/**
 * Unable to locate a file.
 */
class FileNotFound extends PEAR_Error {}

/**
 * Input output error.
 */
class IOException extends PEAR_Error {}

?>
