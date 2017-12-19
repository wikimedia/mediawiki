<?php
//Copy this File to 050-Auth.local.php and activate PluggableAuth and SimpleSAMLphp there 
//by removing comments of the folowing lines:
//wfLoadExtension( 'PluggableAuth' );
//wfLoadExtension( 'SimpleSAMLphp' );

//See https://www.mediawiki.org/wiki/Extension:SimpleSAMLphp for configuration details
//See https://simplesamlphp.org/ for simplesamlphp service provider
//example setup instruction can be found here:
//https://simplesamlphp.org/docs/1.8/simplesamlphp-install

//The path on the local server where SimpleSAMLphp is installed.
$wgSimpleSAMLphp_InstallDir = "/var/simplesamlphp/";

//The AuthSourceId to be used for authentication.
$wgSimpleSAMLphp_AuthSourceId = "default-sp";

//The name of the attribute(s) to be used for the user's real name. This may be a single
//attribute name or an array of attribute names. In the latter case, the attribute values
//will be concatenated with spaces between them to form the value for the user's real name.
$wgSimpleSAMLphp_RealNameAttribute = "name";

//The name of the attribute to be used for the user's email address.
$wgSimpleSAMLphp_EmailAttribute = "email";

//The name of the attribute to be used for the user's username.
$wgSimpleSAMLphp_UsernameAttribute = "username";
