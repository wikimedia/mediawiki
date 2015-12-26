<?php

/**
 * @covers Action
 *
 * @author Thiemo Mättig
 *
 * @group Action
 * @group Database
 */
class ActionTest extends MediaWikiTestCase {

	public function testAutopromote() {
		$this->setMwGlobals( 'wgAutopromote', array(
			'test' => array( '&',
   				array( APCOND_EDITCOUNT, 0 ),
   				array( '|',
					array( APCOND_AGE, 24 * 60 * 60 * 1000 ),
					array( APCOND_AGE_FROM_EDIT, 6 * 60 * 60 * 1000 ),
				),
				array( APCOND_INGROUPS, 'sysop' ),
				array( '^',
					array( APCOND_ISIP, '127.0.0.1' ),
					array( APCOND_IPINRANGE, '127.0.0.1/16' ),
				),
				array( '!',
					array( APCOND_BLOCKED ),
					array( APCOND_ISBOT ),
 					array( APCOND_EMAILCONFIRMED ),
				),
			),
		) );

		$this->assertEquals(
			array(),
			Autopromote::getAutopromoteGroups( new User ),
			'Autopromoted to test group'
		);
	}

	public function testAutopromoteOnce() {
		$this->setMwGlobals( 'wgAutopromoteOnce', array(
			'once' => array( '&',
   				array( APCOND_EDITCOUNT, 100 ),
				array( APCOND_AGE, 24 * 60 * 60 * 1000 ),
			),
		) );

		$this->assertEquals(
			array(),
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
