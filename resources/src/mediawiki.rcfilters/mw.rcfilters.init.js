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
				}
			} );

			$( '.mw-specialpage-summary' ).after( widget.$element );

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
