<?php

class DeferredUpdatesTest extends MediaWikiTestCase {

	public function testDoUpdates() {
		$updates = array(
			'1' => 'deferred update 1',
			'2' => 'deferred update 2',
			'3' => 'deferred update 3',
			'2-1' => 'deferred update 1 within deferred update 2',
		);
		DeferredUpdates::addCallableUpdate(
			function () use ( $updates ) {
				echo $updates['1'];
			}
		);
		DeferredUpdates::addCallableUpdate(
			function () use ( $updates ) {
				echo $updates['2'];
				DeferredUpdates::addCallableUpdate(
					function () use ( $updates ) {
						echo $updates['2-1'];
					}
				);
			}
		);
		DeferredUpdates::addCallableUpdate(
			function () use ( $updates ) {
				echo $updates[3];
			}
		);

		$this->expectOutputString( implode( '', $updates ) );

		DeferredUpdates::doUpdates();
	}

}
