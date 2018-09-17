( function () {
	QUnit.module( 'mediawiki.html' );

	QUnit.test( 'escape', function ( assert ) {
		assert.throws(
			function () {
				mw.html.escape();
			},
			TypeError,
			'throw a TypeError if argument is not a string'
		);

		assert.strictEqual(
			mw.html.escape( '<mw awesome="awesome" value=\'test\' />' ),
			'&lt;mw awesome=&quot;awesome&quot; value=&#039;test&#039; /&gt;',
			'Escape special characters to html entities'
		);
	} );

	QUnit.test( 'element()', function ( assert ) {
		assert.strictEqual(
			mw.html.element(),
			'<undefined/>',
			'return valid html even without arguments'
		);
	} );

	QUnit.test( 'element( tagName )', function ( assert ) {
		assert.strictEqual( mw.html.element( 'div' ), '<div/>', 'DIV' );
	} );

	QUnit.test( 'element( tagName, attrs )', function ( assert ) {
		assert.strictEqual( mw.html.element( 'div', {} ), '<div/>', 'DIV' );

		assert.strictEqual(
			mw.html.element(
				'div', {
					id: 'foobar'
				}
			),
			'<div id="foobar"/>',
			'DIV with attribs'
		);
	} );

	QUnit.test( 'element( tagName, attrs, content )', function ( assert ) {

		assert.strictEqual( mw.html.element( 'div', {}, '' ), '<div></div>', 'DIV with empty attributes and content' );

		assert.strictEqual( mw.html.element( 'p', {}, 12 ), '<p>12</p>', 'numbers as content cast to strings' );

		assert.strictEqual( mw.html.element( 'p', { title: 12 }, '' ), '<p title="12"></p>', 'number as attribute value' );

		assert.strictEqual(
			mw.html.element(
				'div',
				{},
				new mw.html.Raw(
					mw.html.element( 'img', { src: '<' } )
				)
			),
			'<div><img src="&lt;"/></div>',
			'unescaped content with mw.html.Raw'
		);

		assert.strictEqual(
			mw.html.element(
				'option',
				{
					selected: true
				},
				'Foo'
			),
			'<option selected="selected">Foo</option>',
			'boolean true attribute value'
		);

		assert.strictEqual(
			mw.html.element(
				'option',
				{
					value: 'foo',
					selected: false
				},
				'Foo'
			),
			'<option value="foo">Foo</option>',
			'boolean false attribute value'
		);

		assert.strictEqual(
			mw.html.element( 'div', null, 'a' ),
			'<div>a</div>',
			'Skip attributes with null' );

		assert.strictEqual(
			mw.html.element( 'a', {
				href: 'http://mediawiki.org/w/index.php?title=RL&action=history'
			}, 'a' ),
			'<a href="http://mediawiki.org/w/index.php?title=RL&amp;action=history">a</a>',
			'Andhor tag with attributes and content'
		);
	} );

}() );
