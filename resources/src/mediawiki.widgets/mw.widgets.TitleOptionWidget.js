/*!
 * MediaWiki Widgets - TitleOptionWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

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
	 * @cfg {string} [imageUrl] Thumbnail image URL with URL encoding
	 * @cfg {string} [description] Page description
	 * @cfg {boolean} [missing] Page doesn't exist
	 * @cfg {boolean} [redirect] Page is a redirect
	 * @cfg {boolean} [disambiguation] Page is a disambiguation page
	 * @cfg {string} [query] Matching query string
	 */
	mw.widgets.TitleOptionWidget = function MwWidgetsTitleOptionWidget( config ) {
		var icon;

		if ( config.missing ) {
			icon = 'page-not-found';
		} else if ( config.redirect ) {
			icon = 'page-redirect';
		} else if ( config.disambiguation ) {
			icon = 'page-disambiguation';
		} else {
			icon = 'page-existing';
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
		this.$label.autoEllipsis( { hasSpan: false, tooltip: true, matchText: config.query } );

		if ( config.missing ) {
			this.$label.addClass( 'new' );
		}

		if ( config.imageUrl ) {
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

}( jQuery, mediaWiki ) );
