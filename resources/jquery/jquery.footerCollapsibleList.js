( function( $ ) {
	// Small jQuery plugin to handle the toggle function & cookie for state
	// For collapsible items in the footer
	$.fn.footerCollapsibleList = function( config ) {
		if ( !( 'title' in config ) || !( 'name' in config ) ) {
			return;
		}

		return this.each( function () {
			var $container, $content, $icon;

			$container = $( this );
			$container.addClass( 'collapsible-list-container' );
			$container.find( '.mw-footerListExplanation' ).remove();

			$content = $container.children();

			$icon = $( '<span>' )
				.addClass( 'mw-collapsible-arrow' );
			$content.before(
				$( '<a>' )
					.addClass( 'collapsible-list' )
					.text( config.title )
					.append( $icon )
					.on( 'click', function( e ) {
						// Modify state cookie.
						var state = ( $.cookie( config.name ) !== 'expanded' ) ? 'expanded' : 'collapsed';
						$.cookie( config.name, state );

						// Modify DOM.
						$content.slideToggle();
						$icon.toggleClass( 'mw-collapsible-toggle-expanded', state === 'expanded' );
						$icon.toggleClass( 'mw-collapsible-toggle-collapsed', state === 'collapsed' );

						e.preventDefault();
					} )
			);

			// Check cookie and collapse.
			if( $.cookie( config.name ) === null || $.cookie( config.name ) === 'collapsed' ) {
				$content.hide();
				$icon.addClass( 'mw-collapsible-toggle-collapsed' );
			} else {
				$icon.addClass( 'mw-collapsible-toggle-expanded' );
			}
		} );
	};
}( jQuery ) );
