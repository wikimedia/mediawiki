/*!
 * MediaWiki Widgets - UserInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {
	/**
	 * Creates a mw.widgets.UserInputWidget object.
	 *
	 * @class
	 * @extends OO.ui.TextInputWidget
	 * @mixins OO.ui.mixin.LookupElement
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {number} [limit=10] Number of results to show
	 */
	mw.widgets.UserInputWidget = function MwWidgetsUserInputWidget( config ) {
		// Config initialization
		config = config || {};

		// Parent constructor
		OO.ui.TextInputWidget.call( this, $.extend( {}, config, { autocomplete: false } ) );

		// Mixin constructors
		OO.ui.mixin.LookupElement.call( this, config );

		// Properties
		this.limit = config.limit || 10;

		// Initialization
		this.$element.addClass( 'mw-widget-userInputWidget' );
		this.lookupMenu.$element.addClass( 'mw-widget-userInputWidget-menu' );
	};

	/* Inheritance */

	OO.inheritClass( mw.widgets.UserInputWidget, OO.ui.TextInputWidget );

	OO.mixinClass( mw.widgets.UserInputWidget, OO.ui.mixin.LookupElement );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.widgets.UserInputWidget.prototype.onLookupMenuItemChoose = function ( item ) {
		this.closeLookupMenu();
		this.setLookupsDisabled( true );
		this.setValue( item.getData() );
		this.setLookupsDisabled( false );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.UserInputWidget.prototype.focus = function () {
		var retval;

		// Prevent programmatic focus from opening the menu
		this.setLookupsDisabled( true );

		// Parent method
		retval = OO.ui.TextInputWidget.prototype.focus.apply( this, arguments );

		this.setLookupsDisabled( false );

		return retval;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.UserInputWidget.prototype.getLookupRequest = function () {
		var req,
			params,
			inputValue = this.value;

		params = {
			action: 'query',
			list: 'allusers',
			// Prefix of list=allusers is case sensitive. Normalise first
			// character to uppercase so that "fo" may yield "Foo".
			auprefix: inputValue[0].toUpperCase() + inputValue.slice( 1 ),
			aulimit: this.limit
		};

		req = new mw.Api().get( params );

		return req;
	};

	/**
	 * Get lookup cache item from server response data.
	 *
	 * @method
	 * @param {Mixed} data Response from server
	 */
	mw.widgets.UserInputWidget.prototype.getLookupCacheDataFromResponse = function ( data ) {
		return data.query || {};
	};

	/**
	 * Get list of menu items from a server response.
	 *
	 * @param {Object} data Query result
	 * @returns {OO.ui.MenuOptionWidget[]} Menu items
	 */
	mw.widgets.UserInputWidget.prototype.getLookupMenuOptionsFromData = function ( data ) {
		var len, i, user,
			users = data.allusers,
			items = [];

		for ( i = 0, len = users.length; i < len; i++ ) {
			user = users[i] || {};
			items.push( new OO.ui.MenuOptionWidget( {
				label: user.name,
				data: user.name
			} ) );
		}

		return items;
	};

}( jQuery, mediaWiki ) );
