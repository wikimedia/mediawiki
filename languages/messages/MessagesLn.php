<?php

/**
 * Lingala language
 *
 * @addtogroup language
 */

$linkPrefixExtension = true;

# Same as the French (bug 8485)
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array( 'linkprefix' => '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD' );

?>