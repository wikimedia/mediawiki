<?php

namespace MediaWiki\Tests\User;

use MediaWiki\User\Options\StaticUserOptionsLookup;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\Options\StaticUserOptionsLookup
 */
class StaticUserOptionsLookupTest extends MediaWikiUnitTestCase {

	public function testGetDefaultOptions() {
		$lookup = new StaticUserOptionsLookup( [], [ 'opt1' => 'val1', 'opt2' => 'val2' ] );
		$this->assertSame( [ 'opt1' => 'val1', 'opt2' => 'val2' ], $lookup->getDefaultOptions() );
	}

	public function testGetDefaultOption() {
		$lookup = new StaticUserOptionsLookup( [], [ 'opt1' => 'val1', 'opt2' => 'val2' ] );
		$this->assertSame( 'val1', $lookup->getDefaultOption( 'opt1' ) );
		$this->assertSame( 'val2', $lookup->getDefaultOption( 'opt2' ) );
		$this->assertNull( $lookup->getDefaultOption( 'opt3' ) );
	}

	public function testGetOption() {
		$lookup = new StaticUserOptionsLookup(
			[
				'User1' => [ 'opt1' => 'val1', 'opt2' => 'val2' ],
				'User2' => [ 'opt2' => 'val2b', 'opt3' => 'val3b' ]
			],
			[ 'opt1' => 'val1def', 'opt4' => 'val4def' ]
		);
		$user1 = new UserIdentityValue( 1, 'User1' );
		$user2 = new UserIdentityValue( 2, 'User2' );
		$user3 = new UserIdentityValue( 3, 'User3' );
		$fakeUser1 = new UserIdentityValue( 0, 'User1' );

		$this->assertSame( 'val1', $lookup->getOption( $user1, 'opt1' ) );
		$this->assertSame( 'val1', $lookup->getOption( $user1, 'opt1', 'def' ) );
		$this->assertSame( 'val2', $lookup->getOption( $user1, 'opt2' ) );
		$this->assertSame( 'val2', $lookup->getOption( $user1, 'opt2', 'def' ) );
		$this->assertSame( null, $lookup->getOption( $user1, 'opt3' ) );
		$this->assertSame( 'def', $lookup->getOption( $user1, 'opt3', 'def' ) );
		$this->assertSame( 'val4def', $lookup->getOption( $user1, 'opt4' ) );
		$this->assertSame( 'val4def', $lookup->getOption( $user1, 'opt4', 'def' ) );

		$this->assertSame( 'val1def', $lookup->getOption( $user2, 'opt1' ) );
		$this->assertSame( 'val1def', $lookup->getOption( $user2, 'opt1', 'def' ) );
		$this->assertSame( 'val2b', $lookup->getOption( $user2, 'opt2' ) );
		$this->assertSame( 'val2b', $lookup->getOption( $user2, 'opt2', 'def' ) );
		$this->assertSame( 'val3b', $lookup->getOption( $user2, 'opt3' ) );
		$this->assertSame( 'val3b', $lookup->getOption( $user2, 'opt3', 'def' ) );

		$this->assertSame( 'val1def', $lookup->getOption( $user3, 'opt1' ) );
		$this->assertSame( 'val1def', $lookup->getOption( $user3, 'opt1', 'def' ) );
		$this->assertSame( null, $lookup->getOption( $user3, 'opt2' ) );
		$this->assertSame( 'def', $lookup->getOption( $user3, 'opt2', 'def' ) );
		$this->assertSame( null, $lookup->getOption( $user3, 'opt3' ) );
		$this->assertSame( 'def', $lookup->getOption( $user3, 'opt3', 'def' ) );
		$this->assertSame( 'val4def', $lookup->getOption( $user3, 'opt4' ) );
		$this->assertSame( 'val4def', $lookup->getOption( $user3, 'opt4', 'def' ) );

		$this->assertSame( 'val1def', $lookup->getOption( $fakeUser1, 'opt1' ) );
		$this->assertSame( 'val1def', $lookup->getOption( $fakeUser1, 'opt1', 'def' ) );
		$this->assertSame( null, $lookup->getOption( $fakeUser1, 'opt2' ) );
		$this->assertSame( 'def', $lookup->getOption( $fakeUser1, 'opt2', 'def' ) );
	}

	public function testGetOptions() {
		$lookup = new StaticUserOptionsLookup(
			[
				'User1' => [ 'opt1' => 'val1', 'opt2' => 'val2' ],
				'User2' => [ 'opt2' => 'val2b', 'opt3' => 'val3b' ]
			],
			[ 'opt1' => 'val1def', 'opt4' => 'val4def' ]
		);
		$user1 = new UserIdentityValue( 1, 'User1' );
		$user2 = new UserIdentityValue( 2, 'User2' );
		$user3 = new UserIdentityValue( 3, 'User3' );
		$fakeUser1 = new UserIdentityValue( 0, 'User1' );

		$this->assertArrayEquals( [ 'opt1' => 'val1', 'opt2' => 'val2', 'opt4' => 'val4def' ],
			$lookup->getOptions( $user1 ) );
		$this->assertArrayEquals( [ 'opt1' => 'val1', 'opt2' => 'val2' ],
			$lookup->getOptions( $user1, UserOptionsLookup::EXCLUDE_DEFAULTS ) );

		$this->assertArrayEquals(
			[ 'opt1' => 'val1def', 'opt2' => 'val2b', 'opt3' => 'val3b', 'opt4' => 'val4def' ],
			$lookup->getOptions( $user2 ) );
		$this->assertArrayEquals( [ 'opt2' => 'val2b', 'opt3' => 'val3b' ],
			$lookup->getOptions( $user2, UserOptionsLookup::EXCLUDE_DEFAULTS ) );

		$this->assertArrayEquals( [ 'opt1' => 'val1def', 'opt4' => 'val4def' ],
			$lookup->getOptions( $user3 ) );
		$this->assertArrayEquals( [],
			$lookup->getOptions( $user3, UserOptionsLookup::EXCLUDE_DEFAULTS ) );

		$this->assertArrayEquals( [ 'opt1' => 'val1def', 'opt4' => 'val4def' ],
			$lookup->getOptions( $fakeUser1 ) );
		$this->assertArrayEquals( [],
			$lookup->getOptions( $fakeUser1, UserOptionsLookup::EXCLUDE_DEFAULTS ) );
	}

}
