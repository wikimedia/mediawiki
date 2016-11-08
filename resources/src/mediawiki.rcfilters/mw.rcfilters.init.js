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
							name: 'editsbyself',
							label: mw.msg( 'rcfilters-filter-editsbyself-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyself-description' ),
							selected: !!Number( uri.query.editsbyself )
						},
						{
							name: 'editsbyother',
							label: mw.msg( 'rcfilters-filter-editsbyother-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyother-description' ),
							selected: !!Number( uri.query.editsbyother )
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
				if ( namespace ) {
					uri.query.namespace = namespace;

					if ( nsinvert ) {
						uri.query.invert = 1;
					}
					if ( nsassociated ) {
						uri.query.associated = 1;
					}
				}
				if ( tagfilter ) {
					uri.query.tagfilter = tagfilter;
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
