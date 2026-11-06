<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLSelectField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLSelectField
 */
class HTMLSelectFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLSelectField::class;

	public static function provideInputCodex(): iterable {
		yield 'basic select' => [
			[ 'options' => [ 'Apple' => 'a', 'Banana' => 'b' ] ],
			'',
			false,
			<<<HTML
				<select name="testfield" id="mw-input-testfield" class="cdx-select">
					<option value="a">Apple</option>
					<option value="b">Banana</option>
				</select>
			HTML
		];

		yield 'select with value and attributes' => [
			[ 'options' => [ 'Apple' => 'a', 'Banana' => 'b' ], 'cssclass' => 'my-select', 'tabindex' => 2, 'size' => 2 ],
			'b',
			false,
			<<<HTML
				<select name="testfield" id="mw-input-testfield" tabindex="2" size="2" class="cdx-select my-select">
					<option value="a">Apple</option>
					<option value="b" selected="">Banana</option>
				</select>
			HTML
		];

		yield 'disabled select with option groups' => [
			[ 'options' => [ 'Fruit' => [ 'Apple' => 'a' ], 'Other' => 'o' ], 'disabled' => true ],
			'a',
			false,
			<<<HTML
				<select name="testfield" id="mw-input-testfield" disabled="" class="cdx-select">
					<optgroup label="Fruit">
						<option value="a" selected="">Apple</option>
					</optgroup>
					<option value="o">Other</option>
				</select>
			HTML
		];
	}
}
