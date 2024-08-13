<?php
namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers MediaWiki\HTMLForm\Field\HTMLRadioField
 */
class HTMLRadioFieldTest extends HTMLFormFieldTestCase {
	protected $className = 'HTMLRadioField';

	public static function provideInputCodex() {
		yield 'Radios with none selected' => [
			[
				'options' => [
					'One' => '1',
					'Two' => '2',
					'Three' => '3'
				]
			],
			null,
			false,
			<<<HTML
				<div class="cdx-radio">
					<input id="mw-input-testfield-1" type="radio" name="testfield" class="cdx-radio__input" value="1">
					<span class="cdx-radio__icon"></span>
					<div class="cdx-radio__label cdx-label"><label class="cdx-label__label" for="mw-input-testfield-1">One</label></div>
				</div>
				<div class="cdx-radio">
					<input id="mw-input-testfield-2" type="radio" name="testfield" class="cdx-radio__input" value="2">
					<span class="cdx-radio__icon"></span>
					<div class="cdx-radio__label cdx-label"><label class="cdx-label__label" for="mw-input-testfield-2">Two</label></div>
				</div>
				<div class="cdx-radio">
					<input id="mw-input-testfield-3" type="radio" name="testfield" class="cdx-radio__input" value="3">
					<span class="cdx-radio__icon"></span>
					<div class="cdx-radio__label cdx-label"><label class="cdx-label__label" for="mw-input-testfield-3">Three</label></div>
				</div>
			HTML
		];

		yield 'Radios with one selected' => [
			[
				'options' => [
					'One' => '1',
					'Two' => '2',
					'Three' => '3'
				]
			],
			'2',
			false,
			<<<HTML
				<div class="cdx-radio">
					<input id="mw-input-testfield-1" type="radio" name="testfield" class="cdx-radio__input" value="1">
					<span class="cdx-radio__icon"></span>
					<div class="cdx-radio__label cdx-label"><label class="cdx-label__label" for="mw-input-testfield-1">One</label></div>
				</div>
				<div class="cdx-radio">
					<input id="mw-input-testfield-2" type="radio" name="testfield" class="cdx-radio__input" value="2" checked="">
					<span class="cdx-radio__icon"></span>
					<div class="cdx-radio__label cdx-label"><label class="cdx-label__label" for="mw-input-testfield-2">Two</label></div>
				</div>
				<div class="cdx-radio">
					<input id="mw-input-testfield-3" type="radio" name="testfield" class="cdx-radio__input" value="3">
					<span class="cdx-radio__icon"></span>
					<div class="cdx-radio__label cdx-label"><label class="cdx-label__label" for="mw-input-testfield-3">Three</label></div>
				</div>
			HTML
		];

		yield 'Radios with descriptions' => [
			[
				'options' => [
					'One' => '1',
					'Two' => '2',
					'Three' => '3'
				],
				'option-descriptions' => [
					'1' => 'First',
					'2' => 'Second',
					'3' => 'Third'
				]
			],
			'2',
			false,
			<<<HTML
				<div class="cdx-radio">
					<input id="mw-input-testfield-1" type="radio" name="testfield" class="cdx-radio__input" value="1" aria-describedby="mw-input-testfield-1-description">
					<span class="cdx-radio__icon"></span>
					<div class="cdx-radio__label cdx-label">
						<label class="cdx-label__label" for="mw-input-testfield-1">One</label>
						<span id="mw-input-testfield-1-description" class="cdx-label__description">First</span>
					</div>
				</div>
				<div class="cdx-radio">
					<input id="mw-input-testfield-2" type="radio" name="testfield" class="cdx-radio__input" value="2" aria-describedby="mw-input-testfield-2-description" checked="">
					<span class="cdx-radio__icon"></span>
					<div class="cdx-radio__label cdx-label">
						<label class="cdx-label__label" for="mw-input-testfield-2">Two</label>
						<span id="mw-input-testfield-2-description" class="cdx-label__description">Second</span>
					</div>
				</div>
				<div class="cdx-radio">
					<input id="mw-input-testfield-3" type="radio" name="testfield" class="cdx-radio__input" value="3" aria-describedby="mw-input-testfield-3-description">
					<span class="cdx-radio__icon"></span>
					<div class="cdx-radio__label cdx-label">
						<label class="cdx-label__label" for="mw-input-testfield-3">Three</label>
						<span id="mw-input-testfield-3-description" class="cdx-label__description">Third</span>
					</div>
				</div>
			HTML
		];
	}
}
