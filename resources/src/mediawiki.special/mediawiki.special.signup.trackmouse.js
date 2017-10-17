( function ( mw ) {
	var elapsed, now, trackingData, averageTypingSpeed = 0, numKeyPresses = 0, startTime = new Date().getTime();
	$( document ).keyup( function () {
		numKeyPresses++;
		now = new Date().getTime();
		elapsed = now - startTime;
		averageTypingSpeed = ( elapsed + ( numKeyPresses - 1 ) * averageTypingSpeed ) / numKeyPresses;
		trackingData = {
			averageTypingSpeed: averageTypingSpeed
		};
		startTime = now;
	} );
	$( '#wpCreateaccount' ).click( function () {
		mw.track( 'mediawiki.special.signup.trackmouse', trackingData );
	} );
}( mediaWiki ) );
