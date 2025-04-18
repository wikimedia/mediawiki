<?php

namespace MediaWiki\Tests\Integration\HTMLForm;

use LogicException;
use MediaWiki\Context\RequestContext;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\RawMessage;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\CsrfTokenSet;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\HTMLForm\HTMLForm
 *
 * @license GPL-2.0-or-later
 * @author Gergő Tisza
 */
class HTMLFormTest extends MediaWikiIntegrationTestCase {

	private function newInstance() {
		$context = new RequestContext();
		$out = new OutputPage( $context );
		$out->setTitle( Title::newMainPage() );
		$context->setOutput( $out );
		$form = new HTMLForm( [], $context );
		$form->setTitle( Title::makeTitle( NS_MAIN, 'Foo' ) );
		return $form;
	}

	public function testGetHTML_empty() {
		$form = $this->newInstance();
		$form->prepareForm();
		$html = $form->getHTML( false );
		$this->assertStringStartsWith( '<form ', $html );
	}

	public function testGetHTML_noPrepare() {
		$form = $this->newInstance();
		$this->expectException( LogicException::class );
		$form->getHTML( false );
	}

	public function testAutocompleteDefaultsToNull() {
		$form = $this->newInstance();
		$this->assertStringNotContainsString( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToNull() {
		$form = $this->newInstance();
		$form->setAutocomplete( null );
		$this->assertStringNotContainsString( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToFalse() {
		$form = $this->newInstance();
		// Previously false was used instead of null to indicate the attribute should not be set
		$form->setAutocomplete( false );
		$this->assertStringNotContainsString( 'autocomplete', $form->wrapForm( '' ) );
	}

	public function testAutocompleteWhenSetToOff() {
		$form = $this->newInstance();
		$form->setAutocomplete( 'off' );
		$this->assertStringContainsString( ' autocomplete="off"', $form->wrapForm( '' ) );
	}

	public function testGetPreHtml() {
		$preHtml = 'TEST';
		$form = $this->newInstance();
		$form->setPreHtml( $preHtml );
		$this->assertSame( $preHtml, $form->getPreHtml() );
		$preHtml = 'TEST2';
		$form->setPreHtml( $preHtml );
		$this->assertSame( $preHtml, $form->getPreHtml() );
		$preHtml = 'TEST';
		$form->addPreHtml( $preHtml );
		$this->assertSame( $preHtml . '2' . $preHtml, $form->getPreHtml() );
	}

	public function testGetPostHtml() {
		$postHtml = 'TESTED';
		$form = $this->newInstance();
		$form->setPostHtml( $postHtml );
		$this->assertSame( $postHtml, $form->getPostHtml() );
		$postHtml = 'TESTED2';
		$form->setPostHtml( $postHtml );
		$this->assertSame( $postHtml, $form->getPostHtml() );
		$postHtml = 'TESTED';
		$form->addPostHtml( $postHtml );
		$this->assertSame( $postHtml . '2' . $postHtml, $form->getPostHtml() );
	}

	public function testCollapsible() {
		$form = $this->newInstance();
		$form->prepareForm()->getHTML( '' );
		$this->assertContains( 'mediawiki.htmlform', $form->getOutput()->getModules() );
		$this->assertNotContains( 'jquery.makeCollapsible', $form->getOutput()->getModules() );

		$form = $this->newInstance();
		$form->setCollapsibleOptions( null );
		$form->prepareForm()->getHTML( '' );
		$this->assertContains( 'jquery.makeCollapsible', $form->getOutput()->getModules() );

		$form = $this->newInstance();
		$form->setCollapsibleOptions( false );
		$form->prepareForm()->getHTML( '' );
		$this->assertContains( 'jquery.makeCollapsible', $form->getOutput()->getModules() );

		$form = $this->newInstance();
		$form->setCollapsibleOptions( true );
		$form->prepareForm()->getHTML( '' );
		$this->assertContains( 'jquery.makeCollapsible', $form->getOutput()->getModules() );
	}

	public function testGetErrorsOrWarningsWithRawParams() {
		$form = $this->newInstance();
		$msg = new RawMessage( 'message with $1' );
		$msg->rawParams( '<a href="raw">params</a>' );
		$status = Status::newFatal( $msg );

		$result = $form->getErrorsOrWarnings( $status, 'error' );

		$this->assertStringContainsString( 'message with <a href="raw">params</a>', $result );
	}

	/**
	 * @dataProvider provideCsrf
	 * @param string|null $formTokenSalt Salt to pass to HTMLForm::setTokenSalt()
	 * @param array $requestData HTTP request data
	 * @param array|null $tokens User's CSRF tokens in a salt => value format, or null for anon
	 * @param bool $shouldBeAuthorized
	 */
	public function testCsrf(
		?string $formTokenSalt,
		array $requestData,
		?array $tokens,
		bool $shouldBeAuthorized
	) {
		$user = $this->createNoOpMock( User::class, [ 'isRegistered' ] );
		$user->method( 'isRegistered' )->willReturn( $tokens !== null );

		$request = new FauxRequest( $requestData, true );

		$csrfTokenSet = $this->createMock( CsrfTokenSet::class );
		$csrfTokenSet->method( 'matchTokenField' )
			->with( CsrfTokenSet::DEFAULT_FIELD_NAME, $formTokenSalt )
			->willReturnCallback(
				static function ( $fieldName, $salt ) use ( $request, $tokens ): bool {
					return $tokens &&
						isset( $tokens[$salt] ) &&
						$tokens[$salt] === $request->getRawVal( $fieldName );
				}
			);

		$context = $this->createConfiguredMock( RequestContext::class, [
			'getRequest' => new FauxRequest( $requestData, true ),
			'getUser' => $user,
			'getCsrfTokenSet' => $csrfTokenSet,
		] );
		$form = new HTMLForm( [], $context );
		if ( $formTokenSalt !== null ) {
			$form->setTokenSalt( $formTokenSalt );
		}
		$form->setSubmitCallback( static function () {
			return true;
		} );

		$this->assertSame( $shouldBeAuthorized, $form->tryAuthorizedSubmit() );
	}

	public static function provideCsrf() {
		return [
			// form token salt, request data, tokens, should be authorized?
			'Anon user, CSRF token ignored' => [ null, [], null, true ],
			'No CSRF token sent' => [ null, [], [ '' => '123' ], false ],
			'Wrong CSRF token sent' => [ null, [ 'wpEditToken' => 'xyz' ], [ '' => '123' ], false ],
			// this isn't possible but helps catch errors in the test itself
			'User has no CSRF token' => [ null, [ 'wpEditToken' => 'xyz' ], [], false ],
			'Correct CSRF token' => [ null, [ 'wpEditToken' => '123' ], [ '' => '123' ], true ],
			'Wrong CSRF token type' => [ 'delete', [ 'wpEditToken' => '123' ], [ '' => '123' ], false ],
			'Correct non-default CSRF token' => [ 'delete', [ 'wpEditToken' => 'xyz' ],
				[ '' => 123, 'delete' => 'xyz' ], true ],
		];
	}

}
