<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLSelectAndOtherField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLSelectAndOtherField
 */
class HTMLSelectAndOtherFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLSelectAndOtherField::class;

	public static function provideInputCodex(): iterable {
		yield 'basic field' => [
			[ 'options' => [ 'Apple' => 'a', 'Banana' => 'b' ] ],
			[ '', 'other', '' ],
			false,
			<<<HTML
				<div id="mw-input-testfield" class="mw-htmlform-select-and-other-field">
					<select name="testfield" id="mw-input-testfield" class="cdx-select">
						<option value="other" selected="">htmlform-selectorother-other</option>
						<option value="a">Apple</option>
						<option value="b">Banana</option>
					</select>
					<br />
					<div class="cdx-text-input">
						<input size="45" name="testfield-other" class="cdx-text-input__input">
					</div>
				</div>
			HTML
		];

		yield 'field with values and attributes' => [
			[ 'options' => [ 'Apple' => 'a', 'Banana' => 'b' ], 'cssclass' => 'my-field', 'maxlength' => 100, 'maxlength-unit' => 'codepoints', 'required' => true ],
			[ 'b: Ripe', 'b', 'Ripe' ],
			false,
			<<<HTML
				<div id="mw-input-testfield" class="mw-htmlform-select-and-other-field my-field">
					<select name="testfield" id="mw-input-testfield" class="cdx-select my-field">
						<option value="other">htmlform-selectorother-other</option>
						<option value="a">Apple</option>
						<option value="b" selected="">Banana</option>
					</select>
					<br />
					<div class="cdx-text-input">
						<input size="45" name="testfield-other" data-mw-maxlength-unit="codepoints" required="" maxlength="100" maxlength-unit="codepoints" class="cdx-text-input__input" value="Ripe">
					</div>
				</div>
			HTML
		];

		yield 'disabled field with error state' => [
			[ 'options' => [ 'Apple' => 'a' ], 'disabled' => true ],
			[ 'Text', 'other', 'Text' ],
			true,
			<<<HTML
				<div id="mw-input-testfield" class="mw-htmlform-select-and-other-field">
					<select name="testfield" id="mw-input-testfield" disabled="" class="cdx-select">
						<option value="other" selected="">htmlform-selectorother-other</option>
						<option value="a">Apple</option>
					</select>
					<br />
					<div class="cdx-text-input cdx-text-input--status-error">
						<input size="45" name="testfield-other" disabled="" class="cdx-text-input__input" value="Text">
					</div>
				</div>
			HTML
		];
	}
}
