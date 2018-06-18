<?php
define( "WEBDAV_AUTH_NONE", 0 );   
define( "WEBDAV_AUTH_TOKEN", 1 );
define( "WEBDAV_AUTH_MW", 2 );
wfLoadExtension( 'BlueSpiceWebDAV' );
wfLoadExtension( 'BlueSpiceWebDAVMinorSave' ); #was not activated by default!
wfLoadExtension( 'BlueSpiceWebDAVClientIntegration' );
