( function () {
	/**
	 * Menu header for the RCFilters filters menu
	 *
	 * @class
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.FilterMenuHeaderWidget = function MwRcfiltersUiFilterMenuHeaderWidget( controller, model, config ) {
		config = config || {};

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

		// Parent
		mw.rcfilters.ui.FilterMenuHeaderWidget.parent.call( this, config );
		OO.ui.mixin.LabelElement.call( this, $.extend( {
			label: mw.msg( 'rcfilters-filterlist-title' ),
			$label: $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-title' )
		}, config ) );

		// "Back" to default view button
		this.backButton = new OO.ui.ButtonWidget( {
			icon: 'previous',
			framed: false,
			title: mw.msg( 'rcfilters-view-return-to-default-tooltip' ),
			classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-backButton' ]
		} );
		this.backButton.toggle( this.model.getCurrentView() !== 'default' );

		// Help icon for Tagged edits
		this.helpIcon = new OO.ui.ButtonWidget( {
			icon: 'helpNotice',
			framed: false,
			title: mw.msg( 'rcfilters-view-tags-help-icon-tooltip' ),
			classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-helpIcon' ],
			href: mw.util.getUrl( 'Special:Tags' ),
			target: '_blank'
		} );
		this.helpIcon.toggle( this.model.getCurrentView() === 'tags' );

		// Highlight button
		this.highlightButton = new OO.ui.ToggleButtonWidget( {
			icon: 'highlight',
			label: mw.message( 'rcfilters-highlightbutton-title' ).text(),
			classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-hightlightButton' ]
		} );

		// Invert namespaces button
		this.invertNamespacesButton = new OO.ui.ToggleButtonWidget( {
			icon: '',
			classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-invertNamespacesButton' ]
		} );
		this.invertNamespacesButton.toggle( this.model.getCurrentView() === 'namespaces' );

		// Events
		this.backButton.connect( this, { click: 'onBackButtonClick' } );
		this.highlightButton
			.connect( this, { click: 'onHighlightButtonClick' } );
		this.invertNamespacesButton
			.connect( this, { click: 'onInvertNamespacesButtonClick' } );
		this.model.connect( this, {
			highlightChange: 'onModelHighlightChange',
			searchChange: 'onModelSearchChange',
			initialize: 'onModelInitialize'
		} );
		this.view = this.model.getCurrentView();

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-header' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-header-back' )
									.append( this.backButton.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-header-title' )
									.append( this.$label, this.helpIcon.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-header-invert' )
									.append( this.invertNamespacesButton.$element ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-header-highlight' )
									.append( this.highlightButton.$element )
							)
					)
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterMenuHeaderWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterMenuHeaderWidget, OO.ui.mixin.LabelElement );

	/* Methods */

	/**
	 * Respond to model initialization event
	 *
	 * Note: need to wait for initialization before getting the invertModel
	 * and registering its update event. Creating all the models before the UI
	 * would help with that.
	 */
	mw.rcfilters.ui.FilterMenuHeaderWidget.prototype.onModelInitialize = function () {
		this.invertModel = this.model.getInvertModel();
		this.updateInvertButton();
		this.invertModel.connect( this, { update: 'updateInvertButton' } );
	};

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.FilterMenuHeaderWidget.prototype.onModelSearchChange = function () {
		var currentView = this.model.getCurrentView();

		if ( this.view !== currentView ) {
			this.setLabel( this.model.getViewTitle( currentView ) );

			this.invertNamespacesButton.toggle( currentView === 'namespaces' );
			this.backButton.toggle( currentView !== 'default' );
			this.helpIcon.toggle( currentView === 'tags' );
			this.view = currentView;
		}
	};

	/**
	 * Respond to model highlight change event
	 *
	 * @param {boolean} highlightEnabled Highlight is enabled
	 */
	mw.rcfilters.ui.FilterMenuHeaderWidget.prototype.onModelHighlightChange = function ( highlightEnabled ) {
		this.highlightButton.setActive( highlightEnabled );
	};

	/**
	 * Update the state of the invert button
	 */
	mw.rcfilters.ui.FilterMenuHeaderWidget.prototype.updateInvertButton = function () {
		this.invertNamespacesButton.setActive( this.invertModel.isSelected() );
		this.invertNamespacesButton.setLabel(
			this.invertModel.isSelected() ?
				mw.msg( 'rcfilters-exclude-button-on' ) :
				mw.msg( 'rcfilters-exclude-button-off' )
		);
	};

	mw.rcfilters.ui.FilterMenuHeaderWidget.prototype.onBackButtonClick = function () {
		this.controller.switchView( 'default' );
	};

	/**
	 * Respond to highlight button click
	 */
	mw.rcfilters.ui.FilterMenuHeaderWidget.prototype.onHighlightButtonClick = function () {
		this.controller.toggleHighlight();
	};

	/**
	 * Respond to highlight button click
	 */
	mw.rcfilters.ui.FilterMenuHeaderWidget.prototype.onInvertNamespacesButtonClick = function () {
		this.controller.toggleInvertedNamespaces();
	};
}() );
