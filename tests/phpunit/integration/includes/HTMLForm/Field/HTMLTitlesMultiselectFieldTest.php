<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLTitlesMultiselectField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLTitlesMultiselectField
 */
class HTMLTitlesMultiselectFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLTitlesMultiselectField::class;

	public static function provideInputCodex(): iterable {
		yield 'basic field' => [
			[],
			'',
			false,
			<<<HTML
				<div class="cdx-text-area">
					<textarea id="mw-input-testfield" cols="80" rows="10" class="cdx-text-area__textarea" name="testfield"></textarea>
				</div>
			HTML
		];

		yield 'field with value and placeholder' => [
			[ 'placeholder' => 'Add titles' ],
			'Foo',
			false,
			<<<HTML
				<div class="cdx-text-area">
					<textarea id="mw-input-testfield" cols="80" rows="10" class="cdx-text-area__textarea" placeholder="Add titles" name="testfield">Foo</textarea>
				</div>
			HTML
		];

		yield 'field with error state' => [
			[],
			'Foo',
			true,
			<<<HTML
				<div class="cdx-text-area cdx-text-area--status-error">
					<textarea id="mw-input-testfield" cols="80" rows="10" class="cdx-text-area__textarea" name="testfield">Foo</textarea>
				</div>
			HTML
		];
	}
}
