/**
 * Widget for toggling group by pages.
 *
 * @class mw.rcfilters.ui.GroupByToggleWidget
 * @ignore
 * @extends OO.ui.Widget
 *
 * @param {mw.rcfilters.Controller} controller
 * @param {mw.rcfilters.dm.FiltersViewModel} model
 * @param {Object} [config] Configuration object
 */
const GroupByToggleWidget = function MwRcfiltersUiGroupByToggleWidget( controller, model, config ) {
	config = config || {};

	// Parent
	GroupByToggleWidget.super.call( this, config );

	this.controller = controller;
	this.model = model;

	this.groupByPageItemModel = null;
	this.groupByPageToggle = null;

	// Events
	this.model.connect( this, {
		initialize: 'onModelInitialize',
		groupByPageUserClick: 'onGroupByPageUserClick'
	} );

	this.$element.addClass( 'mw-rcfilters-ui-groupByToggleWidget' );
	this.$element.addClass( 'mw-xlab-experiment-fy24-25-we-1-7-rc-grouping-toggle-control' );
};

/* Initialization */

OO.inheritClass( GroupByToggleWidget, OO.ui.Widget );

/* Methods */

/**
 * Respond to model initialize event
 */
GroupByToggleWidget.prototype.onModelInitialize = function () {
	const displayGroupModel = this.model.getGroup( 'display' );
	const skinIsNotMobile = mw.config.get( 'skin' ) !== 'minerva';
	this.groupByPageItemModel = displayGroupModel.getItemByParamName( 'enhanced' );
	if ( skinIsNotMobile ) {
		this.groupByPageToggle = new OO.ui.ToggleSwitchWidget( {
			id: 'mw-rcfilters-ui-filterWrapperWidget-groupByToggle',
			value: this.groupByPageItemModel.isSelected()
		} );
		this.groupByPageToggle.connect( this, { change: 'onGroupByPageChange' } );
		// HACK: Directly connect to the checkbox click event so that we can save the preference
		// when the user explicitly interacts with the checkbox rather than when the checkbox changes
		// state. This is to make sure that we only save preference when the user explicitly interacts
		// with the UI.
		this.groupByPageToggle.$element.on( 'click', this.onGroupByPageUserClick.bind( this ) );
		this.groupByPageItemModel.connect( this, { update: 'onGroupByPageModelUpdate' } );
		this.$element.append(
			new OO.ui.FieldLayout(
				this.groupByPageToggle,
				{
					label: mw.msg( 'rcfilters-group-results-by-page' ),
					align: 'side'
				}
			).$element
		);
	}
	mw.hook( 'rcfilters.groupbytogglewidget.initialized' ).fire( this );
};

/**
 * Respond to group by page model update
 */
GroupByToggleWidget.prototype.onGroupByPageModelUpdate = function () {
	this.groupByPageToggle.setValue( this.groupByPageItemModel.isSelected() );
};

/**
 *  Respond to toggle state change event
 *
 * @param isSelected
 */
GroupByToggleWidget.prototype.onGroupByPageChange = function ( isSelected ) {
	this.controller.toggleFilterSelect( this.groupByPageItemModel.getName(), isSelected );
};

/**
 * Respond to user explicitly clicking the Group by page toggle
 */
GroupByToggleWidget.prototype.onGroupByPageUserClick = function () {
	const isSelected = event.target.ariaChecked ? 1 : 0;
	this.controller.updateGroupByPageDefault( isSelected );
	this.emit( 'groupByPageUserClick', isSelected );
	mw.hook( 'rcfilters.groupbytogglewidget.click' ).fire( this );
};

module.exports = GroupByToggleWidget;
