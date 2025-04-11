<?php

namespace MediaWiki\Tests\Integration\EditPage;

use MediaWiki\Context\RequestContext;
use MediaWiki\EditPage\EditPage;
use MediaWiki\Page\Article;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiLangTestCase;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Test class for {@link EditPage} that creates test users for each test as needed.
 *
 * This needs to be separated from {@link \EditPageTest} because creating new users
 * in each test causes temporary account auto-creation to fail if it was in that class.
 *
 * @covers \MediaWiki\EditPage\EditPage
 * @group Editing
 * @group Database
 */
class EditPageWithoutFixedTestUsersTest extends MediaWikiLangTestCase {
	use TempUserTestTrait;

	/**
	 * Gets an EditPage instance for the given $user, $title, and $request
	 *
	 * @param User $user
	 * @param Title $title
	 * @param WebRequest $request
	 * @return EditPage
	 */
	protected function getEditPageInstance( User $user, Title $title, WebRequest $request ): EditPage {
		$context = new RequestContext();
		$context->setRequest( $request );
		$context->setTitle( $title );
		$context->setUser( $user );
		$article = new Article( $title );
		$article->setContext( $context );
		return new EditPage( $article );
	}

	public function testMaybeActivateTempUserCreateDoesNothingWhenUserIsNamed() {
		$this->enableAutoCreateTempUser();

		// Get an EditPage instance which uses a named user
		$title = Title::newFromText( __METHOD__ );
		$user = $this->getTestUser()->getUser();
		$request = new FauxRequest();
		$request->getSession()->setUser( $user );
		$editPage = $this->getEditPageInstance( $user, $title, $request );

		// Assert that calling ::maybeActivateTempUserCreate does not affect the EditPage instance
		// if the user is named.
		$this->assertStatusGood( $editPage->maybeActivateTempUserCreate( true ) );
		$this->assertNull( $request->getSession()->get( 'TempUser:name' ) );
		$editPage = TestingAccessWrapper::newFromObject( $editPage );
		$this->assertTrue( $user->equals( $editPage->getUserForPreview() ) );
	}

	/**
	 * Allows tests to determine if the UserLogout hook is called by adding a mock implementation that just
	 * tells the caller of this method if the hook was called.
	 *
	 * @return bool Whether UserLogout hook has been called yet
	 */
	private function &mockUserLogoutHook(): bool {
		$hookCalled = false;
		$this->setTemporaryHook( 'UserLogout', static function () use ( &$hookCalled ) {
			$hookCalled = true;
		} );
		return $hookCalled;
	}

	public function testMaybeActivateTempUserCreateAcquiresNameWhenUserLoggedOut() {
		ConvertibleTimestamp::setFakeTime( '20250203040506' );
		$this->enableAutoCreateTempUser();

		// Get an EditPage instance which uses an IP as the current user
		$title = Title::newFromText( __METHOD__ );
		$user = $this->getServiceContainer()->getUserFactory()->newAnonymous( '1.2.3.4' );
		$request = new FauxRequest();
		$request->getSession()->setUser( $user );
		$editPage = $this->getEditPageInstance( $user, $title, $request );

		// Assert that calling ::maybeActivateTempUserCreate causes a stashed temporary account name
		// to be created.
		$hookCalled = &$this->mockUserLogoutHook();
		$this->assertStatusGood( $editPage->maybeActivateTempUserCreate( true ) );
		$this->assertSame( '~2025-1', $request->getSession()->get( 'TempUser:name' ) );
		$editPage = TestingAccessWrapper::newFromObject( $editPage );
		$this->assertSame( '~2025-1', $editPage->getUserForPreview()->getName() );
		$this->assertFalse( $hookCalled );
	}

	public function testMaybeActivateTempUserCreateWhenUserAlreadyUsingTemporaryAccount() {
		// Get a temporary account which we will make expired by moving forward fake time by enough time
		ConvertibleTimestamp::setFakeTime( '20250203040506' );
		$this->enableAutoCreateTempUser();
		$tempUser = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		$this->assertSame( '~2025-1', $tempUser->getName() );

		// Get an EditPage instance which uses the temporary account as the user
		$title = Title::newFromText( __METHOD__ );
		$request = new FauxRequest();
		$request->getSession()->setUser( $tempUser );
		$editPage = $this->getEditPageInstance( $tempUser, $title, $request );

		// Assert that calling ::maybeActivateTempUserCreate does not cause any changes to the user
		$hookCalled = &$this->mockUserLogoutHook();
		$this->assertStatusGood( $editPage->maybeActivateTempUserCreate( true ) );
		$this->assertNull( $request->getSession()->get( 'TempUser:name' ) );
		$editPage = TestingAccessWrapper::newFromObject( $editPage );
		$this->assertSame( '~2025-1', $editPage->getUserForPreview()->getName() );
		$this->assertFalse( $hookCalled );
	}

	public function testMaybeActivateTempUserCreateCreatesNewTemporaryAccountWhenCurrentOneExpired() {
		// Get a temporary account which we will make expired by moving forward fake time by enough time
		ConvertibleTimestamp::setFakeTime( '20250203040506' );
		$this->enableAutoCreateTempUser();
		$tempUser = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		$this->assertSame( '~2025-1', $tempUser->getName() );
		ConvertibleTimestamp::setFakeTime( '20251003040506' );

		// Get an EditPage instance which uses the expired temporary account as the user
		$title = Title::newFromText( __METHOD__ );
		$request = new FauxRequest();
		$request->getSession()->setUser( $tempUser );
		$editPage = $this->getEditPageInstance( $tempUser, $title, $request );

		// Assert that calling ::maybeActivateTempUserCreate causes the session to use a new temporary account
		// stashed name and the current user to be logged out.
		$hookCalled = &$this->mockUserLogoutHook();
		$this->assertStatusGood( $editPage->maybeActivateTempUserCreate( true ) );
		$this->assertSame( '~2025-2', $request->getSession()->get( 'TempUser:name' ) );
		$editPage = TestingAccessWrapper::newFromObject( $editPage );
		$this->assertSame( '~2025-2', $editPage->getUserForPreview()->getName() );
		$this->assertTrue( $hookCalled );
	}
}
