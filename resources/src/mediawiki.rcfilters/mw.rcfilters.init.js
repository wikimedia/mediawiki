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
			var model = new mw.rcfilters.dm.FiltersViewModel(),
				controller = new mw.rcfilters.Controller( model ),
				widget = new mw.rcfilters.ui.FilterWrapperWidget( controller, model ),
				uri = new mw.Uri();

			model.setFilters( {
				authorship: {
					title: mw.msg( 'rcfilters-filtergroup-authorship' ),
					filters: [
						{
							// This is the name of the parameter we send to the backend.
							// The filters are flipped in meaning - 'show edits by me'
							// is actually 'hidebyothers' and 'show edits by others' is
							// actually 'hidemyself'
							name: 'hidebyothers',
							label: mw.msg( 'rcfilters-filter-editsbyself-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyself-description' ),
							selected: !!Number( uri.query.hidebyothers )
						},
						{
							name: 'hidemyself',
							label: mw.msg( 'rcfilters-filter-editsbyother-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyother-description' ),
							selected: !!Number( uri.query.hidebyothers )
						}
					]
				}
			} );

			$( '.mw-specialpage-summary' ).after( widget.$element );

			// SUPER HACK: For the moment, we're going to intercept the "submit" button
			// so we can make sure to send all parameters to the back end, including the
			// ones from the new filter
			$( '.mw-recentchanges-table .mw-input input[type=submit]' ).on( 'click', function () {
				// The filters should be already represented in the URL
				var uri = new mw.Uri(),
					namespace = $( '#namespace' ).val(),
					nsinvert = $( '#nsinvert' ).prop( 'checked' ),
					nsassociated = $( '#nsassociated' ).prop( 'checked' ),
					tagfilter = $( '#tagfilter' ).val();

				// Add info from the namespace/tag form
				uri.query.namespace = namespace;
				uri.query.invert = Number( nsinvert );
				uri.query.associated = Number( nsassociated );
				if ( tagfilter ) {
					uri.query.tagfilter = tagfilter;
				} else {
					delete uri.query.tagfilter;
				}

				// UBERHACK: Route to the page with the new query
				window.location.href = uri.toString();
				return false;
			} );

			// SUPER HACK #2: Intercept the manual link clicks
			$( '.rclinks a' ).on( 'click', function () {
				var href = $( this ).attr( 'href' ),
					linkUri = new mw.Uri( href ),
					currUri = new mw.Uri();

				// Merge query string with available filters
				currUri.query = $.extend( linkUri.query, currUri.query );

				// UBERHACK: Route to the page with the new query
				window.location.href = currUri.toString();
				return false;
			} );
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
