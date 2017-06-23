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

		this.valuePicker = new mw.rcfilters.ui.ValuePickerWidget(
			this.model,
			{
				label: mw.msg( 'rcfilters-days-title' )
			}
		);

		// Events
		this.valuePicker.connect( this, { choose: [ 'emit', 'days' ] } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-datePopupWidget' )
			.append( this.valuePicker.$element );
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
