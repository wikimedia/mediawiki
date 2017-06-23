( function ( mw ) {
	/**
	 * Widget defining the popup to choose number of results
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FilterGroup} model Group model for 'limit'
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.ChangesLimitPopupWidget = function MwRcfiltersUiChangesLimitPopupWidget( model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.ChangesLimitPopupWidget.parent.call( this, config );

		this.model = model;

		this.valuePicker = new mw.rcfilters.ui.ValuePickerWidget(
			this.model,
			{
				label: mw.msg( 'rcfilters-limit-title' )
			}
		);

		// Events
		this.valuePicker.connect( this, { choose: [ 'emit', 'limit' ] } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-changesLimitPopupWidget' )
			.append( this.valuePicker.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.ChangesLimitPopupWidget, OO.ui.Widget );

	/* Events */

	/**
	 * @event limit
	 * @param {string} name Item name
	 *
	 * A limit item was chosen
	 */
}( mediaWiki ) );
