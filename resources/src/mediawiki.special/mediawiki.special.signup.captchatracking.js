( function ( mw ) {
	var elapsed, now, averageTypingSpeed = 0, numKeyPresses = 0, startTime = new Date().getTime();
	$( document ).keyup( function () {
		numKeyPresses++;
		now = new Date().getTime();
		elapsed = now - startTime;
		averageTypingSpeed = ( elapsed + ( numKeyPresses - 1 ) * averageTypingSpeed ) / numKeyPresses;
		startTime = now;
	} );
	$( '#wpCreateaccount' ).click( function ( event ) {
		var trackingData = {
			mouseClickX: event.pageX,
			mouseClickY: event.pageY,
			averageTypingSpeed: averageTypingSpeed
		};
		mw.track( 'event.CaptchaTrackingData', trackingData );
	} );
}( mediaWiki ) );
