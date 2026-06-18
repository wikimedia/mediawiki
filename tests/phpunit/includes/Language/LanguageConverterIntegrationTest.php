<?php

namespace MediaWiki\Tests\Language;

use MediaWiki\Title\TitleValue;
use MediaWikiIntegrationTestCase;

/**
 * @group Language
 * @group Database
 * @covers \MediaWiki\Language\LanguageConverter
 */
class LanguageConverterIntegrationTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	public function testHasVariant() {
		// See LanguageSrTest::testHasVariant() for additional tests
		$converterEn = $this->getLanguageConverter( 'en' );
		$this->assertTrue( $converterEn->hasVariant( 'en' ), 'base is always a variant' );
		$this->assertFalse( $converterEn->hasVariant( 'en-bogus' ), 'bogus en variant' );

		$converterBogus = $this->getLanguageConverter( 'bogus' );
		$this->assertTrue( $converterBogus->hasVariant( 'bogus' ), 'base is always a variant' );
	}

	/**
	 * convertSplitTitle() must honour gender-distinct namespace aliases in each
	 * variant, so that the title heading matches the gendered URL (T425402). sh is
	 * used as a concrete language with gender-distinct NS_USER/NS_USER_TALK aliases.
	 *
	 * @dataProvider provideConvertSplitTitleGender
	 */
	public function testConvertSplitTitleGender( string $gender, int $namespace, string $variant, string $expectedNs ) {
		$user = $this->getMutableTestUser()->getUser();
		$userOptionsManager = $this->getServiceContainer()->getUserOptionsManager();
		$userOptionsManager->setOption( $user, 'gender', $gender );
		$userOptionsManager->saveOptions( $user );

		[ $nsText ] = $this->getLanguageConverter( 'sh' )->convertSplitTitle(
			new TitleValue( $namespace, $user->getName() ),
			$variant
		);

		$this->assertSame( $expectedNs, $nsText );
	}

	public static function provideConvertSplitTitleGender() {
		return [
			'female user, Latin' => [ 'female', NS_USER, 'sh-latn', 'Korisnica' ],
			'female user, Cyrillic' => [ 'female', NS_USER, 'sh-cyrl', 'Корисница' ],
			'male user, Latin' => [ 'male', NS_USER, 'sh-latn', 'Korisnik' ],
			'male user, Cyrillic' => [ 'male', NS_USER, 'sh-cyrl', 'Корисник' ],
			'female user talk, Latin' => [ 'female', NS_USER_TALK, 'sh-latn', 'Razgovor s korisnicom' ],
			'female user talk, Cyrillic' => [ 'female', NS_USER_TALK, 'sh-cyrl', 'Разговор с корисницом' ],
		];
	}
}
