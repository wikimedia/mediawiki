QUnit.module( 'jquery.textSelection', () => {
	const sig = {
		pre: '--~~~~'
	};
	const bold = {
		pre: '\'\'\'',
		peri: 'Bold text',
		post: '\'\'\''
	};
	const h2 = {
		pre: '== ',
		peri: 'Heading 2',
		post: ' ==',
		regex: /^(\s*)(={1,6})(.*?)\2(\s*)$/,
		regexReplace: '$1==$3==$4',
		ownline: true
	};
	const ulist = {
		pre: '* ',
		peri: 'Bulleted list item',
		post: '',
		ownline: true,
		splitlines: true
	};

	QUnit.test.each( 'encapsulateText', {
		'Adding sig to end of text': {
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
		},
		'Adding bold to empty': {
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
		},
		'Adding bold to existing text': {
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
		},
		'ownline option: adding new h2': {
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
		},
		'ownline option: turn a whole line into new h2': {
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
		},
		'ownline option: turn a partial line into new h2': {
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
		},
		'splitlines option: no selection, insert new list item': {
			before: {
				text: 'Before\nAfter',
				start: 7,
				end: 7
			},
			after: {
				text: 'Before\n* Bulleted list item\nAfter'
			},
			replace: ulist
		},
		'splitlines option: single partial line selection, insert new list item': {
			before: {
				text: 'BeforeMy List ItemAfter',
				start: 6,
				end: 18
			},
			after: {
				text: 'Before\n* My List Item\nAfter'
			},
			replace: ulist
		},
		'splitlines option: multiple lines': {
			before: {
				text: 'Before\nFirst\nSecond\nThird\nAfter',
				start: 7,
				end: 25
			},
			after: {
				text: 'Before\n* First\n* Second\n* Third\nAfter'
			},
			replace: ulist
		}
	}, ( assert, opt ) => {
		const $textarea = $( '<textarea>' ).appendTo( '#qunit-fixture' );
		$textarea.textSelection( 'setContents', opt.before.text );
		const replace = Object.assign( {
			selectionStart: opt.before.start,
			selectionEnd: opt.before.end
		}, opt.replace );
		$textarea.textSelection( 'encapsulateSelection', replace );

		const text = $textarea.textSelection( 'getContents' ).replace( /\r\n/g, '\n' );
		assert.strictEqual( text, opt.after.text, 'after encapsulation' );

		if ( opt.after.selected !== undefined ) {
			const selected = $textarea.textSelection( 'getSelection' );
			assert.strictEqual( selected, opt.after.selected, 'selected text' );
		}
	} );

	const caretSample = 'Some big text that we like to work with. Nothing fancy... you know what I mean?';

	// Default/empty selection, T36820, T33847
	QUnit.test( 'getCaretPosition [initial]', ( assert ) => {
		const $textarea = $( '<textarea>' ).text( caretSample ).appendTo( '#qunit-fixture' );
		const pos = $textarea.textSelection( 'getCaretPosition', { startAndEnd: true } );
		assert.strictEqual( pos[ 0 ], 0, 'default start' );
		assert.strictEqual( pos[ 1 ], 0, 'default end' );
	} );

	QUnit.test.each( 'getCaretPosition', {
		'forced empty selection': {
			start: 7,
			end: 7
		},
		'small selection': {
			start: 6,
			end: 11
		}
	}, ( assert, options ) => {
		const $textarea = $( '<textarea>' ).text( caretSample ).appendTo( '#qunit-fixture' );

		$textarea.textSelection( 'setSelection', {
			start: options.start,
			end: options.end
		} );

		const pos = $textarea.textSelection( 'getCaretPosition', { startAndEnd: true } );
		assert.strictEqual( pos[ 0 ], options.start, 'Caret start should be where we set it.' );
		assert.strictEqual( pos[ 1 ], options.end, 'Caret end should be where we set it.' );
	} );
} );
