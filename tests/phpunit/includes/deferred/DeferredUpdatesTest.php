<?php

class DeferredUpdatesTest extends MediaWikiTestCase {
	public function testDoUpdatesWeb() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

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

	public function testDoUpdatesCLI() {
		$this->setMwGlobals( 'wgCommandLineMode', true );

		$updates = array(
			'1' => 'deferred update 1',
			'2' => 'deferred update 2',
			'2-1' => 'deferred update 1 within deferred update 2',
			'3' => 'deferred update 3',
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
