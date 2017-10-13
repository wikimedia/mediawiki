( function ( mw ) {
	/**
	 * A group widget to allow for aggregation of events
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration object
	 * @param {Object} [events] Events to aggregate. The object represent the
	 *  event name to aggregate and the event value to emit on aggregate for items.
	 */
	mw.rcfilters.ui.GroupWidget = function MwRcfiltersUiViewSwitchWidget( config ) {
		var aggregate = {};

		config = config || {};

		// Parent constructor
		mw.rcfilters.ui.GroupWidget.parent.call( this, config );

		// Mixin constructors
		OO.ui.mixin.GroupElement.call( this, $.extend( {}, config, { $group: this.$element } ) );

		if ( config.events ) {
			// Aggregate events
			$.each( config.events, function ( eventName, eventEmit ) {
				aggregate[ eventName ] = eventEmit;
			} );

			this.aggregate( aggregate );
		}

		if ( Array.isArray( config.items ) ) {
			this.addItems( config.items );
		}
	};

	/* Initialize */

	OO.inheritClass( mw.rcfilters.ui.GroupWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.GroupWidget, OO.ui.mixin.GroupWidget );
}( mediaWiki ) );
