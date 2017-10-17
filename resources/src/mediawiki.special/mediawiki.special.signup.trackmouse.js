( function ( mw ) {
	//"wpCreateaccount" is the id of 'Create your account' button.
	document.querySelector( '#wpCreateaccount' ).addEventListener( "click", function( event ) {
		var trackingData = {
			mouseClickX : event.pageX,
			mouseClickY : event.pageY			
		};
		mw.track( "mediawiki.special.signup.trackmouse", trackingData );
	}); 
}( mediaWiki ) );
