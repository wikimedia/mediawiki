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

		this.groupByPageCheckbox = new OO.ui.CheckboxInputWidget( {
			selected: mw.user.options.get( 'usenewrc' )
		} );

		// Events
		this.valuePicker.connect( this, { choose: [ 'emit', 'limit' ] } );
		this.groupByPageCheckbox.connect( this, { change: [ 'emit', 'groupResults' ] } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-changesLimitPopupWidget' )
			.append(
				this.valuePicker.$element,
				new OO.ui.FieldsetLayout( {
					label: mw.msg( 'rcfilters-group-results-by-page-title' ),
					items: [
						new OO.ui.FieldLayout(
							this.groupByPageCheckbox,
							{
								align: 'inline',
								label: mw.msg( 'rcfilters-group-results-by-page' )
							}
						)
					]
				} ).$element
			);
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

	/**
	 * Respond to group by page checkbox change event
	 *
	 * @param {boolean} isSelected Checkbox is selected
	 */
	mw.rcfilters.ui.ChangesLimitPopupWidget.prototype.onGroupByPageCheckboxChange = function ( isSelected ) {
		this.controller.toggleGroupByPage( isSelected );
	};
}( mediaWiki ) );
