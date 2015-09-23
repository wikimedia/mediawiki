/**
 * @class
 * @extends {OO.ui.Element}
 *
 * @constructor
 */
OO.ui.Demo = function OoUiDemo() {
	// Parent
	OO.ui.Demo.parent.call( this );

	// Normalization
	this.normalizeHash();

	// Properties
	this.mode = this.getCurrentMode();
	this.$menu = $( '<div>' );
	this.pageDropdown = new OO.ui.DropdownWidget( {
		menu: {
			items: [
				new OO.ui.MenuOptionWidget( { data: 'dialogs', label: 'Dialogs' } ),
				new OO.ui.MenuOptionWidget( { data: 'icons', label: 'Icons' } ),
				new OO.ui.MenuOptionWidget( { data: 'toolbars', label: 'Toolbars' } ),
				new OO.ui.MenuOptionWidget( { data: 'widgets', label: 'Widgets' } )
			]
		},
		classes: [ 'oo-ui-demo-pageDropdown' ]
	} );
	this.pageMenu = this.pageDropdown.getMenu();
	this.themeSelect = new OO.ui.ButtonSelectWidget().addItems( [
		new OO.ui.ButtonOptionWidget( { data: 'mediawiki', label: 'MediaWiki' } ),
		new OO.ui.ButtonOptionWidget( { data: 'apex', label: 'Apex' } )
	] );
	this.graphicsSelect = new OO.ui.ButtonSelectWidget().addItems( [
		new OO.ui.ButtonOptionWidget( { data: 'mixed', label: 'Mixed' } ),
		new OO.ui.ButtonOptionWidget( { data: 'vector', label: 'Vector' } ),
		new OO.ui.ButtonOptionWidget( { data: 'raster', label: 'Raster' } )
	] );
	this.directionSelect = new OO.ui.ButtonSelectWidget().addItems( [
		new OO.ui.ButtonOptionWidget( { data: 'ltr', label: 'LTR' } ),
		new OO.ui.ButtonOptionWidget( { data: 'rtl', label: 'RTL' } )
	] );
	this.jsPhpSelect = new OO.ui.ButtonGroupWidget().addItems( [
		new OO.ui.ButtonWidget( { label: 'JS' } ).setActive( true ),
		new OO.ui.ButtonWidget( {
			label: 'PHP',
			href: 'widgets.php' +
				'?theme=' + this.mode.theme +
				'&graphic=' + this.mode.graphics +
				'&direction=' + this.mode.direction
		} )
	] );

	// Events
	this.pageMenu.on( 'choose', OO.ui.bind( this.onModeChange, this ) );
	this.themeSelect.on( 'choose', OO.ui.bind( this.onModeChange, this ) );
	this.graphicsSelect.on( 'choose', OO.ui.bind( this.onModeChange, this ) );
	this.directionSelect.on( 'choose', OO.ui.bind( this.onModeChange, this ) );

	// Initialization
	this.pageMenu.selectItemByData( this.mode.page );
	this.themeSelect.selectItemByData( this.mode.theme );
	this.graphicsSelect.selectItemByData( this.mode.graphics );
	this.directionSelect.selectItemByData( this.mode.direction );
	this.$menu
		.addClass( 'oo-ui-demo-menu' )
		.append(
			this.pageDropdown.$element,
			this.themeSelect.$element,
			this.graphicsSelect.$element,
			this.directionSelect.$element,
			this.jsPhpSelect.$element
		);
	this.$element
		.addClass( 'oo-ui-demo' )
		.append( this.$menu );
	$( 'body' ).addClass( 'oo-ui-' + this.mode.direction );
	// Correctly apply direction to the <html> tags as well
	$( 'html' ).attr( 'dir', this.mode.direction );
	this.stylesheetLinks = this.addStylesheetLinks( $( 'head' ) );
	OO.ui.theme = new ( this.constructor.static.themes[ this.mode.theme ].theme )();
};

/* Setup */

OO.inheritClass( OO.ui.Demo, OO.ui.Element );

/* Static Properties */

