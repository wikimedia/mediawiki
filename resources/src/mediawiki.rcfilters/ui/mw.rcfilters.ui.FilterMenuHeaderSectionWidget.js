( function ( mw, $ ) {
	/**
	 * Menu header for the RCFilters filters menu
	 *
	 * @extends OO.ui.MenuSectionOptionWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterMenuHeaderSectionWidget = function MwRcfiltersUiFilterMenuHeaderSectionWidget( controller, model, config ) {
		config = config || {};

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

		// Parent
		mw.rcfilters.ui.FilterMenuSectionOptionWidget.parent.call( this, $.extend( {
			label: mw.msg( 'rcfilters-filterlist-title' ),
			$label: $( '<div>' )
				.addClass( 'mw-rcfilters-ui-filterMenuHeaderSectionWidget-title' )
		}, config ) );

		// Highlight button
		this.highlightButton = new OO.ui.ToggleButtonWidget( {
			icon: 'highlight',
			label: mw.message( 'rcfilters-highlightbutton-title' ).text(),
			classes: [ 'mw-rcfilters-ui-filtersListWidget-hightlightButton' ]
		} );

		// Events
		this.highlightButton
			.connect( this, { click: 'onHighlightButtonClick' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-filterMenuHeaderSectionWidget' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-table' )
					.addClass( 'mw-rcfilters-ui-filterMenuHeaderSectionWidget-header' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-row' )
							.append(
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-filterMenuHeaderSectionWidget-header-title' )
									.append( this.$label ),
								$( '<div>' )
									.addClass( 'mw-rcfilters-ui-cell' )
									.addClass( 'mw-rcfilters-ui-filterMenuHeaderSectionWidget-header-highlight' )
									.append( this.highlightButton.$element )
							)
					)
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterMenuHeaderSectionWidget, OO.ui.MenuSectionOptionWidget );

	/* Methods */


	/**
	 * Respond to model highlight change event
	 *
	 * @param {boolean} highlightEnabled Highlight is enabled
	 */
	mw.rcfilters.ui.FilterMenuHeaderSectionWidget.prototype.onModelHighlightChange = function ( highlightEnabled ) {
		this.highlightButton.setActive( highlightEnabled );
	};

	/**
	 * Respond to highlight button click
	 */
	mw.rcfilters.ui.FilterMenuHeaderSectionWidget.prototype.onHighlightButtonClick = function () {
		this.controller.toggleHighlight();
	};
}( mediaWiki, jQuery ) );
