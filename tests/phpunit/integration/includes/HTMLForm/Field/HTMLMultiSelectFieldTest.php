<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLMultiSelectField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLMultiSelectField
 */
class HTMLMultiSelectFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLMultiSelectField::class;

	public static function provideInputCodex(): iterable {
		yield 'basic field' => [
			[ 'options' => [ 'Apple' => 'a', 'Banana' => 'b' ] ],
			[],
			false,
			<<<HTML
				<div class="cdx-checkbox">
					<input id="mw-input-testfield-a" class="cdx-checkbox__input" type="checkbox" value="a" name="testfield[]">
					<span class="cdx-checkbox__icon">\u{00A0}</span>
					<label for="mw-input-testfield-a" class="cdx-checkbox__label">Apple</label>
				</div>
				<div class="cdx-checkbox">
					<input id="mw-input-testfield-b" class="cdx-checkbox__input" type="checkbox" value="b" name="testfield[]">
					<span class="cdx-checkbox__icon">\u{00A0}</span>
					<label for="mw-input-testfield-b" class="cdx-checkbox__label">Banana</label>
				</div>
			HTML
		];

		yield 'flatlist with values and a disabled option' => [
			[ 'options' => [ 'Apple' => 'a', 'Banana' => 'b', 'Cherry' => 'c' ], 'flatlist' => true, 'disabled-options' => [ 'c' ] ],
			[ 'b', 'c' ],
			false,
			<<<HTML
				<div class="cdx-checkbox cdx-checkbox--inline">
					<input id="mw-input-testfield-a" class="cdx-checkbox__input" type="checkbox" value="a" name="testfield[]">
					<span class="cdx-checkbox__icon">\u{00A0}</span>
					<label for="mw-input-testfield-a" class="cdx-checkbox__label">Apple</label>
				</div>
				<div class="cdx-checkbox cdx-checkbox--inline">
					<input id="mw-input-testfield-b" class="cdx-checkbox__input" checked="" type="checkbox" value="b" name="testfield[]">
					<span class="cdx-checkbox__icon">\u{00A0}</span>
					<label for="mw-input-testfield-b" class="cdx-checkbox__label">Banana</label>
				</div>
				<div class="cdx-checkbox cdx-checkbox--inline">
					<input id="mw-input-testfield-c" class="cdx-checkbox__input" checked="" type="checkbox" value="c" name="testfield[]" disabled="">
					<span class="cdx-checkbox__icon">\u{00A0}</span>
					<label for="mw-input-testfield-c" class="cdx-checkbox__label">Cherry</label>
				</div>
			HTML
		];

		yield 'disabled field with tabindex' => [
			[ 'options' => [ 'Apple' => 'a' ], 'disabled' => true, 'tabindex' => 2 ],
			[ 'a' ],
			false,
			<<<HTML
				<div class="cdx-checkbox">
					<input disabled="" tabindex="2" id="mw-input-testfield-a" class="cdx-checkbox__input" checked="" type="checkbox" value="a" name="testfield[]">
					<span class="cdx-checkbox__icon">\u{00A0}</span>
					<label for="mw-input-testfield-a" class="cdx-checkbox__label">Apple</label>
				</div>
			HTML
		];

		yield 'option groups with error state' => [
			[ 'options' => [ 'Fruit' => [ 'Apple' => 'a' ], 'Other' => 'o' ] ],
			[ 'o' ],
			true,
			<<<HTML
				<fieldset class="cdx-field cdx-field--is-fieldset">
					<legend class="cdx-label">
						<span class="cdx-label__label__text">Fruit</span>
					</legend>
					<div class="cdx-checkbox cdx-checkbox--status-error">
						<input id="mw-input-testfield-a" class="cdx-checkbox__input" type="checkbox" value="a" name="testfield[]">
						<span class="cdx-checkbox__icon">\u{00A0}</span>
						<label for="mw-input-testfield-a" class="cdx-checkbox__label">Apple</label>
					</div>
				</fieldset>
				<div class="cdx-checkbox cdx-checkbox--status-error">
					<input id="mw-input-testfield-o" class="cdx-checkbox__input" checked="" type="checkbox" value="o" name="testfield[]">
					<span class="cdx-checkbox__icon">\u{00A0}</span>
					<label for="mw-input-testfield-o" class="cdx-checkbox__label">Other</label>
				</div>
			HTML
		];

		yield 'options labels are HTML' => [
			[ 'options' => [ '<b>Bold</b>' => 'b' ] ],
			[],
			false,
			<<<HTML
				<div class="cdx-checkbox">
					<input id="mw-input-testfield-b" class="cdx-checkbox__input" type="checkbox" value="b" name="testfield[]">
					<span class="cdx-checkbox__icon">\u{00A0}</span>
					<label for="mw-input-testfield-b" class="cdx-checkbox__label"><b>Bold</b></label>
				</div>
			HTML
		];

		yield 'options-messages labels are escaped' => [
			[ 'options-messages' => [ '<b>bold-key</b>' => 'b' ] ],
			[],
			false,
			<<<HTML
				<div class="cdx-checkbox">
					<input id="mw-input-testfield-b" class="cdx-checkbox__input" type="checkbox" value="b" name="testfield[]">
					<span class="cdx-checkbox__icon">\u{00A0}</span>
					<label for="mw-input-testfield-b" class="cdx-checkbox__label">&lt;b&gt;bold-key&lt;/b&gt;</label>
				</div>
			HTML
		];
	}
}