/**
 * Available pages.
 *
 * Populated by each of the page scripts in the `pages` directory.
 *
 * @static
 * @property {Object.<string,Function>} pages List of functions that render a page, keyed by
 *   symbolic page name
 */
OO.ui.Demo.static.pages = {};

/**
 * Available themes.
 *
 * List of theme descriptions, each containing a `fileSuffix` property used for linking to the
 * correct stylesheet file and a `theme` property containing a theme class
 *
 * @static
 * @property {Object.<string,Object>}
 */
OO.ui.Demo.static.themes = {
	mediawiki: {
		fileSuffix: '-mediawiki',
		additionalSuffixes: [
			'-icons-movement',
			'-icons-content',
			'-icons-alerts',
			'-icons-interactions',
			'-icons-moderation',
			'-icons-editing-core',
			'-icons-editing-styling',
			'-icons-editing-list',
			'-icons-editing-advanced',
			'-icons-media',
			'-icons-location',
			'-icons-user',
			'-icons-layout',
			'-icons-accessibility',
			'-icons-wikimedia'
		],
		theme: OO.ui.MediaWikiTheme
	},
	apex: {
		fileSuffix: '-apex',
		additionalSuffixes: [
			'-icons-movement',
			'-icons-moderation',
			'-icons-editing-core',
			'-icons-editing-styling',
			'-icons-editing-list',
			'-icons-editing-advanced'
		],
		theme: OO.ui.ApexTheme
	}
};

/**
 * Available graphics formats.
 *
 * List of graphics format descriptions, each containing a `fileSuffix` property used for linking
 * to the correct stylesheet file.
 *
 * @static
 * @property {Object.<string,Object>}
 */
OO.ui.Demo.static.graphics = {
	mixed: { fileSuffix: '' },
	vector: { fileSuffix: '.vector' },
	raster: { fileSuffix: '.raster' }
};

/**
 * Available text directions.
 *
 * List of text direction descriptions, each containing a `fileSuffix` property used for linking to
 * the correct stylesheet file.
 *
 * @static
 * @property {Object.<string,Object>}
 */
OO.ui.Demo.static.directions = {
	ltr: { fileSuffix: '' },
	rtl: { fileSuffix: '.rtl' }
};

/**
 * Default page.
 *
 * Set by one of the page scripts in the `pages` directory.
 *
 * @static
 * @property {string|null}
 */
OO.ui.Demo.static.defaultPage = null;

/**
 * Default page.
 *
 * Set by one of the page scripts in the `pages` directory.
 *
 * @static
 * @property {string}
 */
OO.ui.Demo.static.defaultTheme = 'mediawiki';

/**
 * Default page.
 *
 * Set by one of the page scripts in the `pages` directory.
 *
 * @static
 * @property {string}
 */
OO.ui.Demo.static.defaultGraphics = 'mixed';

/**
 * Default page.
 *
 * Set by one of the page scripts in the `pages` directory.
 *
 * @static
 * @property {string}
 */
OO.ui.Demo.static.defaultDirection = 'ltr';

/* Methods */

/**
 * Load the demo page. Must be called after $element is attached.
 */
OO.ui.Demo.prototype.initialize = function () {
	var demo = this,
		promises = this.stylesheetLinks.map( function ( el ) {
			return $( el ).data( 'load-promise' );
		} );
	$.when.apply( $, promises )
		.done( function () {
			demo.constructor.static.pages[ demo.mode.page ]( demo );
		} )
		.fail( function () {
			demo.$element.append( $( '<p>' ).text( 'Demo styles failed to load.' ) );
		} );
};

/**
 * Handle mode change events.
 *
 * Will load a new page.
 */
OO.ui.Demo.prototype.onModeChange = function () {
	var page = this.pageMenu.getSelectedItem().getData(),
		theme = this.themeSelect.getSelectedItem().getData(),
		direction = this.directionSelect.getSelectedItem().getData(),
		graphics = this.graphicsSelect.getSelectedItem().getData();

	location.hash = '#' + [ page, theme, graphics, direction ].join( '-' );
};

