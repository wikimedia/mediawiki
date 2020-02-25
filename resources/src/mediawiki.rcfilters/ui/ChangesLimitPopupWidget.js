var ValuePickerWidget = require( './ValuePickerWidget.js' ),
	ChangesLimitPopupWidget;

/**
 * Widget defining the popup to choose number of results
 *
 * @class mw.rcfilters.ui.ChangesLimitPopupWidget
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {mw.rcfilters.dm.FilterGroup} limitModel Group model for 'limit'
 * @param {mw.rcfilters.dm.FilterItem} groupByPageItemModel Group model for 'limit'
 * @param {Object} [config] Configuration object
 */
ChangesLimitPopupWidget = function MwRcfiltersUiChangesLimitPopupWidget( limitModel, groupByPageItemModel, config ) {
	config = config || {};

	// Parent
	ChangesLimitPopupWidget.parent.call( this, config );

	this.limitModel = limitModel;
	this.groupByPageItemModel = groupByPageItemModel;

	this.valuePicker = new ValuePickerWidget(
		this.limitModel,
		{
			label: mw.msg( 'rcfilters-limit-title' )
		}
	);

	this.groupByPageCheckbox = new OO.ui.CheckboxInputWidget( {
		selected: this.groupByPageItemModel.isSelected()
	} );

	// Events
	this.valuePicker.connect( this, { choose: [ 'emit', 'limit' ] } );
	this.groupByPageCheckbox.connect( this, { change: [ 'emit', 'groupByPage' ] } );
	this.groupByPageItemModel.connect( this, { update: 'onGroupByPageModelUpdate' } );

	// Initialize
	this.$element
		.addClass( 'mw-rcfilters-ui-changesLimitPopupWidget' )
		.append(
			this.valuePicker.$element,
			OO.ui.isMobile() ? undefined :
				new OO.ui.FieldLayout(
					this.groupByPageCheckbox,
					{
						align: 'inline',
						label: mw.msg( 'rcfilters-group-results-by-page' )
					}
				).$element
		);
	this.valuePicker.selectWidget.$element.attr( 'aria-label', mw.msg( 'rcfilters-limit-title' ) );
};

/* Initialization */

OO.inheritClass( ChangesLimitPopupWidget, OO.ui.Widget );

/* Events */

/**
 * @event limit
 * @param {string} name Item name
 *
 * A limit item was chosen
 */

/**
 * @event groupByPage
 * @param {boolean} isGrouped The results are grouped by page
 *
 * Results are grouped by page
 */

/**
 * Respond to group by page model update
 */
ChangesLimitPopupWidget.prototype.onGroupByPageModelUpdate = function () {
	this.groupByPageCheckbox.setSelected( this.groupByPageItemModel.isSelected() );
};

module.exports = ChangesLimitPopupWidget;
