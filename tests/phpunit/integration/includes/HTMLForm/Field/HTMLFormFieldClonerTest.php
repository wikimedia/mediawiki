<?php
namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\HTMLForm\Field\HTMLFormFieldCloner;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;
use OOUI\Tag;
use ReflectionClass;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLFormFieldCloner
 */
class HTMLFormFieldClonerTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLFormFieldCloner::class;

	protected function setUp(): void {
		parent::setUp();

		// Reset unique ID counter for cloner-type fields and OOUI fields between tests.
		$ref = new ReflectionClass( HTMLFormFieldCloner::class );
		$ref->setStaticPropertyValue( 'counter', 0 );

		Tag::resetElementId();
	}

	public static function provideInputHtml() {
		yield 'without legend' => [
			[
				'format' => 'div',
				'fields' => [
					[
						'label' => 'Test',
						'type' => 'text',
					],
				]
			],
			[],
			'<ul id="mw-htmlform-cloner-list-mw-input-testfield" class="mw-htmlform-cloner-ul" data-template="&lt;div class=&quot;mw-htmlform-cloner-row&quot;&gt;&#10;&lt;div class=&quot;mw-htmlform-field-HTMLTextField&quot;&gt;&lt;label for=&quot;mw-input-testfield--HTMLFormFieldCloner1x--0&quot;&gt;Test&lt;/label&gt;&lt;div class=&quot;mw-input&quot;&gt;&lt;input id=&quot;mw-input-testfield--HTMLFormFieldCloner1x--0&quot; name=&quot;testfield[HTMLFormFieldCloner1x][0]&quot; size=&quot;45&quot;&gt;&#10;&lt;/div&gt;&lt;/div&gt;&lt;button class=&quot;mw-htmlform-submit mw-htmlform-cloner-delete-button mw-htmlform-primary mw-htmlform-progressive&quot; id=&quot;mw-input-testfield--HTMLFormFieldCloner1x--delete&quot; type=&quot;submit&quot; name=&quot;testfield[HTMLFormFieldCloner1x][delete]&quot; value=&quot;(htmlform-cloner-delete)&quot; formnovalidate=&quot;&quot;&gt;(htmlform-cloner-delete)&lt;/button&gt;&#10;&lt;/div&gt;" data-unique-id="HTMLFormFieldCloner1x"></ul><button class="mw-htmlform-submit mw-htmlform-cloner-create-button mw-htmlform-primary mw-htmlform-progressive" id="mw-input-testfield--create" type="submit" name="testfield[create]" value="(htmlform-cloner-create)" formnovalidate="">(htmlform-cloner-create)</button>'
		];

		yield 'with legend' => [
			[
				'format' => 'div',
				'row-legend' => 'some-legend-msg',
				'fields' => [
					[
						'label' => 'Test',
						'type' => 'text',
					],
				]
			],
			[],
			'<ul id="mw-htmlform-cloner-list-mw-input-testfield" class="mw-htmlform-cloner-ul" data-template="&lt;fieldset&gt;&lt;legend&gt;some-legend-msg&lt;/legend&gt;&lt;div class=&quot;mw-htmlform-cloner-row&quot;&gt;&#10;&lt;div class=&quot;mw-htmlform-field-HTMLTextField&quot;&gt;&lt;label for=&quot;mw-input-testfield--HTMLFormFieldCloner1x--0&quot;&gt;Test&lt;/label&gt;&lt;div class=&quot;mw-input&quot;&gt;&lt;input id=&quot;mw-input-testfield--HTMLFormFieldCloner1x--0&quot; name=&quot;testfield[HTMLFormFieldCloner1x][0]&quot; size=&quot;45&quot;&gt;&#10;&lt;/div&gt;&lt;/div&gt;&lt;button class=&quot;mw-htmlform-submit mw-htmlform-cloner-delete-button mw-htmlform-primary mw-htmlform-progressive&quot; id=&quot;mw-input-testfield--HTMLFormFieldCloner1x--delete&quot; type=&quot;submit&quot; name=&quot;testfield[HTMLFormFieldCloner1x][delete]&quot; value=&quot;(htmlform-cloner-delete)&quot; formnovalidate=&quot;&quot;&gt;(htmlform-cloner-delete)&lt;/button&gt;&#10;&lt;/div&gt;&lt;/fieldset&gt;" data-unique-id="HTMLFormFieldCloner1x"></ul><button class="mw-htmlform-submit mw-htmlform-cloner-create-button mw-htmlform-primary mw-htmlform-progressive" id="mw-input-testfield--create" type="submit" name="testfield[create]" value="(htmlform-cloner-create)" formnovalidate="">(htmlform-cloner-create)</button>'
		];
	}

	public static function provideInputOOUI() {
		yield 'without legend' => [
			[
				'fields' => [
					[
						'label' => 'Test',
						'type' => 'text',
					],
				]
			],
			[],
			"<ul id=\"mw-htmlform-cloner-list-mw-input-testfield\" class=\"mw-htmlform-cloner-ul\" data-template=\"&lt;div class=&quot;mw-htmlform-cloner-row&quot;&gt;&#10;&lt;div id=&#039;ooui-php-2&#039; class=&#039;mw-htmlform-field-HTMLTextField oo-ui-layout oo-ui-labelElement oo-ui-fieldLayout oo-ui-fieldLayout-align-top&#039; data-ooui=&#039;{&quot;_&quot;:&quot;mw.htmlform.FieldLayout&quot;,&quot;fieldWidget&quot;:{&quot;tag&quot;:&quot;mw-input-testfield--HTMLFormFieldCloner1x--0&quot;},&quot;align&quot;:&quot;top&quot;,&quot;helpInline&quot;:true,&quot;\$overlay&quot;:true,&quot;label&quot;:{&quot;html&quot;:&quot;Test&quot;},&quot;classes&quot;:[&quot;mw-htmlform-field-HTMLTextField&quot;]}&#039;&gt;&lt;div class=&#039;oo-ui-fieldLayout-body&#039;&gt;&lt;span class=&#039;oo-ui-fieldLayout-header&#039;&gt;&lt;label for=&#039;ooui-php-1&#039; class=&#039;oo-ui-labelElement-label&#039;&gt;Test&lt;/label&gt;&lt;/span&gt;&lt;div class=&#039;oo-ui-fieldLayout-field&#039;&gt;&lt;div id=&#039;mw-input-testfield--HTMLFormFieldCloner1x--0&#039; class=&#039;oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-textInputWidget oo-ui-textInputWidget-type-text oo-ui-textInputWidget-php&#039; data-ooui=&#039;{&quot;_&quot;:&quot;OO.ui.TextInputWidget&quot;,&quot;name&quot;:&quot;testfield[HTMLFormFieldCloner1x][0]&quot;,&quot;inputId&quot;:&quot;ooui-php-1&quot;,&quot;required&quot;:false}&#039;&gt;&lt;input type=&#039;text&#039; tabindex=&#039;0&#039; name=&#039;testfield[HTMLFormFieldCloner1x][0]&#039; value=&#039;&#039; id=&#039;ooui-php-1&#039; class=&#039;oo-ui-inputWidget-input&#039; /&gt;&lt;span class=&#039;oo-ui-iconElement-icon oo-ui-iconElement-noIcon&#039;&gt;&lt;/span&gt;&lt;span class=&#039;oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator&#039;&gt;&lt;/span&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;span id=&#039;mw-input-testfield--HTMLFormFieldCloner1x--delete&#039; class=&#039;mw-htmlform-submit mw-htmlform-cloner-delete-button oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-flaggedElement-primary oo-ui-flaggedElement-progressive oo-ui-buttonInputWidget&#039; data-ooui=&#039;{&quot;_&quot;:&quot;OO.ui.ButtonInputWidget&quot;,&quot;type&quot;:&quot;submit&quot;,&quot;name&quot;:&quot;testfield[HTMLFormFieldCloner1x][delete]&quot;,&quot;value&quot;:&quot;(htmlform-cloner-delete)&quot;,&quot;label&quot;:&quot;(htmlform-cloner-delete)&quot;,&quot;flags&quot;:[&quot;primary&quot;,&quot;progressive&quot;],&quot;classes&quot;:[&quot;mw-htmlform-submit&quot;,&quot;mw-htmlform-cloner-delete-button&quot;]}&#039;&gt;&lt;button type=&#039;submit&#039; tabindex=&#039;0&#039; name=&#039;testfield[HTMLFormFieldCloner1x][delete]&#039; value=&#039;(htmlform-cloner-delete)&#039; class=&#039;oo-ui-inputWidget-input oo-ui-buttonElement-button&#039;&gt;&lt;span class=&#039;oo-ui-iconElement-icon oo-ui-iconElement-noIcon&#039;&gt;&lt;/span&gt;&lt;span class=&#039;oo-ui-labelElement-label&#039;&gt;(htmlform-cloner-delete)&lt;/span&gt;&lt;span class=&#039;oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator&#039;&gt;&lt;/span&gt;&lt;/button&gt;&lt;/span&gt;&#10;&lt;/div&gt;\" data-unique-id=\"HTMLFormFieldCloner1x\"></ul><span id='mw-input-testfield--create' class='mw-htmlform-submit mw-htmlform-cloner-create-button oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-flaggedElement-primary oo-ui-flaggedElement-progressive oo-ui-buttonInputWidget' data-ooui='{\"_\":\"OO.ui.ButtonInputWidget\",\"type\":\"submit\",\"name\":\"testfield[create]\",\"value\":\"(htmlform-cloner-create)\",\"label\":\"(htmlform-cloner-create)\",\"flags\":[\"primary\",\"progressive\"],\"classes\":[\"mw-htmlform-submit\",\"mw-htmlform-cloner-create-button\"]}'><button type='submit' tabindex='0' name='testfield[create]' value='(htmlform-cloner-create)' class='oo-ui-inputWidget-input oo-ui-buttonElement-button'><span class='oo-ui-iconElement-icon oo-ui-iconElement-noIcon'></span><span class='oo-ui-labelElement-label'>(htmlform-cloner-create)</span><span class='oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator'></span></button></span>"
		];

		yield 'with legend' => [
			[
				'row-legend' => 'some-legend-msg',
				'fields' => [
					[
						'label' => 'Test',
						'type' => 'text',
					],
				]
			],
			[],
			"<ul id=\"mw-htmlform-cloner-list-mw-input-testfield\" class=\"mw-htmlform-cloner-ul\" data-template=\"&lt;fieldset&gt;&lt;legend&gt;some-legend-msg&lt;/legend&gt;&lt;div class=&quot;mw-htmlform-cloner-row&quot;&gt;&#10;&lt;div id=&#039;ooui-php-2&#039; class=&#039;mw-htmlform-field-HTMLTextField oo-ui-layout oo-ui-labelElement oo-ui-fieldLayout oo-ui-fieldLayout-align-top&#039; data-ooui=&#039;{&quot;_&quot;:&quot;mw.htmlform.FieldLayout&quot;,&quot;fieldWidget&quot;:{&quot;tag&quot;:&quot;mw-input-testfield--HTMLFormFieldCloner1x--0&quot;},&quot;align&quot;:&quot;top&quot;,&quot;helpInline&quot;:true,&quot;\$overlay&quot;:true,&quot;label&quot;:{&quot;html&quot;:&quot;Test&quot;},&quot;classes&quot;:[&quot;mw-htmlform-field-HTMLTextField&quot;]}&#039;&gt;&lt;div class=&#039;oo-ui-fieldLayout-body&#039;&gt;&lt;span class=&#039;oo-ui-fieldLayout-header&#039;&gt;&lt;label for=&#039;ooui-php-1&#039; class=&#039;oo-ui-labelElement-label&#039;&gt;Test&lt;/label&gt;&lt;/span&gt;&lt;div class=&#039;oo-ui-fieldLayout-field&#039;&gt;&lt;div id=&#039;mw-input-testfield--HTMLFormFieldCloner1x--0&#039; class=&#039;oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-textInputWidget oo-ui-textInputWidget-type-text oo-ui-textInputWidget-php&#039; data-ooui=&#039;{&quot;_&quot;:&quot;OO.ui.TextInputWidget&quot;,&quot;name&quot;:&quot;testfield[HTMLFormFieldCloner1x][0]&quot;,&quot;inputId&quot;:&quot;ooui-php-1&quot;,&quot;required&quot;:false}&#039;&gt;&lt;input type=&#039;text&#039; tabindex=&#039;0&#039; name=&#039;testfield[HTMLFormFieldCloner1x][0]&#039; value=&#039;&#039; id=&#039;ooui-php-1&#039; class=&#039;oo-ui-inputWidget-input&#039; /&gt;&lt;span class=&#039;oo-ui-iconElement-icon oo-ui-iconElement-noIcon&#039;&gt;&lt;/span&gt;&lt;span class=&#039;oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator&#039;&gt;&lt;/span&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;/div&gt;&lt;span id=&#039;mw-input-testfield--HTMLFormFieldCloner1x--delete&#039; class=&#039;mw-htmlform-submit mw-htmlform-cloner-delete-button oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-flaggedElement-primary oo-ui-flaggedElement-progressive oo-ui-buttonInputWidget&#039; data-ooui=&#039;{&quot;_&quot;:&quot;OO.ui.ButtonInputWidget&quot;,&quot;type&quot;:&quot;submit&quot;,&quot;name&quot;:&quot;testfield[HTMLFormFieldCloner1x][delete]&quot;,&quot;value&quot;:&quot;(htmlform-cloner-delete)&quot;,&quot;label&quot;:&quot;(htmlform-cloner-delete)&quot;,&quot;flags&quot;:[&quot;primary&quot;,&quot;progressive&quot;],&quot;classes&quot;:[&quot;mw-htmlform-submit&quot;,&quot;mw-htmlform-cloner-delete-button&quot;]}&#039;&gt;&lt;button type=&#039;submit&#039; tabindex=&#039;0&#039; name=&#039;testfield[HTMLFormFieldCloner1x][delete]&#039; value=&#039;(htmlform-cloner-delete)&#039; class=&#039;oo-ui-inputWidget-input oo-ui-buttonElement-button&#039;&gt;&lt;span class=&#039;oo-ui-iconElement-icon oo-ui-iconElement-noIcon&#039;&gt;&lt;/span&gt;&lt;span class=&#039;oo-ui-labelElement-label&#039;&gt;(htmlform-cloner-delete)&lt;/span&gt;&lt;span class=&#039;oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator&#039;&gt;&lt;/span&gt;&lt;/button&gt;&lt;/span&gt;&#10;&lt;/div&gt;&lt;/fieldset&gt;\" data-unique-id=\"HTMLFormFieldCloner1x\"></ul><span id='mw-input-testfield--create' class='mw-htmlform-submit mw-htmlform-cloner-create-button oo-ui-widget oo-ui-widget-enabled oo-ui-inputWidget oo-ui-buttonElement oo-ui-buttonElement-framed oo-ui-labelElement oo-ui-flaggedElement-primary oo-ui-flaggedElement-progressive oo-ui-buttonInputWidget' data-ooui='{\"_\":\"OO.ui.ButtonInputWidget\",\"type\":\"submit\",\"name\":\"testfield[create]\",\"value\":\"(htmlform-cloner-create)\",\"label\":\"(htmlform-cloner-create)\",\"flags\":[\"primary\",\"progressive\"],\"classes\":[\"mw-htmlform-submit\",\"mw-htmlform-cloner-create-button\"]}'><button type='submit' tabindex='0' name='testfield[create]' value='(htmlform-cloner-create)' class='oo-ui-inputWidget-input oo-ui-buttonElement-button'><span class='oo-ui-iconElement-icon oo-ui-iconElement-noIcon'></span><span class='oo-ui-labelElement-label'>(htmlform-cloner-create)</span><span class='oo-ui-indicatorElement-indicator oo-ui-indicatorElement-noIndicator'></span></button></span>"
		];
	}
}
