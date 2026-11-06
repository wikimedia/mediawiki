<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLNamespacesMultiselectField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLNamespacesMultiselectField
 */
class HTMLNamespacesMultiselectFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLNamespacesMultiselectField::class;

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

		yield 'field with value and custom rows' => [
			[ 'rows' => 3 ],
			'0',
			false,
			<<<HTML
				<div class="cdx-text-area">
					<textarea id="mw-input-testfield" cols="80" rows="3" class="cdx-text-area__textarea" name="testfield">0</textarea>
				</div>
			HTML
		];

		yield 'field with error state' => [
			[],
			'0',
			true,
			<<<HTML
				<div class="cdx-text-area cdx-text-area--status-error">
					<textarea id="mw-input-testfield" cols="80" rows="10" class="cdx-text-area__textarea" name="testfield">0</textarea>
				</div>
			HTML
		];
	}
}
