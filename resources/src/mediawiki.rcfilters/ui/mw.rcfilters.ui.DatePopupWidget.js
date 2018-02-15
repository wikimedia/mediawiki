( function ( mw ) {
	/**
	 * Widget defining the popup to choose date for the results
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FilterGroup} model Group model for 'days'
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.DatePopupWidget = function MwRcfiltersUiDatePopupWidget( model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.ChangesLimitPopupWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.LabelElement.call( this, config );

		this.model = model;

		this.hoursValuePicker = new mw.rcfilters.ui.ValuePickerWidget(
			this.model,
			{
				classes: [ 'mw-rcfilters-ui-datePopupWidget-hours' ],
				label: mw.msg( 'rcfilters-hours-title' ),
				itemFilter: function ( itemModel ) { return Number( itemModel.getParamName() ) < 1; }
			}
		);
		this.daysValuePicker = new mw.rcfilters.ui.ValuePickerWidget(
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

	OO.inheritClass( mw.rcfilters.ui.DatePopupWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.DatePopupWidget, OO.ui.mixin.LabelElement );

	/* Events */

	/**
	 * @event days
	 * @param {string} name Item name
	 *
	 * A days item was chosen
	 */
}( mediaWiki ) );
