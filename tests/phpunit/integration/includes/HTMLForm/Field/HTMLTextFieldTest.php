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

		yield 'basic field with start and end icon' => [
			[
				'label-message' => 'test-label-key',
				'start-icon-class' => 'my-field__search-icon',
				'end-icon-class' => 'my-field__info-icon',
			],
			'',
			<<<HTML
				<div class="mw-htmlform-field-HTMLTextField cdx-field">
					<div class="cdx-label">
						<label class="cdx-label__label" for="mw-input-testfield">
							<span class="cdx-label__label__text">(test-label-key)</span>
						</label>
					</div>
					<div class="cdx-field__control">
						<div class="cdx-text-input cdx-text-input--has-start-icon cdx-text-input--has-end-icon">
							<input id="mw-input-testfield" name="testfield" size="45" class="cdx-text-input__input">
							<span class="cdx-text-input__icon cdx-text-input__start-icon my-field__search-icon"></span>
							<span class="cdx-text-input__icon cdx-text-input__end-icon my-field__info-icon"></span>
						</div>
					</div>
				</div>
			HTML
		];
	}

	public static function provideInputCodex(): iterable {
		yield 'basic text field' => [
			[],
			'',
			false,
			<<<HTML
				<div class="cdx-text-input">
					<input id="mw-input-testfield" name="testfield" size="45" class="cdx-text-input__input">
				</div>
			HTML
		];

		yield 'text field with attributes and value' => [
			[
				'cssclass' => 'my-text',
				'size' => 10,
				'dir' => 'ltr',
				'spellcheck' => false,
				'placeholder' => 'Type something',
				'min' => 1,
				'max' => 100,
				'step' => 7,
				'title' => 'Title',
				'minlength' => 1,
				'maxlength' => 3,
				'tabindex' => 2,
				'required' => true,
				'autofocus' => true,
				'readonly' => true,
				'autocomplete' => false,
				'pattern' => '\d{1,3}',
				'list' => 'dataListID',
			],
			'42',
			false,
			<<<HTML
				<div class="cdx-text-input">
					<input id="mw-input-testfield" name="testfield" size="10" value="42" dir="ltr" spellcheck="false"
					 placeholder="Type something" class="my-text cdx-text-input__input" min="1" max="100" step="7"
					 title="Title" maxlength="3" minlength="1" tabindex="2" required="" autofocus="" readonly=""
					 autocomplete="off" pattern="\\d{1,3}" list="dataListID">
				</div>
			HTML
		];

		yield 'disabled text field with error state' => [
			[ 'disabled' => true ],
			'Bleh!',
			true,
			<<<HTML
				<div class="cdx-text-input cdx-text-input--status-error">
					<input id="mw-input-testfield" name="testfield" size="45" value="Bleh!" class="cdx-text-input__input" disabled="">
				</div>
			HTML
		];

		foreach ( [ 'email', 'url' ] as $type ) {
			yield "$type field" => [
				[ 'type' => $type ],
				'value',
				false,
				<<<HTML
					<div class="cdx-text-input">
						<input id="mw-input-testfield" name="testfield" size="45" value="value" class="cdx-text-input__input" type="$type">
					</div>
				HTML
			];
		}

		yield 'password field drops the value' => [
			[ 'type' => 'password' ],
			'secret',
			false,
			<<<HTML
				<div class="cdx-text-input">
					<input id="mw-input-testfield" name="testfield" size="45" class="cdx-text-input__input" type="password">
				</div>
			HTML
		];

		yield 'persistent password field keeps the value' => [
			[ 'type' => 'password', 'persistent' => true ],
			'secret',
			false,
			<<<HTML
				<div class="cdx-text-input">
					<input id="mw-input-testfield" name="testfield" size="45" value="secret" class="cdx-text-input__input" type="password">
				</div>
			HTML
		];

		yield 'int field' => [
			[ 'type' => 'int' ],
			'7',
			false,
			<<<HTML
				<div class="cdx-text-input">
					<input id="mw-input-testfield" name="testfield" size="45" value="7" class="cdx-text-input__input" step="1" type="number">
				</div>
			HTML
		];

		yield 'float field' => [
			[ 'type' => 'float' ],
			'7.5',
			false,
			<<<HTML
				<div class="cdx-text-input">
					<input id="mw-input-testfield" name="testfield" size="45" value="7.5" class="cdx-text-input__input" step="any" type="number">
				</div>
			HTML
		];
	}
}
