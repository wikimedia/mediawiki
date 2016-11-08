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
				widget = new mw.rcfilters.ui.FilterWrapperWidget( model );

			model.setFilters( {
				authorship: {
					title: mw.msg( 'rcfilters-filtergroup-authorship' ),
					filters: [
						{
							name: 'editsbyself',
							label: mw.msg( 'rcfilters-filter-editsbyself-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyself-description' )
						},
						{
							name: 'editsbyother',
							label: mw.msg( 'rcfilters-filter-editsbyother-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyother-description' )
						}
					]
				}
			} );

			$( '.mw-specialpage-summary' ).after( widget.$element );
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
