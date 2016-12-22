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
				$form = $( '.rcoptions form' ),
				submittingPromise = null;

			function updateResults( fromPopState ) {
				var $submitButton = $form.find( 'input[type=submit]' ),
					$changesList = $( '.mw-changeslist' ),
					uri = new mw.Uri();
				if ( submittingPromise ) {
					// If a submission is already pending, ignore this one
					return;
				}

				$changesList.addClass( 'oo-ui-pendingElement-pending' );
				$submitButton.prop( 'disabled', true );

				// FIXME in theory this should use controller.makeFiltersQuery() but its API
				// is not very useful and it breaks non-boolean params
				uri.extend( model.getFiltersToParameters() );
				submittingPromise = $.ajax( uri.toString(), { contentType: 'html' } );
				submittingPromise
					.then( function ( html ) {
						$changesList.empty().append(
							$( $.parseHTML( html ) ).find( '.mw-changeslist' ).first().contents()
						);
						// FIXME ideally this would use controller.updateURL(); , but since it uses
						// makeFiltersQuery() it also breaks non-boolean params
						if ( !fromPopState ) {
							history.pushState( { tag: 'mw-rcfilters' }, document.title, uri.toString() );
						}
					} )
					.always( function () {
						$changesList.removeClass( 'oo-ui-pendingElement-pending' );
						$submitButton.prop( 'disabled', false );
						submittingPromise = null;
					} );
			}

			model.toggleLoading( true );
			model.initializeFilters( {
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
					filters: [
						{
							name: 'hidemyself',
							label: mw.msg( 'rcfilters-filter-editsbyself-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyself-description' )
						},
						{
							name: 'hidebyothers',
							label: mw.msg( 'rcfilters-filter-editsbyother-label' ),
							description: mw.msg( 'rcfilters-filter-editsbyother-description' )
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
					filters: [
						{
							name: 'newcomer',
							label: mw.msg( 'rcfilters-filter-userExpLevel-newcomer-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-newcomer-description' )
						},
						{
							name: 'experienced',
							label: mw.msg( 'rcfilters-filter-userExpLevel-experienced-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-experienced-description' )
						},
						{
							name: 'moreexperienced',
							label: mw.msg( 'rcfilters-filter-userExpLevel-moreexperienced-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-moreexperienced-description' )
						}
					]
				}
			} );

			$( '.mw-specialpage-summary' ).after( widget.$element );

			// Initialize values
			controller.initialize();
			model.toggleLoading( false );

			$form.submit( function () {
				updateResults();
				// Prevent native form submission
				return false;
			} );

			window.addEventListener( 'popstate', function ( e ) {
				if ( !e.state || e.state.tag !== 'mw-rcfilters' ) {
					// Not our state, ignore
					return;
				}
				controller.updateFromURL();
				updateResults( true );
			} );

			// Replace the current state with one that's marked with our tag, so that using the
			// back button to navigate to the initial state works
			history.replaceState( { tag: 'mw-rcfilters' }, document.title, location.href );
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
