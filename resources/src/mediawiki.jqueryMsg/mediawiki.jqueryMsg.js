/*!
* Experimental advanced wikitext parser-emitter.
* See: https://www.mediawiki.org/wiki/Extension:UploadWizard/MessageParser for docs
*
* @author neilk@wikimedia.org
* @author mflaschen@wikimedia.org
*/
( function () {
	/**
	 * @class mw.jqueryMsg
	 * @singleton
	 */

	var oldParser,
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
				if ( mw.config.get( 'wgUserLanguage' ) === 'qqx' ) {
					wikiText = '(' + key + '$*)';
				} else {
					wikiText = this.settings.messages.get( key );
					if ( typeof wikiText !== 'string' ) {
						wikiText = '⧼' + key + '⧽';
					}
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
				nonWhitespaceExpression, paramExpression, expression, curlyBraceTransformExpression, result,
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
				var i, res,
					originalPos = pos,
					result = [];
				for ( i = 0; i < ps.length; i++ ) {
					res = ps[ i ]();
					if ( res === null ) {
						pos = originalPos;
						return null;
					}
					result.push( res );
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
				var i, len, attributeName;

				startTagName = startTagName.toLowerCase();
				endTagName = endTagName.toLowerCase();
				if ( startTagName !== endTagName || settings.allowedHtmlElements.indexOf( startTagName ) === -1 ) {
					return false;
				}

				for ( i = 0, len = attributes.length; i < len; i += 2 ) {
					attributeName = attributes[ i ];
					if ( settings.allowedHtmlCommonAttributes.indexOf( attributeName ) === -1 &&
						( settings.allowedHtmlAttributesByElement[ startTagName ] || [] ).indexOf( attributeName ) === -1 ) {
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
					var res = sequence( [
						// templates can have placeholders for dynamic replacement eg: {{PLURAL:$1|one car|$1 cars}}
						// or no placeholders eg: {{GRAMMAR:genitive|{{SITENAME}}}
						choice( [ templateWithReplacement, templateWithOutReplacement, templateWithOutFirstParameter ] ),
						nOrMore( 0, templateParam )
					] );
					return res === null ? null : res[ 0 ].concat( res[ 1 ] );
				},
				function () {
					var res = sequence( [
						templateName,
						nOrMore( 0, templateParam )
					] );
					if ( res === null ) {
						return null;
					}
					return [ res[ 0 ] ].concat( res[ 1 ] );
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

			result = start( this.settings.onlyCurlyBraceTransform ? curlyBraceTransformExpression : expression );

			/*
			 * For success, the p must have gotten to the end of the input
			 * and returned a non-null.
			 * n.b. This is part of language infrastructure, so we do not throw an internationalizable message.
			 */
			if ( result === null || pos !== input.length ) {
				throw new Error( 'Parse error at position ' + pos.toString() + ' in input: ' + input );
			}
			return result;
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
			var ret, subnodes, operation,
				jmsg = this;
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
		 * TODO: Throw error if nodes.length > 1 ?
		 *
		 * @param {Array} nodes List of one element, integer, n >= 0
		 * @param {Array} replacements List of at least n strings
		 * @return {string|jQuery} replacement
		 */
		replace: function ( nodes, replacements ) {
			var index = parseInt( nodes[ 0 ], 10 );

			if ( index < replacements.length ) {
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
				contents = nodes[ 1 ];
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
					$el.attr( 'href', textify( arg ) );
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
			forms = $.map( forms, function ( form ) {
				return form;
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
			return $wrapper.msg( this.key, this.parameters ).contents().detach();
		};
	}() );

}() );
