/*!
* Experimental advanced wikitext parser-emitter.
* See: https://www.mediawiki.org/wiki/Extension:UploadWizard/MessageParser for docs
*
* @author neilk@wikimedia.org
* @author mflaschen@wikimedia.org
*/

/**
 * @class mw.jqueryMsg
 * @singleton
 */

var oldParser,
	strongDirRegExp,
	slice = Array.prototype.slice,
	parserDefaults = {
		// Magic words and their expansions. Server-side data is added to this below.
		magic: {
			PAGENAME: mw.config.get( 'wgPageName' ),
			PAGENAMEE: mw.util.wikiUrlencode( mw.config.get( 'wgPageName' ) )
		},
		// Whitelist for allowed HTML elements in wikitext.
		// Self-closing tags are not currently supported.
		// Filled in with server-side data below
		allowedHtmlElements: [],
		// Key tag name, value allowed attributes for that tag.
		// See Sanitizer::setupAttributeWhitelist
		allowedHtmlCommonAttributes: [
			// HTML
			'id',
			'class',
			'style',
			'lang',
			'dir',
			'title',

			// WAI-ARIA
			'role'
		],

		// Attributes allowed for specific elements.
		// Key is element name in lower case
		// Value is array of allowed attributes for that element
		allowedHtmlAttributesByElement: {},
		messages: mw.messages,
		language: mw.language,

		// Same meaning as in mediawiki.js.
		//
		// Only 'text', 'parse', and 'escaped' are supported, and the
		// actual escaping for 'escaped' is done by other code (generally
		// through mediawiki.js).
		//
		// However, note that this default only
		// applies to direct calls to jqueryMsg. The default for mediawiki.js itself
		// is 'text', including when it uses jqueryMsg.
		format: 'parse'
	};

// Add in server-side data (allowedHtmlElements and magic words)
$.extend( true, parserDefaults, require( './parserDefaults.json' ) );

/**
 * Wrapper around jQuery append that converts all non-objects to TextNode so append will not
 * convert what it detects as an htmlString to an element.
 *
 * If our own HtmlEmitter jQuery object is given, its children will be unwrapped and appended to
 * new parent.
 *
 * Object elements of children (jQuery, HTMLElement, TextNode, etc.) will be left as is.
 *
 * @private
 * @param {jQuery} $parent Parent node wrapped by jQuery
 * @param {Object|string|Array} children What to append, with the same possible types as jQuery
 * @return {jQuery} $parent
 */
function appendWithoutParsing( $parent, children ) {
	var i, len;

	if ( !Array.isArray( children ) ) {
		children = [ children ];
	}

	for ( i = 0, len = children.length; i < len; i++ ) {
		if ( typeof children[ i ] !== 'object' ) {
			children[ i ] = document.createTextNode( children[ i ] );
		}
		if ( children[ i ] instanceof $ && children[ i ].hasClass( 'mediaWiki_htmlEmitter' ) ) {
			children[ i ] = children[ i ].contents();
		}
	}

	return $parent.append( children );
}

/**
 * Decodes the main HTML entities, those encoded by mw.html.escape.
 *
 * @private
 * @param {string} encoded Encoded string
 * @return {string} String with those entities decoded
 */
