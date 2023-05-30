<?php

use MediaWiki\Deferred\DeferredUpdatesManager;
use MediaWiki\MediaWikiServices;

/**
 * Based on DeferredUpdatesTest but calling the corresponding
 * service methods instead
 *
 * @coversDefaultClass \MediaWiki\Deferred\DeferredUpdatesManager
 */
class DeferredUpdatesManagerTest extends MediaWikiIntegrationTestCase {

	private function getManager() {
		return MediaWikiServices::getInstance()->getDeferredUpdatesManager();
	}

	/**
	 * @covers ::addUpdate
	 * @covers ::doUpdates
	 * @covers ::attemptUpdate
	 * @covers DeferredUpdatesScopeStack
	 * @covers DeferredUpdatesScope
	 */
	public function testAddAndRun() {
		$update = $this->getMockBuilder( DeferrableUpdate::class )
			->onlyMethods( [ 'doUpdate' ] )->getMock();
		$update->expects( $this->once() )->method( 'doUpdate' );

		$manager = $this->getManager();
		$manager->addUpdate( $update );
		$manager->doUpdates();
	}

	/**
	 * @covers ::addUpdate
	 * @covers DeferredUpdatesScopeStack
	 * @covers DeferredUpdatesScope
	 */
	public function testAddMergeable() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$update1 = $this->getMockBuilder( MergeableUpdate::class )
			->onlyMethods( [ 'merge', 'doUpdate' ] )->getMock();
		$update1->expects( $this->once() )->method( 'merge' );
		$update1->expects( $this->never() )->method( 'doUpdate' );

		$update2 = $this->getMockBuilder( MergeableUpdate::class )
			->onlyMethods( [ 'merge', 'doUpdate' ] )->getMock();
		$update2->expects( $this->never() )->method( 'merge' );
		$update2->expects( $this->never() )->method( 'doUpdate' );

