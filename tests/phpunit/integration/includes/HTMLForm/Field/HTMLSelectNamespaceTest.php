<?php
namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLSelectNamespace;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLSelectNamespace
 */
class HTMLSelectNamespaceTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLSelectNamespace::class;

	/**
	 * Until T277470 is fixed, because each time this is run it might be on a box that has
	 * different extensions/config, we just have to grab the data structure ourselves. Ick.
	 */
	private static function makeNamespaceOptionsList( Language $language ): string {
		$namespaces = $language->getNamespaces();
		$expectedOptions = '';
		foreach ( $namespaces as $id => $label ) {
			if ( $id < 0 ) {
				// Don't list special namespaces
				continue;
			}
			if ( $id === 0 ) {
				$repLabel = wfMessage( 'blanknamespace' )->inLanguage( $language )->text();
			} else {
				$repLabel = str_replace( '_', ' ', $label );
			}
			$expectedOptions .= "<option value=\"$id\">$repLabel</option>";
		}
		return $expectedOptions;
	}

	public static function provideInputHtml() {
		$expectedOptions = static::makeNamespaceOptionsList(
			MediaWikiServices::getInstance()->getContentLanguage()
		);

		yield 'Basic list' => [
			[],
			'',
			"<select class=\"namespaceselector\" id=\"mw-input-testfield\" name=\"testfield\">\n<option value=\"all\">all</option>\n" . $expectedOptions . "\n</select>"
		];

		yield 'Basic list, explicitly in userlang' => [
			[
				'in-user-lang' => false
			],
			'',
			"<select class=\"namespaceselector\" id=\"mw-input-testfield\" name=\"testfield\">\n<option value=\"all\">all</option>\n" . $expectedOptions . "\n</select>"
		];

		yield 'Basic list, blank all' => [
			[
				'all' => '',
			],
			'',
			"<select class=\"namespaceselector\" id=\"mw-input-testfield\" name=\"testfield\">\n<option value=\"\" selected=\"\">all</option>\n" . $expectedOptions . "\n</select>"
		];

		yield 'Basic list, empty include (only show all)' => [
			[
				'include' => [],
			],
			'',
			"<select class=\"namespaceselector\" id=\"mw-input-testfield\" name=\"testfield\"><option value=\"all\">all</option></select>"
		];

		yield 'Basic list, use include (show all and main only)' => [
			[
				'include' => [ NS_MAIN ],
			],
			'',
			"<select class=\"namespaceselector\" id=\"mw-input-testfield\" name=\"testfield\"><option value=\"all\">all</option><option value=\"0\">(Main)</option></select>"
		];
	}

	public static function provideInputCodex() {
		$expectedOptions = static::makeNamespaceOptionsList(
			MediaWikiServices::getInstance()->getContentLanguage()
		);

		yield 'Basic list' => [
			[],
			'',
			false,
			"<select name=\"testfield\" id=\"mw-input-testfield\" class=\"cdx-select\"><option value=\"all\">all</option>" . $expectedOptions . "</select>"
		];

		yield 'Basic list, explicitly in userlang' => [
			[
				'in-user-lang' => false
			],
			'',
			false,
			"<select name=\"testfield\" id=\"mw-input-testfield\" class=\"cdx-select\"><option value=\"all\">all</option>" . $expectedOptions . "</select>"
		];

		yield 'Basic list, blank all' => [
			[
				'all' => '',
			],
			'',
			false,
			"<select name=\"testfield\" id=\"mw-input-testfield\" class=\"cdx-select\"><option value=\"\" selected=\"\">all</option>" . $expectedOptions . "</select>"
		];
	}

	public static function provideInputOOUI() {
		$expectedOptions = str_replace(
			'"', "'",
			static::makeNamespaceOptionsList( MediaWikiServices::getInstance()->getContentLanguage() )
		);

		yield 'Basic list' => [
			[],
			'',
			"<div id='mw-input-testfield' class='oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-dropdownInputWidget oo-ui-dropdownInputWidget-php mw-widget-namespaceInputWidget'><select tabindex='0' name='testfield' class='oo-ui-inputWidget-input'><option value='all' selected='selected'>all</option>" . $expectedOptions . "</select><span class='oo-ui-widget oo-ui-widget-enabled oo-ui-indicatorElement-indicator oo-ui-indicator-down oo-ui-indicatorElement oo-ui-labelElement-invisible oo-ui-indicatorWidget'></span></div>"
		];

		yield 'Basic list, explicitly in userlang' => [
			[
				'in-user-lang' => false
			],
			'',
			"<div id='mw-input-testfield' class='oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-dropdownInputWidget oo-ui-dropdownInputWidget-php mw-widget-namespaceInputWidget'><select tabindex='0' name='testfield' class='oo-ui-inputWidget-input'><option value='all' selected='selected'>all</option>" . $expectedOptions . "</select><span class='oo-ui-widget oo-ui-widget-enabled oo-ui-indicatorElement-indicator oo-ui-indicator-down oo-ui-indicatorElement oo-ui-labelElement-invisible oo-ui-indicatorWidget'></span></div>"

		];

		yield 'Basic list, blank all' => [
			[
				'all' => '',
			],
			'',
			"<div id='mw-input-testfield' class='oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-dropdownInputWidget oo-ui-dropdownInputWidget-php mw-widget-namespaceInputWidget'><select tabindex='0' name='testfield' class='oo-ui-inputWidget-input'><option value='' selected='selected'>all</option>" . $expectedOptions . "</select><span class='oo-ui-widget oo-ui-widget-enabled oo-ui-indicatorElement-indicator oo-ui-indicator-down oo-ui-indicatorElement oo-ui-labelElement-invisible oo-ui-indicatorWidget'></span></div>"

		];

		yield 'Basic list, empty include (only show all)' => [
			[
				'include' => [],
			],
			'',
			"<div id='mw-input-testfield' class='oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-dropdownInputWidget oo-ui-dropdownInputWidget-php mw-widget-namespaceInputWidget'><select tabindex='0' name='testfield' class='oo-ui-inputWidget-input'><option value='all' selected='selected'>all</option></select><span class='oo-ui-widget oo-ui-widget-enabled oo-ui-indicatorElement-indicator oo-ui-indicator-down oo-ui-indicatorElement oo-ui-labelElement-invisible oo-ui-indicatorWidget'></span></div>"
		];

		yield 'Basic list, use include (show all and main only)' => [
			[
				'include' => [ NS_MAIN ],
			],
			'',
			"<div id='mw-input-testfield' class='oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-dropdownInputWidget oo-ui-dropdownInputWidget-php mw-widget-namespaceInputWidget'><select tabindex='0' name='testfield' class='oo-ui-inputWidget-input'><option value='all' selected='selected'>all</option><option value='0'>(Main)</option></select><span class='oo-ui-widget oo-ui-widget-enabled oo-ui-indicatorElement-indicator oo-ui-indicator-down oo-ui-indicatorElement oo-ui-labelElement-invisible oo-ui-indicatorWidget'></span></div>"
		];
	}
}
