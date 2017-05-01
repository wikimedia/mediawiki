/*!
 * MediaWiki Widgets - UsersMultiselectWidget class.
 *
 * @copyright 2017 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {

	/**
	 * UsersMultiselectWidget can be used to input list of users in a single
	 * line.
	 *
	 * If used inside HTML form the results will be sent as the list of
	 * newline-separated usernames.
	 *
	 * @class
	 * @extends OO.ui.CapsuleMultiselectWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {mw.Api} [api] Instance of mw.Api (or subclass thereof) to use for queries
	 * @cfg {number} [limit=10] Number of results to show in autocomplete menu
	 * @cfg {string} [name] Name of input to submit results (when used in HTML forms)
	 */
	mw.widgets.UsersMultiselectWidget = function MwWidgetsUsersMultiselectWidget( config ) {
		// Config initialization
		config = $.extend( {
			limit: 10
		}, config, {
			// Because of using autocomplete (constantly changing menu), we need to
			// allow adding usernames, which do not present in the menu.
			allowArbitrary: true
		} );

		// Parent constructor
		mw.widgets.UsersMultiselectWidget.parent.call( this, $.extend( {}, config, {} ) );

		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, $.extend( {}, config, { $pending: this.$handle } ) );

		// Properties
		this.limit = config.limit;

		if ( 'name' in config ) {
			// If used inside HTML form, then create hidden input, which will store
			// the results.
			this.hiddenInput = $( '<input>' )
				.attr( 'type', 'hidden' )
				.attr( 'name', config.name )
				.appendTo( this.$element );

			// Update with preset values
			this.updateHiddenInput();
		}

		this.menu = this.getMenu();

		// Events
		// Update contents of autocomplete menu as user types letters
		this.$input.on( {
			keyup: this.updateMenuItems.bind( this )
		} );
		// When option is selected from autocomplete menu, update the menu
		this.menu.connect( this, {
			select: 'updateMenuItems'
		} );
		// When list of selected usernames changes, update hidden input
		this.connect( this, {
			change: 'updateHiddenInput'
		} );

		// API init
		this.api = config.api || new mw.Api();
	};

	/* Setup */

	OO.inheritClass( mw.widgets.UsersMultiselectWidget, OO.ui.CapsuleMultiselectWidget );
	OO.mixinClass( mw.widgets.UsersMultiselectWidget, OO.ui.mixin.PendingElement );

	/* Methods */

	/**
	 * Get currently selected usernames
	 *
	 * @return {Array} usernames
	 */
	mw.widgets.UsersMultiselectWidget.prototype.getSelectedUsernames = function() {
		return this.getItemsData();
	};

	/**
	 * Update autocomplete menu with items
	 *
	 * @private
	 */
	mw.widgets.UsersMultiselectWidget.prototype.updateMenuItems = function() {
		var inputValue = this.$input.val();

		if ( inputValue === this.inputValue ) {
			// Do not restart api query if nothing has changed in the input
			return;
		} else {
			this.inputValue = inputValue;
		}

		this.api.abort(); // Abort all unfinished api requests

		if ( inputValue.length > 0 ) {
			this.pushPending();

			this.api.get( {
				action: 'query',
				list: 'allusers',
				// Prefix of list=allusers is case sensitive. Normalise first
				// character to uppercase so that "fo" may yield "Foo".
				auprefix: inputValue[ 0 ].toUpperCase() + inputValue.slice( 1 ),
				aulimit: this.limit
			} ).done( function( response ) {
				var suggestions = response.query.allusers,
					selected = this.getSelectedUsernames();

				// Remove usernames, which are already selected from suggestions
				suggestions = suggestions.map( function ( user ) {
					if ( selected.indexOf( user.name ) === -1 ) {
						return new OO.ui.MenuOptionWidget( {
							data: user.name,
							label: user.name
						} );
					}
				} ).filter( function( item ) {
					return item !== undefined;
				} );

				// Remove all items from menu add fill it with new
				this.menu.clearItems();

				// Additional check to prevent bug of autoinserting first suggestion
				// while removing user from the list
				if ( inputValue.length > 1 || suggestions.length > 1 ) {
					this.menu.addItems( suggestions );
				}

				this.popPending();
			}.bind( this ) ).fail( this.popPending.bind( this ) );
		} else {
			this.menu.clearItems();
		}
	};

	/**
	 * If used inside HTML form, then update hiddenInput with list o
	 * newline-separated usernames.
	 *
	 * @private
	 */
	mw.widgets.UsersMultiselectWidget.prototype.updateHiddenInput = function() {
		if ( 'hiddenInput' in this ) {
			this.hiddenInput.val( this.getSelectedUsernames().join( '\n' ) );
		}
	};

}( jQuery, mediaWiki ) );
