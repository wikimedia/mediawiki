/*!
 * JavaScript for Special:RecentChanges
 */
( function ( mw, $ ) {
	/**
	 * @class mw.rcfilters
	 * @singleton
	 */
	var rcfilters = {
		/** */
		init: function () {
			var filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
				changesListModel = new mw.rcfilters.dm.ChangesListViewModel(),
				controller = new mw.rcfilters.Controller( filtersModel, changesListModel ),
				$overlay = $( '<div>' )
					.addClass( 'mw-rcfilters-ui-overlay' ),
				filtersWidget = new mw.rcfilters.ui.FilterWrapperWidget(
					controller, filtersModel, { $overlay: $overlay } );

			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.ChangesListWrapperWidget(
				changesListModel, $( '.mw-changeslist, .mw-changeslist-empty' ) );

			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.FormWrapperWidget(
				changesListModel, $( '.rcoptions form' ) );

			controller.initialize( mw.config.get( 'wgStructuredChangeFilters' ) );

			$( '.rcoptions' ).before( filtersWidget.$element );
			$( 'body' ).append( $overlay );

			// HACK: Remove old-style filter links for filters handled by the widget
			// Ideally the widget would handle all filters and we'd just remove .rcshowhide entirely
			$( '.rcshowhide' ).children().each( function () {
				// HACK: Interpret the class name to get the filter name
				// This should really be set as a data attribute
				var i,
					name = null,
					// Some of the older browsers we support don't have .classList,
					// so we have to interpret the class attribute manually.
					classes = this.getAttribute( 'class' ).split( ' ' );
				for ( i = 0; i < classes.length; i++ ) {
					if ( classes[ i ].substr( 0, 'rcshow'.length ) === 'rcshow' ) {
						name = classes[ i ].substr( 'rcshow'.length );
						break;
					}
				}
				if ( name === null ) {
					return;
				}
				if ( name === 'hidemine' ) {
					// HACK: the span for hidemyself is called hidemine
					name = 'hidemyself';
				}
				// This span corresponds to a filter that's in our model, so remove it
				if ( filtersModel.getItemByName( name ) ) {
					// HACK: Remove the text node after the span.
					// If there isn't one, we're at the end, so remove the text node before the span.
					// This would be unnecessary if we added separators with CSS.
					if ( this.nextSibling && this.nextSibling.nodeType === Node.TEXT_NODE ) {
						this.parentNode.removeChild( this.nextSibling );
					} else if ( this.previousSibling && this.previousSibling.nodeType === Node.TEXT_NODE ) {
						this.parentNode.removeChild( this.previousSibling );
					}
					// Remove the span itself
					this.parentNode.removeChild( this );
				}
			} );

			window.addEventListener( 'popstate', function () {
				controller.updateFromURL();
				controller.updateChangesList();
			} );
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
