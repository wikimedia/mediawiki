/**
 * Vector-specific scripts
 */
jQuery( function ( $ ) {
	$( 'div.vectorMenu' ).each( function () {
		var $el = $( this );
		$el.find( 'h3:first a:first' )
			// For accessibility, show the menu when the hidden link in the menu is clicked (bug 24298)
			.click( function ( e ) {
				$el.find( '.menu:first' ).toggleClass( 'menuForceShow' );
				e.preventDefault();
			} )
			// When the hidden link has focus, also set a class that will change the arrow icon
			.focus( function () {
				$el.addClass( 'vectorMenuFocus' );
			} )
			.blur( function () {
				$el.removeClass( 'vectorMenuFocus' );
			} );
	} );
	$( 'a#hide' ).click(function(){
					$( '#mw-panel' ).hide();
					$( '#content' ).css( 'margin-left' , '0em' );
					$( this ).hide();
					$( 'a#unhide' ).fadeIn();
					return false;
		});
		$( 'a#unhide' ).click(function(){
					$( '#content' ).css( 'margin-left' , '11em' );
					$( '#mw-panel' ).fadeIn();
					$(this).hide();
					$ ( 'a#hide' ).fadeIn();
					return false;
		});

} );