		$manager = $this->getManager();
		$manager->addUpdate( $update1 );
		$manager->addUpdate( $update2 );
	}

	/**
	 * @covers ::addCallableUpdate
	 * @covers MWCallableUpdate::getOrigin
	 */
	public function testAddCallableUpdate() {
		$this->setMwGlobals( 'wgCommandLineMode', true );

		$manager = $this->getManager();

		$ran = 0;
		$manager->addCallableUpdate( static function () use ( &$ran ) {
			$ran++;
		} );
		$manager->doUpdates();

		$this->assertSame( 1, $ran, 'Update ran' );
	}

	/**
	 * @covers ::getPendingUpdates
	 * @covers ::clearPendingUpdates
	 */
	public function testGetPendingUpdates() {
		// Prevent updates from running
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$pre = DeferredUpdatesManager::PRESEND;
		$post = DeferredUpdatesManager::POSTSEND;
		$all = DeferredUpdatesManager::ALL;

		$update = $this->createMock( DeferrableUpdate::class );
		$update->expects( $this->never() )
			->method( 'doUpdate' );

		$manager = $this->getManager();
		$manager->addUpdate( $update, $pre );
		$this->assertCount( 1, $manager->getPendingUpdates( $pre ) );
		$this->assertSame( [], $manager->getPendingUpdates( $post ) );
		$this->assertCount( 1, $manager->getPendingUpdates( $all ) );
		$this->assertCount( 1, $manager->getPendingUpdates() );
		$manager->clearPendingUpdates();
		$this->assertSame( [], $manager->getPendingUpdates() );

		$manager->addUpdate( $update, $post );
		$this->assertSame( [], $manager->getPendingUpdates( $pre ) );
		$this->assertCount( 1, $manager->getPendingUpdates( $post ) );
		$this->assertCount( 1, $manager->getPendingUpdates( $all ) );
		$this->assertCount( 1, $manager->getPendingUpdates() );
		$manager->clearPendingUpdates();
		$this->assertSame( [], $manager->getPendingUpdates() );
	}

	/**
	 * @covers ::doUpdates
	 * @covers ::addUpdate
	 * @covers DeferredUpdatesScopeStack
	 * @covers DeferredUpdatesScope
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
		$manager = $this->getManager();
		$manager->addCallableUpdate(
			static function () use ( $updates ) {
				echo $updates['1'];
			}
		);
		$manager->addCallableUpdate(
			static function () use ( $updates, $manager ) {
				echo $updates['2'];
				$manager->addCallableUpdate(
					static function () use ( $updates ) {
						echo $updates['2-1'];
					}
				);
				$manager->addCallableUpdate(
					static function () use ( $updates ) {
						echo $updates['2-2'];
					}
				);
			}
		);
		$manager->addCallableUpdate(
			static function () use ( $updates, $manager ) {
				echo $updates['3'];
				$manager->addCallableUpdate(
					static function () use ( $updates, $manager ) {
						echo $updates['3-1'];
						$manager->addCallableUpdate(
							static function () use ( $updates ) {
								echo $updates['3-1-1'];
							}
						);
					}
				);
				$manager->addCallableUpdate(
					static function () use ( $updates, $manager ) {
						echo $updates['3-2'];
						$manager->addCallableUpdate(
							static function () use ( $updates ) {
								echo $updates['3-2-1'];
							}
						);
					}
				);
			}
		);

		$this->assertEquals( 3, $manager->pendingUpdatesCount() );

		$this->expectOutputString( implode( '', $updates ) );

		$manager->doUpdates();

		$x = null;
		$y = null;
		$manager->addCallableUpdate(
			static function () use ( &$x ) {
				$x = 'Sherity';
			},
			DeferredUpdatesManager::PRESEND
		);
		$manager->addCallableUpdate(
			static function () use ( &$y ) {
				$y = 'Marychu';
			},
			DeferredUpdatesManager::POSTSEND
		);

		$this->assertNull( $x, "Update not run yet" );
		$this->assertNull( $y, "Update not run yet" );

		$manager->doUpdates( DeferredUpdatesManager::PRESEND );
		$this->assertEquals( "Sherity", $x, "PRESEND update ran" );
		$this->assertNull( $y, "POSTSEND update not run yet" );

		$manager->doUpdates( DeferredUpdatesManager::POSTSEND );
		$this->assertEquals( "Marychu", $y, "POSTSEND update ran" );
	}

	/**
	 * @covers ::doUpdates
	 * @covers ::addUpdate
	 * @covers DeferredUpdatesScopeStack
	 * @covers DeferredUpdatesScope
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
		$lbFactory->commitPrimaryChanges( __METHOD__ );

		$manager = $this->getManager();
		$manager->addCallableUpdate(
			static function () use ( $updates ) {
				echo $updates['1'];
			}
		);
		$manager->addCallableUpdate(
			static function () use ( $updates, $manager ) {
				echo $updates['2'];
				$manager->addCallableUpdate(
					static function () use ( $updates ) {
						echo $updates['2-1'];
					}
				);
				$manager->addCallableUpdate(
					static function () use ( $updates ) {
						echo $updates['2-2'];
					}
				);
			}
		);
		$manager->addCallableUpdate(
			static function () use ( $updates, $manager ) {
				echo $updates['3'];
				$manager->addCallableUpdate(
					static function () use ( $updates, $manager ) {
						echo $updates['3-1'];
						$manager->addCallableUpdate(
							static function () use ( $updates ) {
								echo $updates['3-1-1'];
							}
						);
					}
				);
				$manager->addCallableUpdate(
					static function () use ( $updates, $manager ) {
						echo $updates['3-2'];
						$manager->addCallableUpdate(
							static function () use ( $updates ) {
								echo $updates['3-2-1'];
							}
						);
					}
				);
			}
		);

		$this->expectOutputString( implode( '', $updates ) );

		$manager->doUpdates();
	}

	/**
	 * @covers ::doUpdates
	 * @covers ::addUpdate
	 * @covers DeferredUpdatesScopeStack
	 * @covers DeferredUpdatesScope
	 */
	public function testPresendAddOnPostsendRun() {
		$this->setMwGlobals( 'wgCommandLineMode', true );

		$x = false;
		$y = false;
		// clear anything
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->commitPrimaryChanges( __METHOD__ );

		$manager = $this->getManager();
		$manager->addCallableUpdate(
			static function () use ( &$x, &$y, $manager ) {
				$x = true;
				$manager->addCallableUpdate(
					static function () use ( &$y ) {
						$y = true;
					},
					DeferredUpdatesManager::PRESEND
				);
			},
			DeferredUpdatesManager::POSTSEND
		);

		$manager->doUpdates();

		$this->assertTrue( $x, "Outer POSTSEND update ran" );
		$this->assertTrue( $y, "Nested PRESEND update ran" );
	}

	/**
	 * @covers ::attemptUpdate
	 */
	public function testRunUpdateTransactionScope() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$this->assertFalse( $lbFactory->hasTransactionRound(), 'Initial state' );

		$manager = $this->getManager();

		$ran = 0;
		$manager->addCallableUpdate( function () use ( &$ran, $lbFactory ) {
			$ran++;
			$this->assertTrue( $lbFactory->hasTransactionRound(), 'Has transaction' );
		} );
		$manager->doUpdates();

		$this->assertSame( 1, $ran, 'Update ran' );
		$this->assertFalse( $lbFactory->hasTransactionRound(), 'Final state' );
	}

	/**
	 * @covers ::attemptUpdate
	 * @covers TransactionRoundDefiningUpdate::getOrigin
	 */
	public function testRunOuterScopeUpdate() {
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$this->assertFalse( $lbFactory->hasTransactionRound(), 'Initial state' );

		$manager = $this->getManager();

		$ran = 0;
		$manager->addUpdate( new TransactionRoundDefiningUpdate(
			function () use ( &$ran, $lbFactory ) {
				$ran++;
				$this->assertFalse( $lbFactory->hasTransactionRound(), 'No transaction' );
			} )
		);
		$manager->doUpdates();

		$this->assertSame( 1, $ran, 'Update ran' );
	}

	/**
	 * @covers ::tryOpportunisticExecute
	 * @covers ::doUpdates
	 * @covers DeferredUpdatesScopeStack
	 * @covers DeferredUpdatesScope
	 */
	public function testTryOpportunisticExecute() {
		$calls = [];
		$callback1 = static function () use ( &$calls ) {
			$calls[] = 1;
		};
		$callback2 = static function () use ( &$calls ) {
			$calls[] = 2;
		};

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->beginPrimaryChanges( __METHOD__ );

		$manager = $this->getManager();
		$manager->addCallableUpdate( $callback1 );
		$this->assertEquals( [], $calls );

		$manager->tryOpportunisticExecute();
		$this->assertEquals( [], $calls );

		$dbw = $this->getDb();
		$dbw->onTransactionCommitOrIdle( function () use ( &$calls, $callback2, $manager ) {
			$manager->addCallableUpdate( $callback2 );
			$this->assertEquals( [], $calls );
			$calls[] = 'oti';
		} );
		$this->assertSame( 1, $dbw->trxLevel() );
		$this->assertEquals( [], $calls );

		$lbFactory->commitPrimaryChanges( __METHOD__ );

		$this->assertEquals( [ 'oti' ], $calls );

		$manager->tryOpportunisticExecute();
		$this->assertEquals( [ 'oti', 1, 2 ], $calls );
	}

	/**
	 * @covers ::attemptUpdate
	 */
	public function testCallbackUpdateRounds() {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		$fname = __METHOD__;
		$called = false;
		$manager = $this->getManager();
		$manager->attemptUpdate(
			new MWCallableUpdate(
				static function () use ( $lbFactory, $fname, &$called ) {
					$lbFactory->flushReplicaSnapshots( $fname );
					$lbFactory->commitPrimaryChanges( $fname );
					$called = true;
				},
				$fname
			),
			$lbFactory
		);

		$this->assertTrue( $called, "Callback ran" );
	}

	/**
	 * @covers ::doUpdates
	 * @covers DeferredUpdatesScopeStack
	 * @covers DeferredUpdatesScope
	 */
	public function testNestedExecution() {
		// No immediate execution
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$res = null;
		$resSub = null;
		$resSubSub = null;
		$resA = null;

		$manager = $this->getManager();
		$manager->clearPendingUpdates();

		$this->assertSame( 0, $manager->pendingUpdatesCount() );
		$this->assertSame( 0, $manager->getRecursiveExecutionStackDepth() );

		// T249069: TransactionRoundDefiningUpdate => JobRunner => DeferredUpdatesManager::doUpdates()
		$manager->addUpdate( new TransactionRoundDefiningUpdate(
			function () use ( &$res, &$resSub, &$resSubSub, &$resA, $manager ) {
				$res = 1;

				$this->assertSame( 0, $manager->pendingUpdatesCount() );
				$this->assertSame( 1, $manager->getRecursiveExecutionStackDepth() );

				// Add update to subqueue of in-progress top-queue job
				$manager->addCallableUpdate( function () use ( &$resSub, &$resSubSub, $manager ) {
					$resSub = 'a';

					$this->assertSame( 0, $manager->pendingUpdatesCount() );
					$this->assertSame( 2, $manager->getRecursiveExecutionStackDepth() );

					// Add update to subqueue of in-progress top-queue job (not recursive)
					$manager->addCallableUpdate( static function () use ( &$resSubSub ) {
						$resSubSub = 'b';
					} );

					$this->assertSame( 1, $manager->pendingUpdatesCount() );
				} );

				$this->assertSame( 1, $manager->pendingUpdatesCount() );
				$this->assertSame( 1, $manager->getRecursiveExecutionStackDepth() );

				if ( $resSub === null && $resA === null && $resSubSub === null ) {
					$res = 418;
				}

				$manager->doUpdates();
			}
		) );

		$this->assertSame( 1, $manager->pendingUpdatesCount() );
		$this->assertSame( 0, $manager->getRecursiveExecutionStackDepth() );

		$manager->addCallableUpdate( static function () use ( &$resA ) {
			$resA = 93;
		} );

		$this->assertSame( 2, $manager->pendingUpdatesCount() );
		$this->assertSame( 0, $manager->getRecursiveExecutionStackDepth() );

		$this->assertNull( $resA );
		$this->assertNull( $res );
		$this->assertNull( $resSub );
		$this->assertNull( $resSubSub );

		$manager->doUpdates();

		$this->assertSame( 0, $manager->pendingUpdatesCount() );
		$this->assertSame( 0, $manager->getRecursiveExecutionStackDepth() );
		$this->assertSame( 418, $res );
		$this->assertSame( 'a', $resSub );
		$this->assertSame( 'b', $resSubSub );
		$this->assertSame( 93, $resA );
	}
}
