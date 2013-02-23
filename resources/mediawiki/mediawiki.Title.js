/*!
 * @author Neil Kandalgaonkar, 2010
 * @author Timo Tijhof, 2011
 * @since 1.18
 *
 * Relies on: mw.config (wgFormattedNamespaces, wgNamespaceIds, wgCaseSensitiveNamespaces), mw.util.wikiGetlink
 */
( function ( mw, $ ) {

	/* Local space */

	/**
	 * @class mw.Title
	 *
	 * @constructor
	 * @param {string} title Title of the page. If no second argument given,
	 * this will be searched for a namespace.
	 * @param {number} [namespace] Namespace id. If given, title will be taken as-is.
	 */
	function Title( title, namespace ) {
		this.ns = 0; // integer namespace id
		this.name = null; // name in canonical 'database' form
		this.ext = null; // extension

		if ( arguments.length === 2 ) {
			setNameAndExtension( this, title );
			this.ns = fixNsId( namespace );
		} else if ( arguments.length === 1 ) {
			setAll( this, title );
		}
		return this;
	}

var
	/* Public methods (defined later) */
	fn,

	/**
	 * Strip some illegal chars: control chars, colon, less than, greater than,
	 * brackets, braces, pipe, whitespace and normal spaces. This still leaves some insanity
	 * intact, like unicode bidi chars, but it's a good start..
	 * @ignore
	 * @param {string} s
	 * @return {string}
	 */
	clean = function ( s ) {
		if ( s !== undefined ) {
			return s.replace( /[\x00-\x1f\x23\x3c\x3e\x5b\x5d\x7b\x7c\x7d\x7f\s]+/g, '_' );
		}
	},

	/**
	 * Convert db-key to readable text.
	 * @ignore
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
	 * Sanitize name.
	 * @ignore
	 */
	fixName = function ( s ) {
		return clean( $.trim( s ) );
	},

	/**
	 * Sanitize extension.
	 * @ignore
	 */
	fixExt = function ( s ) {
		return clean( s );
	},

	/**
	 * Sanitize namespace id.
	 * @ignore
	 * @param id {Number} Namespace id.
	 * @return {Number|Boolean} The id as-is or boolean false if invalid.
	 */
	fixNsId = function ( id ) {
		// wgFormattedNamespaces is an object of *string* key-vals (ie. arr["0"] not arr[0] )
		var ns = mw.config.get( 'wgFormattedNamespaces' )[id.toString()];

		// Check only undefined (may be false-y, such as '' (main namespace) ).
		if ( ns === undefined ) {
			return false;
		} else {
			return Number( id );
		}
	},

	/**
	 * Get namespace id from namespace name by any known namespace/id pair (localized, canonical or alias).
	 * Example: On a German wiki this would return 6 for any of 'File', 'Datei', 'Image' or even 'Bild'.
	 * @ignore
	 * @param ns {String} Namespace name (case insensitive, leading/trailing space ignored).
	 * @return {Number|Boolean} Namespace id or boolean false if unrecognized.
	 */
	getNsIdByName = function ( ns ) {
		// Don't cast non-strings to strings, because null or undefined
		// should not result in returning the id of a potential namespace
		// called "Null:" (e.g. on nullwiki.example.org)
		// Also, toLowerCase throws exception on null/undefined, because
		// it is a String.prototype method.
		if ( typeof ns !== 'string' ) {
			return false;
		}
		ns = clean( $.trim( ns.toLowerCase() ) ); // Normalize
		var id = mw.config.get( 'wgNamespaceIds' )[ns];
		if ( id === undefined ) {
			mw.log( 'mw.Title: Unrecognized namespace: ' + ns );
			return false;
		}
		return fixNsId( id );
	},

	/**
	 * Helper to extract namespace, name and extension from a string.
	 *
	 * @ignore
	 * @param {mw.Title} title
	 * @param {string} raw
	 * @return {mw.Title}
	 */
	setAll = function ( title, s ) {
		// In normal browsers the match-array contains null/undefined if there's no match,
		// IE returns an empty string.
		var matches = s.match( /^(?:([^:]+):)?(.*?)(?:\.(\w+))?$/ ),
			nsMatch = getNsIdByName( matches[1] );

		// Namespace must be valid, and title must be a non-empty string.
		if ( nsMatch && typeof matches[2] === 'string' && matches[2] !== '' ) {
			title.ns = nsMatch;
			title.name = fixName( matches[2] );
			if ( typeof matches[3] === 'string' && matches[3] !== '' ) {
				title.ext = fixExt( matches[3] );
			}
		} else {
			// Consistency with MediaWiki PHP: Unknown namespace -> fallback to main namespace.
			title.ns = 0;
			setNameAndExtension( title, s );
		}
		return title;
	},

	/**
	 * Helper to extract name and extension from a string.
	 *
	 * @ignore
	 * @param {mw.Title} title
	 * @param {string} raw
	 * @return {mw.Title}
	 */
	setNameAndExtension = function ( title, raw ) {
		// In normal browsers the match-array contains null/undefined if there's no match,
		// IE returns an empty string.
		var matches = raw.match( /^(?:)?(.*?)(?:\.(\w+))?$/ );

		// Title must be a non-empty string.
		if ( typeof matches[1] === 'string' && matches[1] !== '' ) {
			title.name = fixName( matches[1] );
			if ( typeof matches[2] === 'string' && matches[2] !== '' ) {
				title.ext = fixExt( matches[2] );
			}
		} else {
			throw new Error( 'mw.Title: Could not parse title "' + raw + '"' );
		}
		return title;
	};


	/* Static space */

	/**
	 * Whether this title exists on the wiki.
	 * @static
	 * @param {Mixed} title prefixed db-key name (string) or instance of Title
	 * @return {Mixed} Boolean true/false if the information is available. Otherwise null.
	 */
	Title.exists = function ( title ) {
		var type = $.type( title ), obj = Title.exist.pages, match;
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

	/**
	 * @static
	 * @property
	 */
	Title.exist = {
		/**
		 * @static
		 * @property {Object} exist.pages Keyed by PrefixedDb title.
		 * Boolean true value indicates page does exist.
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
		 * @param {string|Array} titles Title(s) in strict prefixedDb title form.
		 * @param {boolean} [state] State of the given titles. Defaults to true.
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

	/* Public methods */

	fn = {
		constructor: Title,

		/**
		 * Get the namespace number.
		 * @return {number}
		 */
		getNamespaceId: function (){
			return this.ns;
		},

		/**
		 * Get the namespace prefix (in the content-language).
		 * In NS_MAIN this is '', otherwise namespace name plus ':'
		 * @return {string}
		 */
		getNamespacePrefix: function (){
			return mw.config.get( 'wgFormattedNamespaces' )[this.ns].replace( / /g, '_' ) + (this.ns === 0 ? '' : ':');
		},

		/**
		 * The name, like "Foo_bar"
		 * @return {string}
		 */
		getName: function () {
			if ( $.inArray( this.ns, mw.config.get( 'wgCaseSensitiveNamespaces' ) ) !== -1 ) {
				return this.name;
			} else {
				return $.ucFirst( this.name );
			}
		},

		/**
		 * The name, like "Foo bar"
		 * @return {string}
		 */
		getNameText: function () {
			return text( this.getName() );
		},

		/**
		 * Get full name in prefixed DB form, like File:Foo_bar.jpg,
		 * most useful for API calls, anything that must identify the "title".
		 * @return {string}
		 */
		getPrefixedDb: function () {
			return this.getNamespacePrefix() + this.getMain();
		},

		/**
		 * Get full name in text form, like "File:Foo bar.jpg".
		 * @return {string}
		 */
		getPrefixedText: function () {
			return text( this.getPrefixedDb() );
		},

		/**
		 * The main title (without namespace), like "Foo_bar.jpg"
		 * @return {string}
		 */
		getMain: function () {
			return this.getName() + this.getDotExtension();
		},

		/**
		 * The "text" form, like "Foo bar.jpg"
		 * @return {string}
		 */
		getMainText: function () {
			return text( this.getMain() );
		},

		/**
		 * Get the extension (returns null if there was none)
		 * @return {string|null}
		 */
		getExtension: function () {
			return this.ext;
		},

		/**
		 * Convenience method: return string like ".jpg", or "" if no extension
		 * @return {string}
		 */
		getDotExtension: function () {
			return this.ext === null ? '' : '.' + this.ext;
		},

		/**
		 * Return the URL to this title
		 * @see mw.util#wikiGetlink
		 * @return {string}
		 */
		getUrl: function () {
			return mw.util.wikiGetlink( this.toString() );
		},

		/**
		 * Whether this title exists on the wiki.
		 * @see #static-method-exists
		 * @return {boolean|null} If the information is available. Otherwise null.
		 */
		exists: function () {
			return Title.exists( this );
		}
	};

	// Alias
	fn.toString = fn.getPrefixedDb;
	fn.toText = fn.getPrefixedText;

	// Assign
	Title.prototype = fn;

	// Expose
	mw.Title = Title;

}( mediaWiki, jQuery ) );
