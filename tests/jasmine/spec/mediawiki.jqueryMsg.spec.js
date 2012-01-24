/* spec for language & message behaviour in MediaWiki */

mw.messages.set( {
	"en_empty": "",
	"en_simple": "Simple message",
	"en_replace": "Simple $1 replacement",
	"en_replace2": "Simple $1 $2 replacements",
	"en_link": "Simple [http://example.com link to example].",
	"en_link_replace": "Complex [$1 $2] behaviour.",
	"en_simple_magic": "Simple {{ALOHOMORA}} message",
	"en_undelete_short": "Undelete {{PLURAL:$1|one edit|$1 edits}}",
	"en_undelete_empty_param": "Undelete{{PLURAL:$1|| multiple edits}}",
	"en_category-subcat-count": "{{PLURAL:$2|This category has only the following subcategory.|This category has the following {{PLURAL:$1|subcategory|$1 subcategories}}, out of $2 total.}}",
	"en_escape0": "Escape \\to fantasy island",
	"en_escape1": "I had \\$2.50 in my pocket",
	"en_escape2": "I had {{PLURAL:$1|the absolute \\|$1\\| which came out to \\$3.00 in my C:\\\\drive| some stuff}}",
	"en_fail": "This should fail to {{parse",
	"en_fail_magic": "There is no such magic word as {{SIETNAME}}",
	"en_evil": "This has <script type='text/javascript'>window.en_evil = true;</script> tags"
} );

/**
 * Tests
 */
