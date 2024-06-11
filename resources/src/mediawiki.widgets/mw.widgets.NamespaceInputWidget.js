/*!
 * MediaWiki Widgets - NamespaceInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * @classdesc Displays a dropdown box with the choice of available namespaces.
	 *
	 * @class
	 * @extends OO.ui.DropdownInputWidget
	 *
	 * @constructor
	 * @description Create an instance of `mw.widgets.NamespaceInputWidget`.
	 * @param {Object} [config] Configuration options
	 * @param {string|null} [config.includeAllValue] Value for "all namespaces" option, if any
	 * @param {boolean} [config.userLang=false] Display namespaces in user language
	 * @param {number[]} [config.exclude] List of namespace numbers to exclude from the selector
	 */
	mw.widgets.NamespaceInputWidget = function MwWidgetsNamespaceInputWidget( config ) {
		// Configuration initialization
		config = Object.assign( {}, config, { options: this.constructor.static.getNamespaceDropdownOptions( config ) } );

		// Parent constructor
		mw.widgets.NamespaceInputWidget.super.call( this, config );

		// Initialization
		this.$element.addClass( 'mw-widget-namespaceInputWidget' );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.NamespaceInputWidget, OO.ui.DropdownInputWidget );

	/* Static methods */

	/**
	 * @typedef {Object} mw.widgets.NamespaceInputWidget~DropdownOptions
	 * @property {any} data
	 * @property {string} label
	 */

	/**
	 * Get a list of namespace options, sorted by ID.
	 *
	 * @method getNamespaceDropdownOptions
	 * @param {Object} [config] Configuration options
	 * @return {DropdownOptions[]} Dropdown options
	 * @memberof mw.widgets.NamespaceInputWidget
	 */
	mw.widgets.NamespaceInputWidget.static.getNamespaceDropdownOptions = function ( config ) {
		const exclude = config.exclude || [],
			mainNamespace = mw.config.get( 'wgNamespaceIds' )[ '' ];

		const namespaces = config.userLang ?
			require( './data.json' ).formattedNamespaces :
			mw.config.get( 'wgFormattedNamespaces' );

		// eslint-disable-next-line no-jquery/no-map-util
		const options = $.map( namespaces, ( name, ns ) => {
			if ( ns < mainNamespace || exclude.indexOf( Number( ns ) ) !== -1 ) {
				return null; // skip
			}
			ns = String( ns );
			if ( ns === String( mainNamespace ) ) {
				name = mw.msg( 'blanknamespace' );
			}
			return { data: ns, label: name };
		} ).sort(
			// wgFormattedNamespaces is an object, and so technically doesn't have to be ordered
			( a, b ) => a.data - b.data
		);

		if ( config.includeAllValue !== null && config.includeAllValue !== undefined ) {
			options.unshift( {
				data: config.includeAllValue,
				label: mw.msg( 'namespacesall' )
			} );
		}

		return options;
	};

}() );
