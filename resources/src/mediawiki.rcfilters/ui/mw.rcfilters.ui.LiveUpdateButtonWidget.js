( function ( mw ) {
	/**
	 * Widget for toggling live updates
	 *
	 * @extends OO.ui.ToggleButtonWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.LiveUpdateButtonWidget = function MwRcfiltersUiLiveUpdateButtonWidget( controller, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.LiveUpdateButtonWidget.parent.call( this, $.extend( {
			icon: 'play',
			label: mw.message( 'rcfilters-liveupdates-button' ).text()
		}, config ) );

		this.controller = controller;

		// Events
		this.connect( this, { change: 'onChange' } );

		this.$element.addClass( 'mw-rcfilters-ui-liveUpdateButtonWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.LiveUpdateButtonWidget, OO.ui.ToggleButtonWidget );

	/* Methods */

	/**
	 * Respond to the button being toggled.
	 * @param {boolean} enable Whether the button is now pressed/enabled
	 */
	mw.rcfilters.ui.LiveUpdateButtonWidget.prototype.onChange = function ( enable ) {
		this.controller.toggleLiveUpdate( enable );
	};

}( mediaWiki ) );
