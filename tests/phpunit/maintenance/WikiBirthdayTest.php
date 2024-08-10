<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\Title\Title;
use WikiBirthday;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \WikiBirthday
 * @group Database
 * @author Dreamy Jazz
 */
class WikiBirthdayTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return WikiBirthday::class;
	}

	public function testExecuteForOneRevision() {
		// Create a fake revision with a set timestamp
		ConvertibleTimestamp::setFakeTime( '20230405060708' );
		$this->editPage( Title::newFromText( 'Testing1234' ), 'Test1234' );
		// Set the timestamp to a year in advance
		ConvertibleTimestamp::setFakeTime( '20240405060708' );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Wiki was created on: 5 April 2023.*age: 1 yr.*0 month.*0 day.*old/' );
	}

	public function testExecuteForDeletedRevision() {
		// Create a fake revision with a set timestamp, and then delete the associated page
		ConvertibleTimestamp::setFakeTime( '20230505060708' );
		$editStatus = $this->editPage( Title::newFromText( 'Testing1234' ), 'Test1234' );
		ConvertibleTimestamp::setFakeTime( '20230505060709' );
		$this->deletePage( $this->getServiceContainer()->getWikiPageFactory()->newFromTitle(
			$editStatus->getNewRevision()->getPage()
		) );
		// Set the timestamp to 3 months and 3 days in advance
		ConvertibleTimestamp::setFakeTime( '20230708060708' );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Wiki was created on: 5 May.*age: 0 yr.*2 month.*3 day.*old/' );
	}
}
