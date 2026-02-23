<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLTextField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLTextField
 * @covers \MediaWiki\HTMLForm\HTMLFormField
 */
class HTMLTextFieldTest extends HTMLFormFieldTestCase {

	/** @inheritDoc */
	protected $className = HTMLTextField::class;

	public static function provideCodex(): iterable {
		yield 'basic field with description and help-text' => [
			[
				'label-message' => 'test-label-key',
				'description-message' => 'meaningful-description-key',
				'help-message' => 'useful-help-key',
			],
			'',
			<<<HTML
				<div class="mw-htmlform-field-HTMLTextField cdx-field">
					<div class="cdx-label">
						<label class="cdx-label__label" for="mw-input-testfield">
							<span class="cdx-label__label__text">(test-label-key)</span>
						</label>
						<span class="cdx-label__description">(meaningful-description-key)</span>
					</div>
					<div class="cdx-field__control cdx-field__control--has-help-text">
						<div class="cdx-text-input">
							<input id="mw-input-testfield" name="testfield" size="45" class="cdx-text-input__input">
						</div>
					</div>
					<div class="cdx-field__help-text htmlform-tip">(useful-help-key)</div>
				</div>
			HTML
		];

		yield 'basic field, optional, with raw label, description, and help-text' => [
			[
				'label-raw' => 'literal <em>label</em>',
				'description-raw' => 'literal <a href="https://example.com">description</a>',
				'help-raw' => 'literal <s>verbose</s> help text',
				'show-optional-flag' => true,
			],
			'',
			<<<HTML
				<div class="mw-htmlform-field-HTMLTextField cdx-field">
					<div class="cdx-label">
						<label class="cdx-label__label" for="mw-input-testfield">
							<span class="cdx-label__label__text">literal <em>label</em></span>
							<span class="cdx-label__label__optional-flag"> (htmlform-optional-flag)</span>
						</label>
						<span class="cdx-label__description">
							literal <a href="https://example.com">description</a>
						</span>
					</div>
					<div class="cdx-field__control cdx-field__control--has-help-text">
						<div class="cdx-text-input">
							<input id="mw-input-testfield" name="testfield" size="45" class="cdx-text-input__input">
						</div>
					</div>
					<div class="cdx-field__help-text htmlform-tip">literal <s>verbose</s> help text</div>
				</div>
			HTML
		];

		yield 'basic field, optional with overridden optional-message' => [
			[
				'label-message' => 'label-key',
				'show-optional-flag' => true,
				'optional-message' => 'recommended-key',
			],
			'',
			<<<HTML
				<div class="mw-htmlform-field-HTMLTextField cdx-field">
					<div class="cdx-label">
						<label class="cdx-label__label" for="mw-input-testfield">
							<span class="cdx-label__label__text">(label-key)</span>
							<span class="cdx-label__label__optional-flag"> (recommended-key)</span>
						</label>
					</div>
					<div class="cdx-field__control">
						<div class="cdx-text-input">
							<input id="mw-input-testfield" name="testfield" size="45" class="cdx-text-input__input">
						</div>
					</div>
				</div>
			HTML
		];
	}
}
