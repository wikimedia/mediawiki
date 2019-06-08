( function () {
	/**
	 * Widget to select to view changes that link TO or FROM the target page
	 * on Special:RecentChangesLinked (AKA Related Changes)
	 *
	 * @class mw.rcfilters.ui.RclToOrFromWidget
	 * @extends OO.ui.DropdownWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.FilterItem} showLinkedToModel model this widget is bound to
	 * @param {Object} [config] Configuration object
	 */
	var RclToOrFromWidget = function MwRcfiltersUiRclToOrFromWidget(
		controller, showLinkedToModel, config
	) {
		config = config || {};

		this.showLinkedFrom = new OO.ui.MenuOptionWidget( {
			data: 'from', // default (showlinkedto=0)
			label: new OO.ui.HtmlSnippet( mw.msg( 'rcfilters-filter-showlinkedfrom-option-label' ) )
		} );
		this.showLinkedTo = new OO.ui.MenuOptionWidget( {
			data: 'to', // showlinkedto=1
			label: new OO.ui.HtmlSnippet( mw.msg( 'rcfilters-filter-showlinkedto-option-label' ) )
		} );

		// Parent
		RclToOrFromWidget.parent.call( this, $.extend( {
			classes: [ 'mw-rcfilters-ui-rclToOrFromWidget' ],
			menu: { items: [ this.showLinkedFrom, this.showLinkedTo ] }
		}, config ) );

		this.controller = controller;
		this.model = showLinkedToModel;

		this.getMenu().connect( this, { choose: 'onUserChooseItem' } );
		this.model.connect( this, { update: 'onModelUpdate' } );

		// force an initial update of the component based on the state
		this.onModelUpdate();
	};

	/* Initialization */

	OO.inheritClass( RclToOrFromWidget, OO.ui.DropdownWidget );

	/* Methods */

	/**
	 * Respond to the user choosing an item in the menu
	 *
	 * @param {OO.ui.MenuOptionWidget} chosenItem
	 */
	RclToOrFromWidget.prototype.onUserChooseItem = function ( chosenItem ) {
		this.controller.setShowLinkedTo( chosenItem.getData() === 'to' );
	};

	/**
	 * Respond to model update
	 */
	RclToOrFromWidget.prototype.onModelUpdate = function () {
		this.getMenu().selectItem(
			this.model.isSelected() ?
				this.showLinkedTo :
				this.showLinkedFrom
		);
		this.setLabel( mw.msg(
			this.model.isSelected() ?
				'rcfilters-filter-showlinkedto-label' :
				'rcfilters-filter-showlinkedfrom-label'
		) );
	};

	module.exports = RclToOrFromWidget;
}() );
