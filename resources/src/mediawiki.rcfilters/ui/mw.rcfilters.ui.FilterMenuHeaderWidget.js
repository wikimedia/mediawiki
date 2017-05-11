( function ( mw, $ ) {
	/**
	 * Menu header for the RCFilters filters menu
	 *
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

		// Highlight button
		this.highlightButton = new OO.ui.ToggleButtonWidget( {
			icon: 'highlight',
			label: mw.message( 'rcfilters-highlightbutton-title' ).text(),
			classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-hightlightButton' ]
		} );

		// Invert namespaces button
		this.invertNamespacesButton = new OO.ui.ToggleButtonWidget( {
			icon: '',
			label: mw.msg( 'invert' ),
			classes: [ 'mw-rcfilters-ui-filterMenuHeaderWidget-invertNamespacesButton' ]
		} );
		this.invertNamespacesButton.toggle( this.model.getCurrentView() === 'namespaces' );

		// Events
		this.highlightButton
			.connect( this, { click: 'onHighlightButtonClick' } );
		this.invertNamespacesButton
			.connect( this, { click: 'onInvertNamespacesButtonClick' } );
		this.model.connect( this, {
			highlightChange: 'onModelHighlightChange',
			invertChange: 'onModelInvertChange',
			update: 'onModelUpdate'
		} );

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
									.addClass( 'mw-rcfilters-ui-filterMenuHeaderWidget-header-title' )
									.append( this.$label ),
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
	 * Respond to model update event
	 */
	mw.rcfilters.ui.FilterMenuHeaderWidget.prototype.onModelUpdate = function () {
		this.setLabel( this.model.getCurrentViewLabel() );

		this.invertNamespacesButton.toggle( this.model.getCurrentView() === 'namespaces' );
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
	 * Respond to model invert change event
	 *
	 * @param {boolean} isInverted Namespaces selection is inverted
	 */
	mw.rcfilters.ui.FilterMenuHeaderWidget.prototype.onModelInvertChange = function ( isInverted ) {
		this.invertNamespacesButton.setActive( isInverted );
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
}( mediaWiki, jQuery ) );
