( function ( mw ) {
	/**
	 * Widget defining the popup to choose number of results
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.dm.FilterGroup} model Group model for 'limit'
	 * @param {mw.rcfilters.dm.FilterItem} model Item model for 'displayState__enhanced'
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.ChangesLimitPopupWidget = function MwRcfiltersUiChangesLimitPopupWidget( limitModel, displayModel, config ) {
		var displayGroup;

		config = config || {};

		// Parent
		mw.rcfilters.ui.ChangesLimitPopupWidget.parent.call( this, config );

		this.limitModel = limitModel;
		this.enhancedViewModel = displayModel;

		this.valuePicker = new mw.rcfilters.ui.ValuePickerWidget(
			this.limitModel,
			{
				label: mw.msg( 'rcfilters-limit-title' )
			}
		);

		this.groupByPageCheckbox = new OO.ui.CheckboxInputWidget( {
			selected: this.enhancedViewModel.isSelected()
		} );

		// Events
		this.valuePicker.connect( this, { choose: [ 'emit', 'limit' ] } );
		this.groupByPageCheckbox.connect( this, { change: [ 'emit', 'groupResults' ] } );
		this.enhancedViewModel.connect( this, { update: 'onEnhancedViewUpdate' } );

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
	 * @event groupResults
	 * @param {boolean} selected Grouping results
	 *
	 * Group results has changed
	 */

	/* Methods */

	/**
	 * Respond to enhanced view model change event
	 */
	mw.rcfilters.ui.ChangesLimitPopupWidget.prototype.onEnhancedViewUpdate = function () {
		this.groupByPageCheckbox.setSelected( this.enhancedViewModel.isSelected() );
	};
}( mediaWiki ) );
