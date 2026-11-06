<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLSelectNamespaceWithButton;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLSelectNamespaceWithButton
 * @covers \MediaWiki\HTMLForm\Field\HTMLFormFieldWithButton
 */
class HTMLSelectNamespaceWithButtonTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLSelectNamespaceWithButton::class;

	public static function provideInputCodex(): iterable {
		yield 'default button' => [
			[ 'include' => [ NS_MAIN ], 'buttondefault' => 'Go' ],
			'0',
			false,
			<<<HTML
				<select name="testfield" id="mw-input-testfield" class="cdx-select">
					<option value="all">all</option>
					<option value="0" selected="">(Main)</option>
				</select>
				\u{00A0}
				<button class="mw-htmlform-submit cdx-button cdx-button--action-progressive" type="submit" value="Go">Go</button>
			HTML
		];

		yield 'button with attributes' => [
			[
				'include' => [ NS_MAIN ],
				'buttondefault' => 'Go <now>',
				'buttonclass' => 'my-button',
				'buttonid' => 'my-button-id',
				'buttonname' => 'gobutton',
				'buttontype' => 'button',
				'buttonflags' => [ 'primary', 'progressive' ],
				'disabled' => true,
			],
			'all',
			false,
			<<<HTML
				<select name="testfield" id="mw-input-testfield" disabled="" class="cdx-select">
					<option value="all" selected="">all</option>
					<option value="0">(Main)</option>
				</select>
				\u{00A0}
				<button class="mw-htmlform-submit cdx-button my-button cdx-button--weight-primary cdx-button--action-progressive" id="my-button-id" type="button" name="gobutton" value="Go &lt;now&gt;" disabled="">Go &lt;now&gt;</button>
			HTML
		];
	}
}
