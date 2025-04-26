<?php

namespace MediaWiki\Tests\Maintenance;

use EditCLI;
use MediaWiki\Context\RequestContext;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Page\WikiPage;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Mock for the input/output of EditCLI
 *
 * EditCLI internally tries to access stdin and stdout. We mock those aspects
 * for testing.
 */
class SemiMockedEditCLI extends EditCLI {

	/**
	 * @var string|null Text to pass as stdin
	 */
	private ?string $mockStdinText = null;

	/**
	 * Data for the fake stdin
	 *
	 * @param string $stdin The string to be used instead of stdin
	 */
	public function mockStdin( $stdin ) {
		$this->mockStdinText = $stdin;
	}

	public function getStdin( $len = null ) {
		if ( $len !== Maintenance::STDIN_ALL ) {
			throw new ExpectationFailedException( "Tried to get stdin without using Maintenance::STDIN_ALL" );
		}

		return file_get_contents( 'data://text/plain,' . $this->mockStdinText );
	}
}

/**
 * @covers \EditCLI
 * @group Database
 * @author Dreamy Jazz
 */
class EditTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return SemiMockedEditCLI::class;
	}

	private function commonTextExecute(
		array $options, string $title, WikiPage $wikiPage, string $stdin, string $expectedWikitextContent
	) {
		$this->maintenance->mockStdin( $stdin );
		$this->maintenance->setArg( 'title', $title );
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		$wikiPage->clear();
		$actualWikitextContent = $wikiPage->getContent()->getWikitextForTransclusion();
		$this->assertSame( $expectedWikitextContent, $actualWikitextContent );
	}

	/** @dataProvider provideExecute */
	public function testExecute(
		$options, $content, $expectedUserName, $expectedComment, $shouldBeAMinorEdit, $shouldBeSentToRecentChanges
	) {
		$testPage = $this->getExistingTestPage();
		$this->commonTextExecute( $options, $testPage->getTitle()->getPrefixedText(), $testPage, $content, $content );
		$lastRevision = $testPage->getRevisionRecord();
		$this->assertSame( $expectedUserName, $lastRevision->getUser()->getName() );
		$this->assertSame( $expectedComment, $lastRevision->getComment()->text );
		$this->assertSame( $shouldBeAMinorEdit, $lastRevision->isMinor() );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'recentchanges' )
			->where( [ 'rc_this_oldid' => $lastRevision->getId() ] )
			->assertFieldValue( (int)$shouldBeSentToRecentChanges );
		$this->expectOutputRegex( '/Saving.*done/' );
	}

	public static function provideExecute() {
		return [
			'No options provided' => [ [], "testcontent\ntest", User::MAINTENANCE_SCRIPT_USER, '', false, true ],
			'Summary provided, minor edit, not sent to recentchanges' => [
				[ 'summary' => 'test', 'minor' => 1, 'no-rc' => 1 ], "testcontent", User::MAINTENANCE_SCRIPT_USER,
				'test', true, false,
			],
			'Autosummary enabled' => [
				// TODO: Don't hard code the output of the autosummary?
				[ 'autosummary' => 1 ], "#REDIRECT [[Test]]", User::MAINTENANCE_SCRIPT_USER,
				'Redirected page to [[Test]]', false, true,
			],
		];
	}

	public function testExecuteWithBotFlagAndAutosummaryForRedirect() {
		$testPage = $this->getExistingTestPage();
		$oldContent = $testPage->getContent();
		$this->commonTextExecute(
			[ 'bot' => 1, 'autosummary' => 1 ],
			$testPage->getTitle()->getPrefixedText(),
			$testPage,
			"#REDIRECT [[Test]]",
			"#REDIRECT [[Test]]"
		);
		$lastRevision = $testPage->getRevisionRecord();
		$newContent = $lastRevision->getContent( SlotRecord::MAIN );
		$this->assertSame(
			$testPage->getContentHandler()->getAutosummary( $oldContent, $newContent ),
			$lastRevision->getComment()->text
		);
		$this->newSelectQueryBuilder()
			->select( 'rc_bot' )
			->from( 'recentchanges' )
			->where( [ 'rc_this_oldid' => $lastRevision->getId() ] )
			->assertFieldValue( 1 );
		$this->expectOutputRegex( '/Saving.*done/' );
	}

	public function testExecuteForParseTitle() {
		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()
			->newFromTitle( Title::newFromText( RequestContext::getMain()->msg( 'mainpage' )->text() ) );
		$this->commonTextExecute(
			[ 'parse-title' => 1 ], '{{int:mainpage}}', $wikiPage,
			"* testing1234abc", "* testing1234abc"
		);
		$this->expectOutputRegex( '/Saving.*done/' );
	}

	public function testExecuteWithUserProvided() {
		$testUser = $this->getTestUser()->getUser();
		$testPage = $this->getExistingTestPage();
		$this->commonTextExecute(
			[ 'user' => $testUser->getName() ],
			$testPage->getTitle()->getPrefixedText(),
			$testPage,
			"test",
			"test"
		);
		$lastRevision = $testPage->getRevisionRecord();
		$this->assertSame( $testUser->getName(), $lastRevision->getUser()->getName() );
		$this->expectOutputRegex( '/Saving.*done/' );
	}

	public function testExecuteForFailedEdit() {
		$testUser = $this->getTestUser()->getUser();
		$testPage = $this->getExistingTestPage();
		// Prevent all edits using a hook.
		$this->setTemporaryHook( 'MultiContentSave', static function () {
			return false;
		} );
		$this->commonTextExecute(
			[ 'user' => $testUser->getName() ],
			$testPage->getTitle()->getPrefixedText(),
			$testPage,
			"test",
			$testPage->getContent()->getWikitextForTransclusion()
		);
		$this->expectOutputRegex( '/Saving.*failed/' );
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $expectedOutputRegex, $title = null ) {
		$this->expectCallToFatalError();
		$this->maintenance->setArg( 'title', $title ?? 'test' );
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecuteForFatalError() {
		return [
			'Invalid username' => [ [ 'user' => 'Template:Testing#test' ], '/Invalid username/' ],
			'Invalid title' => [ [], '/Invalid title/', ':::' ],
			'Title does not exist and nocreate is set' => [
				[ 'nocreate' => 1 ], '/Page does not exist/', 'Non-existing-test-page-1234',
			],
			'Attempts to remove the main slot' => [
				[ 'remove' => 1, 'slot' => SlotRecord::MAIN ], '/Cannot remove main slot/',
			],
		];
	}

	public function testExecuteForCreateOnlyWhenPageExists() {
		$this->testExecuteForFatalError(
			[ 'createonly' => 1 ],
			'/Page already exists/',
			$this->getExistingTestPage()->getTitle()->getPrefixedText()
		);
	}
}
