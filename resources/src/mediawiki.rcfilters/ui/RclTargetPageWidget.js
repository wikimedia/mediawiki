/**
 * Widget to select and display target page on Special:RecentChangesLinked (AKA Related Changes)
 *
 * @class mw.rcfilters.ui.RclTargetPageWidget
 * @extends OO.ui.Widget
 *
 * @constructor
 * @param {mw.rcfilters.Controller} controller
 * @param {mw.rcfilters.dm.FilterItem} targetPageModel
 * @param {Object} [config] Configuration object
 */
var RclTargetPageWidget = function MwRcfiltersUiRclTargetPageWidget(
	controller, targetPageModel, config
) {
	config = config || {};

	// Parent
	RclTargetPageWidget.parent.call( this, config );

	this.controller = controller;
	this.model = targetPageModel;

	this.titleSearch = new mw.widgets.TitleInputWidget( {
		validate: false,
		placeholder: mw.msg( 'rcfilters-target-page-placeholder' ),
		showImages: true,
		showDescriptions: true,
		addQueryInput: false
	} );

	// Events
	this.model.connect( this, { update: 'updateUiBasedOnModel' } );

	this.titleSearch.$input.on( {
		blur: this.onLookupInputBlur.bind( this )
	} );

	this.titleSearch.lookupMenu.connect( this, {
		choose: 'onLookupMenuItemChoose'
	} );

	// Initialize
	this.$element
		.addClass( 'mw-rcfilters-ui-rclTargetPageWidget' )
		.append( this.titleSearch.$element );

	this.updateUiBasedOnModel();
};

/* Initialization */

OO.inheritClass( RclTargetPageWidget, OO.ui.Widget );

/* Methods */

/**
 * Respond to the user choosing a title
 */
RclTargetPageWidget.prototype.onLookupMenuItemChoose = function () {
	this.titleSearch.$input.trigger( 'blur' );
};

/**
 * Respond to titleSearch $input blur
 */
RclTargetPageWidget.prototype.onLookupInputBlur = function () {
	this.controller.setTargetPage( this.titleSearch.getQueryValue() );
};

/**
 * Respond to the model being updated
 */
RclTargetPageWidget.prototype.updateUiBasedOnModel = function () {
	var title = mw.Title.newFromText( this.model.getValue() ),
		text = title ? title.toText() : this.model.getValue();
	this.titleSearch.setValue( text );
	this.titleSearch.setTitle( text );
};

module.exports = RclTargetPageWidget;
