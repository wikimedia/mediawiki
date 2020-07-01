<?php

use MediaWiki\MediaWikiServices;

/**
 * Test class for SpecialChangeContentModel class
 *
 * @group Database
 *
 * @covers SpecialChangeContentModel
 */
class SpecialChangeContentModelTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \SpecialChangeContentModel::__construct
	 * @covers \SpecialChangeContentModel::doesWrites
	 */
	public function testBasic() {
		$contentModel = $this->getChangeContentModel();
		$this->assertInstanceOf( SpecialChangeContentModel::class, $contentModel );
		$this->assertTrue( $contentModel->doesWrites() );
	}

	private function getChangeContentModel() {
		return MediaWikiServices::getInstance()->getSpecialPageFactory()
			->getPage( 'ChangeContentModel' );
	}
}
