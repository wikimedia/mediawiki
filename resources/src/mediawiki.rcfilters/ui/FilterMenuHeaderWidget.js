/**
 * Menu header for the RCFilters filters menu.
 *
 * @class mw.rcfilters.ui.FilterMenuHeaderWidget
 * @ignore
 * @extends OO.ui.Widget
 *
 * @param {mw.rcfilters.Controller} controller Controller
 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
 * @param {Object} config Configuration object
 * @param {jQuery} [config.$overlay] A jQuery object serving as overlay for popups
 * @param {boolean} [config.isMobile] a boolean flag that determines whether some
 * elements should be displayed based on whether the UI is mobile or not.
 * @param {boolean} [config.specialPage] title of the page this is loaded on
 */
const FilterMenuHeaderWidget = function MwRcfiltersUiFilterMenuHeaderWidget( controller, model, config ) {
	config = config || {};

	this.controller = controller;
	this.model = model;
	this.$overlay = config.$overlay || this.$element;
	this.specialPage = config.specialPage || '';

	// Parent
	FilterMenuHeaderWidget.super.call( this, config );
	OO.ui.mixin.LabelElement.call( this, Object.assign( {
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

	if ( !config.isMobile ) {
		// Highlight button
		this.highlightButton = new OO.ui.ToggleButtonWidget( {
			icon: 'highlight',
			label: mw.msg( 'rcfilters-highlightbutton-title' ),
			classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-hightlightButton' ]
		} );
	}

	// Invert buttons
	this.invertTagsButton = new OO.ui.ToggleButtonWidget( {
		icon: '',
		classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-invertTagsButton' ]
	} );
	this.invertTagsButton.toggle( this.model.getCurrentView() === 'tags' );
	this.invertNamespacesButton = new OO.ui.ToggleButtonWidget( {
		icon: '',
		classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-invertNamespacesButton' ]
	} );
	this.invertNamespacesButton.toggle( this.model.getCurrentView() === 'namespaces' );
	this.invertWLLabelsButton = new OO.ui.ToggleButtonWidget( {
		icon: '',
		classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-invertWLLabelsButton' ]
	} );
	this.invertWLLabelsButton.toggle( this.model.getCurrentView() === 'wllabels' );

	// Events
	this.backButton.connect( this, { click: 'onBackButtonClick' } );
	if ( !config.isMobile ) {
		this.highlightButton
			.connect( this, { click: 'onHighlightButtonClick' } );
	}
	this.invertTagsButton
		.connect( this, { click: 'onInvertTagsButtonClick' } );
	this.invertNamespacesButton
		.connect( this, { click: 'onInvertNamespacesButtonClick' } );
	this.invertWLLabelsButton
		.connect( this, { click: 'onInvertWLLabelsButtonClick' } );
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
								.append( this.invertTagsButton.$element ),
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-header-invert' )
								.append( this.invertNamespacesButton.$element ),
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-header-invert' )
								.append( this.invertWLLabelsButton.$element )
						)
				)
		);
	if ( !config.isMobile ) {
		this.$element.find( '.mw-rcfilters-ui-row' ).append(
			$( '<div>' )
				.addClass( 'mw-rcfilters-ui-cell' )
				.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-header-highlight' )
				.append( this.highlightButton.$element )
		);
	}
};

/* Initialization */

OO.inheritClass( FilterMenuHeaderWidget, OO.ui.Widget );
OO.mixinClass( FilterMenuHeaderWidget, OO.ui.mixin.LabelElement );

/* Methods */

/**
 * Respond to model initialization event
 *
 * Note: need to wait for initialization before getting the invertModel
 * and registering its update event. Creating all the models before the UI
 * would help with that.
 */
FilterMenuHeaderWidget.prototype.onModelInitialize = function () {
	this.invertNamespacesModel = this.model.getNamespacesInvertModel();
	this.updateInvertNamespacesButton();
	this.invertNamespacesModel.connect( this, { update: 'updateInvertNamespacesButton' } );

	this.invertTagsModel = this.model.getTagsInvertModel();
	this.updateInvertTagsButton();
	this.invertTagsModel.connect( this, { update: 'updateInvertTagsButton' } );

	if ( mw.config.get( 'enableWatchlistLabels' ) && this.specialPage === 'Watchlist' ) {
		this.invertWLLabelsModel = this.model.getWLLabelsInvertModel();
		this.updateInvertWLLabelsButton();
		this.invertWLLabelsModel.connect( this, { update: 'updateInvertWLLabelsButton' } );
	}
};

