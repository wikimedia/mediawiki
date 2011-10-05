/**
 * mediaWiki.Title
 *
 * @author Neil Kandalgaonkar, 2010
 * @author Timo Tijhof, 2011
 * @since 1.18
 *
 * Relies on: mw.config (wgFormattedNamespaces, wgNamespaceIds, wgCaseSensitiveNamespaces), mw.util.wikiGetlink
 */
( function( $ ) {

	/* Local space */

	/**
	 * Title
	 * @constructor
	 *
	 * @param title {String} Title of the page. If no second argument given,
	 * this will be searched for a namespace.
	 * @param namespace {Number} (optional) Namespace id. If given, title will be taken as-is.
	 * @return {Title} this
	 */
var	Title = function( title, namespace ) {
		this._ns = 0; // integer namespace id
		this._name = null; // name in canonical 'database' form
		this._ext = null; // extension

		if ( arguments.length === 2 ) {
			setNameAndExtension( this, title );
			this._ns = fixNsId( namespace );
		} else if ( arguments.length === 1 ) {
			setAll( this, title );
		}
		return this;
	},

	/**
	 * Strip some illegal chars: control chars, colon, less than, greater than,
	 * brackets, braces, pipe, whitespace and normal spaces. This still leaves some insanity
	 * intact, like unicode bidi chars, but it's a good start..
	 * @param s {String}
	 * @return {String}
	 */
	clean = function( s ) {
		if ( s !== undefined ) {
			return s.replace( /[\x00-\x1f\x23\x3c\x3e\x5b\x5d\x7b\x7c\x7d\x7f\s]+/g, '_' );
		}
	},

	/**
	 * Convert db-key to readable text.
	 * @param s {String}
	 * @return {String}
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
	 */
	fixName = function( s ) {
		return clean( $.trim( s ) );
	},

	/**
	 * Sanitize name.
	 */
	fixExt = function( s ) {
		return clean( s );
	},

	/**
	 * Sanitize namespace id.
	 * @param id {Number} Namespace id.
	 * @return {Number|Boolean} The id as-is or boolean false if invalid.
	 */
	fixNsId = function( id ) {
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
	 *
	 * @example On a German wiki this would return 6 for any of 'File', 'Datei', 'Image' or even 'Bild'.
	 * @param ns {String} Namespace name (case insensitive, leading/trailing space ignored).
	 * @return {Number|Boolean} Namespace id or boolean false if unrecognized.
	 */
	getNsIdByName = function( ns ) {
		// toLowerCase throws exception on null/undefined. Return early.
		if ( ns == null ) {
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
	 * @param title {mw.Title}
	 * @param raw {String}
	 * @return {mw.Title}
	 */
	setAll = function( title, s ) {
		// In normal browsers the match-array contains null/undefined if there's no match,
		// IE returns an empty string.
		var	matches = s.match( /^(?:([^:]+):)?(.*?)(?:\.(\w{1,5}))?$/ ),
			ns_match = getNsIdByName( matches[1] );

		// Namespace must be valid, and title must be a non-empty string.
		if ( ns_match && typeof matches[2] === 'string' && matches[2] !== '' ) {
			title._ns = ns_match;
			title._name = fixName( matches[2] );
			if ( typeof matches[3] === 'string' && matches[3] !== '' ) {
				title._ext = fixExt( matches[3] );
			}
		} else {
			// Consistency with MediaWiki PHP: Unknown namespace -> fallback to main namespace.
			title._ns = 0;
			setNameAndExtension( title, s );
		}
		return title;
	},

	/**
	 * Helper to extract name and extension from a string.
	 *
	 * @param title {mw.Title}
	 * @param raw {String}
	 * @return {mw.Title}
	 */
	setNameAndExtension = function( title, raw ) {
		// In normal browsers the match-array contains null/undefined if there's no match,
		// IE returns an empty string.
		var matches = raw.match( /^(?:)?(.*?)(?:\.(\w{1,5}))?$/ );

		// Title must be a non-empty string.
		if ( typeof matches[1] === 'string' && matches[1] !== '' ) {
			title._name = fixName( matches[1] );
			if ( typeof matches[2] === 'string' && matches[2] !== '' ) {
				title._ext = fixExt( matches[2] );
			}
		} else {
			throw new Error( 'mw.Title: Could not parse title "' + raw + '"' );
		}
		return title;
	};


	/* Static space */

	/**
	 * Whether this title exists on the wiki.
	 * @param title {mixed} prefixed db-key name (string) or instance of Title
	 * @return {mixed} Boolean true/false if the information is available. Otherwise null.
	 */
	Title.exists = function( title ) {
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
	 * @var Title.exist {Object}
	 */
	Title.exist = {
		/**
		 * @var Title.exist.pages {Object} Keyed by PrefixedDb title.
		 * Boolean true value indicates page does exist.
		 */
		pages: {},
		/**
		 * @example Declare existing titles: Title.exist.set(['User:John_Doe', ...]);
		 * @example Declare titles nonexistent: Title.exist.set(['File:Foo_bar.jpg', ...], false);
		 * @param titles {String|Array} Title(s) in strict prefixedDb title form.
		 * @param state {Boolean} (optional) State of the given titles. Defaults to true.
		 * @return {Boolean}
		 */
		set: function( titles, state ) {
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

	var fn = {
		constructor: Title,

		/**
		 * Get the namespace number.
		 * @return {Number}
		 */
		getNamespaceId: function(){
			return this._ns;
		},

		/**
		 * Get the namespace prefix (in the content-language).
		 * In NS_MAIN this is '', otherwise namespace name plus ':'
		 * @return {String}
		 */
		getNamespacePrefix: function(){
			return mw.config.get( 'wgFormattedNamespaces' )[this._ns].replace( / /g, '_' ) + (this._ns === 0 ? '' : ':');
		},

		/**
		 * The name, like "Foo_bar"
		 * @return {String}
		 */
		getName: function() {
			if ( $.inArray( this._ns, mw.config.get( 'wgCaseSensitiveNamespaces' ) ) !== -1 ) {
				return this._name;
			} else {
				return $.ucFirst( this._name );
			}
		},

		/**
		 * The name, like "Foo bar"
		 * @return {String}
		 */
		getNameText: function() {
			return text( this.getName() );
		},

		/**
		 * Get full name in prefixed DB form, like File:Foo_bar.jpg,
		 * most useful for API calls, anything that must identify the "title".
		 */
		getPrefixedDb: function() {
			return this.getNamespacePrefix() + this.getMain();
		},

		/**
		 * Get full name in text form, like "File:Foo bar.jpg".
		 * @return {String}
		 */
		getPrefixedText: function() {
			return text( this.getPrefixedDb() );
		},

		/**
		 * The main title (without namespace), like "Foo_bar.jpg"
		 * @return {String}
		 */
		getMain: function() {
			return this.getName() + this.getDotExtension();
		},

		/**
		 * The "text" form, like "Foo bar.jpg"
		 * @return {String}
		 */
		getMainText: function() {
			return text( this.getMain() );
		},

		/**
		 * Get the extension (returns null if there was none)
		 * @return {String|null} extension
		 */
		getExtension: function() {
			return this._ext;
		},

		/**
		 * Convenience method: return string like ".jpg", or "" if no extension
		 * @return {String}
		 */
		getDotExtension: function() {
			return this._ext === null ? '' : '.' + this._ext;
		},

		/**
		 * Return the URL to this title
		 * @return {String}
		 */
		getUrl: function() {
			return mw.util.wikiGetlink( this.toString() );
		},

		/**
		 * Whether this title exists on the wiki.
		 * @return {mixed} Boolean true/false if the information is available. Otherwise null.
		 */
		exists: function() {
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

})(jQuery);
