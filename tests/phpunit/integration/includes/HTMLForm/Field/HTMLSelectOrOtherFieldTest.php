<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLSelectOrOtherField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLSelectOrOtherField
 */
class HTMLSelectOrOtherFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLSelectOrOtherField::class;

	public static function provideInputCodex(): iterable {
		yield 'value in options' => [
			[ 'options' => [ 'Apple' => 'a', 'Banana' => 'b' ] ],
			'a',
			false,
			<<<HTML
				<div id="mw-input-testfield" class="mw-htmlform-select-or-other">
					<select name="testfield" class="cdx-select">
						<option value="other">htmlform-selectorother-other</option>
						<option value="a" selected="">Apple</option>
						<option value="b">Banana</option>
					</select>
					<div class="cdx-text-input">
						<input size="45" name="testfield-other" class="cdx-text-input__input">
					</div>
				</div>
			HTML
		];

		yield 'value not in options' => [
			[ 'options' => [ 'Apple' => 'a' ], 'cssclass' => 'my-field' ],
			'Cherry',
			false,
			<<<HTML
				<div id="mw-input-testfield" class="mw-htmlform-select-or-other my-field">
					<select name="testfield" class="cdx-select">
						<option value="other" selected="">htmlform-selectorother-other</option>
						<option value="a">Apple</option>
					</select>
					<div class="cdx-text-input">
						<input size="45" name="testfield-other" class="cdx-text-input__input" value="Cherry">
					</div>
				</div>
			HTML
		];

		yield 'disabled with attributes and error state' => [
			[ 'options' => [ 'Apple' => 'a' ], 'disabled' => true, 'tabindex' => 3, 'maxlength' => 10, 'minlength' => 2 ],
			'Cherry',
			true,
			<<<HTML
				<div id="mw-input-testfield" class="mw-htmlform-select-or-other">
					<select name="testfield" class="cdx-select" disabled="" tabindex="3">
						<option value="other" selected="">htmlform-selectorother-other</option>
						<option value="a">Apple</option>
					</select>
					<div class="cdx-text-input cdx-text-input--status-error">
						<input size="45" name="testfield-other" disabled="" tabindex="3" maxlength="10" minlength="2" class="cdx-text-input__input" value="Cherry">
					</div>
				</div>
			HTML
		];
	}
}
