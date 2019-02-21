/*!
 * MediaWiki Widgets - NamespaceInputWidget class.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function () {

	/**
	 * Namespace input widget. Displays a dropdown box with the choice of available namespaces.
	 *
	 * @class
	 * @extends OO.ui.DropdownInputWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration options
	 * @cfg {string|null} [includeAllValue] Value for "all namespaces" option, if any
	 * @cfg {number[]} [exclude] List of namespace numbers to exclude from the selector
	 */
	mw.widgets.NamespaceInputWidget = function MwWidgetsNamespaceInputWidget( config ) {
		// Configuration initialization
		config = $.extend( {}, config, { options: this.constructor.static.getNamespaceDropdownOptions( config ) } );

		// Parent constructor
		mw.widgets.NamespaceInputWidget.parent.call( this, config );

		// Initialization
		this.$element.addClass( 'mw-widget-namespaceInputWidget' );
	};

	/* Setup */

	OO.inheritClass( mw.widgets.NamespaceInputWidget, OO.ui.DropdownInputWidget );

	/* Static methods */

	/**
	 * Get a list of namespace options, sorted by ID.
	 *
	 * @param {Object} [config] Configuration options
	 * @return {Object[]} Dropdown options
	 */
	mw.widgets.NamespaceInputWidget.static.getNamespaceDropdownOptions = function ( config ) {
		var options,
			exclude = config.exclude || [],
			mainNamespace = mw.config.get( 'wgNamespaceIds' )[ '' ];

		// eslint-disable-next-line no-jquery/no-map-util
		options = $.map( mw.config.get( 'wgFormattedNamespaces' ), function ( name, ns ) {
			if ( ns < mainNamespace || exclude.indexOf( Number( ns ) ) !== -1 ) {
				return null; // skip
			}
			ns = String( ns );
			if ( ns === String( mainNamespace ) ) {
				name = mw.message( 'blanknamespace' ).text();
			}
			return { data: ns, label: name };
		} ).sort( function ( a, b ) {
			// wgFormattedNamespaces is an object, and so technically doesn't have to be ordered
			return a.data - b.data;
		} );

		if ( config.includeAllValue !== null && config.includeAllValue !== undefined ) {
			options.unshift( {
				data: config.includeAllValue,
				label: mw.message( 'namespacesall' ).text()
			} );
		}

		return options;
	};

}() );
