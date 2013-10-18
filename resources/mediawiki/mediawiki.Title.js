/*!
 * @author Neil Kandalgaonkar, 2010
 * @author Timo Tijhof, 2011-2013
 * @since 1.18
 */
( function ( mw, $ ) {

	/**
	 * @class mw.Title
	 *
	 * Parse titles into an object struture. Note that when using the constructor
	 * directly, passing invalid titles will result in an exception. Use #newFromText to use the
	 * logic directly and get null for invalid titles which is easier to work with.
	 *
	 * @constructor
	 * @param {string} title Title of the page. If no second argument given,
	 *  this will be searched for a namespace
	 * @param {number} [namespace=NS_MAIN] If given, will used as default namespace for the given title
	 * @throws {Error} When the title is invalid
	 */
	function Title( title, namespace ) {
		var parsed = parse( title, namespace );
		if ( !parsed ) {
			throw new Error( 'Unable to parse title' );
		}

		this.namespace = parsed.namespace;
		this.title = parsed.title;
		this.ext = parsed.ext;
		this.fragment = parsed.fragment;

		return this;
	}

	/* Private members */

	var

	/**
	 * @private
	 * @static
	 * @property NS_MAIN
	 */
	NS_MAIN = 0,

	/**
	 * @private
	 * @static
	 * @property NS_TALK
	 */
	NS_TALK = 1,

	/**
	 * @private
	 * @static
	 * @property NS_SPECIAL
	 */
	NS_SPECIAL = -1,

	/**
	 * Get the namespace id from a namespace name (either from the localized, canonical or alias
	 * name).
	 *
	 * Example: On a German wiki this would return 6 for any of 'File', 'Datei', 'Image' or
	 * even 'Bild'.
	 *
	 * @private
	 * @static
	 * @method getNsIdByName
	 * @param {string} ns Namespace name (case insensitive, leading/trailing space ignored)
	 * @return {number|boolean} Namespace id or boolean false
	 */
	getNsIdByName = function ( ns ) {
		var id;

		// Don't cast non-strings to strings, because null or undefined should not result in
		// returning the id of a potential namespace called "Null:" (e.g. on null.example.org/wiki)
		// Also, toLowerCase throws exception on null/undefined, because it is a String method.
		if ( typeof ns !== 'string' ) {
			return false;
		}
		ns = ns.toLowerCase();
		id = mw.config.get( 'wgNamespaceIds' )[ns];
		if ( id === undefined ) {
			return false;
		}
		return id;
	},

	rUnderscoreTrim = /^_+|_+$/g,

	rSplit = /^(.+?)_*:_*(.*)$/,

	// See Title.php#getTitleInvalidRegex
	rInvalid = new RegExp(
		'[^' + mw.config.get( 'wgLegalTitleChars' ) + ']' +
		// URL percent encoding sequences interfere with the ability
		// to round-trip titles -- you can't link to them consistently.
		'|%[0-9A-Fa-f]{2}' +
		// XML/HTML character references produce similar issues.
		'|&[A-Za-z0-9\u0080-\uFFFF]+;' +
		'|&#[0-9]+;' +
		'|&#x[0-9A-Fa-f]+;'
	),

	/**
	 * Internal helper for #constructor and #newFromtext.
	 *
	 * Based on Title.php#secureAndSplit
	 *
	 * @private
	 * @static
	 * @method parse
	 * @param {string} title
	 * @param {number} [defaultNamespace=NS_MAIN]
	 * @return {Object|boolean}
	 */
	parse = function ( title, defaultNamespace ) {
		var namespace, m, id, i, fragment, ext;

		namespace = defaultNamespace === undefined ? NS_MAIN : defaultNamespace;

		title = title
			// Normalise whitespace to underscores and remove duplicates
			.replace( /[ _\s]+/g, '_' )
			// Trim underscores
			.replace( rUnderscoreTrim, '' );

		if ( title === '' ) {
			return false;
		}

		// Process initial colon
		if ( title.charAt( 0 ) === ':' ) {
			// Initial colon means main namespace instead of specified default
			namespace = NS_MAIN;
			title = title
				// Strip colon
				.substr( 1 )
				// Trim underscores
				.replace( rUnderscoreTrim, '' );
		}

		// Process namespace prefix (if any)
		m = title.match( rSplit );
		if ( m ) {
			id = getNsIdByName( m[1] );
			if ( id !== false ) {
				// Ordinary namespace
				namespace = id;
				title = m[2];

				// For Talk:X pages, make sure X has no "namespace" prefix
				if ( namespace === NS_TALK && ( m = title.match( rSplit ) ) ) {
					// Disallow titles like Talk:File:x (subject should roundtrip: talk:file:x -> file:x -> file_talk:x)
					if ( getNsIdByName( m[1] ) !== false ) {
						return false;
					}
				}
			}
		}

		// Process fragment
		i = title.indexOf( '#' );
		if ( i === -1 ) {
			fragment = null;
		} else {
			fragment = title
				// Get segment starting after the hash
				.substr( i + 1 )
				// Convert to text
				// NB: Must not be trimmed ("Example#_foo" is not the same as "Example#foo")
				.replace( /_/g, ' ' );

			title = title
				// Strip hash
				.substr( 0, i )
				// Trim underscores, again (strips "_" from "bar" in "Foo_bar_#quux")
				.replace( rUnderscoreTrim, '' );
		}


		// Reject illegal characters
		if ( title.match( rInvalid ) ) {
			return false;
		}

		// Disallow titles that browsers or servers might resolve as directory navigation
		if (
			title.indexOf( '.' ) !== -1 && (
				title === '.' || title === '..' ||
				title.indexOf( './' ) === 0 ||
				title.indexOf( '../' ) === 0 ||
				title.indexOf( '/./' ) !== -1 ||
				title.indexOf( '/../' ) !== -1 ||
				title.substr( -2 ) === '/.' ||
				title.substr( -3 ) === '/..'
			)
		) {
			return false;
		}

		// Disallow magic tilde sequence
		if ( title.indexOf( '~~~' ) !== -1 ) {
			return false;
		}

		// Disallow titles exceeding the 255 byte size limit (size of underlying database field)
		// Except for special pages, e.g. [[Special:Block/Long name]]
		// Note: The PHP implementation also asserts that even in NS_SPECIAL, the title should
		// be less than 512 bytes.
		if ( namespace !== NS_SPECIAL && $.byteLength( title ) > 255 ) {
			return false;
		}

		// Can't make a link to a namespace alone.
		if ( title === '' && namespace !== NS_MAIN ) {
			return false;
		}

		// Any remaining initial :s are illegal.
		if ( title.charAt( 0 ) === ':' ) {
			return false;
		}

		// For backwards-compatibility with old mw.Title, we separate the extension from the
		// rest of the title.
		i = title.lastIndexOf( '.' );
		if ( i === -1 || title.length <= i + 1 ) {
			// Extensions are the non-empty segment after the last dot
			ext = null;
		} else {
			ext = title.substr( i + 1 );
			title = title.substr( 0, i );
		}

		return {
			namespace: namespace,
			title: title,
			ext: ext,
			fragment: fragment
		};
	},

	/**
	 * Convert db-key to readable text.
	 *
	 * @private
	 * @static
	 * @method text
	 * @param {string} s
	 * @return {string}
	 */
	text = function ( s ) {
		if ( s !== null && s !== undefined ) {
			return s.replace( /_/g, ' ' );
		} else {
			return '';
		}
	},

	// Polyfill for ES5 Object.create
	createObject = Object.create || ( function () {
		return function ( o ) {
			function Title() {}
			if ( o !== Object( o ) ) {
				throw new Error( 'Cannot inherit from a non-object' );
			}
			Title.prototype = o;
			return new Title();
		};
	}() );


	/* Static members */

	/**
	 * Constructor for Title objects with a null return instead of an exception for invalid titles.
	 *
	 * @static
	 * @method
	 * @param {string} title
	 * @param {number} [namespace=NS_MAIN] Default namespace
	 * @return {mw.Title|null} A valid Title object or null if the title is invalid
	 */
	Title.newFromText = function ( title, namespace ) {
		var t, parsed = parse( title, namespace );
		if ( !parsed ) {
			return null;
		}

		t = createObject( Title.prototype );
		t.namespace = parsed.namespace;
		t.title = parsed.title;
		t.ext = parsed.ext;
		t.fragment = parsed.fragment;

		return t;
	};

	/**
	 * Get the file title from an image element
	 *
	 *     var title = mw.Title.newFromImg( $( 'img:first' ) );
	 *
	 * @static
	 * @param {HTMLElement|jQuery} img The image to use as a base
	 * @return {mw.Title|null} The file title or null if unsuccessful
	 */
	Title.newFromImg = function ( img ) {
		var matches, i, regex, src, decodedSrc,

			// thumb.php-generated thumbnails
			thumbPhpRegex = /thumb\.php/,

			regexes = [
				// Thumbnails
				/\/[a-f0-9]\/[a-f0-9]{2}\/([^\s\/]+)\/[0-9]+px-\1[^\s\/]*$/,

				// Thumbnails in non-hashed upload directories
				/\/([^\s\/]+)\/[0-9]+px-\1[^\s\/]*$/,

				// Full size images
				/\/[a-f0-9]\/[a-f0-9]{2}\/([^\s\/]+)$/,

				// Full-size images in non-hashed upload directories
				/\/([^\s\/]+)$/
			],

			recount = regexes.length;

		src = img.jquery ? img[0].src : img.src;

		matches = src.match( thumbPhpRegex );

		if ( matches ) {
			return mw.Title.newFromText( 'File:' + mw.util.getParamValue( 'f', src ) );
		}

		decodedSrc = decodeURIComponent( src );

		for ( i = 0; i < recount; i++ ) {
			regex = regexes[i];
			matches = decodedSrc.match( regex );

			if ( matches && matches[1] ) {
				return mw.Title.newFromText( 'File:' + matches[1] );
			}
		}

		return null;
	};

	/**
	 * Whether this title exists on the wiki.
	 *
	 * @static
	 * @param {string|mw.Title} title prefixed db-key name (string) or instance of Title
	 * @return {boolean|null} Boolean if the information is available, otherwise null
	 */
	Title.exists = function ( title ) {
		var match,
			type = $.type( title ),
			obj = Title.exist.pages;

		if ( type === 'string' ) {
			match = obj[title];
		} else if ( type === 'object' && title instanceof Title ) {
			match = obj[title.toString()];
		} else {
			throw new Error( 'mw.Title.exists: title must be a string or an instance of Title' );
		}

		if ( typeof match === 'boolean' ) {
			return match;
		}

		return null;
	};

	Title.exist = {
		/**
		 * Boolean true value indicates page does exist.
		 *
		 * @static
		 * @property {Object} exist.pages Keyed by PrefixedDb title.
		 */
		pages: {},

		/**
		 * Example to declare existing titles:
		 *     Title.exist.set(['User:John_Doe', ...]);
		 * Eample to declare titles nonexistent:
		 *     Title.exist.set(['File:Foo_bar.jpg', ...], false);
		 *
		 * @static
		 * @property exist.set
		 * @param {string|Array} titles Title(s) in strict prefixedDb title form
		 * @param {boolean} [state=true] State of the given titles
		 * @return {boolean}
		 */
		set: function ( titles, state ) {
			titles = $.isArray( titles ) ? titles : [titles];
			state = state === undefined ? true : !!state;
			var pages = this.pages, i, len = titles.length;
			for ( i = 0; i < len; i++ ) {
				pages[ titles[i] ] = state;
			}
			return true;
		}
	};

	/* Public members */

	Title.prototype = {
		constructor: Title,

		/**
		 * Get the namespace number
		 *
		 * Example: 6 for "File:Example_image.svg".
		 *
		 * @return {number}
		 */
		getNamespaceId: function () {
			return this.namespace;
		},

		/**
		 * Get the namespace prefix (in the content language)
		 *
		 * Example: "File:" for "File:Example_image.svg".
		 * In #NS_MAIN this is '', otherwise namespace name plus ':'
		 *
		 * @return {string}
		 */
		getNamespacePrefix: function () {
			return this.namespace === NS_MAIN ?
				'' :
				( mw.config.get( 'wgFormattedNamespaces' )[ this.namespace ].replace( / /g, '_' ) + ':' );
		},

		/**
		 * Get the page name without extension or namespace prefix
		 *
		 * Example: "Example_image" for "File:Example_image.svg".
		 *
		 * For the page title (full page name without namespace prefix), see #getMain.
		 *
		 * @return {string}
		 */
		getName: function () {
			if ( $.inArray( this.namespace, mw.config.get( 'wgCaseSensitiveNamespaces' ) ) !== -1 ) {
				return this.title;
			} else {
				return $.ucFirst( this.title );
			}
		},

		/**
		 * Get the page name (transformed by #text)
		 *
		 * Example: "Example image" for "File:Example_image.svg".
		 *
		 * For the page title (full page name without namespace prefix), see #getMainText.
		 *
		 * @return {string}
		 */
		getNameText: function () {
			return text( this.getName() );
		},

		/**
		 * Get the extension of the page name (if any)
		 *
		 * @return {string|null} Name extension or null if there is none
		 */
		getExtension: function () {
			return this.ext;
		},

		/**
		 * Shortcut for appendable string to form the main page name.
		 *
		 * Returns a string like ".json", or "" if no extension.
		 *
		 * @return {string}
		 */
		getDotExtension: function () {
			return this.ext === null ? '' : '.' + this.ext;
		},

		/**
		 * Get the main page name (transformed by #text)
		 *
		 * Example: "Example_image.svg" for "File:Example_image.svg".
		 *
		 * @return {string}
		 */
		getMain: function () {
			return this.getName() + this.getDotExtension();
		},

		/**
		 * Get the main page name (transformed by #text)
		 *
		 * Example: "Example image.svg" for "File:Example_image.svg".
		 *
		 * @return {string}
		 */
		getMainText: function () {
			return text( this.getMain() );
		},

		/**
		 * Get the full page name
		 *
		 * Eaxample: "File:Example_image.svg".
		 * Most useful for API calls, anything that must identify the "title".
		 *
		 * @return {string}
		 */
		getPrefixedDb: function () {
			return this.getNamespacePrefix() + this.getMain();
		},

		/**
		 * Get the full page name (transformed by #text)
		 *
		 * Example: "File:Example image.svg" for "File:Example_image.svg".
		 *
		 * @return {string}
		 */
		getPrefixedText: function () {
			return text( this.getPrefixedDb() );
		},

		/**
		 * Get the fragment (if any).
		 *
		 * Note that this method (by design) does not include the hash character and
		 * the value is not url encoded.
		 *
		 * @return {string|null}
		 */
		getFragment: function () {
			return this.fragment;
		},

		/**
		 * Get the URL to this title
		 *
		 * @see mw.util#getUrl
		 * @return {string}
		 */
		getUrl: function () {
			return mw.util.getUrl( this.toString() );
		},

		/**
		 * Whether this title exists on the wiki.
		 *
		 * @see #static-method-exists
		 * @return {boolean|null} Boolean if the information is available, otherwise null
		 */
		exists: function () {
			return Title.exists( this );
		}
	};

	/**
	 * @alias #getPrefixedDb
	 * @method
	 */
	Title.prototype.toString = Title.prototype.getPrefixedDb;


	/**
	 * @alias #getPrefixedText
	 * @method
	 */
	Title.prototype.toText = Title.prototype.getPrefixedText;

	// Expose
	mw.Title = Title;

}( mediaWiki, jQuery ) );
