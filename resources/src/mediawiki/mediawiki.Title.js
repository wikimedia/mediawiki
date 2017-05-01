/*!
 * @author Neil Kandalgaonkar, 2010
 * @author Timo Tijhof, 2011-2013
 * @since 1.18
 */

/* eslint-disable no-use-before-define */

( function ( mw, $ ) {
	/**
	 * Parse titles into an object structure. Note that when using the constructor
	 * directly, passing invalid titles will result in an exception. Use #newFromText to use the
	 * logic directly and get null for invalid titles which is easier to work with.
	 *
	 * Note that in the constructor and #newFromText method, `namespace` is the **default** namespace
	 * only, and can be overridden by a namespace prefix in `title`. If you do not want this behavior,
	 * use #makeTitle. Compare:
	 *
	 *     new mw.Title( 'Foo', NS_TEMPLATE ).getPrefixedText();                  // => 'Template:Foo'
	 *     mw.Title.newFromText( 'Foo', NS_TEMPLATE ).getPrefixedText();          // => 'Template:Foo'
	 *     mw.Title.makeTitle( NS_TEMPLATE, 'Foo' ).getPrefixedText();            // => 'Template:Foo'
	 *
	 *     new mw.Title( 'Category:Foo', NS_TEMPLATE ).getPrefixedText();         // => 'Category:Foo'
	 *     mw.Title.newFromText( 'Category:Foo', NS_TEMPLATE ).getPrefixedText(); // => 'Category:Foo'
	 *     mw.Title.makeTitle( NS_TEMPLATE, 'Category:Foo' ).getPrefixedText();   // => 'Template:Category:Foo'
	 *
	 *     new mw.Title( 'Template:Foo', NS_TEMPLATE ).getPrefixedText();         // => 'Template:Foo'
	 *     mw.Title.newFromText( 'Template:Foo', NS_TEMPLATE ).getPrefixedText(); // => 'Template:Foo'
	 *     mw.Title.makeTitle( NS_TEMPLATE, 'Template:Foo' ).getPrefixedText();   // => 'Template:Template:Foo'
	 *
	 * @class mw.Title
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
	}

	/* Private members */

	// eslint-disable-next-line vars-on-top
	var
		namespaceIds = mw.config.get( 'wgNamespaceIds' ),

		/**
		 * @private
		 * @static
		 * @property NS_MAIN
		 */
		NS_MAIN = namespaceIds[ '' ],

		/**
		 * @private
		 * @static
		 * @property NS_TALK
		 */
		NS_TALK = namespaceIds.talk,

		/**
		 * @private
		 * @static
		 * @property NS_SPECIAL
		 */
		NS_SPECIAL = namespaceIds.special,

		/**
		 * @private
		 * @static
		 * @property NS_MEDIA
		 */
		NS_MEDIA = namespaceIds.media,

		/**
		 * @private
		 * @static
		 * @property NS_FILE
		 */
		NS_FILE = namespaceIds.file,

		/**
		 * @private
		 * @static
		 * @property FILENAME_MAX_BYTES
		 */
		FILENAME_MAX_BYTES = 240,

		/**
		 * @private
		 * @static
		 * @property TITLE_MAX_BYTES
		 */
		TITLE_MAX_BYTES = 255,

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
			// TODO: Should just use local var namespaceIds here but it
			// breaks test which modify the config
			id = mw.config.get( 'wgNamespaceIds' )[ ns.toLowerCase() ];
			if ( id === undefined ) {
				return false;
			}
			return id;
		},

		/**
		 * @private
		 * @method getNamespacePrefix_
		 * @param {number} namespace
		 * @return {string}
		 */
		getNamespacePrefix = function ( namespace ) {
			return namespace === NS_MAIN ?
				'' :
				( mw.config.get( 'wgFormattedNamespaces' )[ namespace ].replace( / /g, '_' ) + ':' );
		},

		rUnderscoreTrim = /^_+|_+$/g,

		rSplit = /^(.+?)_*:_*(.*)$/,

		// See MediaWikiTitleCodec.php#getTitleInvalidRegex
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

		// From MediaWikiTitleCodec::splitTitleString() in PHP
		// Note that this is not equivalent to /\s/, e.g. underscore is included, tab is not included.
		rWhitespace = /[ _\u00A0\u1680\u180E\u2000-\u200A\u2028\u2029\u202F\u205F\u3000]+/g,

		// From MediaWikiTitleCodec::splitTitleString() in PHP
		rUnicodeBidi = /[\u200E\u200F\u202A-\u202E]/g,

		/**
		 * Slightly modified from Flinfo. Credit goes to Lupo and Flominator.
		 * @private
		 * @static
		 * @property sanitationRules
		 */
		sanitationRules = [
			// "signature"
			{
				pattern: /~{3}/g,
				replace: '',
				generalRule: true
			},
			// control characters
			{
				// eslint-disable-next-line no-control-regex
				pattern: /[\x00-\x1f\x7f]/g,
				replace: '',
				generalRule: true
			},
			// URL encoding (possibly)
			{
				pattern: /%([0-9A-Fa-f]{2})/g,
				replace: '% $1',
				generalRule: true
			},
			// HTML-character-entities
			{
				pattern: /&(([A-Za-z0-9\x80-\xff]+|#[0-9]+|#x[0-9A-Fa-f]+);)/g,
				replace: '& $1',
				generalRule: true
			},
			// slash, colon (not supported by file systems like NTFS/Windows, Mac OS 9 [:], ext4 [/])
			{
				pattern: new RegExp( '[' + mw.config.get( 'wgIllegalFileChars', '' ) + ']', 'g' ),
				replace: '-',
				fileRule: true
			},
			// brackets, greater than
			{
				pattern: /[\]\}>]/g,
				replace: ')',
				generalRule: true
			},
			// brackets, lower than
			{
				pattern: /[\[\{<]/g,
				replace: '(',
				generalRule: true
			},
			// everything that wasn't covered yet
			{
				pattern: new RegExp( rInvalid.source, 'g' ),
				replace: '-',
				generalRule: true
			},
			// directory structures
			{
				pattern: /^(\.|\.\.|\.\/.*|\.\.\/.*|.*\/\.\/.*|.*\/\.\.\/.*|.*\/\.|.*\/\.\.)$/g,
				replace: '',
				generalRule: true
			}
		],

		/**
		 * Internal helper for #constructor and #newFromText.
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
				// Strip Unicode bidi override characters
				.replace( rUnicodeBidi, '' )
				// Normalise whitespace to underscores and remove duplicates
				.replace( rWhitespace, '_' )
				// Trim underscores
				.replace( rUnderscoreTrim, '' );

			// Process initial colon
			if ( title !== '' && title[ 0 ] === ':' ) {
				// Initial colon means main namespace instead of specified default
				namespace = NS_MAIN;
				title = title
					// Strip colon
					.slice( 1 )
					// Trim underscores
					.replace( rUnderscoreTrim, '' );
			}

			if ( title === '' ) {
				return false;
			}

			// Process namespace prefix (if any)
			m = title.match( rSplit );
			if ( m ) {
				id = getNsIdByName( m[ 1 ] );
				if ( id !== false ) {
					// Ordinary namespace
					namespace = id;
					title = m[ 2 ];

					// For Talk:X pages, make sure X has no "namespace" prefix
					if ( namespace === NS_TALK && ( m = title.match( rSplit ) ) ) {
						// Disallow titles like Talk:File:x (subject should roundtrip: talk:file:x -> file:x -> file_talk:x)
						if ( getNsIdByName( m[ 1 ] ) !== false ) {
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
					.slice( i + 1 )
					// Convert to text
					// NB: Must not be trimmed ("Example#_foo" is not the same as "Example#foo")
					.replace( /_/g, ' ' );

				title = title
					// Strip hash
					.slice( 0, i )
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
					title.slice( -2 ) === '/.' ||
					title.slice( -3 ) === '/..'
				)
			) {
				return false;
			}

			// Disallow magic tilde sequence
			if ( title.indexOf( '~~~' ) !== -1 ) {
				return false;
			}

			// Disallow titles exceeding the TITLE_MAX_BYTES byte size limit (size of underlying database field)
			// Except for special pages, e.g. [[Special:Block/Long name]]
			// Note: The PHP implementation also asserts that even in NS_SPECIAL, the title should
			// be less than 512 bytes.
			if ( namespace !== NS_SPECIAL && $.byteLength( title ) > TITLE_MAX_BYTES ) {
				return false;
			}

			// Can't make a link to a namespace alone.
			if ( title === '' && namespace !== NS_MAIN ) {
				return false;
			}

			// Any remaining initial :s are illegal.
			if ( title[ 0 ] === ':' ) {
				return false;
			}

			// For backwards-compatibility with old mw.Title, we separate the extension from the
			// rest of the title.
			i = title.lastIndexOf( '.' );
			if ( i === -1 || title.length <= i + 1 ) {
				// Extensions are the non-empty segment after the last dot
				ext = null;
			} else {
				ext = title.slice( i + 1 );
				title = title.slice( 0, i );
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

		/**
		 * Sanitizes a string based on a rule set and a filter
		 *
		 * @private
		 * @static
		 * @method sanitize
		 * @param {string} s
		 * @param {Array} filter
		 * @return {string}
		 */
		sanitize = function ( s, filter ) {
			var i, ruleLength, rule, m, filterLength,
				rules = sanitationRules;

			for ( i = 0, ruleLength = rules.length; i < ruleLength; ++i ) {
				rule = rules[ i ];
				for ( m = 0, filterLength = filter.length; m < filterLength; ++m ) {
					if ( rule[ filter[ m ] ] ) {
						s = s.replace( rule.pattern, rule.replace );
					}
				}
			}
			return s;
		},

		/**
		 * Cuts a string to a specific byte length, assuming UTF-8
		 * or less, if the last character is a multi-byte one
		 *
		 * @private
		 * @static
		 * @method trimToByteLength
		 * @param {string} s
		 * @param {number} length
		 * @return {string}
		 */
		trimToByteLength = function ( s, length ) {
			var byteLength, chopOffChars, chopOffBytes;

			// bytelength is always greater or equal to the length in characters
			s = s.substr( 0, length );
			while ( ( byteLength = $.byteLength( s ) ) > length ) {
				// Calculate how many characters can be safely removed
				// First, we need to know how many bytes the string exceeds the threshold
				chopOffBytes = byteLength - length;
				// A character in UTF-8 is at most 4 bytes
				// One character must be removed in any case because the
				// string is too long
				chopOffChars = Math.max( 1, Math.floor( chopOffBytes / 4 ) );
				s = s.substr( 0, s.length - chopOffChars );
			}
			return s;
		},

		/**
		 * Cuts a file name to a specific byte length
		 *
		 * @private
		 * @static
		 * @method trimFileNameToByteLength
		 * @param {string} name without extension
		 * @param {string} extension file extension
		 * @return {string} The full name, including extension
		 */
		trimFileNameToByteLength = function ( name, extension ) {
			// There is a special byte limit for file names and ... remember the dot
			return trimToByteLength( name, FILENAME_MAX_BYTES - extension.length - 1 ) + '.' + extension;
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
	 * Note that `namespace` is the **default** namespace only, and can be overridden by a namespace
	 * prefix in `title`. If you do not want this behavior, use #makeTitle. See #constructor for
	 * details.
	 *
	 * @static
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
	 * Constructor for Title objects with predefined namespace.
	 *
	 * Unlike #newFromText or #constructor, this function doesn't allow the given `namespace` to be
	 * overridden by a namespace prefix in `title`. See #constructor for details about this behavior.
	 *
	 * The single exception to this is when `namespace` is 0, indicating the main namespace. The
	 * function behaves like #newFromText in that case.
	 *
	 * @static
	 * @param {number} namespace Namespace to use for the title
	 * @param {string} title
	 * @return {mw.Title|null} A valid Title object or null if the title is invalid
	 */
	Title.makeTitle = function ( namespace, title ) {
		return mw.Title.newFromText( getNamespacePrefix( namespace ) + title );
	};

	/**
	 * Constructor for Title objects from user input altering that input to
	 * produce a title that MediaWiki will accept as legal
	 *
	 * @static
	 * @param {string} title
	 * @param {number} [defaultNamespace=NS_MAIN]
	 *  If given, will used as default namespace for the given title.
	 * @param {Object} [options] additional options
	 * @param {boolean} [options.forUploading=true]
	 *  Makes sure that a file is uploadable under the title returned.
	 *  There are pages in the file namespace under which file upload is impossible.
	 *  Automatically assumed if the title is created in the Media namespace.
	 * @return {mw.Title|null} A valid Title object or null if the input cannot be turned into a valid title
	 */
	Title.newFromUserInput = function ( title, defaultNamespace, options ) {
		var namespace, m, id, ext, parts;

		// defaultNamespace is optional; check whether options moves up
		if ( arguments.length < 3 && $.type( defaultNamespace ) === 'object' ) {
			options = defaultNamespace;
			defaultNamespace = undefined;
		}

		// merge options into defaults
		options = $.extend( {
			forUploading: true
		}, options );

		namespace = defaultNamespace === undefined ? NS_MAIN : defaultNamespace;

		// Normalise additional whitespace
		title = $.trim( title.replace( /\s/g, ' ' ) );

		// Process initial colon
		if ( title !== '' && title[ 0 ] === ':' ) {
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
			id = getNsIdByName( m[ 1 ] );
			if ( id !== false ) {
				// Ordinary namespace
				namespace = id;
				title = m[ 2 ];
			}
		}

		if (
			namespace === NS_MEDIA ||
			( options.forUploading && ( namespace === NS_FILE ) )
		) {

			title = sanitize( title, [ 'generalRule', 'fileRule' ] );

			// Operate on the file extension
			// Although it is possible having spaces between the name and the ".ext" this isn't nice for
			// operating systems hiding file extensions -> strip them later on
			parts = title.split( '.' );

			if ( parts.length > 1 ) {

				// Get the last part, which is supposed to be the file extension
				ext = parts.pop();

				// Remove whitespace of the name part (that W/O extension)
				title = $.trim( parts.join( '.' ) );

				// Cut, if too long and append file extension
				title = trimFileNameToByteLength( title, ext );

			} else {

				// Missing file extension
				title = $.trim( parts.join( '.' ) );

				// Name has no file extension and a fallback wasn't provided either
				return null;
			}
		} else {

			title = sanitize( title, [ 'generalRule' ] );

			// Cut titles exceeding the TITLE_MAX_BYTES byte size limit
			// (size of underlying database field)
			if ( namespace !== NS_SPECIAL ) {
				title = trimToByteLength( title, TITLE_MAX_BYTES );
			}
		}

		// Any remaining initial :s are illegal.
		title = title.replace( /^\:+/, '' );

		return Title.newFromText( title, namespace );
	};

	/**
	 * Sanitizes a file name as supplied by the user, originating in the user's file system
	 * so it is most likely a valid MediaWiki title and file name after processing.
	 * Returns null on fatal errors.
	 *
	 * @static
	 * @param {string} uncleanName The unclean file name including file extension but
	 *   without namespace
	 * @return {mw.Title|null} A valid Title object or null if the title is invalid
	 */
	Title.newFromFileName = function ( uncleanName ) {

		return Title.newFromUserInput( 'File:' + uncleanName, {
			forUploading: true
		} );
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
				/\/[a-f0-9]\/[a-f0-9]{2}\/([^\s\/]+)\/[^\s\/]+-[^\s\/]*$/,

				// Full size images
				/\/[a-f0-9]\/[a-f0-9]{2}\/([^\s\/]+)$/,

				// Thumbnails in non-hashed upload directories
				/\/([^\s\/]+)\/[^\s\/]+-(?:\1|thumbnail)[^\s\/]*$/,

				// Full-size images in non-hashed upload directories
				/\/([^\s\/]+)$/
			],

			recount = regexes.length;

		src = img.jquery ? img[ 0 ].src : img.src;

		matches = src.match( thumbPhpRegex );

		if ( matches ) {
			return mw.Title.newFromText( 'File:' + mw.util.getParamValue( 'f', src ) );
		}

		decodedSrc = decodeURIComponent( src );

		for ( i = 0; i < recount; i++ ) {
			regex = regexes[ i ];
			matches = decodedSrc.match( regex );

			if ( matches && matches[ 1 ] ) {
				return mw.Title.newFromText( 'File:' + matches[ 1 ] );
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
			match = obj[ title ];
		} else if ( type === 'object' && title instanceof Title ) {
			match = obj[ title.toString() ];
		} else {
			throw new Error( 'mw.Title.exists: title must be a string or an instance of Title' );
		}

		if ( typeof match === 'boolean' ) {
			return match;
		}

		return null;
	};

	/**
	 * Store page existence
	 *
	 * @static
	 * @property {Object} exist
	 * @property {Object} exist.pages Keyed by title. Boolean true value indicates page does exist.
	 *
	 * @property {Function} exist.set The setter function.
	 *
	 *  Example to declare existing titles:
	 *
	 *     Title.exist.set( ['User:John_Doe', ...] );
	 *
	 *  Example to declare titles nonexistent:
	 *
	 *     Title.exist.set( ['File:Foo_bar.jpg', ...], false );
	 *
	 * @property {string|Array} exist.set.titles Title(s) in strict prefixedDb title form
	 * @property {boolean} [exist.set.state=true] State of the given titles
	 * @return {boolean}
	 */
	Title.exist = {
		pages: {},

		set: function ( titles, state ) {
			var i, len,
				pages = this.pages;

			titles = Array.isArray( titles ) ? titles : [ titles ];
			state = state === undefined ? true : !!state;

			for ( i = 0, len = titles.length; i < len; i++ ) {
				pages[ titles[ i ] ] = state;
			}
			return true;
		}
	};

	/**
	 * Normalize a file extension to the common form, making it lowercase and checking some synonyms,
	 * and ensure it's clean. Extensions with non-alphanumeric characters will be discarded.
	 * Keep in sync with File::normalizeExtension() in PHP.
	 *
	 * @param {string} extension File extension (without the leading dot)
	 * @return {string} File extension in canonical form
	 */
	Title.normalizeExtension = function ( extension ) {
		var
			lower = extension.toLowerCase(),
			squish = {
				htm: 'html',
				jpeg: 'jpg',
				mpeg: 'mpg',
				tiff: 'tif',
				ogv: 'ogg'
			};
		if ( squish.hasOwnProperty( lower ) ) {
			return squish[ lower ];
		} else if ( /^[0-9a-z]+$/.test( lower ) ) {
			return lower;
		} else {
			return '';
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
			return getNamespacePrefix( this.namespace );
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
			if (
				$.inArray( this.namespace, mw.config.get( 'wgCaseSensitiveNamespaces' ) ) !== -1 ||
				!this.title.length
			) {
				return this.title;
			}
			// PHP's strtoupper differs from String.toUpperCase in a number of cases
			// Bug: T147646
			return mw.Title.phpCharToUpper( this.title[ 0 ] ) + this.title.slice( 1 );
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
		 * Get the main page name
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
		 * Example: "File:Example_image.svg".
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
		 * Get the page name relative to a namespace
		 *
		 * Example:
		 *
		 * - "Foo:Bar" relative to the Foo namespace becomes "Bar".
		 * - "Bar" relative to any non-main namespace becomes ":Bar".
		 * - "Foo:Bar" relative to any namespace other than Foo stays "Foo:Bar".
		 *
		 * @param {number} namespace The namespace to be relative to
		 * @return {string}
		 */
		getRelativeText: function ( namespace ) {
			if ( this.getNamespaceId() === namespace ) {
				return this.getMainText();
			} else if ( this.getNamespaceId() === NS_MAIN ) {
				return ':' + this.getPrefixedText();
			} else {
				return this.getPrefixedText();
			}
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
		 * @param {Object} [params] A mapping of query parameter names to values,
		 *     e.g. `{ action: 'edit' }`.
		 * @return {string}
		 */
		getUrl: function ( params ) {
			var fragment = this.getFragment();
			if ( fragment ) {
				return mw.util.getUrl( this.toString() + '#' + fragment, params );
			} else {
				return mw.util.getUrl( this.toString(), params );
			}
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
