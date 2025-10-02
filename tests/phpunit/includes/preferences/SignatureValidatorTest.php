<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Preferences\SignatureValidator;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * @group Preferences
 * @group Database
 */
class SignatureValidatorTest extends MediaWikiIntegrationTestCase {

	/** @var SignatureValidator */
	private $validator;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( MainConfigNames::ParsoidSettings, [
			'linting' => true
		] );
		// For testing SignatureAllowedLintErrors in ::testValidateSignature
		$this->overrideConfigValue( MainConfigNames::SignatureAllowedLintErrors, [
			// No allowed lint errors in default set up
		] );
		// For testing hidden category support in ::testValidateSignature
		$this->overrideConfigValue( 'LinterCategories', [
			// No hidden categories in default set up
		] );
		$extReg = $this->createMock( ExtensionRegistry::class );
		$extReg->method( 'isLoaded' )->willReturnCallback( static function ( string $which ) {
			return $which == 'Linter';
		} );
		$this->setService( 'ExtensionRegistry', $extReg );
		$this->validator = $this->getSignatureValidator();
	}

	/**
	 * Get a basic SignatureValidator for testing with.
	 * @return SignatureValidator
	 */
	protected function getSignatureValidator() {
		$services = $this->getServiceContainer();
		$lang = $services->getLanguageFactory()->getLanguage( 'en' );
		$user = $services->getUserFactory()->newFromName( 'SignatureValidatorTest' );
		$validator = $services->getSignatureValidatorFactory()->newSignatureValidator(
			$user,
			null,
			ParserOptions::newFromUserAndLang( $user, $lang )
		);

		return TestingAccessWrapper::newFromObject( $validator );
	}

	/**
	 * @covers \MediaWiki\Preferences\SignatureValidator::applyPreSaveTransform()
	 * @dataProvider provideApplyPreSaveTransform
	 */
	public function testApplyPreSaveTransform( $signature, $expected ) {
		$pstSig = $this->validator->applyPreSaveTransform( $signature );
		$this->assertSame( $expected, $pstSig );
	}

	public static function provideApplyPreSaveTransform() {
		return [
			'Pipe trick' =>
				[ '[[test|]]', '[[test|test]]' ],
			'One level substitution' =>
				[ '{{subst:uc:whatever}}', 'WHATEVER' ],
			'Hidden nested substitution' =>
				[ '{{subst:uc:{}}{{subst:uc:{subst:uc:}}}{{subst:uc:}}}', false ],
			'Hidden nested signature' =>
				[ '{{subst:uc:~~}}{{subst:uc:~~}}', false ],
		];
	}

	/**
	 * @covers \MediaWiki\Preferences\SignatureValidator::checkUserLinks()
	 * @dataProvider provideCheckUserLinks
	 */
	public function testCheckUserLinks( $signature, $expected ) {
		$isValid = $this->validator->checkUserLinks( $signature );
		$this->assertSame( $expected, $isValid );
	}

	public static function provideCheckUserLinks() {
		return [
			'Perfect' =>
				[ '[[User:SignatureValidatorTest|Signature]] ([[User talk:SignatureValidatorTest|talk]])', true ],
			'User link' =>
				[ '[[User:SignatureValidatorTest|Signature]]', true ],
			'User talk link' =>
				[ '[[User talk:SignatureValidatorTest]]', true ],
			'Contributions link' =>
				[ '[[Special:Contributions/SignatureValidatorTest]]', true ],
			'Silly formatting permitted' =>
				[ '[[_uSeR :_signatureValidatorTest_]]', true ],
			'Contributions of wrong user' =>
				[ '[[Special:Contributions/SignatureValidatorTestNot]]', false ],
			'Link to subpage only' =>
				[ '[[User:SignatureValidatorTest/blah|Signature]]', false ],
		];
	}

	/**
	 * @covers \MediaWiki\Preferences\SignatureValidator::checkLintErrors()
	 * @dataProvider provideCheckLintErrors
	 */
	public function testCheckLintErrors( $signature, $expected ) {
		$errors = $this->validator->checkLintErrors( $signature );
		$this->assertSame( $expected, $errors );
	}

	public static function provideCheckLintErrors() {
			yield 'Perfect' => [ '<strong>Foo</strong>', [] ];
			yield 'Unclosed tag' => [
				'<strong>Foo',
				[
					[
						'type' => 'missing-end-tag',
						'dsr' => [ 0, 11, 8, 0 ],
						'templateInfo' => null,
						'params' => [
							'name' => 'strong',
							'inTable' => false,
						]
					]
				]
			];
	}

	/**
	 * @covers \MediaWiki\Preferences\SignatureValidator::validateSignature()
	 * @dataProvider provideValidateSignature
	 */
	public function testValidateSignature( string $signature, $expected ) {
		$result = $this->validator->validateSignature( $signature );
		// All special cases should report errors here.
		$this->assertSame( (bool)$expected, $result );
	}

	/**
	 * @covers \MediaWiki\Preferences\SignatureValidator::validateSignature()
	 * @dataProvider provideValidateSignature
	 */
	public function testValidateSignatureAllowed( string $signature, $expected ) {
		$this->overrideConfigValue( MainConfigNames::SignatureAllowedLintErrors, [
			'obsolete-tag'
		] );
		$this->validator = $this->getSignatureValidator();
		$result = $this->validator->validateSignature( $signature );
		if ( $expected === 'allowed' ) {
			$this->assertFalse( $result );
		} else {
			$this->assertSame( $expected, $result );
		}
	}

	/**
	 * Regression test for T381982
	 * @covers \MediaWiki\Preferences\SignatureValidator::validateSignature()
	 */
	public function testValidateWithSpecialPage() {
		$titleFactory = $this->createNoOpMock( TitleFactory::class, [ 'newMainPage' ] );
		$titleFactory->method( 'newMainPage' )->willReturn(
			Title::makeTitle( NS_SPECIAL, 'MainPage' )
		);

		$this->setService( 'TitleFactory', $titleFactory );

		$this->validator = $this->getSignatureValidator();

		$signature = '[[User:SignatureValidatorTest|Signature]] ([[User talk:SignatureValidatorTest|talk]])';
		$result = $this->validator->validateSignature( $signature );

		// This is a dummy, we are mainly checking that nothing epxlodes
		$this->assertFalse( $result );
	}

	public static function provideValidateSignature() {
		yield 'Perfect' => [
			'[[User:SignatureValidatorTest|Signature]] ([[User talk:SignatureValidatorTest|talk]])',
			// no complaints from lint
			false
		];
		yield 'Missing end tag' => [
			'<span>[[User:SignatureValidatorTest|Signature]] ([[User talk:SignatureValidatorTest|talk]])',
			// missing-end-tag is never allowed
			true
		];
		yield 'Obsolete tag' => [
			'<font color="red">RED</font> [[User:SignatureValidatorTest|Signature]] ([[User talk:SignatureValidatorTest|talk]])',
			// This is allowed by SignatureAllowedLintErrors
			'allowed'
		];
	}

	/**
	 * @covers \MediaWiki\Preferences\SignatureValidator::checkLineBreaks()
	 * @dataProvider provideCheckLineBreaks
	 */
	public function testCheckLineBreaks( $signature, $expected ) {
		$isValid = $this->validator->checkLineBreaks( $signature );
		$this->assertSame( $expected, $isValid );
	}

	public static function provideCheckLineBreaks() {
		return [
			'Perfect' =>
				[ '[[User:SignatureValidatorTest|Signature]] ([[User talk:SignatureValidatorTest|talk]])', true ],
			'Line break' =>
				[ "[[User:SignatureValidatorTest|Signature]] ([[User talk:SignatureValidatorTest|talk\n]])", false ],
		];
	}

}
