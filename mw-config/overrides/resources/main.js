$( function () {
	//### Options page ###
	//Enable some fields by default
	$( 'input[name=config_wgEnableUploads]' ).prop( 'checked', true );
	$( 'input[name=config_wgEnableUserEmail]' ).prop( 'checked', true );
	$( 'input[name=config_wgEnotifUserTalk]' ).prop( 'checked', true );
	$( 'input[name=config_wgEnotifWatchlist]' ).prop( 'checked', true );
	$( 'input[name=config_wgEmailAuthentication]' ).prop( 'checked', true );

	//wgPingBack gets dissabled in BSLocalSettingsGenerator.php
	$( 'input[name=config__Subscribe]' ).prop( 'checked', false );
	$( 'input[name=config_wgPingBack]' ).prop( 'checked', false );

	//hide stuff
	$( 'label[for=DBType_mysql]' ).parent().parent().parent().parent().addClass( "hidden" );
	$( 'label[for=mysql__MysqlEngine]' ).parent().parent().addClass( "hidden" );
	$( 'label[for=mysql__MysqlEngine]' ).parent().parent().next().addClass( "hidden" );
	$( 'input[name=config__Subscribe]' ).parent().addClass( "hidden" );
	$( 'input[name=config__Subscribe]' ).parent().prev().addClass( "hidden" );
	$( 'input[name=config_wgPingback]' ).parent().addClass( "hidden" );
	$( 'input[name=config_wgPingback]' ).parent().prev().addClass( "hidden" );

	$( 'input[name=config_wgEnableUserEmail]' ).parent().addClass( "hidden" );
	$( 'input[name=config_wgEnotifUserTalk]' ).parent().addClass( "hidden" );
	$( 'input[name=config_wgEnotifWatchlist]' ).parent().addClass( "hidden" );
	$( 'input[name=config_wgEmailAuthentication]' ).parent().addClass( "hidden" );
	$( 'input[name=config_wgEnableEmail]' ).parent().addClass( "hidden" );
	$( 'input[name=config_wgEnableUserEmail]' ).parent().parent().prev().addClass( "hidden" );
	$( 'input[name=config_wgEnotifUserTalk]' ).parent().parent().prev().addClass( "hidden" );
	$( 'input[name=config_wgEnotifWatchlist]' ).parent().parent().prev().addClass( "hidden" );
	$( 'input[name=config_wgEmailAuthentication]' ).parent().parent().prev().addClass( "hidden" );
	$( 'input[name=config_wgEnableEmail]' ).parent().parent().next().addClass( "hidden" );

	$( 'label[for=config__LicenseCode]' ).parent().parent().addClass( "hidden" );
	$( 'label[for=config__RightsProfile]' ).parent().parent().addClass( "hidden" );
	$( '#uploadwrapper' ).show();
} );