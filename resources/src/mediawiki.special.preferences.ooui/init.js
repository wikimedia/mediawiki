( function () {
	require( './confirmClose.js' );
	require( './convertmessagebox.js' );
	require( './editfont.js' );
	require( './skinPrefs.js' );
	require( './signature.js' );
	require( './timezone.js' );

	var useMobileLayout = require( './config.json' ).useMobileLayout || false;

	if ( useMobileLayout ) {
		require( './mobile.js' );
	} else {
		require( './tabs.js' );
	}
}() );
