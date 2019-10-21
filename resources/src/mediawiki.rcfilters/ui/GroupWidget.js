/**
 * A group widget to allow for aggregation of events
 *
 * @class mw.rcfilters.ui.GroupWidget
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {Object} [config] Configuration object
 * @cfg {Object} [events] Events to aggregate. The object represent the
 *  event name to aggregate and the event value to emit on aggregate for items.
 */
var GroupWidget = function MwRcfiltersUiViewSwitchWidget( config ) {
	var aggregate = {};

	config = config || {};

	// Parent constructor
	GroupWidget.parent.call( this, config );

	// Mixin constructors
	OO.ui.mixin.GroupElement.call( this, $.extend( {}, config, { $group: this.$element } ) );

	if ( config.events ) {
		// Aggregate events
		// eslint-disable-next-line no-jquery/no-each-util
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

OO.inheritClass( GroupWidget, OO.ui.Widget );
OO.mixinClass( GroupWidget, OO.ui.mixin.GroupWidget );

module.exports = GroupWidget;
