<?php

namespace MediaWiki\Tests\Integration\HTMLForm\Field;

use MediaWiki\Config\HashConfig;
use MediaWiki\HTMLForm\Field\HTMLSelectLanguageField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Tests\Integration\HTMLForm\HTMLFormFieldTestCase;

/**
 * @covers \MediaWiki\HTMLForm\Field\HTMLSelectLanguageField
 */
class HTMLSelectLanguageFieldTest extends HTMLFormFieldTestCase {
	/** @inheritDoc */
	protected $className = HTMLSelectLanguageField::class;

	private const LANGUAGES = [ 'en' => 'English', 'de' => 'Deutsch' ];

	/** @inheritDoc */
	public function constructField( array $params ): HTMLFormField {
		// The field reads the site language and adds a module, so the form mock needs more
		$form = $this->createMock( HTMLForm::class );
		$form->method( 'getLanguage' )
			->willReturn( $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'qqx' ) );
		$form->method( 'msg' )
			->willReturnCallback( static fn ( ...$args ) => new RawMessage( ...$args ) );
		$form->method( 'getConfig' )
			->willReturn( new HashConfig( [ MainConfigNames::LanguageCode => 'en' ] ) );
		$form->method( 'getOutput' )
			->willReturn( $this->createMock( OutputPage::class ) );

		return new HTMLSelectLanguageField( $params + [
			'parent' => $form,
			'name' => 'testfield'
		] );
	}

	public static function provideInputCodex(): iterable {
		yield 'single value' => [
			[ 'languages' => self::LANGUAGES, 'useCodex' => true ],
			'en',
			false,
			<<<HTML
				<select class="mw-widgets-languageSelectWidget mw-widgets-languageSelectWidget-select cdx-text-input__input" name="testfield" id="mw-input-testfield" data-mw-languages="{&quot;de&quot;:&quot;Deutsch&quot;,&quot;en&quot;:&quot;English&quot;}">
					<option value="de">de · Deutsch</option>
					<option value="en" selected="">en · English</option>
				</select>
			HTML
		];

		yield 'multiple values' => [
			[ 'languages' => self::LANGUAGES, 'useCodex' => true, 'multiple' => true ],
			'de, en',
			false,
			<<<HTML
				<select class="mw-widgets-languageSelectWidget mw-widgets-languageSelectWidget-select cdx-text-input__input" name="testfield" id="mw-input-testfield" multiple="" data-mw-languages="{&quot;de&quot;:&quot;Deutsch&quot;,&quot;en&quot;:&quot;English&quot;}">
					<option value="de" selected="">de · Deutsch</option>
					<option value="en" selected="">en · English</option>
				</select>
			HTML
		];

		yield 'empty value uses the site language, disabled with attributes' => [
			[
				'languages' => self::LANGUAGES,
				'useCodex' => true,
				'cssclass' => 'my-field',
				'disabled' => true,
				'required' => true,
				'placeholder' => 'Pick one',
			],
			'',
			true,
			<<<HTML
				<select class="mw-widgets-languageSelectWidget mw-widgets-languageSelectWidget-select my-field cdx-text-input__input" name="testfield" id="mw-input-testfield" data-mw-placeholder="Pick one" disabled="" required="" data-mw-languages="{&quot;de&quot;:&quot;Deutsch&quot;,&quot;en&quot;:&quot;English&quot;}">
					<option value="de">de · Deutsch</option>
					<option value="en" selected="">en · English</option>
				</select>
			HTML
		];
	}
}
