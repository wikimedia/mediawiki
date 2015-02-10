( function ($) {

	QUnit.module('jquery.placeholder', QUnit.newMwEnvironment());

	QUnit.test('caches results of feature tests', 2, function (assert) {
		assert.strictEqual( typeof $.fn.placeholder.input, 'boolean', '$.fn.placeholder.input');
		assert.strictEqual( typeof $.fn.placeholder.textarea, 'boolean', '$.fn.placeholder.textarea');
	});

	if ($.fn.placeholder.input && $.fn.placeholder.textarea) {
		return;
	}

	var html = '<form>' +
			'<input id="input-type-search" type="search" placeholder="Search this site...">' +
			'<input id="input-type-text" type="text" placeholder="e.g. John Doe">' +
			'<input id="input-type-email" type="email" placeholder="e.g. address@example.ext">' +
			'<input id="input-type-url" type="url" placeholder="e.g. http://mathiasbynens.be/">' +
			'<input id="input-type-tel" type="tel" placeholder="e.g. +32 472 77 69 88">' +
			'<input id="input-type-password" type="password" placeholder="e.g. hunter2">' +
			'<textarea id="textarea" name="message" placeholder="Your message goes here"></textarea>' +
		'</form>',
	testElement = function ($el, assert) {

		var el = $el[0],
			placeholder = el.getAttribute('placeholder');

		assert.strictEqual($el.placeholder(), $el, 'should be chainable');

		assert.strictEqual(el.value, placeholder, 'should set `placeholder` text as `value`');
		assert.strictEqual($el.prop('value'), '', 'propHooks works properly');
		assert.strictEqual($el.val(), '', 'valHooks works properly');
		assert.ok($el.hasClass('placeholder'), 'should have `placeholder` class');

		// test on focus
		$el.focus();
		assert.strictEqual(el.value, '', '`value` should be the empty string on focus');
		assert.strictEqual($el.prop('value'), '', 'propHooks works properly');
		assert.strictEqual($el.val(), '', 'valHooks works properly');
		assert.ok(!$el.hasClass('placeholder'), 'should not have `placeholder` class on focus');

		// and unfocus (blur) again
		$el.blur();

		assert.strictEqual(el.value, placeholder, 'should set `placeholder` text as `value`');
		assert.strictEqual($el.prop('value'), '', 'propHooks works properly');
		assert.strictEqual($el.val(), '', 'valHooks works properly');
		assert.ok($el.hasClass('placeholder'), 'should have `placeholder` class');

		// change the value
		$el.val('lorem ipsum');
		assert.strictEqual($el.prop('value'), 'lorem ipsum', '`$el.val(string)` should change the `value` property');
		assert.strictEqual(el.value, 'lorem ipsum', '`$el.val(string)` should change the `value` attribute');
		assert.ok(!$el.hasClass('placeholder'), '`$el.val(string)` should remove `placeholder` class');

		// and clear it again
		$el.val('');
		assert.strictEqual($el.prop('value'), '', '`$el.val("")` should change the `value` property');
		assert.strictEqual(el.value, placeholder, '`$el.val("")` should change the `value` attribute');
		assert.ok($el.hasClass('placeholder'), '`$el.val("")` should re-enable `placeholder` class');

		// make sure the placeholder property works as expected.
		assert.strictEqual($el.prop('placeholder'), placeholder, '$el.prop(`placeholder`) should return the placeholder value');
		$el.placeholder('new placeholder');
		assert.strictEqual(el.getAttribute('placeholder'), 'new placeholder', '$el.placeholder(<string>) should set the placeholder value');
		assert.strictEqual(el.value, 'new placeholder', '$el.placeholder(<string>) should update the displayed placeholder value');
		$el.placeholder(placeholder);
	};

	QUnit.test('emulates placeholder for <input type=text>', 22, function (assert) {
		$('<div>').html(html).appendTo($('#qunit-fixture'));
		testElement($('#input-type-text'), assert);
	});

	QUnit.test('emulates placeholder for <input type=search>', 22, function (assert) {
		$('<div>').html(html).appendTo($('#qunit-fixture'));
		testElement($('#input-type-search'), assert);
	});

	QUnit.test('emulates placeholder for <input type=email>', 22, function (assert) {
		$('<div>').html(html).appendTo($('#qunit-fixture'));
		testElement($('#input-type-email'), assert);
	});

	QUnit.test('emulates placeholder for <input type=url>', 22, function (assert) {
		$('<div>').html(html).appendTo($('#qunit-fixture'));
		testElement($('#input-type-url'), assert);
	});

	QUnit.test('emulates placeholder for <input type=tel>', 22, function (assert) {
		$('<div>').html(html).appendTo($('#qunit-fixture'));
		testElement($('#input-type-tel'), assert);
	});

	QUnit.test('emulates placeholder for <input type=password>', 13, function (assert) {
		$('<div>').html(html).appendTo($('#qunit-fixture'));

		var selector = '#input-type-password',
			$el = $(selector),
			el = $el[0],
			placeholder = el.getAttribute('placeholder');

		assert.strictEqual($el.placeholder(), $el, 'should be chainable');

		// Re-select the element, as it gets replaced by another one in some browsers
		$el = $(selector);
		el = $el[0];

		assert.strictEqual(el.value, placeholder, 'should set `placeholder` text as `value`');
		assert.strictEqual($el.prop('value'), '', 'propHooks works properly');
		assert.strictEqual($el.val(), '', 'valHooks works properly');
		assert.ok($el.hasClass('placeholder'), 'should have `placeholder` class');

		// test on focus
		$el.focus();

		// Re-select the element, as it gets replaced by another one in some browsers
		$el = $(selector);
		el = $el[0];

		assert.strictEqual(el.value, '', '`value` should be the empty string on focus');
		assert.strictEqual($el.prop('value'), '', 'propHooks works properly');
		assert.strictEqual($el.val(), '', 'valHooks works properly');
		assert.ok(!$el.hasClass('placeholder'), 'should not have `placeholder` class on focus');

		// and unfocus (blur) again
		$el.blur();

		// Re-select the element, as it gets replaced by another one in some browsers
		$el = $(selector);
		el = $el[0];

		assert.strictEqual(el.value, placeholder, 'should set `placeholder` text as `value`');
		assert.strictEqual($el.prop('value'), '', 'propHooks works properly');
		assert.strictEqual($el.val(), '', 'valHooks works properly');
		assert.ok($el.hasClass('placeholder'), 'should have `placeholder` class');

	});

	QUnit.test('emulates placeholder for <textarea></textarea>', 22, function (assert) {
		$('<div>').html(html).appendTo($('#qunit-fixture'));
		testElement($('#textarea'), assert);
	});

}(jQuery));
