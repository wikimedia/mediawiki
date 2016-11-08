/*!
 * JavaScript for Special:RecentChanges
 */
( function ( mw, $ ) {
	var rcfilters

	/**
	 * @class mw.rcfilters
	 * @singleton
	 */
	rcfilters = {
		/** */
		init: function () {
			var model = new mw.rcfilters.dm.FiltersViewModel(),
				widget = new mw.rcfilters.ui.FilterWrapperWidget( model );

			model.setFilters( {
				authorship: [
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
			} );

			$( '.mw-specialpage-summary' ).after( widget.$element );
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
