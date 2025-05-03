<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use UpdateSpecialPages;
use Wikimedia\TestingAccessWrapper;

class MockSpecialPageForUpdateSpecialPagesTest extends SpecialPage {

}

/**
 * @covers \UpdateSpecialPages
 * @group Database
 * @group Maintenance
 */
class UpdateSpecialPagesTest extends MaintenanceBaseTestCase {
	use TempUserTestTrait;

	public function getMaintenanceClass() {
		return UpdateSpecialPages::class;
	}

	public function testExecuteForList() {
		$this->maintenance->setOption( 'list', 1 );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Statistics [callback]', $actualOutput );
		$this->assertStringContainsString( 'Uncategorizedcategories [QueryPage]', $actualOutput );
		$this->assertStringContainsString( 'BrokenRedirects [QueryPage]', $actualOutput );
	}

	public function testExecuteWhenAllQueryPagesDisabled() {
		$this->overrideConfigValue(
			MainConfigNames::DisableQueryPageUpdate,
			array_column( QueryPage::getPages(), 1 )
		);
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertMatchesRegularExpression(
			'/Statistics[\s\S]*\[callback] completed in/', $actualOutput
		);
		$this->assertMatchesRegularExpression(
			'/Uncategorizedcategories[\s\S]*\[QueryPage] disabled/', $actualOutput
		);
		$this->assertMatchesRegularExpression(
			'/BrokenRedirects[\s\S]*\[QueryPage] disabled/', $actualOutput
		);
	}

	public function testExecuteForAllUpdates() {
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertMatchesRegularExpression(
			'/Statistics[\s\S]*\[callback] completed in/', $actualOutput
		);
		$this->assertMatchesRegularExpression(
			'/Uncategorizedcategories[\s\S]*\[QueryPage] got 0 rows in/', $actualOutput
		);
		$this->assertMatchesRegularExpression(
			'/BrokenRedirects[\s\S]*\[QueryPage] got 0 rows in/', $actualOutput
		);
		$this->assertMatchesRegularExpression(
			'/MIMEsearch[\s\S]*\[QueryPage] cheap, skipped/', $actualOutput
		);
		$this->assertStringNotContainsString( 'No such special page', $actualOutput );
		$this->assertStringNotContainsString( 'is not an instance of QueryPage', $actualOutput );
	}

	/**
	 * Installs a mock SpecialPageFactory that mocks ::getPage to return the specified
	 * value if the special page is "Ancientpages" (chosen at random) and otherwise
	 * use the real service.
	 *
	 * We cannot easily mock the return value of QueryPages::getPages as it uses a static variable
	 * in the method to cache the calls, so cannot be reset between tests.
	 *
	 * @param SpecialPage|null $ancientPagesReturnValue
	 * @return void
	 */
	private function installMockSpecialPageFactory( ?SpecialPage $ancientPagesReturnValue ) {
		$realSpecialPageFactory = $this->getServiceContainer()->getSpecialPageFactory();
		$mockSpecialPageFactory = $this->createMock( SpecialPageFactory::class );
		$mockSpecialPageFactory->method( 'getPage' )
			->willReturnCallback( static function ( $name ) use ( $realSpecialPageFactory, $ancientPagesReturnValue ) {
				if ( $name === 'Ancientpages' ) {
					return $ancientPagesReturnValue;
				} else {
					return $realSpecialPageFactory->getPage( $name );
				}
			} );
		$this->setService( 'SpecialPageFactory', $mockSpecialPageFactory );
	}

	public function testExecuteWhenListIncludesMissingSpecialPage() {
		$this->installMockSpecialPageFactory( null );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( "No such special page: Ancientpages", $actualOutput );
	}

	public function testExecuteWhenListIncludesSpecialPageThatDoesNotExtendQueryPage() {
		$this->installMockSpecialPageFactory( new MockSpecialPageForUpdateSpecialPagesTest() );

		$this->expectOutputRegex(
			'/MockSpecialPageForUpdateSpecialPagesTest is not an instance of QueryPage/'
		);
		$this->expectCallToFatalError();
		$this->maintenance->execute();
	}

