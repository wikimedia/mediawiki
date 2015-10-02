( function ( $ ) {

	QUnit.module( 'jquery.textSelection', QUnit.newMwEnvironment() );

	/**
	 * Test factory for $.fn.textSelection( 'encapsulateText' )
	 *
	 * @param {Object} options Associative configuration array
	 * @param {string} options.description Description
	 * @param {string} options.input Input
	 * @param {string} options.output Output
	 * @param {int} options.start Starting char for selection
	 * @param {int} options.end Ending char for selection
	 * @param {Object} options.params Additional parameters for $().textSelection( 'encapsulateText' )
	 */
	function encapsulateTest( options ) {
		var opt = $.extend( {
			description: '',
			before: {},
			after: {},
			replace: {}
		}, options );

		opt.before = $.extend( {
			text: '',
			start: 0,
			end: 0
		}, opt.before );
		opt.after = $.extend( {
			text: '',
			selected: null
		}, opt.after );

		QUnit.test( opt.description, function ( assert ) {
			var $textarea, start, end, options, text, selected,
				tests = 1;
			if ( opt.after.selected !== null ) {
				tests++;
			}
			QUnit.expect( tests );

			$textarea = $( '<textarea>' );

			$( '#qunit-fixture' ).append( $textarea );

			$textarea.textSelection( 'setContents', opt.before.text );

			start = opt.before.start;
			end = opt.before.end;

			// Clone opt.replace
			options = $.extend( {}, opt.replace );
			options.selectionStart = start;
			options.selectionEnd = end;
			$textarea.textSelection( 'encapsulateSelection', options );

			text = $textarea.textSelection( 'getContents' ).replace( /\r\n/g, '\n' );

			assert.equal( text, opt.after.text, 'Checking full text after encapsulation' );

			if ( opt.after.selected !== null ) {
				selected = $textarea.textSelection( 'getSelection' );
				assert.equal( selected, opt.after.selected, 'Checking selected text after encapsulation.' );
			}

		} );
	}

	var caretSample,
		sig = {
			pre: '--~~~~'
		},
		bold = {
			pre: '\'\'\'',
			peri: 'Bold text',
			post: '\'\'\''
		},
		h2 = {
			pre: '== ',
			peri: 'Heading 2',
			post: ' ==',
			regex: /^(\s*)(={1,6})(.*?)\2(\s*)$/,
			regexReplace: '$1==$3==$4',
			ownline: true
		},
		ulist = {
			pre: '* ',
			peri: 'Bulleted list item',
			post: '',
			ownline: true,
			splitlines: true
		};

	encapsulateTest( {
		description: 'Adding sig to end of text',
		before: {
			text: 'Wikilove dude! ',
			start: 15,
			end: 15
		},
		after: {
			text: 'Wikilove dude! --~~~~',
			selected: ''
		},
		replace: sig
	} );

	encapsulateTest( {
		description: 'Adding bold to empty',
		before: {
			text: '',
			start: 0,
			end: 0
		},
		after: {
			text: '\'\'\'Bold text\'\'\'',
			selected: 'Bold text' // selected because it's the default
		},
		replace: bold
	} );

	encapsulateTest( {
		description: 'Adding bold to existing text',
		before: {
			text: 'Now is the time for all good men to come to the aid of their country',
			start: 20,
			end: 32
		},
		after: {
			text: 'Now is the time for \'\'\'all good men\'\'\' to come to the aid of their country',
			selected: '' // empty because it's not the default'
		},
		replace: bold
	} );

	encapsulateTest( {
		description: 'ownline option: adding new h2',
		before: {
			text: 'Before\nAfter',
			start: 7,
			end: 7
		},
		after: {
			text: 'Before\n== Heading 2 ==\nAfter',
			selected: 'Heading 2'
		},
		replace: h2
	} );

	encapsulateTest( {
		description: 'ownline option: turn a whole line into new h2',
		before: {
			text: 'Before\nMy heading\nAfter',
			start: 7,
			end: 17
		},
		after: {
			text: 'Before\n== My heading ==\nAfter',
			selected: ''
		},
		replace: h2
	} );

	encapsulateTest( {
		description: 'ownline option: turn a partial line into new h2',
		before: {
			text: 'BeforeMy headingAfter',
			start: 6,
			end: 16
		},
		after: {
			text: 'Before\n== My heading ==\nAfter',
			selected: ''
		},
		replace: h2
	} );

	encapsulateTest( {
		description: 'splitlines option: no selection, insert new list item',
		before: {
			text: 'Before\nAfter',
			start: 7,
			end: 7
		},
		after: {
			text: 'Before\n* Bulleted list item\nAfter'
		},
		replace: ulist
	} );

	encapsulateTest( {
		description: 'splitlines option: single partial line selection, insert new list item',
		before: {
			text: 'BeforeMy List ItemAfter',
			start: 6,
			end: 18
		},
		after: {
			text: 'Before\n* My List Item\nAfter'
		},
		replace: ulist
	} );

	encapsulateTest( {
		description: 'splitlines option: multiple lines',
		before: {
			text: 'Before\nFirst\nSecond\nThird\nAfter',
			start: 7,
			end: 25
		},
		after: {
			text: 'Before\n* First\n* Second\n* Third\nAfter'
		},
		replace: ulist
	} );

	function caretTest( options ) {
		QUnit.test( options.description, 2, function ( assert ) {
			var pos,
				$textarea = $( '<textarea>' ).text( options.text );

			$( '#qunit-fixture' ).append( $textarea );

			if ( options.mode === 'set' ) {
				$textarea.textSelection( 'setSelection', {
					start: options.start,
					end: options.end
				} );
			}

			function among( actual, expected, message ) {
				if ( $.isArray( expected ) ) {
					assert.ok( $.inArray( actual, expected ) !== -1, message + ' (got ' + actual + '; expected one of ' + expected.join( ', ' ) + ')' );
				} else {
					assert.equal( actual, expected, message );
				}
			}

			pos = $textarea.textSelection( 'getCaretPosition', { startAndEnd: true } );
			among( pos[ 0 ], options.start, 'Caret start should be where we set it.' );
			among( pos[ 1 ], options.end, 'Caret end should be where we set it.' );
		} );
	}

	caretSample = 'Some big text that we like to work with. Nothing fancy... you know what I mean?';

	/* @broken: Disabled per bug 34820
	caretTest({
		description: 'getCaretPosition with original/empty selection - bug 31847 with IE 6/7/8',
		text: caretSample,
		start: [0, caretSample.length], // Opera and Firefox (prior to FF 6.0) default caret to the end of the box (caretSample.length)
		end: [0, caretSample.length], // Other browsers default it to the beginning (0), so check both.
		mode: 'get'
	});
	*/

	caretTest( {
		description: 'set/getCaretPosition with forced empty selection',
		text: caretSample,
		start: 7,
		end: 7,
		mode: 'set'
	} );

	caretTest( {
		description: 'set/getCaretPosition with small selection',
		text: caretSample,
		start: 6,
		end: 11,
		mode: 'set'
	} );
}( jQuery ) );
