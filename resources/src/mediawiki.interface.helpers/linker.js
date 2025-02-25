/**
 * Library interfacing with links output by MediaWiki's LinkRenderer or UserLinkRenderer.
 *
 * @namespace Linker
 * @memberof module:mediawiki.interface.helpers
 */

/**
 * Initialize tooltips for expired temporary account links.
 *
 * @memberof module:mediawiki.interface.helpers.Linker
 */
function init() {
	// Close any open tooltips when Esc is pressed.
	$( document ).on( 'keydown.tempusertooltip', ( event ) => {
		if ( event.key === 'Escape' ) {
			$( '.mw-tempuserlink-expired--tooltip' ).css( 'visibility', 'hidden' );
		}
	} );

	mw.hook( 'wikipage.content' ).add( ( $content ) => {
		const $expiredTempUserLinks = $content.find( '.mw-tempuserlink-expired' );

		$expiredTempUserLinks.on( 'focus mouseover', function () {
			const $link = $( this );

			const tooltipId = $link.attr( 'aria-describedby' );
			const $tooltip = $( `#${ tooltipId }` );

			const { left: offsetParentLeft, top: offsetParentTop } = $link.offsetParent().offset();

			const { left, top } = $link.offset();
			const height = $link.height();

			$tooltip.css( {
				left: Math.floor( left - offsetParentLeft ) + 'px',
				// Add some spacing between the tooltip and the corresponding link.
				top: Math.floor( top - offsetParentTop ) + height + 4 + 'px',
				visibility: 'visible',
				display: 'block'
			} );
		} );

		$expiredTempUserLinks.on( 'blur mouseout', function () {
			const tooltipId = $( this ).attr( 'aria-describedby' );
			const $tooltip = $( `#${ tooltipId }` );

			$tooltip.css( 'visibility', 'hidden' );
		} );
	} );
}

module.exports = { init };