/**
 * Get a list of mode factors.
 *
 * Factors are a mapping between symbolic names used in the URL hash and internal information used
 * to act on those symbolic names.
 *
 * Factor lists are in URL order: page, theme, graphics, direction. Page contains the symbolic
 * page name, others contain file suffixes.
 *
 * @return {Object[]} List of mode factors, keyed by symbolic name
 */
OO.ui.Demo.prototype.getFactors = function () {
	var key,
		factors = [ {}, {}, {}, {} ];

	for ( key in this.constructor.static.pages ) {
		factors[ 0 ][ key ] = key;
	}
	for ( key in this.constructor.static.themes ) {
		factors[ 1 ][ key ] = this.constructor.static.themes[ key ].fileSuffix;
	}
	for ( key in this.constructor.static.graphics ) {
		factors[ 2 ][ key ] = this.constructor.static.graphics[ key ].fileSuffix;
	}
	for ( key in this.constructor.static.directions ) {
		factors[ 3 ][ key ] = this.constructor.static.directions[ key ].fileSuffix;
	}

	return factors;
};

/**
 * Get a list of default factors.
 *
 * Factor defaults are in URL order: page, theme, graphics, direction. Each contains a symbolic
 * factor name which should be used as a fallback when the URL hash is missing or invalid.
 *
 * @return {Object[]} List of default factors
 */
OO.ui.Demo.prototype.getDefaultFactorValues = function () {
	return [
		this.constructor.static.defaultPage,
		this.constructor.static.defaultTheme,
		this.constructor.static.defaultGraphics,
		this.constructor.static.defaultDirection
	];
};

/**
 * Parse the current URL hash into factor values.
 *
 * @return {string[]} Factor values in URL order: page, theme, graphics, direction
 */
OO.ui.Demo.prototype.getCurrentFactorValues = function () {
	return location.hash.slice( 1 ).split( '-' );
};

/**
 * Get the current mode.
 *
 * Generated from parsed URL hash values.
 *
 * @return {Object} List of factor values keyed by factor name
 */
OO.ui.Demo.prototype.getCurrentMode = function () {
	var factorValues = this.getCurrentFactorValues();

	return {
		page: factorValues[ 0 ],
		theme: factorValues[ 1 ],
		graphics: factorValues[ 2 ],
		direction: factorValues[ 3 ]
	};
};

/**
 * Get and insert link elements for the current mode.
 *
 * @param {jQuery} $where Node to insert the links into
 * @return {HTMLElement[]} List of link elements
 */
OO.ui.Demo.prototype.addStylesheetLinks = function ( $where ) {
	var i, len, links, fragments,
		factors = this.getFactors(),
		theme = this.getCurrentFactorValues()[ 1 ],
		suffixes = this.constructor.static.themes[ theme ].additionalSuffixes || [],
		urls = [];

	// Translate modes to filename fragments
	fragments = this.getCurrentFactorValues().map( function ( val, index ) {
		return factors[ index ][ val ];
	} );

	// Theme styles
	urls.push( 'dist/oojs-ui' + fragments.slice( 1 ).join( '' ) + '.css' );
	for ( i = 0, len = suffixes.length; i < len; i++ ) {
		urls.push( 'dist/oojs-ui' + fragments[ 1 ] + suffixes[ i ] + fragments.slice( 2 ).join( '' ) + '.css' );
	}

	// Demo styles
	urls.push( 'styles/demo' + fragments[ 3 ] + '.css' );

	// Add link tags
	links = urls.map( function ( url ) {
		var
			link = document.createElement( 'link' ),
			$link = $( link ),
			deferred = $.Deferred();
		$link.data( 'load-promise', deferred.promise() );
		$link.on( {
			load: deferred.resolve,
			error: deferred.reject
		} );
		// Insert into DOM before setting 'href' for IE 8 compatibility
		$where.append( $link );
		link.rel = 'stylesheet';
		link.href = url;
		return link;
	} );

	return links;
};

/**
 * Normalize the URL hash.
 */
