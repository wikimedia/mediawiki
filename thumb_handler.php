<?php

# Valid web server entry point
define( 'THUMB_HANDLER', true );

# Execute thumb.php, having set THUMB_HANDLER so that
# it knows to extract params from a thumbnail file URL.
require( dirname( __FILE__ ) . '/thumb.php' );