function decodePrimaryHtmlEntities( encoded ) {
	return encoded
		.replace( /&#039;/g, '\'' )
		.replace( /&quot;/g, '"' )
		.replace( /&lt;/g, '<' )
		.replace( /&gt;/g, '>' )
		.replace( /&amp;/g, '&' );
}

/**
 * Turn input into a string.
 *
 * @private
 * @param {string|jQuery} input
 * @return {string} Textual value of input
 */
function textify( input ) {
	if ( input instanceof $ ) {
		input = input.text();
	}
	return String( input );
}

/**
 * Given parser options, return a function that parses a key and replacements, returning jQuery object
 *
 * Try to parse a key and optional replacements, returning a jQuery object that may be a tree of jQuery nodes.
 * If there was an error parsing, return the key and the error message (wrapped in jQuery). This should put the error right into
 * the interface, without causing the page to halt script execution, and it hopefully should be clearer how to fix it.
 *
 * @private
 * @param {Object} options Parser options
 * @return {Function}
 * @return {Array} return.args First element is the key, replacements may be in array in 2nd element, or remaining elements.
 * @return {jQuery} return.return
 */
function getFailableParserFn( options ) {
	return function ( args ) {
		var fallback,
			parser = new mw.jqueryMsg.Parser( options ),
			key = args[ 0 ],
			argsArray = Array.isArray( args[ 1 ] ) ? args[ 1 ] : slice.call( args, 1 );
		try {
			return parser.parse( key, argsArray );
		} catch ( e ) {
			fallback = parser.settings.messages.get( key );
			mw.log.warn( 'mediawiki.jqueryMsg: ' + key + ': ' + e.message );
			mw.track( 'mediawiki.jqueryMsg.error', {
				messageKey: key,
				errorMessage: e.message
			} );
			return $( '<span>' ).text( fallback );
		}
	};
}

mw.jqueryMsg = {};

/**
 * Initialize parser defaults.
 *
 * ResourceLoaderJqueryMsgModule calls this to provide default values from
 * Sanitizer.php for allowed HTML elements. To override this data for individual
 * parsers, pass the relevant options to mw.jqueryMsg.Parser.
 *
 * @private
 * @param {Object} data New data to extend parser defaults with
 * @param {boolean} [deep=false] Whether the extend is done recursively (deep)
 */
mw.jqueryMsg.setParserDefaults = function ( data, deep ) {
	if ( deep ) {
		$.extend( true, parserDefaults, data );
	} else {
		$.extend( parserDefaults, data );
	}
};

/**
 * Get current parser defaults.
 *
 * Primarily used for the unit test. Returns a copy.
 *
 * @private
 * @return {Object}
 */
mw.jqueryMsg.getParserDefaults = function () {
	return $.extend( {}, parserDefaults );
};

/**
 * Returns a function suitable for static use, to construct strings from a message key (and optional replacements).
 *
 * Example:
 *
 *       var format = mediaWiki.jqueryMsg.getMessageFunction( options );
 *       $( '#example' ).text( format( 'hello-user', username ) );
 *
 * Tthis returns only strings, so it destroys any bindings. If you want to preserve bindings, use the
 * jQuery plugin version instead. This was originally created to ease migration from `window.gM()`,
 * from a time when the parser used by `mw.message` was not extendable.
 *
 * N.B. replacements are variadic arguments or an array in second parameter. In other words:
 *    somefunction( a, b, c, d )
 * is equivalent to
 *    somefunction( a, [b, c, d] )
 *
 * @param {Object} options parser options
 * @return {Function} Function The message formatter
 * @return {string} return.key Message key.
 * @return {Array|Mixed} return.replacements Optional variable replacements (variadically or an array).
 * @return {string} return.return Rendered HTML.
 */
mw.jqueryMsg.getMessageFunction = function ( options ) {
	var failableParserFn, format;

	if ( options && options.format !== undefined ) {
		format = options.format;
	} else {
		format = parserDefaults.format;
	}

	return function () {
		var failableResult;
		if ( !failableParserFn ) {
			failableParserFn = getFailableParserFn( options );
		}
		failableResult = failableParserFn( arguments );
		if ( format === 'text' || format === 'escaped' ) {
			return failableResult.text();
		} else {
			return failableResult.html();
		}
	};
};

/**
 * Returns a jQuery plugin which parses the message in the message key, doing replacements optionally, and appends the nodes to
 * the current selector. Bindings to passed-in jquery elements are preserved. Functions become click handlers for [$1 linktext] links.
 * e.g.
 *
 *        $.fn.msg = mediaWiki.jqueryMsg.getPlugin( options );
 *        var $userlink = $( '<a>' ).click( function () { alert( "hello!!" ) } );
 *        $( 'p#headline' ).msg( 'hello-user', $userlink );
 *
 * N.B. replacements are variadic arguments or an array in second parameter. In other words:
 *    somefunction( a, b, c, d )
 * is equivalent to
 *    somefunction( a, [b, c, d] )
 *
 * We append to 'this', which in a jQuery plugin context will be the selected elements.
 *
 * @param {Object} options Parser options
 * @return {Function} Function suitable for assigning to jQuery plugin, such as jQuery#msg
 * @return {string} return.key Message key.
 * @return {Array|Mixed} return.replacements Optional variable replacements (variadically or an array).
 * @return {jQuery} return.return
 */
mw.jqueryMsg.getPlugin = function ( options ) {
	var failableParserFn;

	return function () {
		var $target;
		if ( !failableParserFn ) {
			failableParserFn = getFailableParserFn( options );
		}
		$target = this.empty();
		appendWithoutParsing( $target, failableParserFn( arguments ) );
		return $target;
	};
};

/**
 * The parser itself.
 * Describes an object, whose primary duty is to .parse() message keys.
 *
 * @class
 * @private
 * @param {Object} options
 */
mw.jqueryMsg.Parser = function ( options ) {
	this.settings = $.extend( {}, parserDefaults, options );
	this.settings.onlyCurlyBraceTransform = ( this.settings.format === 'text' || this.settings.format === 'escaped' );
	this.astCache = {};

	this.emitter = new mw.jqueryMsg.HtmlEmitter( this.settings.language, this.settings.magic );
};
// Backwards-compatible alias
// @deprecated since 1.31
mw.jqueryMsg.parser = mw.jqueryMsg.Parser;

mw.jqueryMsg.Parser.prototype = {
	/**
	 * Where the magic happens.
	 * Parses a message from the key, and swaps in replacements as necessary, wraps in jQuery
	 * If an error is thrown, returns original key, and logs the error
	 *
	 * @param {string} key Message key.
	 * @param {Array} replacements Variable replacements for $1, $2... $n
	 * @return {jQuery}
	 */
	parse: function ( key, replacements ) {
		var ast = this.getAst( key, replacements );
		return this.emitter.emit( ast, replacements );
	},

	/**
	 * Fetch the message string associated with a key, return parsed structure. Memoized.
	 * Note that we pass '⧼' + key + '⧽' back for a missing message here.
	 *
	 * @param {string} key
	 * @param {Array} replacements Variable replacements for $1, $2... $n
	 * @return {string|Array} string of '⧼key⧽' if message missing, simple string if possible, array of arrays if needs parsing
	 */
	getAst: function ( key, replacements ) {
		var wikiText;

		if ( !Object.prototype.hasOwnProperty.call( this.astCache, key ) ) {
			wikiText = this.settings.messages.get( key );
			if (
				mw.config.get( 'wgUserLanguage' ) === 'qqx' &&
				wikiText === '(' + key + ')'
			) {
				wikiText = '(' + key + '$*)';
			} else if ( typeof wikiText !== 'string' ) {
				wikiText = '⧼' + key + '⧽';
			}
			wikiText = mw.internalDoTransformFormatForQqx( wikiText, replacements );
			this.astCache[ key ] = this.wikiTextToAst( wikiText );
		}
		return this.astCache[ key ];
	},

	/**
	 * Parses the input wikiText into an abstract syntax tree, essentially an s-expression.
	 *
	 * CAVEAT: This does not parse all wikitext. It could be more efficient, but it's pretty good already.
	 * n.b. We want to move this functionality to the server. Nothing here is required to be on the client.
	 *
	 * @param {string} input Message string wikitext
	 * @throws Error
	 * @return {Mixed} abstract syntax tree
	 */
	wikiTextToAst: function ( input ) {
		var pos,
			regularLiteral, regularLiteralWithoutBar, regularLiteralWithoutSpace, regularLiteralWithSquareBrackets,
			doubleQuote, singleQuote, backslash, anyCharacter, asciiAlphabetLiteral,
			escapedOrLiteralWithoutSpace, escapedOrLiteralWithoutBar, escapedOrRegularLiteral,
			whitespace, dollar, digits, htmlDoubleQuoteAttributeValue, htmlSingleQuoteAttributeValue,
			htmlAttributeEquals, openHtmlStartTag, optionalForwardSlash, openHtmlEndTag, closeHtmlTag,
			openExtlink, closeExtlink, wikilinkContents, openWikilink, closeWikilink, templateName, pipe, colon,
			templateContents, openTemplate, closeTemplate,
			nonWhitespaceExpression, paramExpression, expression, curlyBraceTransformExpression, res,
			settings = this.settings,
			concat = Array.prototype.concat;

		// Indicates current position in input as we parse through it.
		// Shared among all parsing functions below.
		pos = 0;

		// =========================================================
		// parsing combinators - could be a library on its own
		// =========================================================

		/**
		 * Try parsers until one works, if none work return null
		 *
		 * @private
		 * @param {Function[]} ps
		 * @return {string|null}
		 */
		function choice( ps ) {
			return function () {
				var i, result;
				for ( i = 0; i < ps.length; i++ ) {
					result = ps[ i ]();
					if ( result !== null ) {
						return result;
					}
				}
				return null;
			};
		}

		/**
		 * Try several ps in a row, all must succeed or return null.
		 * This is the only eager one.
		 *
		 * @private
		 * @param {Function[]} ps
		 * @return {string|null}
		 */
		function sequence( ps ) {
			var i, r,
				originalPos = pos,
				result = [];
			for ( i = 0; i < ps.length; i++ ) {
				r = ps[ i ]();
				if ( r === null ) {
					pos = originalPos;
					return null;
				}
				result.push( r );
			}
			return result;
		}

		/**
		 * Run the same parser over and over until it fails.
		 * Must succeed a minimum of n times or return null.
		 *
		 * @private
		 * @param {number} n
		 * @param {Function} p
		 * @return {string|null}
		 */
		function nOrMore( n, p ) {
			return function () {
				var originalPos = pos,
					result = [],
					parsed = p();
				while ( parsed !== null ) {
					result.push( parsed );
					parsed = p();
				}
				if ( result.length < n ) {
					pos = originalPos;
					return null;
				}
				return result;
			};
		}

		/**
		 * There is a general pattern -- parse a thing, if that worked, apply transform, otherwise return null.
		 *
		 * TODO: But using this as a combinator seems to cause problems when combined with #nOrMore().
		 * May be some scoping issue
		 *
		 * @private
		 * @param {Function} p
		 * @param {Function} fn
		 * @return {string|null}
		 */
		function transform( p, fn ) {
			return function () {
				var result = p();
				return result === null ? null : fn( result );
			};
		}

		/**
		 * Just make parsers out of simpler JS builtin types
		 *
		 * @private
		 * @param {string} s
		 * @return {Function}
		 * @return {string} return.return
		 */
		function makeStringParser( s ) {
			var len = s.length;
			return function () {
				var result = null;
				if ( input.substr( pos, len ) === s ) {
					result = s;
					pos += len;
				}
				return result;
			};
		}

		/**
		 * Makes a regex parser, given a RegExp object.
		 * The regex being passed in should start with a ^ to anchor it to the start
		 * of the string.
		 *
		 * @private
		 * @param {RegExp} regex anchored regex
		 * @return {Function} function to parse input based on the regex
		 */
		function makeRegexParser( regex ) {
			return function () {
				var matches = input.slice( pos ).match( regex );
				if ( matches === null ) {
					return null;
				}
				pos += matches[ 0 ].length;
				return matches[ 0 ];
			};
		}

		// ===================================================================
		// General patterns above this line -- wikitext specific parsers below
		// ===================================================================

		// Parsing functions follow. All parsing functions work like this:
		// They don't accept any arguments.
		// Instead, they just operate non destructively on the string 'input'
		// As they can consume parts of the string, they advance the shared variable pos,
		// and return tokens (or whatever else they want to return).
		// some things are defined as closures and other things as ordinary functions
		// converting everything to a closure makes it a lot harder to debug... errors pop up
		// but some debuggers can't tell you exactly where they come from. Also the mutually
		// recursive functions seem not to work in all browsers then. (Tested IE6-7, Opera, Safari, FF)
		// This may be because, to save code, memoization was removed

		/* eslint-disable no-useless-escape */
		regularLiteral = makeRegexParser( /^[^{}\[\]$<\\]/ );
		regularLiteralWithoutBar = makeRegexParser( /^[^{}\[\]$\\|]/ );
		regularLiteralWithoutSpace = makeRegexParser( /^[^{}\[\]$\s]/ );
		regularLiteralWithSquareBrackets = makeRegexParser( /^[^{}$\\]/ );
		/* eslint-enable no-useless-escape */

		backslash = makeStringParser( '\\' );
		doubleQuote = makeStringParser( '"' );
		singleQuote = makeStringParser( '\'' );
		anyCharacter = makeRegexParser( /^./ );

		openHtmlStartTag = makeStringParser( '<' );
		optionalForwardSlash = makeRegexParser( /^\/?/ );
		openHtmlEndTag = makeStringParser( '</' );
		htmlAttributeEquals = makeRegexParser( /^\s*=\s*/ );
		closeHtmlTag = makeRegexParser( /^\s*>/ );

		function escapedLiteral() {
			var result = sequence( [
				backslash,
				anyCharacter
			] );
			return result === null ? null : result[ 1 ];
		}
		escapedOrLiteralWithoutSpace = choice( [
			escapedLiteral,
			regularLiteralWithoutSpace
		] );
		escapedOrLiteralWithoutBar = choice( [
			escapedLiteral,
			regularLiteralWithoutBar
		] );
		escapedOrRegularLiteral = choice( [
			escapedLiteral,
			regularLiteral
		] );
		// Used to define "literals" without spaces, in space-delimited situations
		function literalWithoutSpace() {
			var result = nOrMore( 1, escapedOrLiteralWithoutSpace )();
			return result === null ? null : result.join( '' );
		}
		// Used to define "literals" within template parameters. The pipe character is the parameter delimeter, so by default
		// it is not a literal in the parameter
		function literalWithoutBar() {
			var result = nOrMore( 1, escapedOrLiteralWithoutBar )();
			return result === null ? null : result.join( '' );
		}

		function literal() {
			var result = nOrMore( 1, escapedOrRegularLiteral )();
			return result === null ? null : result.join( '' );
		}

		function curlyBraceTransformExpressionLiteral() {
			var result = nOrMore( 1, regularLiteralWithSquareBrackets )();
			return result === null ? null : result.join( '' );
		}

		asciiAlphabetLiteral = makeRegexParser( /^[A-Za-z]+/ );
		htmlDoubleQuoteAttributeValue = makeRegexParser( /^[^"]*/ );
		htmlSingleQuoteAttributeValue = makeRegexParser( /^[^']*/ );

		whitespace = makeRegexParser( /^\s+/ );
		dollar = makeStringParser( '$' );
		digits = makeRegexParser( /^\d+/ );

		function replacement() {
			var result = sequence( [
				dollar,
				digits
			] );
			if ( result === null ) {
				return null;
			}
			return [ 'REPLACE', parseInt( result[ 1 ], 10 ) - 1 ];
		}
		openExtlink = makeStringParser( '[' );
		closeExtlink = makeStringParser( ']' );
		// this extlink MUST have inner contents, e.g. [foo] not allowed; [foo bar] [foo <i>bar</i>], etc. are allowed
		function extlink() {
			var result, parsedResult, target;
			result = null;
			parsedResult = sequence( [
				openExtlink,
				nOrMore( 1, nonWhitespaceExpression ),
				whitespace,
				nOrMore( 1, expression ),
				closeExtlink
			] );
			if ( parsedResult !== null ) {
				// When the entire link target is a single parameter, we can't use CONCAT, as we allow
				// passing fancy parameters (like a whole jQuery object or a function) to use for the
				// link. Check only if it's a single match, since we can either do CONCAT or not for
				// singles with the same effect.
				target = parsedResult[ 1 ].length === 1 ?
					parsedResult[ 1 ][ 0 ] :
					[ 'CONCAT' ].concat( parsedResult[ 1 ] );
				result = [
					'EXTLINK',
					target,
					[ 'CONCAT' ].concat( parsedResult[ 3 ] )
				];
			}
			return result;
		}
		openWikilink = makeStringParser( '[[' );
		closeWikilink = makeStringParser( ']]' );
		pipe = makeStringParser( '|' );

		function template() {
			var result = sequence( [
				openTemplate,
				templateContents,
				closeTemplate
			] );
			return result === null ? null : result[ 1 ];
		}

		function pipedWikilink() {
			var result = sequence( [
				nOrMore( 1, paramExpression ),
				pipe,
				nOrMore( 1, expression )
			] );
			return result === null ? null : [
				[ 'CONCAT' ].concat( result[ 0 ] ),
				[ 'CONCAT' ].concat( result[ 2 ] )
			];
		}

		function unpipedWikilink() {
			var result = sequence( [
				nOrMore( 1, paramExpression )
			] );
			return result === null ? null : [
				[ 'CONCAT' ].concat( result[ 0 ] )
			];
		}

		wikilinkContents = choice( [
			pipedWikilink,
			unpipedWikilink
		] );

		function wikilink() {
			var result, parsedResult, parsedLinkContents;
			result = null;

			parsedResult = sequence( [
				openWikilink,
				wikilinkContents,
				closeWikilink
			] );
			if ( parsedResult !== null ) {
				parsedLinkContents = parsedResult[ 1 ];
				result = [ 'WIKILINK' ].concat( parsedLinkContents );
			}
			return result;
		}

		// TODO: Support data- if appropriate
		function doubleQuotedHtmlAttributeValue() {
			var parsedResult = sequence( [
				doubleQuote,
				htmlDoubleQuoteAttributeValue,
				doubleQuote
			] );
			return parsedResult === null ? null : parsedResult[ 1 ];
		}

		function singleQuotedHtmlAttributeValue() {
			var parsedResult = sequence( [
				singleQuote,
				htmlSingleQuoteAttributeValue,
				singleQuote
			] );
			return parsedResult === null ? null : parsedResult[ 1 ];
		}

		function htmlAttribute() {
			var parsedResult = sequence( [
				whitespace,
				asciiAlphabetLiteral,
				htmlAttributeEquals,
				choice( [
					doubleQuotedHtmlAttributeValue,
					singleQuotedHtmlAttributeValue
				] )
			] );
			return parsedResult === null ? null : [ parsedResult[ 1 ], parsedResult[ 3 ] ];
		}

		/**
		 * Checks if HTML is allowed
		 *
		 * @param {string} startTagName HTML start tag name
		 * @param {string} endTagName HTML start tag name
		 * @param {Object} attributes array of consecutive key value pairs,
		 *  with index 2 * n being a name and 2 * n + 1 the associated value
		 * @return {boolean} true if this is HTML is allowed, false otherwise
		 */
		function isAllowedHtml( startTagName, endTagName, attributes ) {
			var i, len, attributeName, badStyle;

			startTagName = startTagName.toLowerCase();
			endTagName = endTagName.toLowerCase();
			if ( startTagName !== endTagName || settings.allowedHtmlElements.indexOf( startTagName ) === -1 ) {
				return false;
			}

			badStyle = /[\000-\010\013\016-\037\177]|expression|filter\s*:|accelerator\s*:|-o-link\s*:|-o-link-source\s*:|-o-replace\s*:|url\s*\(|image\s*\(|image-set\s*\(/i;

			for ( i = 0, len = attributes.length; i < len; i += 2 ) {
				attributeName = attributes[ i ];
				if ( settings.allowedHtmlCommonAttributes.indexOf( attributeName ) === -1 &&
					( settings.allowedHtmlAttributesByElement[ startTagName ] || [] ).indexOf( attributeName ) === -1 ) {
					return false;
				}
				if ( attributeName === 'style' && attributes[ i + 1 ].search( badStyle ) !== -1 ) {
					mw.log( 'HTML tag not parsed due to dangerous style attribute' );
					return false;
				}
			}

			return true;
		}

		function htmlAttributes() {
			var parsedResult = nOrMore( 0, htmlAttribute )();
			// Un-nest attributes array due to structure of jQueryMsg operations (see emit).
			return concat.apply( [ 'HTMLATTRIBUTES' ], parsedResult );
		}

		// Subset of allowed HTML markup.
		// Most elements and many attributes allowed on the server are not supported yet.
		function html() {
			var parsedOpenTagResult, parsedHtmlContents, parsedCloseTagResult,
				wrappedAttributes, attributes, startTagName, endTagName, startOpenTagPos,
				startCloseTagPos, endOpenTagPos, endCloseTagPos,
				result = null;

			// Break into three sequence calls.  That should allow accurate reconstruction of the original HTML, and requiring an exact tag name match.
			// 1. open through closeHtmlTag
			// 2. expression
			// 3. openHtmlEnd through close
			// This will allow recording the positions to reconstruct if HTML is to be treated as text.

			startOpenTagPos = pos;
			parsedOpenTagResult = sequence( [
				openHtmlStartTag,
				asciiAlphabetLiteral,
				htmlAttributes,
				optionalForwardSlash,
				closeHtmlTag
			] );

			if ( parsedOpenTagResult === null ) {
				return null;
			}

			endOpenTagPos = pos;
			startTagName = parsedOpenTagResult[ 1 ];

			parsedHtmlContents = nOrMore( 0, expression )();

			startCloseTagPos = pos;
			parsedCloseTagResult = sequence( [
				openHtmlEndTag,
				asciiAlphabetLiteral,
				closeHtmlTag
			] );

			if ( parsedCloseTagResult === null ) {
				// Closing tag failed.  Return the start tag and contents.
				return [ 'CONCAT', input.slice( startOpenTagPos, endOpenTagPos ) ]
					.concat( parsedHtmlContents );
			}

			endCloseTagPos = pos;
			endTagName = parsedCloseTagResult[ 1 ];
			wrappedAttributes = parsedOpenTagResult[ 2 ];
			attributes = wrappedAttributes.slice( 1 );
			if ( isAllowedHtml( startTagName, endTagName, attributes ) ) {
				result = [ 'HTMLELEMENT', startTagName, wrappedAttributes ]
					.concat( parsedHtmlContents );
			} else {
				// HTML is not allowed, so contents will remain how
				// it was, while HTML markup at this level will be
				// treated as text
				// E.g. assuming script tags are not allowed:
				//
				// <script>[[Foo|bar]]</script>
				//
				// results in '&lt;script&gt;' and '&lt;/script&gt;'
				// (not treated as an HTML tag), surrounding a fully
				// parsed HTML link.
				//
				// Concatenate everything from the tag, flattening the contents.
				result = [ 'CONCAT', input.slice( startOpenTagPos, endOpenTagPos ) ]
					.concat( parsedHtmlContents, input.slice( startCloseTagPos, endCloseTagPos ) );
			}

			return result;
		}

		// <nowiki>...</nowiki> tag. The tags are stripped and the contents are returned unparsed.
		function nowiki() {
			var parsedResult, plainText,
				result = null;

			parsedResult = sequence( [
				makeStringParser( '<nowiki>' ),
				// We use a greedy non-backtracking parser, so we must ensure here that we don't take too much
				makeRegexParser( /^.*?(?=<\/nowiki>)/ ),
				makeStringParser( '</nowiki>' )
			] );
			if ( parsedResult !== null ) {
				plainText = parsedResult[ 1 ];
				result = [ 'CONCAT' ].concat( plainText );
			}

			return result;
		}

		templateName = transform(
			// see $wgLegalTitleChars
			// not allowing : due to the need to catch "PLURAL:$1"
			makeRegexParser( /^[ !"$&'()*,./0-9;=?@A-Z^_`a-z~\x80-\xFF+-]+/ ),
			function ( result ) { return result.toString(); }
		);
		function templateParam() {
			var expr, result;
			result = sequence( [
				pipe,
				nOrMore( 0, paramExpression )
			] );
			if ( result === null ) {
				return null;
			}
			expr = result[ 1 ];
			// use a CONCAT operator if there are multiple nodes, otherwise return the first node, raw.
			return expr.length > 1 ? [ 'CONCAT' ].concat( expr ) : expr[ 0 ];
		}

		function templateWithReplacement() {
			var result = sequence( [
				templateName,
				colon,
				replacement
			] );
			return result === null ? null : [ result[ 0 ], result[ 2 ] ];
		}
		function templateWithOutReplacement() {
			var result = sequence( [
				templateName,
				colon,
				paramExpression
			] );
			return result === null ? null : [ result[ 0 ], result[ 2 ] ];
		}
		function templateWithOutFirstParameter() {
			var result = sequence( [
				templateName,
				colon
			] );
			return result === null ? null : [ result[ 0 ], '' ];
		}
		colon = makeStringParser( ':' );
		templateContents = choice( [
			function () {
				var result = sequence( [
					// templates can have placeholders for dynamic replacement eg: {{PLURAL:$1|one car|$1 cars}}
					// or no placeholders eg: {{GRAMMAR:genitive|{{SITENAME}}}
					choice( [ templateWithReplacement, templateWithOutReplacement, templateWithOutFirstParameter ] ),
					nOrMore( 0, templateParam )
				] );
				return result === null ? null : result[ 0 ].concat( result[ 1 ] );
			},
			function () {
				var result = sequence( [
					templateName,
					nOrMore( 0, templateParam )
				] );
				if ( result === null ) {
					return null;
				}
				return [ result[ 0 ] ].concat( result[ 1 ] );
			}
		] );
		openTemplate = makeStringParser( '{{' );
		closeTemplate = makeStringParser( '}}' );
		nonWhitespaceExpression = choice( [
			template,
			wikilink,
			extlink,
			replacement,
			literalWithoutSpace
		] );
		paramExpression = choice( [
			template,
			wikilink,
			extlink,
			replacement,
			literalWithoutBar
		] );

		expression = choice( [
			template,
			wikilink,
			extlink,
			replacement,
			nowiki,
			html,
			literal
		] );

		// Used when only {{-transformation is wanted, for 'text'
		// or 'escaped' formats
		curlyBraceTransformExpression = choice( [
			template,
			replacement,
			curlyBraceTransformExpressionLiteral
		] );

		/**
		 * Starts the parse
		 *
		 * @param {Function} rootExpression Root parse function
		 * @return {Array|null}
		 */
		function start( rootExpression ) {
			var result = nOrMore( 0, rootExpression )();
			if ( result === null ) {
				return null;
			}
			return [ 'CONCAT' ].concat( result );
		}
		// everything above this point is supposed to be stateless/static, but
		// I am deferring the work of turning it into prototypes & objects. It's quite fast enough
		// finally let's do some actual work...

		res = start( this.settings.onlyCurlyBraceTransform ? curlyBraceTransformExpression : expression );

		/*
		 * For success, the p must have gotten to the end of the input
		 * and returned a non-null.
		 * n.b. This is part of language infrastructure, so we do not throw an internationalizable message.
		 */
		if ( res === null || pos !== input.length ) {
			throw new Error( 'Parse error at position ' + pos.toString() + ' in input: ' + input );
		}
		return res;
	}

};

/**
 * Class that primarily exists to emit HTML from parser ASTs.
 *
 * @private
 * @class
 * @param {Object} language
 * @param {Object} magic
 */
mw.jqueryMsg.HtmlEmitter = function ( language, magic ) {
	var jmsg = this;
	this.language = language;
	// eslint-disable-next-line no-jquery/no-each-util
	$.each( magic, function ( key, val ) {
		jmsg[ key.toLowerCase() ] = function () {
			return val;
		};
	} );

	/**
	 * (We put this method definition here, and not in prototype, to make sure it's not overwritten by any magic.)
	 * Walk entire node structure, applying replacements and template functions when appropriate
	 *
	 * @param {Mixed} node Abstract syntax tree (top node or subnode)
	 * @param {Array} replacements for $1, $2, ... $n
	 * @return {Mixed} single-string node or array of nodes suitable for jQuery appending
	 */
	this.emit = function ( node, replacements ) {
		var ret, subnodes, operation;
		switch ( typeof node ) {
			case 'string':
			case 'number':
				ret = node;
				break;
			// typeof returns object for arrays
			case 'object':
				// node is an array of nodes
				// eslint-disable-next-line no-jquery/no-map-util
				subnodes = $.map( node.slice( 1 ), function ( n ) {
					return jmsg.emit( n, replacements );
				} );
				operation = node[ 0 ].toLowerCase();
				if ( typeof jmsg[ operation ] === 'function' ) {
					ret = jmsg[ operation ]( subnodes, replacements );
				} else {
					throw new Error( 'Unknown operation "' + operation + '"' );
				}
				break;
			case 'undefined':
				// Parsing the empty string (as an entire expression, or as a paramExpression in a template) results in undefined
				// Perhaps a more clever parser can detect this, and return the empty string? Or is that useful information?
				// The logical thing is probably to return the empty string here when we encounter undefined.
				ret = '';
				break;
			default:
				throw new Error( 'Unexpected type in AST: ' + typeof node );
		}
		return ret;
	};
};

// BIDI utility function, copied from jquery.i18n.emitter.bidi.js
//
// Matches the first strong directionality codepoint:
// - in group 1 if it is LTR
// - in group 2 if it is RTL
// Does not match if there is no strong directionality codepoint.
//
// Generated by UnicodeJS (see tools/strongDir) from the UCD; see
// https://phabricator.wikimedia.org/source/unicodejs/ .
// eslint-disable-next-line no-misleading-character-class
strongDirRegExp = new RegExp(
	'(?:' +
		'(' +
			'[\u0041-\u005a\u0061-\u007a\u00aa\u00b5\u00ba\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u02b8\u02bb-\u02c1\u02d0\u02d1\u02e0-\u02e4\u02ee\u0370-\u0373\u0376\u0377\u037a-\u037d\u037f\u0386\u0388-\u038a\u038c\u038e-\u03a1\u03a3-\u03f5\u03f7-\u0482\u048a-\u052f\u0531-\u0556\u0559-\u055f\u0561-\u0587\u0589\u0903-\u0939\u093b\u093d-\u0940\u0949-\u094c\u094e-\u0950\u0958-\u0961\u0964-\u0980\u0982\u0983\u0985-\u098c\u098f\u0990\u0993-\u09a8\u09aa-\u09b0\u09b2\u09b6-\u09b9\u09bd-\u09c0\u09c7\u09c8\u09cb\u09cc\u09ce\u09d7\u09dc\u09dd\u09df-\u09e1\u09e6-\u09f1\u09f4-\u09fa\u0a03\u0a05-\u0a0a\u0a0f\u0a10\u0a13-\u0a28\u0a2a-\u0a30\u0a32\u0a33\u0a35\u0a36\u0a38\u0a39\u0a3e-\u0a40\u0a59-\u0a5c\u0a5e\u0a66-\u0a6f\u0a72-\u0a74\u0a83\u0a85-\u0a8d\u0a8f-\u0a91\u0a93-\u0aa8\u0aaa-\u0ab0\u0ab2\u0ab3\u0ab5-\u0ab9\u0abd-\u0ac0\u0ac9\u0acb\u0acc\u0ad0\u0ae0\u0ae1\u0ae6-\u0af0\u0af9\u0b02\u0b03\u0b05-\u0b0c\u0b0f\u0b10\u0b13-\u0b28\u0b2a-\u0b30\u0b32\u0b33\u0b35-\u0b39\u0b3d\u0b3e\u0b40\u0b47\u0b48\u0b4b\u0b4c\u0b57\u0b5c\u0b5d\u0b5f-\u0b61\u0b66-\u0b77\u0b83\u0b85-\u0b8a\u0b8e-\u0b90\u0b92-\u0b95\u0b99\u0b9a\u0b9c\u0b9e\u0b9f\u0ba3\u0ba4\u0ba8-\u0baa\u0bae-\u0bb9\u0bbe\u0bbf\u0bc1\u0bc2\u0bc6-\u0bc8\u0bca-\u0bcc\u0bd0\u0bd7\u0be6-\u0bf2\u0c01-\u0c03\u0c05-\u0c0c\u0c0e-\u0c10\u0c12-\u0c28\u0c2a-\u0c39\u0c3d\u0c41-\u0c44\u0c58-\u0c5a\u0c60\u0c61\u0c66-\u0c6f\u0c7f\u0c82\u0c83\u0c85-\u0c8c\u0c8e-\u0c90\u0c92-\u0ca8\u0caa-\u0cb3\u0cb5-\u0cb9\u0cbd-\u0cc4\u0cc6-\u0cc8\u0cca\u0ccb\u0cd5\u0cd6\u0cde\u0ce0\u0ce1\u0ce6-\u0cef\u0cf1\u0cf2\u0d02\u0d03\u0d05-\u0d0c\u0d0e-\u0d10\u0d12-\u0d3a\u0d3d-\u0d40\u0d46-\u0d48\u0d4a-\u0d4c\u0d4e\u0d57\u0d5f-\u0d61\u0d66-\u0d75\u0d79-\u0d7f\u0d82\u0d83\u0d85-\u0d96\u0d9a-\u0db1\u0db3-\u0dbb\u0dbd\u0dc0-\u0dc6\u0dcf-\u0dd1\u0dd8-\u0ddf\u0de6-\u0def\u0df2-\u0df4\u0e01-\u0e30\u0e32\u0e33\u0e40-\u0e46\u0e4f-\u0e5b\u0e81\u0e82\u0e84\u0e87\u0e88\u0e8a\u0e8d\u0e94-\u0e97\u0e99-\u0e9f\u0ea1-\u0ea3\u0ea5\u0ea7\u0eaa\u0eab\u0ead-\u0eb0\u0eb2\u0eb3\u0ebd\u0ec0-\u0ec4\u0ec6\u0ed0-\u0ed9\u0edc-\u0edf\u0f00-\u0f17\u0f1a-\u0f34\u0f36\u0f38\u0f3e-\u0f47\u0f49-\u0f6c\u0f7f\u0f85\u0f88-\u0f8c\u0fbe-\u0fc5\u0fc7-\u0fcc\u0fce-\u0fda\u1000-\u102c\u1031\u1038\u103b\u103c\u103f-\u1057\u105a-\u105d\u1061-\u1070\u1075-\u1081\u1083\u1084\u1087-\u108c\u108e-\u109c\u109e-\u10c5\u10c7\u10cd\u10d0-\u1248\u124a-\u124d\u1250-\u1256\u1258\u125a-\u125d\u1260-\u1288\u128a-\u128d\u1290-\u12b0\u12b2-\u12b5\u12b8-\u12be\u12c0\u12c2-\u12c5\u12c8-\u12d6\u12d8-\u1310\u1312-\u1315\u1318-\u135a\u1360-\u137c\u1380-\u138f\u13a0-\u13f5\u13f8-\u13fd\u1401-\u167f\u1681-\u169a\u16a0-\u16f8\u1700-\u170c\u170e-\u1711\u1720-\u1731\u1735\u1736\u1740-\u1751\u1760-\u176c\u176e-\u1770\u1780-\u17b3\u17b6\u17be-\u17c5\u17c7\u17c8\u17d4-\u17da\u17dc\u17e0-\u17e9\u1810-\u1819\u1820-\u1877\u1880-\u18a8\u18aa\u18b0-\u18f5\u1900-\u191e\u1923-\u1926\u1929-\u192b\u1930\u1931\u1933-\u1938\u1946-\u196d\u1970-\u1974\u1980-\u19ab\u19b0-\u19c9\u19d0-\u19da\u1a00-\u1a16\u1a19\u1a1a\u1a1e-\u1a55\u1a57\u1a61\u1a63\u1a64\u1a6d-\u1a72\u1a80-\u1a89\u1a90-\u1a99\u1aa0-\u1aad\u1b04-\u1b33\u1b35\u1b3b\u1b3d-\u1b41\u1b43-\u1b4b\u1b50-\u1b6a\u1b74-\u1b7c\u1b82-\u1ba1\u1ba6\u1ba7\u1baa\u1bae-\u1be5\u1be7\u1bea-\u1bec\u1bee\u1bf2\u1bf3\u1bfc-\u1c2b\u1c34\u1c35\u1c3b-\u1c49\u1c4d-\u1c7f\u1cc0-\u1cc7\u1cd3\u1ce1\u1ce9-\u1cec\u1cee-\u1cf3\u1cf5\u1cf6\u1d00-\u1dbf\u1e00-\u1f15\u1f18-\u1f1d\u1f20-\u1f45\u1f48-\u1f4d\u1f50-\u1f57\u1f59\u1f5b\u1f5d\u1f5f-\u1f7d\u1f80-\u1fb4\u1fb6-\u1fbc\u1fbe\u1fc2-\u1fc4\u1fc6-\u1fcc\u1fd0-\u1fd3\u1fd6-\u1fdb\u1fe0-\u1fec\u1ff2-\u1ff4\u1ff6-\u1ffc\u200e\u2071\u207f\u2090-\u209c\u2102\u2107\u210a-\u2113\u2115\u2119-\u211d\u2124\u2126\u2128\u212a-\u212d\u212f-\u2139\u213c-\u213f\u2145-\u2149\u214e\u214f\u2160-\u2188\u2336-\u237a\u2395\u249c-\u24e9\u26ac\u2800-\u28ff\u2c00-\u2c2e\u2c30-\u2c5e\u2c60-\u2ce4\u2ceb-\u2cee\u2cf2\u2cf3\u2d00-\u2d25\u2d27\u2d2d\u2d30-\u2d67\u2d6f\u2d70\u2d80-\u2d96\u2da0-\u2da6\u2da8-\u2dae\u2db0-\u2db6\u2db8-\u2dbe\u2dc0-\u2dc6\u2dc8-\u2dce\u2dd0-\u2dd6\u2dd8-\u2dde\u3005-\u3007\u3021-\u3029\u302e\u302f\u3031-\u3035\u3038-\u303c\u3041-\u3096\u309d-\u309f\u30a1-\u30fa\u30fc-\u30ff\u3105-\u312d\u3131-\u318e\u3190-\u31ba\u31f0-\u321c\u3220-\u324f\u3260-\u327b\u327f-\u32b0\u32c0-\u32cb\u32d0-\u32fe\u3300-\u3376\u337b-\u33dd\u33e0-\u33fe\u3400-\u4db5\u4e00-\u9fd5\ua000-\ua48c\ua4d0-\ua60c\ua610-\ua62b\ua640-\ua66e\ua680-\ua69d\ua6a0-\ua6ef\ua6f2-\ua6f7\ua722-\ua787\ua789-\ua7ad\ua7b0-\ua7b7\ua7f7-\ua801\ua803-\ua805\ua807-\ua80a\ua80c-\ua824\ua827\ua830-\ua837\ua840-\ua873\ua880-\ua8c3\ua8ce-\ua8d9\ua8f2-\ua8fd\ua900-\ua925\ua92e-\ua946\ua952\ua953\ua95f-\ua97c\ua983-\ua9b2\ua9b4\ua9b5\ua9ba\ua9bb\ua9bd-\ua9cd\ua9cf-\ua9d9\ua9de-\ua9e4\ua9e6-\ua9fe\uaa00-\uaa28\uaa2f\uaa30\uaa33\uaa34\uaa40-\uaa42\uaa44-\uaa4b\uaa4d\uaa50-\uaa59\uaa5c-\uaa7b\uaa7d-\uaaaf\uaab1\uaab5\uaab6\uaab9-\uaabd\uaac0\uaac2\uaadb-\uaaeb\uaaee-\uaaf5\uab01-\uab06\uab09-\uab0e\uab11-\uab16\uab20-\uab26\uab28-\uab2e\uab30-\uab65\uab70-\uabe4\uabe6\uabe7\uabe9-\uabec\uabf0-\uabf9\uac00-\ud7a3\ud7b0-\ud7c6\ud7cb-\ud7fb\ue000-\ufa6d\ufa70-\ufad9\ufb00-\ufb06\ufb13-\ufb17\uff21-\uff3a\uff41-\uff5a\uff66-\uffbe\uffc2-\uffc7\uffca-\uffcf\uffd2-\uffd7\uffda-\uffdc]|\ud800[\udc00-\udc0b]|\ud800[\udc0d-\udc26]|\ud800[\udc28-\udc3a]|\ud800\udc3c|\ud800\udc3d|\ud800[\udc3f-\udc4d]|\ud800[\udc50-\udc5d]|\ud800[\udc80-\udcfa]|\ud800\udd00|\ud800\udd02|\ud800[\udd07-\udd33]|\ud800[\udd37-\udd3f]|\ud800[\uddd0-\uddfc]|\ud800[\ude80-\ude9c]|\ud800[\udea0-\uded0]|\ud800[\udf00-\udf23]|\ud800[\udf30-\udf4a]|\ud800[\udf50-\udf75]|\ud800[\udf80-\udf9d]|\ud800[\udf9f-\udfc3]|\ud800[\udfc8-\udfd5]|\ud801[\udc00-\udc9d]|\ud801[\udca0-\udca9]|\ud801[\udd00-\udd27]|\ud801[\udd30-\udd63]|\ud801\udd6f|\ud801[\ude00-\udf36]|\ud801[\udf40-\udf55]|\ud801[\udf60-\udf67]|\ud804\udc00|\ud804[\udc02-\udc37]|\ud804[\udc47-\udc4d]|\ud804[\udc66-\udc6f]|\ud804[\udc82-\udcb2]|\ud804\udcb7|\ud804\udcb8|\ud804[\udcbb-\udcc1]|\ud804[\udcd0-\udce8]|\ud804[\udcf0-\udcf9]|\ud804[\udd03-\udd26]|\ud804\udd2c|\ud804[\udd36-\udd43]|\ud804[\udd50-\udd72]|\ud804[\udd74-\udd76]|\ud804[\udd82-\uddb5]|\ud804[\uddbf-\uddc9]|\ud804\uddcd|\ud804[\uddd0-\udddf]|\ud804[\udde1-\uddf4]|\ud804[\ude00-\ude11]|\ud804[\ude13-\ude2e]|\ud804\ude32|\ud804\ude33|\ud804\ude35|\ud804[\ude38-\ude3d]|\ud804[\ude80-\ude86]|\ud804\ude88|\ud804[\ude8a-\ude8d]|\ud804[\ude8f-\ude9d]|\ud804[\ude9f-\udea9]|\ud804[\udeb0-\udede]|\ud804[\udee0-\udee2]|\ud804[\udef0-\udef9]|\ud804\udf02|\ud804\udf03|\ud804[\udf05-\udf0c]|\ud804\udf0f|\ud804\udf10|\ud804[\udf13-\udf28]|\ud804[\udf2a-\udf30]|\ud804\udf32|\ud804\udf33|\ud804[\udf35-\udf39]|\ud804[\udf3d-\udf3f]|\ud804[\udf41-\udf44]|\ud804\udf47|\ud804\udf48|\ud804[\udf4b-\udf4d]|\ud804\udf50|\ud804\udf57|\ud804[\udf5d-\udf63]|\ud805[\udc80-\udcb2]|\ud805\udcb9|\ud805[\udcbb-\udcbe]|\ud805\udcc1|\ud805[\udcc4-\udcc7]|\ud805[\udcd0-\udcd9]|\ud805[\udd80-\uddb1]|\ud805[\uddb8-\uddbb]|\ud805\uddbe|\ud805[\uddc1-\udddb]|\ud805[\ude00-\ude32]|\ud805\ude3b|\ud805\ude3c|\ud805\ude3e|\ud805[\ude41-\ude44]|\ud805[\ude50-\ude59]|\ud805[\ude80-\udeaa]|\ud805\udeac|\ud805\udeae|\ud805\udeaf|\ud805\udeb6|\ud805[\udec0-\udec9]|\ud805[\udf00-\udf19]|\ud805\udf20|\ud805\udf21|\ud805\udf26|\ud805[\udf30-\udf3f]|\ud806[\udca0-\udcf2]|\ud806\udcff|\ud806[\udec0-\udef8]|\ud808[\udc00-\udf99]|\ud809[\udc00-\udc6e]|\ud809[\udc70-\udc74]|\ud809[\udc80-\udd43]|\ud80c[\udc00-\udfff]|\ud80d[\udc00-\udc2e]|\ud811[\udc00-\ude46]|\ud81a[\udc00-\ude38]|\ud81a[\ude40-\ude5e]|\ud81a[\ude60-\ude69]|\ud81a\ude6e|\ud81a\ude6f|\ud81a[\uded0-\udeed]|\ud81a\udef5|\ud81a[\udf00-\udf2f]|\ud81a[\udf37-\udf45]|\ud81a[\udf50-\udf59]|\ud81a[\udf5b-\udf61]|\ud81a[\udf63-\udf77]|\ud81a[\udf7d-\udf8f]|\ud81b[\udf00-\udf44]|\ud81b[\udf50-\udf7e]|\ud81b[\udf93-\udf9f]|\ud82c\udc00|\ud82c\udc01|\ud82f[\udc00-\udc6a]|\ud82f[\udc70-\udc7c]|\ud82f[\udc80-\udc88]|\ud82f[\udc90-\udc99]|\ud82f\udc9c|\ud82f\udc9f|\ud834[\udc00-\udcf5]|\ud834[\udd00-\udd26]|\ud834[\udd29-\udd66]|\ud834[\udd6a-\udd72]|\ud834\udd83|\ud834\udd84|\ud834[\udd8c-\udda9]|\ud834[\uddae-\udde8]|\ud834[\udf60-\udf71]|\ud835[\udc00-\udc54]|\ud835[\udc56-\udc9c]|\ud835\udc9e|\ud835\udc9f|\ud835\udca2|\ud835\udca5|\ud835\udca6|\ud835[\udca9-\udcac]|\ud835[\udcae-\udcb9]|\ud835\udcbb|\ud835[\udcbd-\udcc3]|\ud835[\udcc5-\udd05]|\ud835[\udd07-\udd0a]|\ud835[\udd0d-\udd14]|\ud835[\udd16-\udd1c]|\ud835[\udd1e-\udd39]|\ud835[\udd3b-\udd3e]|\ud835[\udd40-\udd44]|\ud835\udd46|\ud835[\udd4a-\udd50]|\ud835[\udd52-\udea5]|\ud835[\udea8-\udeda]|\ud835[\udedc-\udf14]|\ud835[\udf16-\udf4e]|\ud835[\udf50-\udf88]|\ud835[\udf8a-\udfc2]|\ud835[\udfc4-\udfcb]|\ud836[\udc00-\uddff]|\ud836[\ude37-\ude3a]|\ud836[\ude6d-\ude74]|\ud836[\ude76-\ude83]|\ud836[\ude85-\ude8b]|\ud83c[\udd10-\udd2e]|\ud83c[\udd30-\udd69]|\ud83c[\udd70-\udd9a]|\ud83c[\udde6-\ude02]|\ud83c[\ude10-\ude3a]|\ud83c[\ude40-\ude48]|\ud83c\ude50|\ud83c\ude51|[\ud840-\ud868][\udc00-\udfff]|\ud869[\udc00-\uded6]|\ud869[\udf00-\udfff]|[\ud86a-\ud86c][\udc00-\udfff]|\ud86d[\udc00-\udf34]|\ud86d[\udf40-\udfff]|\ud86e[\udc00-\udc1d]|\ud86e[\udc20-\udfff]|[\ud86f-\ud872][\udc00-\udfff]|\ud873[\udc00-\udea1]|\ud87e[\udc00-\ude1d]|[\udb80-\udbbe][\udc00-\udfff]|\udbbf[\udc00-\udffd]|[\udbc0-\udbfe][\udc00-\udfff]|\udbff[\udc00-\udffd]' +
		')|(' +
			'[\u0590\u05be\u05c0\u05c3\u05c6\u05c8-\u05ff\u07c0-\u07ea\u07f4\u07f5\u07fa-\u0815\u081a\u0824\u0828\u082e-\u0858\u085c-\u089f\u200f\ufb1d\ufb1f-\ufb28\ufb2a-\ufb4f\u0608\u060b\u060d\u061b-\u064a\u066d-\u066f\u0671-\u06d5\u06e5\u06e6\u06ee\u06ef\u06fa-\u0710\u0712-\u072f\u074b-\u07a5\u07b1-\u07bf\u08a0-\u08e2\ufb50-\ufd3d\ufd40-\ufdcf\ufdf0-\ufdfc\ufdfe\ufdff\ufe70-\ufefe]|\ud802[\udc00-\udd1e]|\ud802[\udd20-\ude00]|\ud802\ude04|\ud802[\ude07-\ude0b]|\ud802[\ude10-\ude37]|\ud802[\ude3b-\ude3e]|\ud802[\ude40-\udee4]|\ud802[\udee7-\udf38]|\ud802[\udf40-\udfff]|\ud803[\udc00-\ude5f]|\ud803[\ude7f-\udfff]|\ud83a[\udc00-\udccf]|\ud83a[\udcd7-\udfff]|\ud83b[\udc00-\uddff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\udf00-\udfff]|\ud83b[\ude00-\udeef]|\ud83b[\udef2-\udeff]' +
		')' +
	')'
);

/**
 * Gets directionality of the first strongly directional codepoint
 *
 * This is the rule the BIDI algorithm uses to determine the directionality of
 * paragraphs ( http://unicode.org/reports/tr9/#The_Paragraph_Level ) and
 * FSI isolates ( http://unicode.org/reports/tr9/#Explicit_Directional_Isolates ).
 *
 * TODO: Does not handle BIDI control characters inside the text.
 * TODO: Does not handle unallocated characters.
 *
 * @param {string} text The text from which to extract initial directionality.
 * @return {string} Directionality (either 'ltr' or 'rtl')
 */
function strongDirFromContent( text ) {
	var m = text.match( strongDirRegExp );
	if ( !m ) {
		return null;
	}
	if ( m[ 2 ] === undefined ) {
		return 'ltr';
	}
	return 'rtl';
}

// For everything in input that follows double-open-curly braces, there should be an equivalent parser
// function. For instance {{PLURAL ... }} will be processed by 'plural'.
// If you have 'magic words' then configure the parser to have them upon creation.
//
// An emitter method takes the parent node, the array of subnodes and the array of replacements (the values that $1, $2... should translate to).
// Note: all such functions must be pure, with the exception of referring to other pure functions via this.language (convertPlural and so on)
mw.jqueryMsg.HtmlEmitter.prototype = {
	/**
	 * Parsing has been applied depth-first we can assume that all nodes here are single nodes
	 * Must return a single node to parents -- a jQuery with synthetic span
	 * However, unwrap any other synthetic spans in our children and pass them upwards
	 *
	 * @param {Mixed[]} nodes Some single nodes, some arrays of nodes
	 * @return {jQuery}
	 */
	concat: function ( nodes ) {
		var $span = $( '<span>' ).addClass( 'mediaWiki_htmlEmitter' );
		// eslint-disable-next-line no-jquery/no-each-util
		$.each( nodes, function ( i, node ) {
			// Let jQuery append nodes, arrays of nodes and jQuery objects
			// other things (strings, numbers, ..) are appended as text nodes (not as HTML strings)
			appendWithoutParsing( $span, node );
		} );
		return $span;
	},

	/**
	 * Return escaped replacement of correct index, or string if unavailable.
	 * Note that we expect the parsed parameter to be zero-based. i.e. $1 should have become [ 0 ].
	 * if the specified parameter is not found return the same string
	 * (e.g. "$99" -> parameter 98 -> not found -> return "$99" )
	 *
	 * If the replacement at the index is an object, then a special property
	 * is is added to it (if it does not exist already).
	 * If the special property was already set, then we try to clone (instead of append)
	 * the replacement object. This allows allow using a jQuery or HTMLElement object
	 * multiple times within a single interface message.
	 *
	 * TODO: Throw error if nodes.length > 1 ?
	 *
	 * @param {Array} nodes List of one element, integer, n >= 0
	 * @param {Array} replacements List of at least n strings
	 * @return {string|jQuery} replacement
	 */
	replace: function ( nodes, replacements ) {
		var index = parseInt( nodes[ 0 ], 10 );

		if ( index < replacements.length ) {
			if ( typeof replacements[ index ] === 'object' ) {
				// Only actually clone on second use
				if ( !replacements[ index ].mwJQueryMsgHasAlreadyBeenUsedAsAReplacement ) {
					// Add our special property to the foreign object
					// in the least invasive way
					Object.defineProperty(
						replacements[ index ],
						'mwJQueryMsgHasAlreadyBeenUsedAsAReplacement',
						{
							value: true,
							enumerable: false,
							writable: false
						}
					);
					return replacements[ index ];
				}
				if ( typeof replacements[ index ].clone === 'function' ) {
					// if it is a jQuery object, use jQuery's clone method
					return replacements[ index ].clone( true );
				}
				if ( typeof replacements[ index ].cloneNode === 'function' ) {
					// if it is a Node, then use the native cloning functionality
					return replacements[ index ].cloneNode( true );
				}
				return replacements[ index ];
			}
			return replacements[ index ];
		} else {
			// index not found, fallback to displaying variable
			return '$' + ( index + 1 );
		}
	},

	/**
	 * Transform wiki-link
	 *
	 * TODO:
	 * It only handles basic cases, either no pipe, or a pipe with an explicit
	 * anchor.
	 *
	 * It does not attempt to handle features like the pipe trick.
	 * However, the pipe trick should usually not be present in wikitext retrieved
	 * from the server, since the replacement is done at save time.
	 * It may, though, if the wikitext appears in extension-controlled content.
	 *
	 * @param {string[]} nodes
	 * @return {jQuery}
	 */
	wikilink: function ( nodes ) {
		var page, anchor, url, $el;

		page = textify( nodes[ 0 ] );
		// Strip leading ':', which is used to suppress special behavior in wikitext links,
		// e.g. [[:Category:Foo]] or [[:File:Foo.jpg]]
		if ( page.charAt( 0 ) === ':' ) {
			page = page.slice( 1 );
		}
		url = mw.util.getUrl( page );

		if ( nodes.length === 1 ) {
			// [[Some Page]] or [[Namespace:Some Page]]
			anchor = page;
		} else {
			// [[Some Page|anchor text]] or [[Namespace:Some Page|anchor]]
			anchor = nodes[ 1 ];
		}

		$el = $( '<a>' ).attr( {
			title: page,
			href: url
		} );
		return appendWithoutParsing( $el, anchor );
	},

	/**
	 * Converts array of HTML element key value pairs to object
	 *
	 * @param {Array} nodes Array of consecutive key value pairs, with index 2 * n being a
	 *  name and 2 * n + 1 the associated value
	 * @return {Object} Object mapping attribute name to attribute value
	 */
	htmlattributes: function ( nodes ) {
		var i, len, mapping = {};
		for ( i = 0, len = nodes.length; i < len; i += 2 ) {
			mapping[ nodes[ i ] ] = decodePrimaryHtmlEntities( nodes[ i + 1 ] );
		}
		return mapping;
	},

	/**
	 * Handles an (already-validated) HTML element.
	 *
	 * @param {Array} nodes Nodes to process when creating element
	 * @return {jQuery}
	 */
	htmlelement: function ( nodes ) {
		var tagName, attributes, contents, $element;

		tagName = nodes.shift();
		attributes = nodes.shift();
		contents = nodes;
		$element = $( document.createElement( tagName ) ).attr( attributes );
		return appendWithoutParsing( $element, contents );
	},

	/**
	 * Transform parsed structure into external link.
	 *
	 * The "href" can be:
	 * - a jQuery object, treat it as "enclosing" the link text.
	 * - a function, treat it as the click handler.
	 * - a string, or our HtmlEmitter jQuery object, treat it as a URI after stringifying.
	 *
	 * TODO: throw an error if nodes.length > 2 ?
	 *
	 * @param {Array} nodes List of two elements, {jQuery|Function|String} and {string}
	 * @return {jQuery}
	 */
	extlink: function ( nodes ) {
		var $el,
			arg = nodes[ 0 ],
			contents = nodes[ 1 ],
			target;
		if ( arg instanceof $ && !arg.hasClass( 'mediaWiki_htmlEmitter' ) ) {
			$el = arg;
		} else {
			$el = $( '<a>' );
			if ( typeof arg === 'function' ) {
				$el.attr( {
					role: 'button',
					tabindex: 0
				} ).on( 'click keypress', function ( e ) {
					if (
						e.type === 'click' ||
						e.type === 'keypress' && e.which === 13
					) {
						arg.call( this, e );
					}
				} );
			} else {
				target = textify( arg );
				if ( target.search( new RegExp( '^(/|' + mw.config.get( 'wgUrlProtocols' ) + ')' ) ) !== -1 ) {
					$el.attr( 'href', target );
				} else {
					mw.log( 'External link in message had illegal target ' + target );
					return appendWithoutParsing(
						$( '<span>' ),
						[ '[' + target + ' ' ].concat( contents ).concat( ']' )
					).contents();
				}
			}
		}
		return appendWithoutParsing( $el.empty(), contents );
	},

	/**
	 * Transform parsed structure into pluralization
	 * n.b. The first node may be a non-integer (for instance, a string representing an Arabic number).
	 * So convert it back with the current language's convertNumber.
	 *
	 * @param {Array} nodes List of nodes, [ {string|number}, {string}, {string} ... ]
	 * @return {string|jQuery} selected pluralized form according to current language
	 */
	plural: function ( nodes ) {
		var forms, firstChild, firstChildText, explicitPluralFormNumber, formIndex, form, count,
			explicitPluralForms = {};

		count = parseFloat( this.language.convertNumber( textify( nodes[ 0 ] ), true ) );
		forms = nodes.slice( 1 );
		for ( formIndex = 0; formIndex < forms.length; formIndex++ ) {
			form = forms[ formIndex ];

			if ( form instanceof $ && form.hasClass( 'mediaWiki_htmlEmitter' ) ) {
				// This is a nested node, may be an explicit plural form like 5=[$2 linktext]
				firstChild = form.contents().get( 0 );
				if ( firstChild && firstChild.nodeType === Node.TEXT_NODE ) {
					firstChildText = firstChild.textContent;
					if ( /^\d+=/.test( firstChildText ) ) {
						explicitPluralFormNumber = parseInt( firstChildText.split( /=/ )[ 0 ], 10 );
						// Use the digit part as key and rest of first text node and
						// rest of child nodes as value.
						firstChild.textContent = firstChildText.slice( firstChildText.indexOf( '=' ) + 1 );
						explicitPluralForms[ explicitPluralFormNumber ] = form;
						forms[ formIndex ] = undefined;
					}
				}
			} else if ( /^\d+=/.test( form ) ) {
				// Simple explicit plural forms like 12=a dozen
				explicitPluralFormNumber = parseInt( form.split( /=/ )[ 0 ], 10 );
				explicitPluralForms[ explicitPluralFormNumber ] = form.slice( form.indexOf( '=' ) + 1 );
				forms[ formIndex ] = undefined;
			}
		}

		// Remove explicit plural forms from the forms. They were set undefined in the above loop.
		// eslint-disable-next-line no-jquery/no-map-util
		forms = $.map( forms, function ( f ) {
			return f;
		} );

		return this.language.convertPlural( count, forms, explicitPluralForms );
	},

	/**
	 * Transform parsed structure according to gender.
	 *
	 * Usage: {{gender:[ mw.user object | '' | 'male' | 'female' | 'unknown' ] | masculine form | feminine form | neutral form}}.
	 *
	 * The first node must be one of:
	 * - the mw.user object (or a compatible one)
	 * - an empty string - indicating the current user, same effect as passing the mw.user object
	 * - a gender string ('male', 'female' or 'unknown')
	 *
	 * @param {Array} nodes List of nodes, [ {string|mw.user}, {string}, {string}, {string} ]
	 * @return {string|jQuery} Selected gender form according to current language
	 */
	gender: function ( nodes ) {
		var gender,
			maybeUser = nodes[ 0 ],
			forms = nodes.slice( 1 );

		if ( maybeUser === '' ) {
			maybeUser = mw.user;
		}

		// If we are passed a mw.user-like object, check their gender.
		// Otherwise, assume the gender string itself was passed .
		if ( maybeUser && maybeUser.options instanceof mw.Map ) {
			gender = maybeUser.options.get( 'gender' );
		} else {
			gender = textify( maybeUser );
		}

		return this.language.gender( gender, forms );
	},

	/**
	 * Wraps argument with unicode control characters for directionality safety
	 *
	 * Identical to the implementation in jquery.i18n.emitter.bidi.js
	 *
	 * This solves the problem where directionality-neutral characters at the edge of
	 * the argument string get interpreted with the wrong directionality from the
	 * enclosing context, giving renderings that look corrupted like "(Ben_(WMF".
	 *
	 * The wrapping is LRE...PDF or RLE...PDF, depending on the detected
	 * directionality of the argument string, using the BIDI algorithm's own "First
	 * strong directional codepoint" rule. Essentially, this works round the fact that
	 * there is no embedding equivalent of U+2068 FSI (isolation with heuristic
	 * direction inference). The latter is cleaner but still not widely supported.
	 *
	 * @param {string[]} nodes The text nodes from which to take the first item.
	 * @return {string} Wrapped String of content as needed.
	 */
	bidi: function ( nodes ) {
		var dir = strongDirFromContent( nodes[ 0 ] );
		if ( dir === 'ltr' ) {
			// Wrap in LEFT-TO-RIGHT EMBEDDING ... POP DIRECTIONAL FORMATTING
			return '\u202A' + nodes[ 0 ] + '\u202C';
		}
		if ( dir === 'rtl' ) {
			// Wrap in RIGHT-TO-LEFT EMBEDDING ... POP DIRECTIONAL FORMATTING
			return '\u202B' + nodes[ 0 ] + '\u202C';
		}
		// No strong directionality: do not wrap
		return nodes[ 0 ];
	},

	/**
	 * Transform parsed structure into grammar conversion.
	 * Invoked by putting `{{grammar:form|word}}` in a message
	 *
	 * @param {Array} nodes List of nodes [{Grammar case eg: genitive}, {string word}]
	 * @return {string|jQuery} selected grammatical form according to current language
	 */
	grammar: function ( nodes ) {
		var form = nodes[ 0 ],
			word = nodes[ 1 ];
		// These could be jQuery objects (passed as message parameters),
		// in which case we can't transform them (like rawParams() in PHP).
		if ( typeof form === 'string' && typeof word === 'string' ) {
			return this.language.convertGrammar( word, form );
		}
		return word;
	},

	/**
	 * Tranform parsed structure into a int: (interface language) message include
	 * Invoked by putting `{{int:othermessage}}` into a message
	 *
	 * TODO Syntax in the included message is not parsed, this seems like a bug?
	 *
	 * @param {Array} nodes List of nodes
	 * @return {string} Other message
	 */
	int: function ( nodes ) {
		var msg = textify( nodes[ 0 ] );
		return mw.jqueryMsg.getMessageFunction()( msg.charAt( 0 ).toLowerCase() + msg.slice( 1 ) );
	},

	/**
	 * Get localized namespace name from canonical name or namespace number.
	 * Invoked by putting `{{ns:foo}}` into a message
	 *
	 * @param {Array} nodes List of nodes
	 * @return {string} Localized namespace name
	 */
	ns: function ( nodes ) {
		var ns = textify( nodes[ 0 ] ).trim();
		if ( !/^\d+$/.test( ns ) ) {
			ns = mw.config.get( 'wgNamespaceIds' )[ ns.replace( / /g, '_' ).toLowerCase() ];
		}
		ns = mw.config.get( 'wgFormattedNamespaces' )[ ns ];
		return ns || '';
	},

	/**
	 * Takes an unformatted number (arab, no group separators and . as decimal separator)
	 * and outputs it in the localized digit script and formatted with decimal
	 * separator, according to the current language.
	 *
	 * @param {Array} nodes List of nodes
	 * @return {number|string|jQuery} Formatted number
	 */
	formatnum: function ( nodes ) {
		var isInteger = !!nodes[ 1 ] && nodes[ 1 ] === 'R',
			number = nodes[ 0 ];

		// These could be jQuery objects (passed as message parameters),
		// in which case we can't transform them (like rawParams() in PHP).
		if ( typeof number === 'string' || typeof number === 'number' ) {
			return this.language.convertNumber( number, isInteger );
		}
		return number;
	},

	/**
	 * Lowercase text
	 *
	 * @param {Array} nodes List of nodes
	 * @return {string} The given text, all in lowercase
	 */
	lc: function ( nodes ) {
		return textify( nodes[ 0 ] ).toLowerCase();
	},

	/**
	 * Uppercase text
	 *
	 * @param {Array} nodes List of nodes
	 * @return {string} The given text, all in uppercase
	 */
	uc: function ( nodes ) {
		return textify( nodes[ 0 ] ).toUpperCase();
	},

	/**
	 * Lowercase first letter of input, leaving the rest unchanged
	 *
	 * @param {Array} nodes List of nodes
	 * @return {string} The given text, with the first character in lowercase
	 */
	lcfirst: function ( nodes ) {
		var text = textify( nodes[ 0 ] );
		return text.charAt( 0 ).toLowerCase() + text.slice( 1 );
	},

	/**
	 * Uppercase first letter of input, leaving the rest unchanged
	 *
	 * @param {Array} nodes List of nodes
	 * @return {string} The given text, with the first character in uppercase
	 */
	ucfirst: function ( nodes ) {
		var text = textify( nodes[ 0 ] );
		return text.charAt( 0 ).toUpperCase() + text.slice( 1 );
	}
};

/**
 * @method
 * @member jQuery
 * @see mw.jqueryMsg#getPlugin
 */
$.fn.msg = mw.jqueryMsg.getPlugin();

// Replace the default message parser with jqueryMsg
oldParser = mw.Message.prototype.parser;
mw.Message.prototype.parser = function () {
	// Fall back to mw.msg's simple parser where possible
	if (
		// Plain text output always uses the simple parser
		this.format === 'plain' ||
		(
			// jqueryMsg parser is needed for messages containing wikitext
			!/\{\{|[<>[&]/.test( this.map.get( this.key ) ) &&
			// jqueryMsg parser is needed when jQuery objects or DOM nodes are passed in as parameters
			!this.parameters.some( function ( param ) {
				return param instanceof $ || ( param && param.nodeType !== undefined );
			} )
		)
	) {
		return oldParser.apply( this );
	}

	if ( !Object.prototype.hasOwnProperty.call( this.map, this.format ) ) {
		this.map[ this.format ] = mw.jqueryMsg.getMessageFunction( {
			messages: this.map,
			// For format 'escaped', escaping part is handled by mediawiki.js
			format: this.format
		} );
	}
	return this.map[ this.format ]( this.key, this.parameters );
};

/**
 * Parse the message to DOM nodes, rather than HTML string like #parse.
 *
 * This method is only available when jqueryMsg is loaded.
 *
 * @since 1.27
 * @method parseDom
 * @member mw.Message
 * @return {jQuery}
 */
mw.Message.prototype.parseDom = ( function () {
	var $wrapper = $( '<div>' );
	return function () {
		// eslint-disable-next-line mediawiki/msg-doc
		return $wrapper.msg( this.key, this.parameters ).contents().detach();
	};
}() );
