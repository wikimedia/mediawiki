/* eslint-disable no-implicit-globals */
/**
 * A special widget that displays a message that a page is being watched/unwatched
 * with a selection widget that can determine how long the page will be watched
 *
 * @class
 * @extends OO.ui.Widget
 * @param  {Object} config Configuration object
 */
var WatchlistExpiryWidget = function ( config ) {
	config = config || {};
	WatchlistExpiryWidget.parent.call( this, config );

	this.message = new OO.ui.LabelWidget( {
		label: config.message
	} );

	this.$element
		.addClass( 'mw-watchstar-WatchlistExpiryWidget' )
		.append( this.message.$element );
};

OO.inheritClass( WatchlistExpiryWidget, OO.ui.Widget );

module.exports = WatchlistExpiryWidget;
