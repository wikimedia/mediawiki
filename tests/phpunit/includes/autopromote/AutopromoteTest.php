<?php

/**
 * @covers Autopromote
 */
class AutopromoteTest extends MediaWikiTestCase {

	public function testAutopromote() {
		$this->setMwGlobals( 'wgAutopromote', [
			'test' => [ '&',
				[ APCOND_EDITCOUNT, 0 ],
				[ '|',
					[ APCOND_AGE, 24 * 60 * 60 * 1000 ],
					[ APCOND_AGE_FROM_EDIT, 6 * 60 * 60 * 1000 ],
				],
				[ APCOND_INGROUPS, 'sysop' ],
				[ '^',
					[ APCOND_ISIP, '127.0.0.1' ],
					[ APCOND_IPINRANGE, '127.0.0.1/16' ],
				],
				[ '!',
					[ APCOND_BLOCKED ],
					[ APCOND_ISBOT ],
					[ APCOND_EMAILCONFIRMED ],
				],
			],
		] );

		$this->assertEquals(
			[],
			Autopromote::getAutopromoteGroups( new User ),
			'Autopromoted to test group'
		);
	}

	public function testAutopromoteOnce() {
		$this->setMwGlobals( 'wgAutopromoteOnce', [
			'once' => [ '&',
				[ APCOND_EDITCOUNT, 100 ],
				[ APCOND_AGE, 24 * 60 * 60 * 1000 ],
			],
		] );

		$this->assertEquals(
			[],
			Autopromote::getAutopromoteOnceGroups( new User, 'edit' ),
			'Autopromoted to test group'
		);
	}

	/**
	 * Test if all handlers exists
	 */
	public function testHandlersInAutoload() {
		global $wgAutoloadLocalClasses, $wgAutoloadClasses, $wgAutopromoteConditionHandlers;

		// wgAutoloadLocalClasses has precedence, just like in includes/AutoLoader.php
		$classes = $wgAutoloadLocalClasses + $wgAutoloadClasses;

		foreach ( $wgAutopromoteConditionHandlers as $name => $class ) {
			$this->assertArrayHasKey(
				$class,
				$classes,
				'Class ' . $class . ' for autopromote handler ' . $name . ' not in autoloader (with exact case)'
			);
		}
	}
}
