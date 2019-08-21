<?php

use MediaWiki\MediaWikiServices;

class DeferredUpdatesTest extends MediaWikiTestCase {

	/**
	 * @covers DeferredUpdates::addUpdate
	 * @covers DeferredUpdates::push
	 * @covers DeferredUpdates::doUpdates
	 * @covers DeferredUpdates::handleUpdateQueue
	 * @covers DeferredUpdates::attemptUpdate
	 */
	public function testAddAndRun() {
		$update = $this->getMockBuilder( DeferrableUpdate::class )
			->setMethods( [ 'doUpdate' ] )->getMock();
		$update->expects( $this->once() )->method( 'doUpdate' );

		DeferredUpdates::addUpdate( $update );
		DeferredUpdates::doUpdates();
	}

	/**
	 * @covers DeferredUpdates::addUpdate
	 * @covers DeferredUpdates::push
	 */
	public function testAddMergeable() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$update1 = $this->getMockBuilder( MergeableUpdate::class )
			->setMethods( [ 'merge', 'doUpdate' ] )->getMock();
		$update1->expects( $this->once() )->method( 'merge' );
		$update1->expects( $this->never() )->method( 'doUpdate' );

		$update2 = $this->getMockBuilder( MergeableUpdate::class )
			->setMethods( [ 'merge', 'doUpdate' ] )->getMock();
		$update2->expects( $this->never() )->method( 'merge' );
		$update2->expects( $this->never() )->method( 'doUpdate' );