OO.ui.Demo.prototype.normalizeHash = function () {
	var i, len, factorValues,
		modes = [],
		factors = this.getFactors(),
		defaults = this.getDefaultFactorValues();

	factorValues = this.getCurrentFactorValues();
	for ( i = 0, len = factors.length; i < len; i++ ) {
		modes[ i ] = factors[ i ][ factorValues[ i ] ] !== undefined ? factorValues[ i ] : defaults[ i ];
	}

	// Update hash
	location.hash = modes.join( '-' );
};

/**
 * Destroy demo.
 */
OO.ui.Demo.prototype.destroy = function () {
	$( 'body' ).removeClass( 'oo-ui-ltr oo-ui-rtl' );
	$( this.stylesheetLinks ).remove();
	this.$element.remove();
};

/**
 * Build a console for interacting with an element.
 *
 * @param {OO.ui.Layout} item
 * @param {string} layout Variable name for layout
 * @param {string} widget Variable name for layout's field widget
 * @return {jQuery} Console interface element
 */
OO.ui.Demo.prototype.buildConsole = function ( item, layout, widget ) {
	var $toggle, $log, $label, $input, $submit, $console, $form,
		console = window.console;

	function exec( str ) {
		var func, ret;
		/*jshint evil:true */
		if ( str.indexOf( 'return' ) !== 0 ) {
			str = 'return ' + str;
		}
		try {
			func = new Function( layout, widget, 'item', str );
			ret = { value: func( item, item.fieldWidget, item.fieldWidget ) };
		} catch ( error ) {
			ret = {
				value: undefined,
				error: error
			};
		}
		return ret;
	}

	function submit() {
		var val, result, logval;

		val = $input.val();
		$input.val( '' );
		$input[ 0 ].focus();
		result = exec( val );

		logval = String( result.value );
		if ( logval === '' ) {
			logval = '""';
		}

		$log.append(
			$( '<div>' )
				.addClass( 'oo-ui-demo-console-log-line oo-ui-demo-console-log-line-input' )
				.text( val ),
			$( '<div>' )
				.addClass( 'oo-ui-demo-console-log-line oo-ui-demo-console-log-line-return' )
				.text( logval || result.value )
		);

		if ( result.error ) {
			$log.append( $( '<div>' ).addClass( 'oo-ui-demo-console-log-line oo-ui-demo-console-log-line-error' ).text( result.error ) );
		}

		if ( console && console.log ) {
			console.log( '[demo]', result.value );
			if ( result.error ) {
				if ( console.error ) {
					console.error( '[demo]', String( result.error ), result.error );
				} else {
					console.log( '[demo] Error: ', result.error );
				}
			}
		}

		// Scrol to end
		$log.prop( 'scrollTop', $log.prop( 'scrollHeight' ) );
	}

	$toggle = $( '<span>' )
		.addClass( 'oo-ui-demo-console-toggle' )
		.attr( 'title', 'Toggle console' )
		.on( 'click', function ( e ) {
			e.preventDefault();
			$console.toggleClass( 'oo-ui-demo-console-collapsed oo-ui-demo-console-expanded' );
			if ( $input.is( ':visible' ) ) {
				$input[ 0 ].focus();
				if ( console && console.log ) {
					window[ layout ] = item;
					window[ widget ] = item.fieldWidget;
					console.log( '[demo]', 'Globals ' + layout + ', ' + widget + ' have been set' );
					console.log( '[demo]', item );
				}
			}
		} );

	$log = $( '<div>' )
		.addClass( 'oo-ui-demo-console-log' );

	$label = $( '<label>' )
		.addClass( 'oo-ui-demo-console-label' );

	$input = $( '<input>' )
		.addClass( 'oo-ui-demo-console-input' )
		.prop( 'placeholder', '... (predefined: ' + layout + ', ' + widget + ')' );

	$submit = $( '<div>' )
		.addClass( 'oo-ui-demo-console-submit' )
		.text( '↵' )
		.on( 'click', submit );

	$form = $( '<form>' ).on( 'submit', function ( e ) {
		e.preventDefault();
		submit();
	} );

	$console = $( '<div>' )
		.addClass( 'oo-ui-demo-console oo-ui-demo-console-collapsed' )
		.append(
			$toggle,
			$log,
			$form.append(
				$label.append(
					$input
				),
				$submit
			)
		);

	return $console;
};
