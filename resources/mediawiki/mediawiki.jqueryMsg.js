/**
* Experimental advanced wikitext parser-emitter.
* See: http://www.mediawiki.org/wiki/Extension:UploadWizard/MessageParser for docs
*
* @author neilk@wikimedia.org
*/
( function ( mw, $ ) {
	var slice = Array.prototype.slice,
		parserDefaults = {
			magic : {
				'SITENAME' : mw.config.get( 'wgSiteName' )
			},
			messages : mw.messages,
			language : mw.language
		};

	/**
	 * Given parser options, return a function that parses a key and replacements, returning jQuery object
	 * @param {Object} parser options
	 * @return {Function} accepting ( String message key, String replacement1, String replacement2 ... ) and returning {jQuery}
	 */
	function getFailableParserFn( options ) {
		var parser = new mw.jqueryMsg.parser( options );
		/**
		 * Try to parse a key and optional replacements, returning a jQuery object that may be a tree of jQuery nodes.
		 * If there was an error parsing, return the key and the error message (wrapped in jQuery). This should put the error right into
		 * the interface, without causing the page to halt script execution, and it hopefully should be clearer how to fix it.
		 *
		 * @param {Array} first element is the key, replacements may be in array in 2nd element, or remaining elements.
		 * @return {jQuery}
		 */
		return function ( args ) {
			var key = args[0];
			var argsArray = $.isArray( args[1] ) ? args[1] : slice.call( args, 1 );
			try {
				return parser.parse( key, argsArray );
			} catch ( e ) {
				return $( '<span>' ).append( key + ': ' + e.message );
			}
		};
	}

	mw.jqueryMsg = {};

	/**
	 * Class method.
	 * Returns a function suitable for use as a global, to construct strings from the message key (and optional replacements).
	 * e.g.
	 *       window.gM = mediaWiki.parser.getMessageFunction( options );
	 *       $( 'p#headline' ).html( gM( 'hello-user', username ) );
	 *
	 * Like the old gM() function this returns only strings, so it destroys any bindings. If you want to preserve bindings use the
	 * jQuery plugin version instead. This is only included for backwards compatibility with gM().
	 *
	 * @param {Array} parser options
	 * @return {Function} function suitable for assigning to window.gM
	 */
	mw.jqueryMsg.getMessageFunction = function ( options ) {
		var failableParserFn = getFailableParserFn( options );
		/**
		 * N.B. replacements are variadic arguments or an array in second parameter. In other words:
		 *    somefunction(a, b, c, d)
		 * is equivalent to
		 *    somefunction(a, [b, c, d])
		 *
		 * @param {String} message key
		 * @param {Array} optional replacements (can also specify variadically)
		 * @return {String} rendered HTML as string
		 */
		return function ( /* key, replacements */ ) {
			return failableParserFn( arguments ).html();
		};
	};

	/**
	 * Class method.
	 * Returns a jQuery plugin which parses the message in the message key, doing replacements optionally, and appends the nodes to
	 * the current selector. Bindings to passed-in jquery elements are preserved. Functions become click handlers for [$1 linktext] links.
	 * e.g.
	 *        $.fn.msg = mediaWiki.parser.getJqueryPlugin( options );
	 *        var userlink = $( '<a>' ).click( function () { alert( "hello!!") } );
	 *        $( 'p#headline' ).msg( 'hello-user', userlink );
	 *
	 * @param {Array} parser options
	 * @return {Function} function suitable for assigning to jQuery plugin, such as $.fn.msg
	 */
	mw.jqueryMsg.getPlugin = function ( options ) {
		var failableParserFn = getFailableParserFn( options );
		/**
		 * N.B. replacements are variadic arguments or an array in second parameter. In other words:
		 *    somefunction(a, b, c, d)
		 * is equivalent to
		 *    somefunction(a, [b, c, d])
		 *
		 * We append to 'this', which in a jQuery plugin context will be the selected elements.
		 * @param {String} message key
		 * @param {Array} optional replacements (can also specify variadically)
		 * @return {jQuery} this
		 */
		return function ( /* key, replacements */ ) {
			var $target = this.empty();
			$.each( failableParserFn( arguments ).contents(), function ( i, node ) {
				$target.append( node );
			} );
			return $target;
		};
	};

	/**
	 * The parser itself.
	 * Describes an object, whose primary duty is to .parse() message keys.
	 * @param {Array} options
	 */
	mw.jqueryMsg.parser = function ( options ) {
		this.settings = $.extend( {}, parserDefaults, options );
		this.emitter = new mw.jqueryMsg.htmlEmitter( this.settings.language, this.settings.magic );
	};

	mw.jqueryMsg.parser.prototype = {
		// cache, map of mediaWiki message key to the AST of the message. In most cases, the message is a string so this is identical.
		// (This is why we would like to move this functionality server-side).
		astCache: {},

		/**
		 * Where the magic happens.
		 * Parses a message from the key, and swaps in replacements as necessary, wraps in jQuery
		 * If an error is thrown, returns original key, and logs the error
		 * @param {String} message key
		 * @param {Array} replacements for $1, $2... $n
		 * @return {jQuery}
		 */
		parse: function ( key, replacements ) {
			return this.emitter.emit( this.getAst( key ), replacements );
		},
		/**
		 * Fetch the message string associated with a key, return parsed structure. Memoized.
		 * Note that we pass '[' + key + ']' back for a missing message here.
		 * @param {String} key
		 * @return {String|Array} string of '[key]' if message missing, simple string if possible, array of arrays if needs parsing
		 */
		getAst: function ( key ) {
			if ( this.astCache[ key ] === undefined ) {
				var wikiText = this.settings.messages.get( key );
				if ( typeof wikiText !== 'string' ) {
					wikiText = "\\[" + key + "\\]";
				}
				this.astCache[ key ] = this.wikiTextToAst( wikiText );
			}
			return this.astCache[ key ];
		},
		/*
		 * Parses the input wikiText into an abstract syntax tree, essentially an s-expression.
		 *
		 * CAVEAT: This does not parse all wikitext. It could be more efficient, but it's pretty good already.
		 * n.b. We want to move this functionality to the server. Nothing here is required to be on the client.
		 *
		 * @param {String} message string wikitext
		 * @throws Error
		 * @return {Mixed} abstract syntax tree
		 */
		wikiTextToAst: function ( input ) {

			// Indicates current position in input as we parse through it.
			// Shared among all parsing functions below.
			var pos = 0;
			// =========================================================
			// parsing combinators - could be a library on its own
			// =========================================================
			// Try parsers until one works, if none work return null
			function choice( ps ) {
				return function () {
					for ( var i = 0; i < ps.length; i++ ) {
						var result = ps[i]();
						if ( result !== null ) {
							 return result;
						}
					}
					return null;
				};
			}
			// try several ps in a row, all must succeed or return null
			// this is the only eager one
			function sequence( ps ) {
				var originalPos = pos;
				var result = [];
				for ( var i = 0; i < ps.length; i++ ) {
					var res = ps[i]();
					if ( res === null ) {
						pos = originalPos;
						return null;
					}
					result.push( res );
				}
				return result;
			}
			// run the same parser over and over until it fails.
			// must succeed a minimum of n times or return null
			function nOrMore( n, p ) {
				return function () {
					var originalPos = pos;
					var result = [];
					var parsed = p();
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
			// There is a general pattern -- parse a thing, if that worked, apply transform, otherwise return null.
			// But using this as a combinator seems to cause problems when combined with nOrMore().
			// May be some scoping issue
			function transform( p, fn ) {
				return function () {
					var result = p();
					return result === null ? null : fn( result );
				};
			}
			// Helpers -- just make ps out of simpler JS builtin types
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
			function makeRegexParser( regex ) {
				return function () {
					var matches = input.substr( pos ).match( regex );
					if ( matches === null ) {
						return null;
					}
					pos += matches[0].length;
					return matches[0];
				};
			}

			/**
			 *  ===================================================================
			 *  General patterns above this line -- wikitext specific parsers below
			 *  ===================================================================
			 */
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
			var regularLiteral = makeRegexParser( /^[^{}\[\]$\\]/ );
			var regularLiteralWithoutBar = makeRegexParser(/^[^{}\[\]$\\|]/);
			var regularLiteralWithoutSpace = makeRegexParser(/^[^{}\[\]$\s]/);
			var backslash = makeStringParser( "\\" );
			var anyCharacter = makeRegexParser( /^./ );
			function escapedLiteral() {
				var result = sequence( [
					backslash,
					anyCharacter
				] );
				return result === null ? null : result[1];
			}
			var escapedOrLiteralWithoutSpace = choice( [
				escapedLiteral,
				regularLiteralWithoutSpace
			] );
			var escapedOrLiteralWithoutBar = choice( [
				escapedLiteral,
				regularLiteralWithoutBar
			] );
			var escapedOrRegularLiteral = choice( [
				escapedLiteral,
				regularLiteral
			] );
			// Used to define "literals" without spaces, in space-delimited situations
			function literalWithoutSpace() {
				 var result = nOrMore( 1, escapedOrLiteralWithoutSpace )();
				 return result === null ? null : result.join('');
			}
			// Used to define "literals" within template parameters. The pipe character is the parameter delimeter, so by default
			// it is not a literal in the parameter
			function literalWithoutBar() {
				 var result = nOrMore( 1, escapedOrLiteralWithoutBar )();
				 return result === null ? null : result.join('');
			}
			function literal() {
				 var result = nOrMore( 1, escapedOrRegularLiteral )();
				 return result === null ? null : result.join('');
			}
			var whitespace = makeRegexParser( /^\s+/ );
			var dollar = makeStringParser( '$' );
			var digits = makeRegexParser( /^\d+/ );

			function replacement() {
				var result = sequence( [
					dollar,
					digits
				] );
				if ( result === null ) {
					return null;
				}
				return [ 'REPLACE', parseInt( result[1], 10 ) - 1 ];
			}
			var openExtlink = makeStringParser( '[' );
			var closeExtlink = makeStringParser( ']' );
			// this extlink MUST have inner text, e.g. [foo] not allowed; [foo bar] is allowed
			function extlink() {
				var result = null;
				var parsedResult = sequence( [
					openExtlink,
					nonWhitespaceExpression,
					whitespace,
					expression,
					closeExtlink
				] );
				if ( parsedResult !== null ) {
					 result = [ 'LINK', parsedResult[1], parsedResult[3] ];
				}
				return result;
			}
			// this is the same as the above extlink, except that the url is being passed on as a parameter
			function extLinkParam() {
				var result = sequence( [
					openExtlink,
					dollar,
					digits,
					whitespace,
					expression,
					closeExtlink
				] );
				if ( result === null ) {
					return null;
				}
				return [ 'LINKPARAM', parseInt( result[2], 10 ) - 1, result[4] ];
			}
			var openLink = makeStringParser( '[[' );
			var closeLink = makeStringParser( ']]' );
			function link() {
				var result = null;
				var parsedResult = sequence( [
					openLink,
					expression,
					closeLink
				] );
				if ( parsedResult !== null ) {
					 result = [ 'WLINK', parsedResult[1] ];
				}
				return result;
			}
			var templateName = transform(
				// see $wgLegalTitleChars
				// not allowing : due to the need to catch "PLURAL:$1"
				makeRegexParser( /^[ !"$&'()*,.\/0-9;=?@A-Z\^_`a-z~\x80-\xFF+\-]+/ ),
				function ( result ) { return result.toString(); }
			);
			function templateParam() {
				var result = sequence( [
					pipe,
					nOrMore( 0, paramExpression )
				] );
				if ( result === null ) {
					return null;
				}
				var expr = result[1];
				// use a "CONCAT" operator if there are multiple nodes, otherwise return the first node, raw.
				return expr.length > 1 ? [ "CONCAT" ].concat( expr ) : expr[0];
			}
			var pipe = makeStringParser( '|' );
			function templateWithReplacement() {
				var result = sequence( [
					templateName,
					colon,
					replacement
				] );
				return result === null ? null : [ result[0], result[2] ];
			}
			function templateWithOutReplacement() {
				var result = sequence( [
					templateName,
					colon,
					paramExpression
				] );
				return result === null ? null : [ result[0], result[2] ];
			}
			var colon = makeStringParser(':');
			var templateContents = choice( [
				function () {
					var res = sequence( [
						// templates can have placeholders for dynamic replacement eg: {{PLURAL:$1|one car|$1 cars}}
						// or no placeholders eg: {{GRAMMAR:genitive|{{SITENAME}}}
						choice( [ templateWithReplacement, templateWithOutReplacement ] ),
						nOrMore( 0, templateParam )
					] );
					return res === null ? null : res[0].concat( res[1] );
				},
				function () {
					var res = sequence( [
						templateName,
						nOrMore( 0, templateParam )
					] );
					if ( res === null ) {
						return null;
					}
					return [ res[0] ].concat( res[1] );
				}
			] );
			var openTemplate = makeStringParser('{{');
			var closeTemplate = makeStringParser('}}');
			function template() {
				var result = sequence( [
					openTemplate,
					templateContents,
					closeTemplate
				] );
				return result === null ? null : result[1];
			}
			var nonWhitespaceExpression = choice( [
				template,
				link,
				extLinkParam,
				extlink,
				replacement,
				literalWithoutSpace
			] );
			var paramExpression = choice( [
				template,
				link,
				extLinkParam,
				extlink,
				replacement,
				literalWithoutBar
			] );
			var expression = choice( [
				template,
				link,
				extLinkParam,
				extlink,
				replacement,
				literal
			] );
			function start() {
				var result = nOrMore( 0, expression )();
				if ( result === null ) {
					return null;
				}
				return [ "CONCAT" ].concat( result );
			}
			// everything above this point is supposed to be stateless/static, but
			// I am deferring the work of turning it into prototypes & objects. It's quite fast enough
			// finally let's do some actual work...
			var result = start();

			/*
			 * For success, the p must have gotten to the end of the input
			 * and returned a non-null.
			 * n.b. This is part of language infrastructure, so we do not throw an internationalizable message.
			 */
			if (result === null || pos !== input.length) {
				throw new Error( "Parse error at position " + pos.toString() + " in input: " + input );
			}
			return result;
		}

	};
	/**
	 * htmlEmitter - object which primarily exists to emit HTML from parser ASTs
	 */
	mw.jqueryMsg.htmlEmitter = function ( language, magic ) {
		this.language = language;
		var jmsg = this;
		$.each( magic, function ( key, val ) {
			jmsg[ key.toLowerCase() ] = function () {
				return val;
			};
		} );
		/**
		 * (We put this method definition here, and not in prototype, to make sure it's not overwritten by any magic.)
		 * Walk entire node structure, applying replacements and template functions when appropriate
		 * @param {Mixed} abstract syntax tree (top node or subnode)
		 * @param {Array} replacements for $1, $2, ... $n
		 * @return {Mixed} single-string node or array of nodes suitable for jQuery appending
		 */
		this.emit = function ( node, replacements ) {
			var ret = null;
			var jmsg = this;
			switch ( typeof node ) {
				case 'string':
				case 'number':
					ret = node;
					break;
				case 'object': // node is an array of nodes
					var subnodes = $.map( node.slice( 1 ), function ( n ) {
						return jmsg.emit( n, replacements );
					} );
					var operation = node[0].toLowerCase();
					if ( typeof jmsg[operation] === 'function' ) {
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
	mw.jqueryMsg.htmlEmitter.prototype = {
		/**
		 * Parsing has been applied depth-first we can assume that all nodes here are single nodes
		 * Must return a single node to parents -- a jQuery with synthetic span
		 * However, unwrap any other synthetic spans in our children and pass them upwards
		 * @param {Array} nodes - mixed, some single nodes, some arrays of nodes
		 * @return {jQuery}
		 */
		concat: function ( nodes ) {
			var $span = $( '<span>' ).addClass( 'mediaWiki_htmlEmitter' );
			$.each( nodes, function ( i, node ) {
				if ( node instanceof jQuery && node.hasClass( 'mediaWiki_htmlEmitter' ) ) {
					$.each( node.contents(), function ( j, childNode ) {
						$span.append( childNode );
					} );
				} else {
					// strings, integers, anything else
					$span.append( node );
				}
			} );
			return $span;
		},

		/**
		 * Return escaped replacement of correct index, or string if unavailable.
		 * Note that we expect the parsed parameter to be zero-based. i.e. $1 should have become [ 0 ].
		 * if the specified parameter is not found return the same string
		 * (e.g. "$99" -> parameter 98 -> not found -> return "$99" )
		 * TODO throw error if nodes.length > 1 ?
		 * @param {Array} of one element, integer, n >= 0
		 * @return {String} replacement
		 */
		replace: function ( nodes, replacements ) {
			var index = parseInt( nodes[0], 10 );

			if ( index < replacements.length ) {
				if ( typeof arg === 'string' ) {
					// replacement is a string, escape it
					return mw.html.escape( replacements[index] );
				} else {
					// replacement is no string, don't touch!
					return replacements[index];
				}
			} else {
				// index not found, fallback to displaying variable
				return '$' + ( index + 1 );
			}
		},

		/**
		 * Transform wiki-link
		 * TODO unimplemented
		 */
		wlink: function ( nodes ) {
			return 'unimplemented';
		},

		/**
		 * Transform parsed structure into external link
		 * If the href is a jQuery object, treat it as "enclosing" the link text.
		 *              ... function, treat it as the click handler
		 *		... string, treat it as a URI
		 * TODO: throw an error if nodes.length > 2 ?
		 * @param {Array} of two elements, {jQuery|Function|String} and {String}
		 * @return {jQuery}
		 */
		link: function ( nodes ) {
			var arg = nodes[0];
			var contents = nodes[1];
			var $el;
			if ( arg instanceof jQuery ) {
				$el = arg;
			} else {
				$el = $( '<a>' );
				if ( typeof arg === 'function' ) {
					$el.click( arg ).attr( 'href', '#' );
				} else {
					$el.attr( 'href', arg.toString() );
				}
			}
			$el.append( contents );
			return $el;
		},

		/**
		 * This is basically use a combination of replace + link (link with parameter
		 * as url), but we don't want to run the regular replace here-on: inserting a
		 * url as href-attribute of a link will automatically escape it already, so
		 * we don't want replace to (manually) escape it as well.
		 * TODO throw error if nodes.length > 1 ?
		 * @param {Array} of one element, integer, n >= 0
		 * @return {String} replacement
		 */
		linkparam: function ( nodes, replacements ) {
			var replacement,
				index = parseInt( nodes[0], 10 );
			if ( index < replacements.length) {
				replacement = replacements[index];
			} else {
				replacement = '$' + ( index + 1 );
			}
			return this.link( [ replacement, nodes[1] ] );
		},

		/**
		 * Transform parsed structure into pluralization
		 * n.b. The first node may be a non-integer (for instance, a string representing an Arabic number).
		 * So convert it back with the current language's convertNumber.
		 * @param {Array} of nodes, [ {String|Number}, {String}, {String} ... ]
		 * @return {String} selected pluralized form according to current language
		 */
		plural: function ( nodes ) {
			var count = parseFloat( this.language.convertNumber( nodes[0], true ) );
			var forms = nodes.slice(1);
			return forms.length ? this.language.convertPlural( count, forms ) : '';
		},

		/**
		 * Transform parsed structure into gender
		 * Usage {{gender:[gender| mw.user object ] | masculine|feminine|neutral}}.
		 * @param {Array} of nodes, [ {String|mw.User}, {String}, {String} , {String} ]
		 * @return {String} selected gender form according to current language
		 */
		gender: function ( nodes ) {
			var gender;
			if  ( nodes[0] && nodes[0].options instanceof mw.Map ){
				gender = nodes[0].options.get( 'gender' );
			} else {
				gender = nodes[0];
			}
			var forms = nodes.slice(1);
			return this.language.gender( gender, forms );
		},

		/**
		 * Transform parsed structure into grammar conversion.
		 * Invoked by putting {{grammar:form|word}} in a message
		 * @param {Array} of nodes [{Grammar case eg: genitive}, {String word}]
		 * @return {String} selected grammatical form according to current language
		 */
		grammar: function ( nodes ) {
			var form = nodes[0];
			var word = nodes[1];
			return word && form && this.language.convertGrammar( word, form );
		}
	};
	// Deprecated! don't rely on gM existing.
	// The window.gM ought not to be required - or if required, not required here.
	// But moving it to extensions breaks it (?!)
	// Need to fix plugin so it could do attributes as well, then will be okay to remove this.
	window.gM = mw.jqueryMsg.getMessageFunction();
	$.fn.msg = mw.jqueryMsg.getPlugin();

	// Replace the default message parser with jqueryMsg
	var oldParser = mw.Message.prototype.parser;
	mw.Message.prototype.parser = function () {
		// TODO: should we cache the message function so we don't create a new one every time? Benchmark this maybe?
		// Caching is somewhat problematic, because we do need different message functions for different maps, so
		// we'd have to cache the parser as a member of this.map, which sounds a bit ugly.
		// Do not use mw.jqueryMsg unless required
		if ( this.map.get( this.key ).indexOf( '{{' ) < 0 ) {
			// Fall back to mw.msg's simple parser
			return oldParser.apply( this );
		}
		var messageFunction = mw.jqueryMsg.getMessageFunction( { 'messages': this.map } );
		return messageFunction( this.key, this.parameters );
	};

}( mediaWiki, jQuery ) );
