<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Preferences\SignatureValidator;
use Wikimedia\TestingAccessWrapper;

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * @group Preferences
 */
class SignatureValidatorTest extends MediaWikiIntegrationTestCase {

	private $validator;

	public function setUp() : void {
		parent::setUp();
		$this->validator = $this->getSignatureValidator();
	}

	/**
	 * Get a basic SignatureValidator for testing with.
	 */
	protected function getSignatureValidator() {
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' );
		$user = User::newFromName( 'SignatureValidatorTest' );

		$validator = new SignatureValidator(
			$user,
			null,
			ParserOptions::newFromUserAndLang( $user, $lang )
		);

		return TestingAccessWrapper::newFromObject( $validator );
	}

	/**
	 * @covers MediaWiki\Preferences\SignatureValidator::applyPreSaveTransform()
	 * @dataProvider provideApplyPreSaveTransform
	 */
	public function testApplyPreSaveTransform( $signature, $expected ) {
		$pstSig = $this->validator->applyPreSaveTransform( $signature );
		$this->assertSame( $expected, $pstSig );
	}

	public function provideApplyPreSaveTransform() {
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
	 * @covers MediaWiki\Preferences\SignatureValidator::checkUserLinks()
	 * @dataProvider provideCheckUserLinks
	 */
	public function testCheckUserLinks( $signature, $expected ) {
		$isValid = $this->validator->checkUserLinks( $signature );
		$this->assertSame( $expected, $isValid );
	}

	public function provideCheckUserLinks() {
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

}
