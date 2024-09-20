<?php
namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLButtonField;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers MediaWiki\HTMLForm\Field\HTMLButtonField
 */
class HTMLButtonFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLButtonField::class;

	public static function provideInputHtml() {
		yield 'Basic button' => [
			[
				'buttonlabel' => 'Click me',
			],
			'',
			'<button class="mw-htmlform-submit" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Button with CSS class' => [
			[
				'buttonlabel' => 'Click me',
				'cssclass' => 'my-button',
			],
			'',
			'<button class="mw-htmlform-submit my-button" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Primary progressive button' => [
			[
				'buttonlabel' => 'Click me',
				'flags' => [ 'primary', 'progressive' ]
			],
			'',
			'<button class="mw-htmlform-submit mw-htmlform-primary mw-htmlform-progressive" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Destructive button' => [
			[
				'buttonlabel' => 'Click me',
				'flags' => [ 'destructive' ]
			],
			'',
			'<button class="mw-htmlform-submit mw-htmlform-destructive" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Quiet button with CSS class' => [
			[
				'buttonlabel' => 'Click me',
				'cssclass' => 'my-button',
				'flags' => [ 'quiet' ]
			],
			'',
			'<button class="mw-htmlform-submit my-button mw-htmlform-quiet" id="mw-input-testfield" type="button" name="testfield">Click me</button>'
		];

		yield 'Disabled button' => [
			[
				'buttonlabel' => 'Click me',
				'disabled' => true
			],
			'',
			'<button class="mw-htmlform-submit" id="mw-input-testfield" type="button" name="testfield" disabled="">Click me</button>'
		];
	}

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

	public static function provideInputOOUI() {
		yield 'Basic button' => [
			[
				'buttonlabel' => 'Click me',
			],
			'',
			"<span id='mw-input-testfield' class='mw-htmlform-submit  oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-buttonInputWidget'><button type='button' tabindex='0' name='testfield' value='' class='oo-ui-inputWidget-input oo-ui-buttonElement-button'><span class='oo-ui-iconElement-icon oo-ui-iconElement-noIcon'></span><span class='oo-ui-labelElement-label'>Click me</span><span class='oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator'></span></button></span>"
		];

		yield 'Button with CSS class' => [
			[
				'buttonlabel' => 'Click me',
				'cssclass' => 'my-button',
			],
			'',
			"<span id='mw-input-testfield' class='mw-htmlform-submit my-button oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-buttonInputWidget'><button type='button' tabindex='0' name='testfield' value='' class='oo-ui-inputWidget-input oo-ui-buttonElement-button'><span class='oo-ui-iconElement-icon oo-ui-iconElement-noIcon'></span><span class='oo-ui-labelElement-label'>Click me</span><span class='oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator'></span></button></span>"
		];

		yield 'Primary progressive button' => [
			[
				'buttonlabel' => 'Click me',
				'flags' => [ 'primary', 'progressive' ]
			],
			'',
			"<span id='mw-input-testfield' class='mw-htmlform-submit  oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-flaggedElement-primary oo-ui-flaggedElement-progressive oo-ui-buttonInputWidget'><button type='button' tabindex='0' name='testfield' value='' class='oo-ui-inputWidget-input oo-ui-buttonElement-button'><span class='oo-ui-iconElement-icon oo-ui-iconElement-noIcon'></span><span class='oo-ui-labelElement-label'>Click me</span><span class='oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator'></span></button></span>"

		];

		yield 'Destructive button' => [
			[
				'buttonlabel' => 'Click me',
				'flags' => [ 'destructive' ]
			],
			'',
			"<span id='mw-input-testfield' class='mw-htmlform-submit  oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-flaggedElement-destructive oo-ui-buttonInputWidget'><button type='button' tabindex='0' name='testfield' value='' class='oo-ui-inputWidget-input oo-ui-buttonElement-button'><span class='oo-ui-iconElement-icon oo-ui-iconElement-noIcon'></span><span class='oo-ui-labelElement-label'>Click me</span><span class='oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator'></span></button></span>"
		];

		yield 'Quiet button with CSS class' => [
			[
				'buttonlabel' => 'Click me',
				'cssclass' => 'my-button',
				'flags' => [ 'quiet' ]
			],
			'',
			"<span id='mw-input-testfield' class='mw-htmlform-submit my-button oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-flaggedElement-quiet oo-ui-buttonInputWidget'><button type='button' tabindex='0' name='testfield' value='' class='oo-ui-inputWidget-input oo-ui-buttonElement-button'><span class='oo-ui-iconElement-icon oo-ui-iconElement-noIcon'></span><span class='oo-ui-labelElement-label'>Click me</span><span class='oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator'></span></button></span>"

		];

		yield 'Disabled button' => [
			[
				'buttonlabel' => 'Click me',
				'disabled' => true
			],
			'',
			"<span id='mw-input-testfield' aria-disabled='true' class='mw-htmlform-submit  oo-ui-widget oo-ui-widget-disabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-buttonInputWidget'><button type='button' tabindex='-1' aria-disabled='true' name='testfield' disabled='disabled' value='' class='oo-ui-inputWidget-input oo-ui-buttonElement-button'><span class='oo-ui-iconElement-icon oo-ui-iconElement-noIcon'></span><span class='oo-ui-labelElement-label'>Click me</span><span class='oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator'></span></button></span>"
		];
	}
}
