/*!
 * MediaWiki Widgets - UserInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {
	const util = require( 'mediawiki.util' );

	/**
	 * @classdesc User input widget.
	 *
	 * @class
	 * @extends OO.ui.TextInputWidget
	 * @mixes OO.ui.mixin.LookupElement
	 *
	 * @constructor
	 * @description Create a mw.widgets.UserInputWidget object.
	 * @param {Object} [config] Configuration options
	 * @param {number} [config.limit=10] Number of results to show
	 * @param {boolean} [config.excludenamed] Whether to exclude named users or not
	 * @param {boolean} [config.excludetemp] Whether to exclude temporary users or not
	 * @param {mw.Api} [config.api] API object to use, creates a default mw.Api instance if not specified
	 */
	mw.widgets.UserInputWidget = function MwWidgetsUserInputWidget( config ) {
		// Config initialization
		config = config || {};

		// Parent constructor
		mw.widgets.UserInputWidget.super.call( this, Object.assign( {}, config, { autocomplete: false } ) );

		// Mixin constructors
		OO.ui.mixin.LookupElement.call( this, config );

		// Properties
		this.limit = config.limit || 10;
		this.excludeNamed = config.excludenamed || false;
		this.excludeTemp = config.excludetemp || false;
		this.api = config.api || new mw.Api();

		// Initialization
		this.$element.addClass( 'mw-widget-userInputWidget' );
		this.lookupMenu.$element.addClass( 'mw-widget-userInputWidget-menu' );

		// Disable autocompletion if this widget only accepts IPs or IP ranges,
		// since the allusers API won't yield results in this case.
		this.alwaysDisableLookups = this.excludeNamed && this.excludeTemp;
		if ( this.alwaysDisableLookups ) {
			this.setLookupsDisabled( true );
		}
	};

	/* Setup */

	OO.inheritClass( mw.widgets.UserInputWidget, OO.ui.TextInputWidget );
	OO.mixinClass( mw.widgets.UserInputWidget, OO.ui.mixin.LookupElement );

	/**
	 * Disable or re-enable lookups, but does not apply the re-enabling of lookups if
	 * this.alwaysDisableLookups is set to true.
	 *
	 * @param {boolean} [disabled=false] Disable lookups
	 */
	mw.widgets.UserInputWidget.prototype.setLookupsDisabled = function ( disabled ) {
		this.lookupsDisabled = !!disabled || this.alwaysDisableLookups;
	};

	/* Methods */

	/**
	 * Handle menu item 'choose' event, updating the text input value to the value of the clicked item.
	 *
	 * @param {OO.ui.MenuOptionWidget} item Selected item
	 */
	mw.widgets.UserInputWidget.prototype.onLookupMenuChoose = function ( item ) {
		this.closeLookupMenu();
		this.setLookupsDisabled( true );
		this.setValue( item.getData() );
		this.setLookupsDisabled( false );
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.UserInputWidget.prototype.focus = function () {
		// Prevent programmatic focus from opening the menu
		this.setLookupsDisabled( true );

		// Parent method
		const retval = mw.widgets.UserInputWidget.super.prototype.focus.apply( this, arguments );

		this.setLookupsDisabled( false );

		return retval;
	};

	/**
	 * @inheritdoc
	 */
	mw.widgets.UserInputWidget.prototype.getLookupRequest = function () {
		let query = this.value;

		if ( typeof query === 'string' ) {
			// If the query is for an IP, trim both leading and trailing
			// whitespaces before the lookup takes place; otherwise, remove only
			// leading whitespaces, as usernames can't start with a whitespace
			// but may contain them after, and we want to filter out usernames
			// that don't contain spaces if the query contains them (T378279).
			if ( util.isIPAddress( query.trim() ) ) {
				query = query.trim();
			} else {
				query = query.replace( /^(\s)+/, '' );
			}
		}

		return this.api.get( {
			action: 'query',
			list: 'allusers',
			auprefix: query,
			aulimit: this.limit,
			auexcludenamed: this.excludeNamed,
			auexcludetemp: this.excludeTemp
		} );
	};

	/**
	 * Get lookup cache item from server response data.
	 *
	 * @method
	 * @param {any} response Response from server
	 * @return {Object}
	 */
	mw.widgets.UserInputWidget.prototype.getLookupCacheDataFromResponse = function ( response ) {
		return response.query.allusers || {};
	};

	/**
	 * Get list of menu items from a server response.
	 *
	 * @param {Object} data Query result
	 * @return {OO.ui.MenuOptionWidget[]} Menu items
	 */
	mw.widgets.UserInputWidget.prototype.getLookupMenuOptionsFromData = function ( data ) {
		const items = [];

		for ( let i = 0, len = data.length; i < len; i++ ) {
			const user = data[ i ] || {};
			items.push( new OO.ui.MenuOptionWidget( {
				label: user.name,
				data: user.name
			} ) );
		}

		return items;
	};

}() );
