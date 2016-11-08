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
							label: mw.msg( 'mw-rcfilters-editsbyself-label' ),
							description: mw.msg( 'mw-rcfilters-editsbyself-description' )
						},
						{
							name: 'editsbyother',
							label: mw.msg( 'mw-rcfilters-editsbyother-label' ),
							description: mw.msg( 'mw-rcfilters-editsbyother-description' )
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
