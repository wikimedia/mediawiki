<?php

namespace MediaWiki\Tests\Maintenance;

use FixDoubleRedirects;
use MediaWiki\Title\Title;

/**
 * @covers \FixDoubleRedirects
 * @covers \DoubleRedirectJob
 * @group Database
 * @author Dreamy Jazz
 */
class FixDoubleRedirectsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return FixDoubleRedirects::class;
	}

	/** @dataProvider provideExecuteWhenNoDoubleRedirects */
	public function testExecuteWhenNoDoubleRedirects( $setTitle ) {
		$testRedirect = $this->getExistingTestPage();
		$this->editPage( $testRedirect, '#REDIRECT [[Test]]' );
		if ( $setTitle ) {
			$this->maintenance->setOption( 'title', $testRedirect->getTitle()->getPrefixedText() );
		}
		$this->maintenance->execute();
		$this->expectOutputRegex( '/No double redirects found/' );
	}

	public static function provideExecuteWhenNoDoubleRedirects() {
		return [
			'Title option set to a redirect' => [ true ],
			'Title option not set' => [ false ],
		];
	}

	/** @dataProvider provideExecute */
	public function testExecute( $options, $expectedProcessedCount, $shouldActuallyFixDoubleRedirect ) {
		// Create one double redirect
		$this->editPage( Title::newFromText( 'DoubleRedirect1' ), '#REDIRECT [[Testing]]' );
		$this->editPage( Title::newFromText( 'Testing' ), '#REDIRECT [[Abc]]' );
		// Create a non-double redirect
		$this->editPage( Title::newFromText( 'Test1234' ), '#REDIRECT [[Testabc]]' );
		// Run the maintenance script
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		// Assert on the output and whether the page content has been updated
		$expectedOutputRegex = "/$expectedProcessedCount double redirects processed";
		if ( $expectedProcessedCount ) {
			$expectedOutputRegex .= '[\s\S]*\* \[\[DoubleRedirect1\]\]';
		}
		$expectedOutputRegex .= '/';
		$this->expectOutputRegex( $expectedOutputRegex );
		$doubleRedirectWikiPage = $this->getServiceContainer()->getWikiPageFactory()
			->newFromTitle( Title::newFromText( 'DoubleRedirect1' ) );
		$doubleRedirectPageContent = $doubleRedirectWikiPage->getContent()->getWikitextForTransclusion();
		if ( $shouldActuallyFixDoubleRedirect ) {
			$this->assertSame( '#REDIRECT [[Abc]]', $doubleRedirectPageContent );
			$lastRevision = $doubleRedirectWikiPage->getRevisionRecord();
			$this->assertSame(
				wfMessage( 'double-redirect-fixer' )->inContentLanguage()->text(),
				$lastRevision->getUser()->getName()
			);
			$this->assertTrue( $lastRevision->isMinor() );
			// Check that the edit made to fix the double redirect was not sent to recentchanges
			$this->newSelectQueryBuilder()
				->select( 'COUNT(*)' )
				->from( 'recentchanges' )
				->where( [ 'rc_this_oldid' => $lastRevision->getId() ] )
				->assertFieldValue( 0 );
		} else {
			$this->assertSame( '#REDIRECT [[Testing]]', $doubleRedirectPageContent );
		}
	}

	public static function provideExecute() {
		return [
			'Dry-run' => [ [ 'dry-run' => 1 ], 1, false ],
			'Dry-run with title set' => [ [ 'dry-run' => 1, 'title' => 'Testing' ], 1, false ],
			'No options' => [ [], 1, true ],
			'Title set' => [ [ 'title' => 'Testing' ], 1, true ],
		];
	}

	public function testExecuteWhenInAsyncMode() {
		// Create one double redirect
		$this->editPage( Title::newFromText( 'DoubleRedirect1' ), '#REDIRECT [[Testing]]' );
		$this->editPage( Title::newFromText( 'Testing' ), '#REDIRECT [[Abc]]' );
		// Create a non-double redirect
		$this->editPage( Title::newFromText( 'Test1234' ), '#REDIRECT [[Testabc]]' );
		// Run the maintenance script
		$this->maintenance->setOption( 'async', 1 );
		$this->maintenance->execute();
		// Run all queued jobs, which were added because the script was run with the
		// async mode.
		$this->runJobs();
		// Assert on the output and whether the page content has been updated
		$this->expectOutputRegex(
			'/Queuing batch of 1 double redirects[\s\S]*' .
			'1 double redirects processed[\s\S]*\* \[\[DoubleRedirect1\]\]/'
		);
		$doubleRedirectWikiPage = $this->getServiceContainer()->getWikiPageFactory()
			->newFromTitle( Title::newFromText( 'DoubleRedirect1' ) );
		$doubleRedirectPageContent = $doubleRedirectWikiPage->getContent()->getWikitextForTransclusion();
		$this->assertSame( '#REDIRECT [[Abc]]', $doubleRedirectPageContent );
	}

	public function testExecuteWhenTitleIsNotARedirect() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Testabc is not a redirect/' );
		$this->maintenance->setOption( 'title', 'Testabc' );
		$this->maintenance->execute();
	}
}
