<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Database
 * @coversNothing
 */
class ImportTemporaryUserIntegrationTest extends MediaWikiIntegrationTestCase {
	use TempUserTestTrait;

	/**
	 * Test that category membership changes in RC function for imported anonymous revisions
	 * when temporary users are enabled (T373318).
	 */
	public function testShouldSuccessfullyUpdateCategoryMembershipInRecentChanges(): void {
		$this->enableAutoCreateTempUser();
		$this->overrideConfigValue( MainConfigNames::RCWatchCategoryMembership, true );

		$referenceTime = wfTimestampNow();
		ConvertibleTimestamp::setFakeTime( $referenceTime );

		$this->importTestData();
		$this->runJobs();

		$rc = RecentChange::newFromConds( [
			'rc_namespace' => NS_CATEGORY,
			'rc_title' => 'Test',
			'rc_source' => RecentChange::SRC_CATEGORIZE
		], __METHOD__ );

		$this->assertSame( '192.0.2.14', $rc->getPerformerIdentity()->getName() );
		$this->assertSame( $referenceTime, $rc->getAttribute( 'rc_timestamp' ) );
	}

	private function importTestData(): void {
		$file = __DIR__ . '/../../data/import/ImportAnonUserTest.xml';

		$importStreamSource = ImportStreamSource::newFromFile( $file );

		$this->assertTrue(
			$importStreamSource->isGood(),
			"Import source {$file} failed"
		);

		$importer = $this->getServiceContainer()
			->getWikiImporterFactory()
			->getWikiImporter( $importStreamSource->value, $this->getTestSysop()->getAuthority() );
		$importer->setDebug( true );

		$context = RequestContext::getMain();
		$context->setUser( $this->getTestUser()->getUser() );
		$reporter = new ImportReporter(
			$importer,
			false,
			'',
			false,
			$context
		);

		$reporter->open();
		$importer->doImport();
		$this->assertStatusGood( $reporter->close() );
	}
}
