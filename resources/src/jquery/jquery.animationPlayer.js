/**
 * Simple playback system for animated GIF/PNG etc
 *
 * @author Derk-Jan Hartman <hartman.wiki@gmail.com>, 2015
 * @version 1.0.0
 * @license MIT
 */
( function ( $ ) {

	$.fn.extend( {
		animationPlayer: function () {
			var $img;

			function play( $elem ) {
				$elem.attr( 'src', $elem.data( 'file-original' ) );
				$elem.removeClass( 'paused' );
			}

			this.each( function () {
				$img = $( this );

				$img
					.wrap(  $( '<div>' ).addClass( 'animatedWrapper' ) )
					.after(
						$( '<div>' )
							.addClass( 'playButton' )
							.css( {
								width: $img.attr( 'width' ) + 'px',
								height: $img.attr( 'height' ) + 'px'
							} )
							.one( 'click', function( event ) {
								play( $img );
								$( this ).remove();
								event.preventDefault();
							} )
					);
			} );
		}
	} );

}( jQuery ));
