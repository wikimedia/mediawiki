/**
 * JavaScript for Special:EditWatchlist
 */

/**
 * Replace the submit button action to operate with ajax.
 */
( function ( mw, $ ) {
	var api, submitButton, form;

	api = new mw.Api();
	submitButton = $( '#watchlistedit-submit' );
	form = submitButton.closest( 'form' );

	/**
	* @param {jQuery.Event} e
	*/
	function formSubmit( e ) {
		var titlesToRemove, chunks, chunksDone, params, $spinner, i, apiDone, apiFail;

		$spinner = $.createSpinner().css( 'margin-left', '1em' );
		submitButton
			.prop( 'disabled', true )
			.after( $spinner );

		titlesToRemove = $.map( $( '.mw-htmlform-flatlist-item input:checked' ), function ( el ) {
			return el.value;
		} );

		params = {
			action: 'watch',
			titles: null, // set below
			token: mw.user.tokens.get( 'watchToken' ),
			unwatch: true
		};

		apiDone = function ( data ) {
			var $watchCheckboxes = $( '.mw-htmlform-flatlist-item input[type="checkbox"]' );
			// TODO the checkboxes have some funky ids, maybe they could be used instead of a naive linear scan
			$.each( data.watch, function ( i, item ) {
				$watchCheckboxes
					.filter( function () {
						return this.value === item.title;
					} )
					.parents( '.mw-htmlform-flatlist-item' )
					.slideUp( 'slow', function() {
						// TODO handle fieldsets becoming empty
						$( this ).remove();
					} );
			} );
			chunksDone++;

			if ( chunksDone === chunks ) {
				submitButton.prop( 'disabled', false );
				$spinner.remove();
			}
		};
		apiFail = function () {
			// An error occurred. Try to submit the form the regular way.
			submitButton.prop( 'disabled', false );
			$spinner.remove();
			form.off( 'submit', formSubmit ).submit();
		};

		// The API only allows up to 50 titles per one request for regular users,
		// but 500 is the user has the 'apihighlimits' right.
		mw.user.getRights( function ( userRights ) {
			var perRequest = ( userRights.indexOf( 'apihighlimits' ) !== -1 ) ? 500 : 50;

			chunks = Math.ceil( titlesToRemove.length / perRequest );
			chunksDone = 0;

			for( i = 0; i < titlesToRemove.length; i += perRequest ) {
				params.titles = titlesToRemove.slice( i, i + perRequest ).join( '|' );
				api.post( params ).done( apiDone ).fail( apiFail );
			}
		} );

		e.preventDefault();
	}

	form.on( 'submit', formSubmit );
} )( mediaWiki, jQuery );
