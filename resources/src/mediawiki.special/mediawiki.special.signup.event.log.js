/*
    Event logging on button click
*/
( function ( mw, $ ) {
	$( function () {
		$('#wpCreateaccount').click( function (e) {
			var info = {
				userId: mw.user.sessionId(),
				mouseX: clientX,
				mouseY: clientY
			};
			mw.track('EventLogging',info);
		});
	} );
}( mediaWiki, jQuery ) );

