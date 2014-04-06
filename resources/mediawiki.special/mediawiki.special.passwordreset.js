/**
 * JavaScript for Special:ChangeEmail
 */
( function ( mw, $ ) {
	/**
	 * Check if the user name entered exists in the db or not. Returns true or false accordingly
	 */
	function validateUserName() {
		var userName = $( '#mw-input-wpUsername' ).val();
		if ( $.trim(userName) === '' ) {
			return false;
		}
		// ask for only those usernames that begin with the first letter of the username typed.
		var listOfUsers = JSON.parse(
		    $.ajax({
		        url:mw.util.wikiScript('api'),
		        data: { action: 'query', format: 'json', list: 'allusers', prefix: userName.charAt(0)  },
		        async:false
		    })
		    .responseText
		);

		var isValid = false;
		for ( var i = 0; i < listOfUsers.query.allusers.length; i++ ) {
			if ( listOfUsers.query.allusers[i].name === userName ) {
				isValid = true;
				break;
			}
		}

		if ( !isValid ) {
			// if there is an error box already being displayed replace it. else display one.
			var error = $( '.error' );
			if ( error.length ) {
				error.html( '<ul><li>' + mw.message('nosuchuser', userName).parse() + '</li></ul>' );
			}
			else{
			 $( '.mw-ui-vform' ).prepend( '<div class="error"><ul><li>' + mw.message('nosuchuser', userName).parse() + '</li></ul></div>' );
			}
			return false;
		}
		return true;
	}

	$( function () {
		$( '.mw-ui-vform' )
			.submit( function(e) {
				if( !validateUserName() ) {
					e.preventDefault();
				}
			} );
	} );
}( mediaWiki, jQuery ) );