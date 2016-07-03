<?php

class DeferredUpdatesTest extends MediaWikiTestCase {
	public function testDoUpdatesWeb() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$updates = [
			'1' => 'deferred update 1',
			'2' => 'deferred update 2',
			'3' => 'deferred update 3',
			'2-1' => 'deferred update 1 within deferred update 2',
		];
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

		$x = null;
		$y = null;
		DeferredUpdates::addCallableUpdate(
			function () use ( &$x ) {
				$x = 'Sherity';
			},
			DeferredUpdates::PRESEND
		);
		DeferredUpdates::addCallableUpdate(
			function () use ( &$y ) {
				$y = 'Marychu';
			},
			DeferredUpdates::POSTSEND
		);

		$this->assertNull( $x, "Update not run yet" );
		$this->assertNull( $y, "Update not run yet" );

		DeferredUpdates::doUpdates( 'run', DeferredUpdates::PRESEND );
		$this->assertEquals( "Sherity", $x, "PRESEND update ran" );
		$this->assertNull( $y, "POSTSEND update not run yet" );

		DeferredUpdates::doUpdates( 'run', DeferredUpdates::POSTSEND );
		$this->assertEquals( "Marychu", $y, "POSTSEND update ran" );
	}

	public function testDoUpdatesCLI() {
		$this->setMwGlobals( 'wgCommandLineMode', true );

		$updates = [
			'1' => 'deferred update 1',
			'2' => 'deferred update 2',
			'2-1' => 'deferred update 1 within deferred update 2',
			'3' => 'deferred update 3',
		];
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
