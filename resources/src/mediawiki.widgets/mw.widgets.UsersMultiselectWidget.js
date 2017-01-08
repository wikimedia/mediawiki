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
	 * If used inside HTML form the results will sent be in string representation
	 * of JSON usernames array.
	 *
	 * @class
	 * @extends OO.ui.CapsuleMultiselectWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {number} [limit=10] Number of results to show in autocomplete menu
	 * @cfg {string} [name] Name of input to submit results (when used in HTML forms)
	 */
	mw.widgets.UsersMultiselectWidget = function MwWidgetsUsersMultiselectWidget( config ) {
		// Config initialization
		config = $.extend( {
			limit: 10
		}, config );

		// Parent constructor
		mw.widgets.UsersMultiselectWidget.parent.call( this, $.extend( {}, config, {} ) );

		// Properties
		this.limit = config.limit;

		if ( 'name' in config ) {
			// If used inside HTML form, then create hidden input, which will store
			// the results.
			this.hiddenInput = $( '<input>', {
				type: 'hidden',
				name: config.name
			} ).appendTo( this.$element );

			// Update with preset values
			this.updateHiddenInput();
		}

		this.menu = this.getMenu();
		this.handle = this.$element.find( '.oo-ui-capsuleMultiselectWidget-handle' );

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
		this.api = new mw.Api();
	};

	/* Setup */

	OO.inheritClass( mw.widgets.UsersMultiselectWidget, OO.ui.CapsuleMultiselectWidget );

	/* Methods */

	/**
	 * Get currently selected usernames
	 *
	 * @return {Array} usernames
	 */
	mw.widgets.UsersMultiselectWidget.prototype.getSelectedUsernames = function () {
		return this.getItems().map( function( item ) {
			return item.label;
		} );
	};

	/**
	 * Update autocomplete menu with items
	 *
	 * @private
	 */
	mw.widgets.UsersMultiselectWidget.prototype.updateMenuItems = function() {
		var inputValue = this.$input.val();

		this.api.abort(); // Abort all unfinished api requests

		if ( inputValue.length > 0 ) {
			this.handle.addClass( 'oo-ui-pendingElement-pending' );

			this.api.get( {
				action: 'query',
				list: 'allusers',
				// Prefix of list=allusers is case sensitive. Normalise first
				// character to uppercase so that "fo" may yield "Foo".
				auprefix: inputValue[ 0 ].toUpperCase() + inputValue.slice( 1 ),
				aulimit: this.limit
			} ).then( function( response ) {
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

				this.handle.removeClass( 'oo-ui-pendingElement-pending' );
			}.bind( this ) );
		} else {
			this.handle.removeClass( 'oo-ui-pendingElement-pending' );
			this.menu.clearItems();
		}
	};

	/**
	 * If used inside HTML form, then update hiddenInput with JSON array of
	 * selected usernames
	 *
	 * @private
	 */
	mw.widgets.UsersMultiselectWidget.prototype.updateHiddenInput = function() {
		if ( 'hiddenInput' in this ) {
			this.hiddenInput.val( JSON.stringify( this.getSelectedUsernames() ) );
		}
	};

}( jQuery, mediaWiki ) );
