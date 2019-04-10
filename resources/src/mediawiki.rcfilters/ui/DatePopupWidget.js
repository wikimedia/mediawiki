var ValuePickerWidget = require( './ValuePickerWidget.js' ),
	DatePopupWidget;

/**
 * Widget defining the popup to choose date for the results
 *
 * @class mw.rcfilters.ui.DatePopupWidget
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {mw.rcfilters.dm.FilterGroup} model Group model for 'days'
 * @param {Object} [config] Configuration object
 */
DatePopupWidget = function MwRcfiltersUiDatePopupWidget( model, config ) {
	config = config || {};

	// Parent
	DatePopupWidget.parent.call( this, config );
	// Mixin constructors
	OO.ui.mixin.LabelElement.call( this, config );

	this.model = model;

	this.hoursValuePicker = new ValuePickerWidget(
		this.model,
		{
			classes: [ 'mw-rcfilters-ui-datePopupWidget-hours' ],
			label: mw.msg( 'rcfilters-hours-title' ),
			itemFilter: function ( itemModel ) { return Number( itemModel.getParamName() ) < 1; }
		}
	);
	this.daysValuePicker = new ValuePickerWidget(
		this.model,
		{
			classes: [ 'mw-rcfilters-ui-datePopupWidget-days' ],
			label: mw.msg( 'rcfilters-days-title' ),
			itemFilter: function ( itemModel ) { return Number( itemModel.getParamName() ) >= 1; }
		}
	);

	// Events
	this.hoursValuePicker.connect( this, { choose: [ 'emit', 'days' ] } );
	this.daysValuePicker.connect( this, { choose: [ 'emit', 'days' ] } );

	// Initialize
	this.$element
		.addClass( 'mw-rcfilters-ui-datePopupWidget' )
		.append(
			this.$label
				.addClass( 'mw-rcfilters-ui-datePopupWidget-title' ),
			this.hoursValuePicker.$element,
			this.daysValuePicker.$element
		);
};

/* Initialization */

OO.inheritClass( DatePopupWidget, OO.ui.Widget );
OO.mixinClass( DatePopupWidget, OO.ui.mixin.LabelElement );

/* Events */

/**
 * @event days
 * @param {string} name Item name
 *
 * A days item was chosen
 */

module.exports = DatePopupWidget;