( function( mw, $, undefined ) {

	describe( "mediaWiki.jqueryMsg", function() {
		
		describe( "basic message functionality", function() {

			it( "should return identity for empty string", function() {
				var parser = new mw.jqueryMsg.parser();
				expect( parser.parse( 'en_empty' ).html() ).toEqual( '' );
			} );


			it( "should return identity for simple string", function() {
				var parser = new mw.jqueryMsg.parser();
				expect( parser.parse( 'en_simple' ).html() ).toEqual( 'Simple message' );
			} );

		} );

		describe( "escaping", function() {

			it ( "should handle simple escaping", function() {
				var parser = new mw.jqueryMsg.parser();
				expect( parser.parse( 'en_escape0' ).html() ).toEqual( 'Escape to fantasy island' );
			} );

			it ( "should escape dollar signs found in ordinary text when backslashed", function() {
				var parser = new mw.jqueryMsg.parser();
				expect( parser.parse( 'en_escape1' ).html() ).toEqual( 'I had $2.50 in my pocket' );
			} );

			it ( "should handle a complicated escaping case, including escaped pipe chars in template args", function() {
				var parser = new mw.jqueryMsg.parser();
				expect( parser.parse( 'en_escape2', [ 1 ] ).html() ).toEqual( 'I had the absolute |1| which came out to $3.00 in my C:\\drive' );
			} );

		} );

		describe( "replacing", function() {

			it ( "should handle simple replacing", function() {
				var parser = new mw.jqueryMsg.parser();
				expect( parser.parse( 'en_replace', [ 'foo' ] ).html() ).toEqual( 'Simple foo replacement' );
			} );

			it ( "should return $n if replacement not there", function() {
				var parser = new mw.jqueryMsg.parser();
				expect( parser.parse( 'en_replace', [] ).html() ).toEqual( 'Simple $1 replacement' );
				expect( parser.parse( 'en_replace2', [ 'bar' ] ).html() ).toEqual( 'Simple bar $2 replacements' );
			} );

		} );

		describe( "linking", function() {

			it ( "should handle a simple link", function() {
				var parser = new mw.jqueryMsg.parser();
				var parsed = parser.parse( 'en_link' );
				var contents = parsed.contents();
				expect( contents.length ).toEqual( 3 );
				expect( contents[0].nodeName ).toEqual( '#text' );
				expect( contents[0].nodeValue ).toEqual( 'Simple ' );
				expect( contents[1].nodeName ).toEqual( 'A' );
				expect( contents[1].getAttribute( 'href' ) ).toEqual( 'http://example.com' );
				expect( contents[1].childNodes[0].nodeValue ).toEqual( 'link to example' );
				expect( contents[2].nodeName ).toEqual( '#text' );
				expect( contents[2].nodeValue ).toEqual( '.' );
			} );

			it ( "should replace a URL into a link", function() {
				var parser = new mw.jqueryMsg.parser();
				var parsed = parser.parse( 'en_link_replace', [ 'http://example.com/foo', 'linking' ] );
				var contents = parsed.contents();
				expect( contents.length ).toEqual( 3 );
				expect( contents[0].nodeName ).toEqual( '#text' );
				expect( contents[0].nodeValue ).toEqual( 'Complex ' );
				expect( contents[1].nodeName ).toEqual( 'A' );
				expect( contents[1].getAttribute( 'href' ) ).toEqual( 'http://example.com/foo' );
				expect( contents[1].childNodes[0].nodeValue ).toEqual( 'linking' );
				expect( contents[2].nodeName ).toEqual( '#text' );
				expect( contents[2].nodeValue ).toEqual( ' behaviour.' );
			} );

			it ( "should bind a click handler into a link", function() {
				var parser = new mw.jqueryMsg.parser();
				var clicked = false;
				var click = function() { clicked = true; };
				var parsed = parser.parse( 'en_link_replace', [ click, 'linking' ] );
				var contents = parsed.contents();
				expect( contents.length ).toEqual( 3 );
				expect( contents[0].nodeName ).toEqual( '#text' );
				expect( contents[0].nodeValue ).toEqual( 'Complex ' );
				expect( contents[1].nodeName ).toEqual( 'A' );
				expect( contents[1].getAttribute( 'href' ) ).toEqual( '#' );
				expect( contents[1].childNodes[0].nodeValue ).toEqual( 'linking' );
				expect( contents[2].nodeName ).toEqual( '#text' );
				expect( contents[2].nodeValue ).toEqual( ' behaviour.' );
				// determining bindings is hard in IE
				var anchor = parsed.find( 'a' );
				if ( ( $.browser.mozilla || $.browser.webkit ) && anchor.click ) {
					expect( clicked ).toEqual( false );
					anchor.click(); 
					expect( clicked ).toEqual( true );
				}
			} );

			it ( "should wrap a jquery arg around link contents -- even another element", function() {
				var parser = new mw.jqueryMsg.parser();
				var clicked = false;
				var click = function() { clicked = true; };
				var button = $( '<button>' ).click( click );
				var parsed = parser.parse( 'en_link_replace', [ button, 'buttoning' ] );
				var contents = parsed.contents();
				expect( contents.length ).toEqual( 3 );
				expect( contents[0].nodeName ).toEqual( '#text' );
				expect( contents[0].nodeValue ).toEqual( 'Complex ' );
				expect( contents[1].nodeName ).toEqual( 'BUTTON' );
				expect( contents[1].childNodes[0].nodeValue ).toEqual( 'buttoning' );
				expect( contents[2].nodeName ).toEqual( '#text' );
				expect( contents[2].nodeValue ).toEqual( ' behaviour.' );
				// determining bindings is hard in IE
				if ( ( $.browser.mozilla || $.browser.webkit ) && button.click ) {
					expect( clicked ).toEqual( false );
					parsed.find( 'button' ).click();
					expect( clicked ).toEqual( true );
				}
			} );


		} );


		describe( "magic keywords", function() {
			it( "should substitute magic keywords", function() {
				var options = {
					magic: { 
						'alohomora' : 'open'
					}
				};
				var parser = new mw.jqueryMsg.parser( options );
				expect( parser.parse( 'en_simple_magic' ).html() ).toEqual( 'Simple open message' );
			} );
		} );
		
		describe( "error conditions", function() {
			it( "should return non-existent key in square brackets", function() {
				var parser = new mw.jqueryMsg.parser();
				expect( parser.parse( 'en_does_not_exist' ).html() ).toEqual( '[en_does_not_exist]' );
			} );


			it( "should fail to parse", function() {
				var parser = new mw.jqueryMsg.parser();
				expect( function() { parser.parse( 'en_fail' ); } ).toThrow( 
					'Parse error at position 20 in input: This should fail to {{parse'
				);
			} );
		} );

		describe( "empty parameters", function() {
			it( "should deal with empty parameters", function() {
				var parser = new mw.jqueryMsg.parser();
				var ast = parser.getAst( 'en_undelete_empty_param' );
				expect( parser.parse( 'en_undelete_empty_param', [ 1 ] ).html() ).toEqual( 'Undelete' );
				expect( parser.parse( 'en_undelete_empty_param', [ 3 ] ).html() ).toEqual( 'Undelete multiple edits' );

			} );
		} );

		describe( "easy message interface functions", function() {
			it( "should allow a global that returns strings", function() {
				var gM = mw.jqueryMsg.getMessageFunction();
				// passing this through jQuery and back to string, because browsers may have subtle differences, like the case of tag names.
				// a surrounding <SPAN> is needed for html() to work right
				var expectedHtml = $( '<span>Complex <a href="http://example.com/foo">linking</a> behaviour.</span>' ).html();
				var result = gM( 'en_link_replace', 'http://example.com/foo', 'linking' );
				expect( typeof result ).toEqual( 'string' );
				expect( result ).toEqual( expectedHtml );
			} );

			it( "should allow a jQuery plugin that appends to nodes", function() {
				$.fn.msg = mw.jqueryMsg.getPlugin();
				var $div = $( '<div>' ).append( $( '<p>' ).addClass( 'foo' ) );
				var clicked = false;
				var $button = $( '<button>' ).click( function() { clicked = true; } );
				$div.find( '.foo' ).msg( 'en_link_replace', $button, 'buttoning' );
				// passing this through jQuery and back to string, because browsers may have subtle differences, like the case of tag names.
				// a surrounding <SPAN> is needed for html() to work right
				var expectedHtml = $( '<span>Complex <button>buttoning</button> behaviour.</span>' ).html();
				var createdHtml = $div.find( '.foo' ).html();
				// it is hard to test for clicks with IE; also it inserts or removes spaces around nodes when creating HTML tags, depending on their type.
				// so need to check the strings stripped of spaces.
				if ( ( $.browser.mozilla || $.browser.webkit ) && $button.click ) {
					expect( createdHtml ).toEqual( expectedHtml );
					$div.find( 'button ').click();
					expect( clicked ).toEqual( true );
				} else if ( $.browser.ie ) {
					expect( createdHtml.replace( /\s/, '' ) ).toEqual( expectedHtml.replace( /\s/, '' ) );
				}
				delete $.fn.msg;
			} );

			it( "jQuery plugin should escape incoming string arguments", function() {
				$.fn.msg = mw.jqueryMsg.getPlugin();
				var $div = $( '<div>' ).addClass( 'foo' );
				$div.msg( 'en_replace', '<p>x</p>' ); // looks like HTML, but as a string, should be escaped.
				// passing this through jQuery and back to string, because browsers may have subtle differences, like the case of tag names.
				var expectedHtml = $( '<div class="foo">Simple &lt;p&gt;x&lt;/p&gt; replacement</div>' ).html();
				var createdHtml = $div.html();
				expect( expectedHtml ).toEqual( createdHtml );
				delete $.fn.msg;
			} );


			it( "jQuery plugin should never execute scripts", function() {
				window.en_evil = false;
				$.fn.msg = mw.jqueryMsg.getPlugin();
				var $div = $( '<div>' );
				$div.msg( 'en_evil' );
				expect( window.en_evil ).toEqual( false );
				delete $.fn.msg;
			} );


			// n.b. this passes because jQuery already seems to strip scripts away; however, it still executes them if they are appended to any element.
			it( "jQuery plugin should never emit scripts", function() {
				$.fn.msg = mw.jqueryMsg.getPlugin();
				var $div = $( '<div>' );
				$div.msg( 'en_evil' );
				// passing this through jQuery and back to string, because browsers may have subtle differences, like the case of tag names.
				var expectedHtml = $( '<div>This has  tags</div>' ).html();
				var createdHtml = $div.html();
				expect( expectedHtml ).toEqual( createdHtml );
				console.log( 'expected: ' + expectedHtml );
				console.log( 'created: ' + createdHtml );
				delete $.fn.msg;
			} );



		} );

		// The parser functions can throw errors, but let's not actually blow up for the user -- instead dump the error into the interface so we have
		// a chance at fixing this
		describe( "easy message interface functions with graceful failures", function() {
			it( "should allow a global that returns strings, with graceful failure", function() {
				var gM = mw.jqueryMsg.getMessageFunction();
				// passing this through jQuery and back to string, because browsers may have subtle differences, like the case of tag names.
				// a surrounding <SPAN> is needed for html() to work right
				var expectedHtml = $( '<span>en_fail: Parse error at position 20 in input: This should fail to {{parse</span>' ).html();
				var result = gM( 'en_fail' );
				expect( typeof result ).toEqual( 'string' );
				expect( result ).toEqual( expectedHtml );
			} );

			it( "should allow a global that returns strings, with graceful failure on missing magic words", function() {
				var gM = mw.jqueryMsg.getMessageFunction();
				// passing this through jQuery and back to string, because browsers may have subtle differences, like the case of tag names.
				// a surrounding <SPAN> is needed for html() to work right
				var expectedHtml = $( '<span>en_fail_magic: unknown operation "sietname"</span>' ).html();
				var result = gM( 'en_fail_magic' );
				expect( typeof result ).toEqual( 'string' );
				expect( result ).toEqual( expectedHtml );
			} );


			it( "should allow a jQuery plugin, with graceful failure", function() {
				$.fn.msg = mw.jqueryMsg.getPlugin();
				var $div = $( '<div>' ).append( $( '<p>' ).addClass( 'foo' ) );
				$div.find( '.foo' ).msg( 'en_fail' );
				// passing this through jQuery and back to string, because browsers may have subtle differences, like the case of tag names.
				// a surrounding <SPAN> is needed for html() to work right
				var expectedHtml = $( '<span>en_fail: Parse error at position 20 in input: This should fail to {{parse</span>' ).html();
				var createdHtml = $div.find( '.foo' ).html();
				expect( createdHtml ).toEqual( expectedHtml );
				delete $.fn.msg;
			} );

		} );




		describe( "test plurals and other language-specific functions", function() {
			/* copying some language definitions in here -- it's hard to make this test fast and reliable 
			   otherwise, and we don't want to have to know the mediawiki URL from this kind of test either.
			   We also can't preload the langs for the test since they clobber the same namespace.
			   In principle Roan said it was okay to change how languages worked so that didn't happen... maybe 
			   someday. We'd have to the same kind of importing of the default rules for most rules, or maybe 
			   come up with some kind of subclassing scheme for languages */
			var languageClasses = {
				ar: {
					/**
					 * Arabic (العربية) language functions
					 */

					convertPlural: function( count, forms ) {
						forms = mw.language.preConvertPlural( forms, 6 );
						if ( count === 0 ) {
							return forms[0];
						}
						if ( count == 1 ) {
							return forms[1];
						}
						if ( count == 2 ) {
							return forms[2];
						}
						if ( count % 100 >= 3 && count % 100 <= 10 ) {
							return forms[3];
						}
						if ( count % 100 >= 11 && count % 100 <= 99 ) {
							return forms[4];
						}
						return forms[5];
					},

					digitTransformTable: {
					    '0': '٠', // &#x0660;
					    '1': '١', // &#x0661;
					    '2': '٢', // &#x0662;
					    '3': '٣', // &#x0663;
					    '4': '٤', // &#x0664;
					    '5': '٥', // &#x0665;
					    '6': '٦', // &#x0666;
					    '7': '٧', // &#x0667;
					    '8': '٨', // &#x0668;
					    '9': '٩', // &#x0669;
					    '.': '٫', // &#x066b; wrong table ?
					    ',': '٬' // &#x066c;
					}

				},
				en: { },
				fr: {
					convertPlural: function( count, forms ) {
						forms = mw.language.preConvertPlural( forms, 2 );
						return ( count <= 1 ) ? forms[0] : forms[1];
					}
				},
				jp: { },
				zh: { }
			};

			/* simulate how the language classes override, or don't, the standard functions in mw.language */
			$.each( languageClasses, function( langCode, rules ) { 
				$.each( [ 'convertPlural', 'convertNumber' ], function( i, propertyName ) { 
					if ( typeof rules[ propertyName ] === 'undefined' ) {
						rules[ propertyName ] = mw.language[ propertyName ];
					}
				} );
			} );

			$.each( jasmineMsgSpec, function( i, test ) { 
				it( "should parse " + test.name, function() { 
					// using language override so we don't have to muck with global namespace
					var parser = new mw.jqueryMsg.parser( { language: languageClasses[ test.lang ] } );
					var parsedHtml = parser.parse( test.key, test.args ).html();
					expect( parsedHtml ).toEqual( test.result );
				} );
			} );

		} );

	} );
} )( window.mediaWiki, jQuery );
