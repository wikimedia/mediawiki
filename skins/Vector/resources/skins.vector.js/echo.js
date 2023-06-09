/**
 * Upgrades Echo for icon consistency.
 * Undos work inside Echo to replace our button.
 */
function init() {
	if ( document.querySelectorAll( '#pt-notifications-alert a, #pt-notifications-notice a' ).length !== 2 ) {
		return;
	}

	mw.hook( 'ext.echo.NotificationBadgeWidget.onInitialize' ).add( function ( badge ) {
		const element = badge.$element[ 0 ];
		element.classList.add( 'mw-list-item' );

		const iconButtonClasses = [ 'mw-ui-button', 'mw-ui-quiet', 'mw-ui-icon', 'mw-ui-icon-element' ];
		if ( element.id === 'pt-notifications-alert' ) {
			const anchor = element.querySelector( 'a' );
			anchor.classList.add( ...iconButtonClasses, 'mw-ui-icon-bell' );
			anchor.classList.remove( 'oo-ui-icon-bell' );
		}
		if ( element.id === 'pt-notifications-notice' ) {

			const anchor = element.querySelector( 'a' );
			anchor.classList.add( ...iconButtonClasses, 'mw-ui-icon-tray' );
			anchor.classList.remove( 'oo-ui-icon-tray' );
		}
	} );
}
module.exports = init;
