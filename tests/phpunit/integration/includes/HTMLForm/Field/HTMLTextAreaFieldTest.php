<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLTextAreaField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLTextAreaField
 */
class HTMLTextAreaFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLTextAreaField::class;

	public static function provideInputCodex(): iterable {
		yield 'basic textarea' => [
			[],
			'',
			false,
			<<<HTML
				<div class="cdx-text-area">
					<textarea id="mw-input-testfield" cols="80" rows="25" class="cdx-text-area__textarea" name="testfield"></textarea>
				</div>
			HTML
		];

		yield 'textarea with value and attributes' => [
			[ 'cssclass' => 'my-textarea', 'cols' => 20, 'rows' => 4, 'placeholder' => 'Type something', 'spellcheck' => false, 'maxlength' => 50, 'readonly' => true ],
			'Some <b>text</b> & more',
			false,
			<<<HTML
				<div class="cdx-text-area">
					<textarea id="mw-input-testfield" cols="20" rows="4" spellcheck="false" class="cdx-text-area__textarea my-textarea" placeholder="Type something" maxlength="50" readonly="" name="testfield">Some &lt;b>text&lt;/b> &amp; more</textarea>
				</div>
			HTML
		];

		yield 'disabled textarea with error state' => [
			[ 'disabled' => true ],
			'Bleh!',
			true,
			<<<HTML
				<div class="cdx-text-area cdx-text-area--status-error">
					<textarea id="mw-input-testfield" cols="80" rows="25" class="cdx-text-area__textarea" disabled="" name="testfield">Bleh!</textarea>
				</div>
			HTML
		];
	}
}
