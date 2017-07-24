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

		this.model = model;

		/*
		// HACK: Temporarily remove hours from UI
		this.hoursValuePicker = new mw.rcfilters.ui.ValuePickerWidget(
			this.model,
			{
				classes: [ 'mw-rcfilters-ui-datePopupWidget-hours' ],
				label: mw.msg( 'rcfilters-hours-title' ),
				itemFilter: function ( itemModel ) { return Number( itemModel.getParamName() ) < 1; }
			}
		);*/
		this.daysValuePicker = new mw.rcfilters.ui.ValuePickerWidget(
			this.model,
			{
				classes: [ 'mw-rcfilters-ui-datePopupWidget-days' ],
				label: mw.msg( 'rcfilters-days-title' )
				// HACK: Temporarily remove hours from UI
				// itemFilter: function ( itemModel ) { return Number( itemModel.getParamName() ) >= 1; }
			}
		);

		// Events
		// HACK: Temporarily remove hours from UI
		// this.hoursValuePicker.connect( this, { choose: [ 'emit', 'days' ] } );
		this.daysValuePicker.connect( this, { choose: [ 'emit', 'days' ] } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-datePopupWidget' )
			.append(
				// HACK: Temporarily remove hours from UI
				// this.hoursValuePicker.$element,
				this.daysValuePicker.$element
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.DatePopupWidget, OO.ui.Widget );

	/* Events */

	/**
	 * @event days
	 * @param {string} name Item name
	 *
	 * A days item was chosen
	 */
}( mediaWiki ) );