		DeferredUpdates::addUpdate( $update1 );
		DeferredUpdates::addUpdate( $update2 );
	}

	/**
	 * @covers DeferredUpdates::addCallableUpdate
	 * @covers MWCallableUpdate::getOrigin
	 */
	public function testAddCallableUpdate() {
		$this->setMwGlobals( 'wgCommandLineMode', true );

		$ran = 0;
		DeferredUpdates::addCallableUpdate( function () use ( &$ran ) {
			$ran++;
		} );
		DeferredUpdates::doUpdates();

		$this->assertSame( 1, $ran, 'Update ran' );
	}

	/**
	 * @covers DeferredUpdates::getPendingUpdates
	 * @covers DeferredUpdates::clearPendingUpdates
	 */
	public function testGetPendingUpdates() {
		// Prevent updates from running
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$pre = DeferredUpdates::PRESEND;
		$post = DeferredUpdates::POSTSEND;
		$all = DeferredUpdates::ALL;

		$update = $this->getMock( DeferrableUpdate::class );
		$update->expects( $this->never() )
			->method( 'doUpdate' );

		DeferredUpdates::addUpdate( $update, $pre );
		$this->assertCount( 1, DeferredUpdates::getPendingUpdates( $pre ) );
		$this->assertCount( 0, DeferredUpdates::getPendingUpdates( $post ) );
		$this->assertCount( 1, DeferredUpdates::getPendingUpdates( $all ) );
		$this->assertCount( 1, DeferredUpdates::getPendingUpdates() );
		DeferredUpdates::clearPendingUpdates();
		$this->assertCount( 0, DeferredUpdates::getPendingUpdates() );

		DeferredUpdates::addUpdate( $update, $post );
		$this->assertCount( 0, DeferredUpdates::getPendingUpdates( $pre ) );
		$this->assertCount( 1, DeferredUpdates::getPendingUpdates( $post ) );
		$this->assertCount( 1, DeferredUpdates::getPendingUpdates( $all ) );
		$this->assertCount( 1, DeferredUpdates::getPendingUpdates() );
		DeferredUpdates::clearPendingUpdates();
		$this->assertCount( 0, DeferredUpdates::getPendingUpdates() );
	}

	/**
	 * @covers DeferredUpdates::doUpdates
	 * @covers DeferredUpdates::handleUpdateQueue
	 * @covers DeferredUpdates::addUpdate
	 */
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

	/**
	 * @covers DeferredUpdates::doUpdates
	 * @covers DeferredUpdates::handleUpdateQueue
	 * @covers DeferredUpdates::addUpdate
	 */
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

		// clear anything
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->commitMasterChanges( __METHOD__ );

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

	/**
	 * @covers DeferredUpdates::doUpdates
	 * @covers DeferredUpdates::handleUpdateQueue
	 * @covers DeferredUpdates::addUpdate
	 */
	public function testPresendAddOnPostsendRun() {
		$this->setMwGlobals( 'wgCommandLineMode', true );

		$x = false;
		$y = false;
		// clear anything
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->commitMasterChanges( __METHOD__ );

		DeferredUpdates::addCallableUpdate(
			function () use ( &$x, &$y ) {
				$x = true;
				DeferredUpdates::addCallableUpdate(
					function () use ( &$y ) {
						$y = true;
					},
					DeferredUpdates::PRESEND
				);
			},
			DeferredUpdates::POSTSEND
		);

		DeferredUpdates::doUpdates();

		$this->assertTrue( $x, "Outer POSTSEND update ran" );
		$this->assertTrue( $y, "Nested PRESEND update ran" );
	}

	/**
	 * @covers DeferredUpdates::attemptUpdate
	 */
	public function testRunUpdateTransactionScope() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$this->assertFalse( $lbFactory->hasTransactionRound(), 'Initial state' );

		$ran = 0;
		DeferredUpdates::addCallableUpdate( function () use ( &$ran, $lbFactory ) {
			$ran++;
			$this->assertTrue( $lbFactory->hasTransactionRound(), 'Has transaction' );
		} );
		DeferredUpdates::doUpdates();

		$this->assertSame( 1, $ran, 'Update ran' );
		$this->assertFalse( $lbFactory->hasTransactionRound(), 'Final state' );
	}

	/**
	 * @covers DeferredUpdates::attemptUpdate
	 * @covers TransactionRoundDefiningUpdate::getOrigin
	 */
	public function testRunOuterScopeUpdate() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$this->assertFalse( $lbFactory->hasTransactionRound(), 'Initial state' );

		$ran = 0;
		DeferredUpdates::addUpdate( new TransactionRoundDefiningUpdate(
				function () use ( &$ran, $lbFactory ) {
					$ran++;
					$this->assertFalse( $lbFactory->hasTransactionRound(), 'No transaction' );
				} )
		);
		DeferredUpdates::doUpdates();

		$this->assertSame( 1, $ran, 'Update ran' );
	}

	/**
	 * @covers DeferredUpdates::tryOpportunisticExecute
	 */
	public function testTryOpportunisticExecute() {
		$calls = [];
		$callback1 = function () use ( &$calls ) {
			$calls[] = 1;
		};
		$callback2 = function () use ( &$calls ) {
			$calls[] = 2;
		};

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->beginMasterChanges( __METHOD__ );

		DeferredUpdates::addCallableUpdate( $callback1 );
		$this->assertEquals( [], $calls );

		DeferredUpdates::tryOpportunisticExecute( 'run' );
		$this->assertEquals( [], $calls );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->onTransactionCommitOrIdle( function () use ( &$calls, $callback2 ) {
			DeferredUpdates::addCallableUpdate( $callback2 );
			$this->assertEquals( [], $calls );
			$calls[] = 'oti';
		} );
		$this->assertEquals( 1, $dbw->trxLevel() );
		$this->assertEquals( [], $calls );

		$lbFactory->commitMasterChanges( __METHOD__ );

		$this->assertEquals( [ 'oti' ], $calls );

		DeferredUpdates::tryOpportunisticExecute( 'run' );
		$this->assertEquals( [ 'oti', 1, 2 ], $calls );
	}

	/**
	 * @covers DeferredUpdates::attemptUpdate
	 */
	public function testCallbackUpdateRounds() {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		$fname = __METHOD__;
		$called = false;
		DeferredUpdates::attemptUpdate(
			new MWCallableUpdate(
				function () use ( $lbFactory, $fname, &$called ) {
					$lbFactory->flushReplicaSnapshots( $fname );
					$lbFactory->commitMasterChanges( $fname );
					$called = true;
				},
				$fname
			),
			$lbFactory
		);

		$this->assertTrue( $called, "Callback ran" );
	}
}
