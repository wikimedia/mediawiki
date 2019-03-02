<?php

class PasswordPolicyStructureTest extends MediaWikiTestCase {

	public function provideChecks() {
		global $wgPasswordPolicy;

		foreach ( $wgPasswordPolicy['checks'] as $name => $callback ) {
			yield [ $name ];
		}
	}

	public function provideFlags() {
		global $wgPasswordPolicy;

		// This won't actually find all flags, just the ones in use. Can't really be helped,
		// other than adding the core flags here.
		$flags = [ 'forceChange', 'suggestChangeOnLogin' ];
		foreach ( $wgPasswordPolicy['policies'] as $group => $checks ) {
			foreach ( $checks as $check => $settings ) {
				if ( is_array( $settings ) ) {
					$flags = array_unique(
						array_merge( $flags, array_diff( array_keys( $settings ), [ 'value' ] ) )
					);
				}
			}
		}

		foreach ( $flags as $flag ) {
			yield [ $flag ];
		}
	}

	/** @dataProvider provideChecks */
	public function testCheckMessage( $check ) {
		$msg = wfMessage( 'passwordpolicies-policy-' . strtolower( $check ) );
		$this->assertTrue( $msg->exists() );
	}

	/** @dataProvider provideFlags */
	public function testFlagMessage( $flag ) {
		$msg = wfMessage( 'passwordpolicies-policyflag-' . strtolower( $flag ) );
		$this->assertTrue( $msg->exists() );
	}

}
