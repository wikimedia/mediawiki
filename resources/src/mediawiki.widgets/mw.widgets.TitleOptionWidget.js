/*!
 * MediaWiki Widgets - TitleOptionWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * @classdesc Title option widget.
	 *
	 * @class
	 * @extends OO.ui.MenuOptionWidget
	 *
	 * @constructor
	 * @description Create a mw.widgets.TitleOptionWidget object.
	 * @param {Object} config Configuration options
	 * @param {string} config.data Label to display
	 * @param {string} config.url URL of page
	 * @param {boolean} [config.showImages] Whether to attempt to show images
	 * @param {string} [config.imageUrl] Thumbnail image URL with URL encoding
	 * @param {string} [config.description] Page description
	 * @param {boolean} [config.missing] Page doesn't exist
	 * @param {boolean} [config.redirect] Page is a redirect
	 * @param {boolean} [config.disambiguation] Page is a disambiguation page
	 * @param {string} [config.query] Matching query string to highlight
	 * @param {Function} [config.compare] String comparison function for query highlighting
	 */
	mw.widgets.TitleOptionWidget = function MwWidgetsTitleOptionWidget( config ) {
		let icon;

		if ( !config.showImages ) {
			icon = null;
		} else if ( config.missing ) {
			icon = 'articleNotFound';
		} else if ( config.redirect ) {
			icon = 'articleRedirect';
		} else if ( config.disambiguation ) {
			icon = 'articleDisambiguation';
		} else {
			icon = 'article';
		}

		// Config initialization
		config = $.extend( {
			icon: icon,
			label: config.data,
			autoFitLabel: false,
			$label: $( '<a>' )
		}, config );

		// Parent constructor
		mw.widgets.TitleOptionWidget.super.call( this, config );

		// Remove check icon
		this.checkIcon.$element.remove();

		// Initialization
		this.$label.attr( 'href', config.url );
		this.$element.addClass( 'mw-widget-titleOptionWidget' );

		// OOUI OptionWidgets make an effort to not be tab accessible, but
		// adding a link inside them would undo that. So, explicitly make it
		// not tabbable.
		this.$label.attr( 'tabindex', '-1' );

		// Allow opening the link in new tab, but not regular navigation.
		this.$label.on( 'click', function ( e ) {
			// Don't interfere with special clicks (e.g. to open in new tab)
			if ( !( e.which !== 1 || e.altKey || e.ctrlKey || e.shiftKey || e.metaKey ) ) {
				e.preventDefault();
			}
		} );

		// Highlight matching parts of link suggestion
		if ( config.query ) {
			this.setHighlightedQuery( config.data, config.query, config.compare, true );
		}
		this.$label.attr( 'title', config.data );

		if ( config.missing ) {
			this.$label.addClass( 'new' );
		} else if ( config.redirect ) {
			this.$label.addClass( 'mw-redirect' );
		} else if ( config.disambiguation ) {
			this.$label.addClass( 'mw-disambig' );
		}

		if ( config.showImages && config.imageUrl ) {
			this.$icon
				.addClass( 'mw-widget-titleOptionWidget-hasImage mw-no-invert' )
				.css( 'background-image', 'url(' + config.imageUrl + ')' );
		}

		if ( config.description ) {
			this.$element.append(
				$( '<span>' )
					.addClass( 'mw-widget-titleOptionWidget-description' )
					.text( config.description )
					.attr( 'title', config.description )
			);
		}
	};

	/* Setup */

	OO.inheritClass( mw.widgets.TitleOptionWidget, OO.ui.MenuOptionWidget );

}() );