	public function testExecuteWhenQueryPageIsCheapButDataExisted() {
		$mockQueryPage = $this->createMock( QueryPage::class );
		$mockQueryPage->method( 'getCachedTimestamp' )
			->willReturn( '20240506070809' );
		$mockQueryPage->method( 'isExpensive' )
			->willReturn( false );
		$mockQueryPage->expects( $this->once() )
			->method( 'deleteAllCachedData' );

		$this->installMockSpecialPageFactory( $mockQueryPage );

		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertMatchesRegularExpression(
			'/Ancientpages[\s\S]*\[QueryPage] cheap, but deleted cached data/', $actualOutput
		);
	}

	public function testExecuteWhenQueryPageIsExpensiveAndQueryFails() {
		$mockQueryPage = $this->createMock( QueryPage::class );
		$mockQueryPage->method( 'isExpensive' )
			->willReturn( true );
		$mockQueryPage->method( 'getName' )
			->willReturn( 'Ancientpages' );
		$mockQueryPage->expects( $this->once() )
			->method( 'recache' )
			->willReturn( false );

		$this->installMockSpecialPageFactory( $mockQueryPage );

		$this->maintenance->setOption( 'only', 'Ancientpages' );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertMatchesRegularExpression(
			'/Ancientpages[\s\S]*\[QueryPage] FAILED: database error/', $actualOutput
		);
		$this->assertStringNotContainsString( 'Mostimages', $actualOutput );
	}

	public function testExecuteWhenQueryPageIsExpensiveAndQuerySucceeds() {
		$mockQueryPage = $this->createMock( QueryPage::class );
		$mockQueryPage->method( 'isExpensive' )
			->willReturn( true );
		$mockQueryPage->expects( $this->once() )
			->method( 'recache' )
			->willReturn( 123456 );

		$this->installMockSpecialPageFactory( $mockQueryPage );

		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertMatchesRegularExpression(
			'/Ancientpages[\s\S]*\[QueryPage] got 123456 rows in \d*.\d\ds/', $actualOutput
		);
	}

	public function testExecuteWhenSpecialPageCacheUpdateCallbackIsNotCallable() {
		$this->overrideConfigValue(
			MainConfigNames::SpecialPageCacheUpdates,
			[ 'Statistics' => 'nonExistingTestFunction' ]
		);

		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertMatchesRegularExpression(
			'/Statistics[\s\S]*\[callback] Uncallable function nonExistingTestFunction!/', $actualOutput
		);
	}

	public function testExecuteWhenSpecialPageCacheUpdate() {
		$callbackCalled = false;
		$this->overrideConfigValue(
			MainConfigNames::SpecialPageCacheUpdates,
			[
				'Statistics' => static function () use ( &$callbackCalled ) {
					$callbackCalled = true;
				},
			]
		);

		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertMatchesRegularExpression(
			'/Statistics[\s\S]*\[callback] completed in \d*.\d\ds/', $actualOutput
		);
		$this->assertTrue( $callbackCalled, 'Callback not called to update special page cache' );
	}

	/** @dataProvider provideOutputElapsedTime */
	public function testOutputElapsedTime( float $elapsedTime, string $expectedOutputString ) {
		/** @var TestingAccessWrapper $maintenance */
		$maintenance = $this->maintenance;
		$maintenance->outputElapsedTime( $elapsedTime );
		$this->expectOutputString( $expectedOutputString );
	}

	public static function provideOutputElapsedTime(): array {
		return [
			'Elapsed time is 0' => [ 0, "0.00s\n" ],
			'Elapsed time is 11.3 seconds' => [ 11.3, "11.30s\n" ],
			'Elapsed time is 3 minutes and 5 seconds' => [ 3 * 60 + 5, "3m 5.00s\n" ],
			'Elapsed time is 3 hours, 5 minutes, and 7.334 seconds' => [ 3 * 3600 + 5 * 60 + 7.33, "3h 5m 7.33s\n" ],
		];
	}
}
