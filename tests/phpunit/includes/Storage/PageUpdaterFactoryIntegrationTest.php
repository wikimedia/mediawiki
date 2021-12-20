<?php

namespace MediaWiki\Tests\Storage;

use ContentHandler;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use TextContent;

/**
 * @covers MediaWiki\Storage\PageUpdaterFactory
 * @group Database
 */
class PageUpdaterFactoryIntegrationTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers WikiPage::newPageUpdater
	 */
	public function testNewPageUpdater() {
		$page = $this->getExistingTestPage();
		$title = $page->getTitle();

		$user = $this->getTestUser()->getUserIdentity();

		/** @var TextContent $content */
		$content = ContentHandler::makeContent(
			"[[Lorem ipsum]] dolor sit amet, consetetur sadipscing elitr, sed diam "
			. " nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.",
			$title,
			CONTENT_MODEL_WIKITEXT
		);

		$factory = $this->getServiceContainer()->getPageUpdaterFactory();
		$updater = $factory->newPageUpdater( $page, $user );
		$updater->setContent( SlotRecord::MAIN, $content );
		$update = $updater->prepareUpdate();

		/** @var TextContent $pstContent */
		$pstContent = $update->getRawContent( SlotRecord::MAIN );
		$this->assertSame( $content->getText(), $pstContent->getText() );

		$pout = $update->getCanonicalParserOutput();
		$this->assertStringContainsString( 'dolor sit amet', $pout->getText() );
	}

}
