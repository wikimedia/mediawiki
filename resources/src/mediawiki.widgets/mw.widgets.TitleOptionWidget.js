/*!
 * MediaWiki Widgets - TitleOptionWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * Creates a mw.widgets.TitleOptionWidget object.
	 *
	 * @class
	 * @extends OO.ui.MenuOptionWidget
	 *
	 * @constructor
	 * @param {Object} config Configuration options
	 * @cfg {string} data Label to display
	 * @cfg {string} url URL of page
	 * @cfg {boolean} [showImages] Whether to attempt to show images
	 * @cfg {string} [imageUrl] Thumbnail image URL with URL encoding
	 * @cfg {string} [description] Page description
	 * @cfg {boolean} [missing] Page doesn't exist
	 * @cfg {boolean} [redirect] Page is a redirect
	 * @cfg {boolean} [disambiguation] Page is a disambiguation page
	 * @cfg {string} [query] Matching query string to highlight
	 * @cfg {Function} [compare] String comparison function for query highlighting
	 */
	mw.widgets.TitleOptionWidget = function MwWidgetsTitleOptionWidget( config ) {
		var icon;

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
		mw.widgets.TitleOptionWidget.parent.call( this, config );

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
				.addClass( 'mw-widget-titleOptionWidget-hasImage' )
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
