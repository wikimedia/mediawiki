<?php
if( empty( $wgVersion ) ){
	$wgVersion = "1.31";
}
require_once( "$IP/extensions/FlaggedRevs/FlaggedRevs.php" );
wfLoadExtension( 'BlueSpiceReview' );
wfLoadExtension( 'BlueSpiceReviewExtended' );
wfLoadExtension( "BlueSpiceFlaggedRevsConnector" );

$wgDefaultUserOptions['echo-subscriptions-email-bs-review-action-cat'] = 1;
$wgDefaultUserOptions['echo-subscriptions-email-bs-review-assignment-cat'] = 1;
