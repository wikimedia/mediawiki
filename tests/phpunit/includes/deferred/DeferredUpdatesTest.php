<?php

class DeferredUpdatesTest extends MediaWikiTestCase {
	public function testDoUpdatesWeb() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$updates = [
			'1' => "deferred update 1;\n",
			'2' => "deferred update 2;\n",
			'2-1' => "deferred update 1 within deferred update 2;\n",
			'2-2' => "deferred update 2 within deferred update 2;\n",
			'3' => "deferred update 3;\n",
			'3-1' => "deferred update 1 within deferred update 3;\n",
			'3-2' => "deferred update 2 within deferred update 3;\n",
			'3-1-1' => "deferred update 1 within deferred update 1 within deferred update 3;\n",
			'3-2-1' => "deferred update 1 within deferred update 2 with deferred update 3;\n",
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
				DeferredUpdates::addCallableUpdate(
					function () use ( $updates ) {
						echo $updates['2-2'];
					}
				);
			}
		);
		DeferredUpdates::addCallableUpdate(
			function () use ( $updates ) {
				echo $updates['3'];
				DeferredUpdates::addCallableUpdate(
					function () use ( $updates ) {
						echo $updates['3-1'];
						DeferredUpdates::addCallableUpdate(
							function () use ( $updates ) {
								echo $updates['3-1-1'];
							}
						);
					}
				);
				DeferredUpdates::addCallableUpdate(
					function () use ( $updates ) {
						echo $updates['3-2'];
						DeferredUpdates::addCallableUpdate(
							function () use ( $updates ) {
								echo $updates['3-2-1'];
							}
						);
					}
				);
			}
		);

		$this->assertEquals( 3, DeferredUpdates::pendingUpdatesCount() );

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
			'1' => "deferred update 1;\n",
			'2' => "deferred update 2;\n",
			'2-1' => "deferred update 1 within deferred update 2;\n",
			'2-2' => "deferred update 2 within deferred update 2;\n",
			'3' => "deferred update 3;\n",
			'3-1' => "deferred update 1 within deferred update 3;\n",
			'3-2' => "deferred update 2 within deferred update 3;\n",
			'3-1-1' => "deferred update 1 within deferred update 1 within deferred update 3;\n",
			'3-2-1' => "deferred update 1 within deferred update 2 with deferred update 3;\n",
		];

		wfGetLBFactory()->commitMasterChanges( __METHOD__ ); // clear anything

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
				DeferredUpdates::addCallableUpdate(
					function () use ( $updates ) {
						echo $updates['2-2'];
					}
				);
			}
		);
		DeferredUpdates::addCallableUpdate(
			function () use ( $updates ) {
				echo $updates['3'];
				DeferredUpdates::addCallableUpdate(
					function () use ( $updates ) {
						echo $updates['3-1'];
						DeferredUpdates::addCallableUpdate(
							function () use ( $updates ) {
								echo $updates['3-1-1'];
							}
						);
					}
				);
				DeferredUpdates::addCallableUpdate(
					function () use ( $updates ) {
						echo $updates['3-2'];
						DeferredUpdates::addCallableUpdate(
							function () use ( $updates ) {
								echo $updates['3-2-1'];
							}
						);
					}
				);
			}
		);

		$this->expectOutputString( implode( '', $updates ) );

		DeferredUpdates::doUpdates();
	}
}
