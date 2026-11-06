<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLExpiryField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLExpiryField
 */
class HTMLExpiryFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLExpiryField::class;

	public static function provideInputCodex(): iterable {
		yield 'text input without options' => [
			[],
			'1 week',
			false,
			<<<HTML
				<div class="cdx-text-input">
					<input id="mw-input-testfield" name="testfield" size="45" value="1 week" class="cdx-text-input__input">
				</div>
			HTML
		];

		yield 'select-or-other with options' => [
			[ 'options' => [ '1 day' => '1 day', '1 week' => '1 week' ] ],
			'1 week',
			false,
			<<<HTML
				<div id="mw-input-testfield" class="mw-htmlform-select-or-other">
					<select name="testfield" class="cdx-select">
						<option value="other">htmlform-selectorother-other</option>
						<option value="1 day">1 day</option>
						<option value="1 week" selected="">1 week</option>
					</select>
					<div class="cdx-text-input">
						<input size="45" name="testfield-other" class="cdx-text-input__input">
					</div>
				</div>
			HTML
		];

		yield 'select-or-other with other value' => [
			[ 'options' => [ '1 day' => '1 day' ] ],
			'2026-12-01',
			false,
			<<<HTML
				<div id="mw-input-testfield" class="mw-htmlform-select-or-other">
					<select name="testfield" class="cdx-select">
						<option value="other" selected="">htmlform-selectorother-other</option>
						<option value="1 day">1 day</option>
					</select>
					<div class="cdx-text-input">
						<input size="45" name="testfield-other" class="cdx-text-input__input" value="2026-12-01">
					</div>
				</div>
			HTML
		];
	}
}