/**
 * Respond to model update event
 */
FilterMenuHeaderWidget.prototype.onModelSearchChange = function () {
	const currentView = this.model.getCurrentView();

	if ( this.view !== currentView ) {
		this.setLabel( this.model.getViewTitle( currentView ) );

		this.invertTagsButton.toggle( currentView === 'tags' );
		this.invertNamespacesButton.toggle( currentView === 'namespaces' );
		this.invertWLLabelsButton.toggle( currentView === 'wllabels' );
		this.backButton.toggle( currentView !== 'default' );

		// Modify help icon for watchlist labels/tags view
		if ( currentView === 'wllabels' ) {
			this.helpIcon.setHref( mw.util.getUrl( 'Special:WatchlistLabels' ) );
			this.helpIcon.setTitle( mw.msg( 'rcfilters-view-wllabels-help-icon-tooltip' ) );
			this.helpIcon.toggle( true );
		} else if ( currentView === 'tags' ) {
			this.helpIcon.setHref( mw.util.getUrl( 'Special:Tags' ) );
			this.helpIcon.setTitle( mw.msg( 'rcfilters-view-tags-help-icon-tooltip' ) );
			this.helpIcon.toggle( true );
		} else {
			this.helpIcon.toggle( false );
		}

		this.view = currentView;
	}
};

/**
 * Respond to model highlight change event
 *
 * @param {boolean} highlightEnabled Highlight is enabled
 */
FilterMenuHeaderWidget.prototype.onModelHighlightChange = function ( highlightEnabled ) {
	this.highlightButton.setActive( highlightEnabled );
};

/**
 * Update the state of the tags invert button
 */
FilterMenuHeaderWidget.prototype.updateInvertTagsButton = function () {
	this.invertTagsButton.setActive( this.invertTagsModel.isSelected() );
	this.invertTagsButton.setLabel(
		this.invertTagsModel.isSelected() ?
			mw.msg( 'rcfilters-exclude-button-on' ) :
			mw.msg( 'rcfilters-exclude-button-off' )
	);
};

/**
 * Update the state of the namespaces invert button
 */
FilterMenuHeaderWidget.prototype.updateInvertNamespacesButton = function () {
	this.invertNamespacesButton.setActive( this.invertNamespacesModel.isSelected() );
	this.invertNamespacesButton.setLabel(
		this.invertNamespacesModel.isSelected() ?
			mw.msg( 'rcfilters-exclude-button-on' ) :
			mw.msg( 'rcfilters-exclude-button-off' )
	);
};

/**
 * Update the state of the labels invert button
 */
FilterMenuHeaderWidget.prototype.updateInvertWLLabelsButton = function () {
	this.invertWLLabelsButton.setActive( this.invertWLLabelsModel.isSelected() );
	this.invertWLLabelsButton.setLabel(
		this.invertWLLabelsModel.isSelected() ?
			mw.msg( 'rcfilters-exclude-button-on' ) :
			mw.msg( 'rcfilters-exclude-button-off' )
	);
};

FilterMenuHeaderWidget.prototype.onBackButtonClick = function () {
	this.controller.switchView( 'default' );
};

/**
 * Respond to highlight button click
 */
FilterMenuHeaderWidget.prototype.onHighlightButtonClick = function () {
	this.controller.toggleHighlight();
};

/**
 * Respond to invert tags button click
 */
FilterMenuHeaderWidget.prototype.onInvertTagsButtonClick = function () {
	this.controller.toggleInvertedTags();
};

/**
 * Respond to invert namespaces button click
 */
FilterMenuHeaderWidget.prototype.onInvertNamespacesButtonClick = function () {
	this.controller.toggleInvertedNamespaces();
};

/**
 * Respond to invert labels button click
 */
FilterMenuHeaderWidget.prototype.onInvertWLLabelsButtonClick = function () {
	this.controller.toggleInvertedWLLabels();
};

module.exports = FilterMenuHeaderWidget;
