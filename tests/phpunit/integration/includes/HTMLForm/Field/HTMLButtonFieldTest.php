<?php
namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers MediaWiki\HTMLForm\Field\HTMLButtonField
 */
class HTMLButtonFieldTest extends HTMLFormFieldTestCase {
	protected $className = 'HTMLButtonField';

	public static function provideInputCodex() {
		yield 'Basic button' => [
			[
				'buttonlabel' => 'Click me',
			],
			'',
			false,
			'<button class="mw-htmlform-submit cdx-button" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Button with CSS class' => [
			[
				'buttonlabel' => 'Click me',
				'cssclass' => 'my-button',
			],
			'',
			false,
			'<button class="mw-htmlform-submit cdx-button my-button" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Primary progressive button' => [
			[
				'buttonlabel' => 'Click me',
				'flags' => [ 'primary', 'progressive' ]
			],
			'',
			false,
			'<button class="mw-htmlform-submit cdx-button cdx-button--weight-primary cdx-button--action-progressive" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Destructive button' => [
			[
				'buttonlabel' => 'Click me',
				'flags' => [ 'destructive' ]
			],
			'',
			false,
			'<button class="mw-htmlform-submit cdx-button cdx-button--action-destructive" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Quiet button with CSS class' => [
			[
				'buttonlabel' => 'Click me',
				'cssclass' => 'my-button',
				'flags' => [ 'quiet' ]
			],
			'',
			false,
			'<button class="mw-htmlform-submit cdx-button my-button cdx-button--weight-quiet" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Disabled button' => [
			[
				'buttonlabel' => 'Click me',
				'disabled' => true
			],
			'',
			false,
			'<button class="mw-htmlform-submit cdx-button" id="mw-input-testfield" type="button" name="testfield" disabled="">Click me</button>'
		];
	}
}
