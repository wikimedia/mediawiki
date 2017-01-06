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
				widget = new mw.rcfilters.ui.FilterWrapperWidget( controller, model );

			model.initializeFilters( {
				registration: {
					title: mw.msg( 'rcfilters-filtergroup-registration' ),
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hideliu',
							label: mw.msg( 'rcfilters-filter-registered-label' ),
							description: mw.msg( 'rcfilters-filter-registered-description' )
						},
						{
							name: 'hideanon',
							label: mw.msg( 'rcfilters-filter-unregistered-label' ),
							description: mw.msg( 'rcfilters-filter-unregistered-description' )
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
							name: 'learner',
							label: mw.msg( 'rcfilters-filter-userExpLevel-learner-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-learner-description' )
						},
						{
							name: 'experienced',
							label: mw.msg( 'rcfilters-filter-userExpLevel-experienced-label' ),
							description: mw.msg( 'rcfilters-filter-userExpLevel-experienced-description' )
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
				automated: {
					title: mw.msg( 'rcfilters-filtergroup-automated' ),
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidebots',
							label: mw.msg( 'rcfilters-filter-bots-label' ),
							description: mw.msg( 'rcfilters-filter-bots-description' )
						},
						{
							name: 'hidehumans',
							label: mw.msg( 'rcfilters-filter-humans-label' ),
							description: mw.msg( 'rcfilters-filter-humans-description' )
						}
					]
				},
				significance: {
					title: mw.msg( 'rcfilters-filtergroup-significance' ),
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hideminor',
							label: mw.msg( 'rcfilters-filter-minor-label' ),
							description: mw.msg( 'rcfilters-filter-minor-description' )
						},
						{
							name: 'hidemajor',
							label: mw.msg( 'rcfilters-filter-major-label' ),
							description: mw.msg( 'rcfilters-filter-major-description' )
						}
					]
				},
				changetype: {
					title: mw.msg( 'rcfilters-filtergroup-changetype' ),
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidepageedits',
							label: mw.msg( 'rcfilters-filter-pageedits-label' ),
							description: mw.msg( 'rcfilters-filter-pageedits-description' )
						},
						{
							name: 'hidenewpages',
							label: mw.msg( 'rcfilters-filter-newpages-label' ),
							description: mw.msg( 'rcfilters-filter-newpages-description' )
						},
						{
							name: 'hidecategorization',
							label: mw.msg( 'rcfilters-filter-categorization-label' ),
							description: mw.msg( 'rcfilters-filter-categorization-description' )
						},
						{
							name: 'hidelog',
							label: mw.msg( 'rcfilters-filter-logactions-label' ),
							description: mw.msg( 'rcfilters-filter-logactions-description' )
						}
					]
				}
			} );

			$( '.rcoptions' ).before( widget.$element );

			// Initialize values
			controller.initialize();

			$( '.rcoptions form' ).submit( function () {
				var $form = $( this );

				// Get current filter values
				$.each( model.getParametersFromFilters(), function ( paramName, paramValue ) {
					var $existingInput = $form.find( 'input[name=' + paramName + ']' );
					// Check if the hidden input already exists
					// This happens if the parameter was already given
					// on load
					if ( $existingInput.length ) {
						// Update the value
						$existingInput.val( paramValue );
					} else {
						// Append hidden fields with filter values
						$form.append(
							$( '<input>' )
								.attr( 'type', 'hidden' )
								.attr( 'name', paramName )
								.val( paramValue )
						);
					}
				} );

				// Continue the submission process
				return true;
			} );
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
