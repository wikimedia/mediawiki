/**
 * mediaWiki.Title
 *
 * @author Neil Kandalgaonkar, 2010
 * @author Timo Tijhof, 2011
 * @since 1.19
 *
 * Relies on: mw.config (wgFormattedNamespaces, wgNamespaceIds, wgCaseSensitiveNamespaces), mw.util.wikiGetlink
 */
(function( $ ) {

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
				this.setNameAndExtension( title ).setNamespaceById( namespace );
			} else if ( arguments.length === 1 ) {
				// If title is like "Blabla: Hello" ignore exception by setNamespace(),
				// and instead assume NS_MAIN and keep prefix
				try {
					this.setAll( title );
				} catch(e) {
					this.setNameAndExtension( title );
				}
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
	};

	/* Static space */

	/**
	 * Wether this title exists on the wiki.
	 * @param title {mixed} prefixed db-key name (string) or instance of Title
	 * @return {mixed} Boolean true/false if the information is available. Otherwise null.
	 */
	Title.exists = function( title ) {
		var	type = $.type( title ), obj = Title.exist.pages, match;
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
		 * @example Declare titles inexisting: Title.exist.set(['File:Foo_bar.jpg', ...], false);
		 * @param titles {String|Array} Title(s) in strict prefixedDb title form.
		 * @param state {Boolean} (optional) State of the given titles. Defaults to true.
		 * @return {Boolean}
		 */
		set: function( titles, state ) {
			titles = $.isArray( titles ) ? titles : [titles];
			state = state === undefined ? true : !!state;
			var	pages = this.pages, i, len = titles.length;
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
		 * @param id {Number} Canonical namespace id.
		 * @return {mw.Title} this
		 */
		setNamespaceById: function( id ) {
			// wgFormattedNamespaces is an object of *string* key-vals,
			var ns = mw.config.get( 'wgFormattedNamespaces' )[id.toString()];

			// Cannot cast to boolean, ns may be '' (main namespace)
			if ( ns === undefined ) {
				this._ns = false;
			} else {
				this._ns = Number( id );
			}
			return this;
		},

		/**
		 * Set namespace by any known namespace/id pair (localized, canonical or alias)
		 * On a German wiki this could be 'File', 'Datei', 'Image' or even 'Bild' for NS_FILE.
		 * @param ns {String} A namespace name (case insensitive, space insensitive)
		 * @return {mw.Title} this
		 */
		setNamespace: function( ns ) {
			ns = clean( $.trim( ns.toLowerCase() ) ); // Normalize
			var id = mw.config.get( 'wgNamespaceIds' )[ns];
			if ( id === undefined ) {
				throw new Error( 'mw.Title: Unrecognized canonical namespace: ' + ns );
			}
			return this.setNamespaceById( id );
		},

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
		 * Set the "name" portion, removing illegal characters.
		 * @param s {String} Page name (without namespace prefix)
		 * @return {mw.Title} this
		 */
		setName: function( s ) {
			this._name = clean( $.trim( s ) );
			return this;
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
		 * Set the "extension" portion, removing illegal characters.
		 * @param s {String}
		 * @return {mw.Title} this
		 */
		setExtension: function( s ) {
			this._ext = clean( s.toLowerCase() );
			return this;
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
		 * @param s {String}
		 * @return {mw.Title} this
		 */
		setAll: function( s ) {
			var matches = s.match( /^(?:([^:]+):)?(.*?)(?:\.(\w{1,5}))?$/ );
			if ( matches.length ) {
				if ( matches[1] ) { this.setNamespace( matches[1] ); }
				if ( matches[2] ) { this.setName( matches[2] ); }
				if ( matches[3] ) { this.setExtension( matches[3] ); }
			} else {
				throw new Error( 'mw.Title: Could not parse title "' + s + '"' );
			}
			return this;
		},

		/**
		 * @param s {String}
		 * @return {mw.Title} this
		 */
		setNameAndExtension: function( s ) {
			var matches = s.match( /^(?:)?(.*?)(?:\.(\w{1,5}))?$/ );
			if ( matches.length ) {
				if ( matches[1] ) { this.setName( matches[1] ); }
				if ( matches[2] ) { this.setExtension( matches[2] ); }
			} else {
				throw new Error( 'mw.Title: Could not parse title "' + s + '"' );
			}
			return this;
		},

		/**
		 * Return the URL to this title
		 * @return {String}
		 */
		getUrl: function() {
			return mw.util.wikiGetlink( this.toString() );
		},

		/**
		 * Wether this title exists on the wiki.
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
