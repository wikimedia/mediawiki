<?php

class PasswordTest extends MediaWikiTestCase {
	public function testCompareEmpty() {
		$password = new Password();
		$this->assertFalse( $password->compare( '' ) );
	}

	public function testCompareSelf() {
		$password = new Password();
		$password->update( 'password123' );
		$this->assertTrue( $password->compare( 'password123' ) );
	}

	/**
	 * @dataProvider provideTestHashes
	 * @group large
	 */
	public function testCompareHash( $plaintext, $hash ) {
		try {
			$password = new Password( $hash );
			$this->assertEquals( $hash, (string) $password );
			$this->assertTrue( $password->compare( $plaintext ) );
		} catch( PasswordError $e ) {
			$this->assertFalse( $plaintext );
		}
	}

	/**
	 * @dataProvider provideNondefaultTypes
	 */
	public function testTypeUpdate( $type ) {
		$tests = $type::tests();
		foreach( $tests as $test ) {
			if( $test[0] === false ) {
				continue;
			}

			list( $plaintext, $hash ) = $test;
			$password = new Password( $hash );
			$this->assertTrue( $password->needsUpdate() );

			$password->update( $plaintext );
			$this->assertFalse( $password->needsUpdate() );
		}
	}

	public function provideNondefaultTypes() {
		Hash::init();
		$types = Hash::getTypes();
		$default = Hash::$default;
		$nondefaults = array_diff($types, array( $default ) );

		foreach( $nondefaults as &$item ) {
			$item = array( $item );
		}
		return $nondefaults;
	}

	public function provideTestHashes() {
		Hash::init();
		$vectors = array();
		$types = Hash::getTypes();
		foreach( $types as $type ) {
			$tests = $type::tests();
			$vectors = array_merge( $vectors, $tests );
		}
		return $vectors;
	}
}
