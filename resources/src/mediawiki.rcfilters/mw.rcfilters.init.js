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

			// TODO: The changesListWrapperWidget should be able to initialize
			// after the model is ready.
			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.ChangesListWrapperWidget(
				filtersModel, changesListModel, $( '.mw-changeslist, .mw-changeslist-empty' ) );

			controller.initialize( {
				registration: {
					title: mw.msg( 'rcfilters-filtergroup-registration' ),
					type: 'send_unselected_if_any',
					fullCoverage: true,
					filters: [
						{
							name: 'hideliu',
							label: mw.msg( 'rcfilters-filter-registered-label' ),
							description: mw.msg( 'rcfilters-filter-registered-description' ),
							'class': 'mw-changeslist-liu'
						},
						{
							name: 'hideanons',
							label: mw.msg( 'rcfilters-filter-unregistered-label' ),
							description: mw.msg( 'rcfilters-filter-unregistered-description' ),
							'class': 'mw-changeslist-anon'
						}
					]
				},
				userExpLevel: {
					title: mw.msg( 'rcfilters-filtergroup-userExpLevel' ),
					// Type 'string_options' means that the group is evaluated by
					// string values separated by comma; for example, param=opt1,opt2
					// If all options are selected they are replaced by the term "all".
					// The filters are the values for the parameter defined by the group.
					// ** In this case, the parameter name is the group name. **
					type: 'string_options',
					separator: ',',
					fullCoverage: false,
					filters: [
						{
							name: 'newcomer',
							label: mw.msg( 'rcfilters-filter-userExpLevel-newcomer-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-newcomer-description' ),
							conflicts: [ 'hideanons' ],
							'class': 'mw-changeslist-user-newcomer'
						},
						{
							name: 'learner',
							label: mw.msg( 'rcfilters-filter-userExpLevel-learner-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-learner-description' ),
							conflicts: [ 'hideanons' ],
							'class': 'mw-changeslist-user-learner'
						},
						{
							name: 'experienced',
							label: mw.msg( 'rcfilters-filter-userExpLevel-experienced-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-experienced-description' ),
							conflicts: [ 'hideanons' ],
							'class': 'mw-changeslist-user-experienced'
						}
					]
				},
				authorship: {
					title: mw.msg( 'rcfilters-filtergroup-authorship' ),
					// Type 'send_unselected_if_any' means that the controller will go over
					// all unselected filters in the group and use their parameters
					// as truthy in the query string.
					// This is to handle the "negative" filters. We are showing users
					// a positive message ("Show xxx") but the filters themselves are
					// based on "hide YYY". The purpose of this is to correctly map
					// the functionality to the UI, whether we are dealing with 2
					// parameters in the group or more.
					type: 'send_unselected_if_any',
					fullCoverage: true,
					filters: [
						{
							name: 'hidemyself',
							label: mw.msg( 'rcfilters-filter-editsbyself-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyself-description' ),
							'class': 'mw-changeslist-self'
						},
						{
							name: 'hidebyothers',
							label: mw.msg( 'rcfilters-filter-editsbyother-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyother-description' ),
							'class': 'mw-changeslist-others'
						}
					]
				},
				automated: {
					title: mw.msg( 'rcfilters-filtergroup-automated' ),
					type: 'send_unselected_if_any',
					fullCoverage: true,
					filters: [
						{
							name: 'hidebots',
							label: mw.msg( 'rcfilters-filter-bots-label' ),
							description: mw.msg( 'rcfilters-filter-bots-description' ),
							'default': true,
							'class': 'mw-changeslist-bot'
						},
						{
							name: 'hidehumans',
							label: mw.msg( 'rcfilters-filter-humans-label' ),
							description: mw.msg( 'rcfilters-filter-humans-description' ),
							'default': false,
							'class': 'mw-changeslist-human'
						}
					]
				},
				significance: {
					title: mw.msg( 'rcfilters-filtergroup-significance' ),
					type: 'send_unselected_if_any',
					fullCoverage: true,
					filters: [
						{
							name: 'hideminor',
							label: mw.msg( 'rcfilters-filter-minor-label' ),
							description: mw.msg( 'rcfilters-filter-minor-description' ),
							'class': 'mw-changeslist-minor'
						},
						{
							name: 'hidemajor',
							label: mw.msg( 'rcfilters-filter-major-label' ),
							description: mw.msg( 'rcfilters-filter-major-description' ),
							'class': 'mw-changeslist-major'
						}
					]
				},
				changetype: {
					title: mw.msg( 'rcfilters-filtergroup-changetype' ),
					type: 'send_unselected_if_any',
					fullCoverage: true,
					filters: [
						{
							name: 'hidepageedits',
							label: mw.msg( 'rcfilters-filter-pageedits-label' ),
							description: mw.msg( 'rcfilters-filter-pageedits-description' ),
							'default': false,
							'class': 'mw-changeslist-src-mw-edit'

						},
						{
							name: 'hidenewpages',
							label: mw.msg( 'rcfilters-filter-newpages-label' ),
							description: mw.msg( 'rcfilters-filter-newpages-description' ),
							'default': false,
							'class': 'mw-changeslist-src-mw-new'
						},
						{
							name: 'hidecategorization',
							label: mw.msg( 'rcfilters-filter-categorization-label' ),
							description: mw.msg( 'rcfilters-filter-categorization-description' ),
							'default': true,
							'class': 'mw-changeslist-src-mw-categorize'
						},
						{
							name: 'hidelog',
							label: mw.msg( 'rcfilters-filter-logactions-label' ),
							description: mw.msg( 'rcfilters-filter-logactions-description' ),
							'default': false,
							'class': 'mw-changeslist-src-mw-log'
						}
					]
				}
			} );

			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.FormWrapperWidget(
				filtersModel, changesListModel, controller, $( 'fieldset.rcoptions' ) );

			$( '.rcfilters-container' ).append( filtersWidget.$element );
			$( 'body' ).append( $overlay );

			window.addEventListener( 'popstate', function () {
				controller.updateChangesList();
			} );

			$( 'a.mw-helplink' ).attr(
				'href',
				'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:New_filters_for_edit_review'
			);
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
