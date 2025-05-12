<?php

use MediaWiki\Password\PasswordError;
use MediaWiki\Password\PasswordFactory;

abstract class Pbkdf2PasswordTestCase extends PasswordTestCase {
	abstract protected static function getPbkdf2PasswordClass();

	protected static function getTypeConfigs() {
		return [ 'pbkdf2' => [
			'class' => static::getPbkdf2PasswordClass(),
			'algo' => 'sha256',
			'cost' => '1000',
			'length' => '128',
		] ];
	}

	public static function providePasswordTests() {
		return [
			[ true, ":pbkdf2:sha512:1:20:c2FsdA==:hn9wzxreAs/zdSWZo6U9xK80x6Y=", 'password' ],
			[ true, ":pbkdf2:sha512:2:20:c2FsdA==:4dnBaqaBcIpF9cfE4hXOtm4BGi4=", 'password' ],
			[ true, ":pbkdf2:sha512:4096:20:c2FsdA==:0Zexsz2wFD4BixLz0dFHnmzevcw=", 'password' ],
			[ true, ":pbkdf2:sha512:4096:16:c2EAbHQ=:nZ6cTNIf5L4k1bgkTHWWZQ==", "pass\x00word" ],
		];
	}

	public function testCryptThrowsOnInvalidAlgo() {
		$factory = new PasswordFactory();
		$class = static::getPbkdf2PasswordClass();
		$password = new $class(
			$factory,
			[
				'type' => 'pbkdf2',
				'algo' => 'fail',
				'cost' => '10000',
				'length' => '128',
			]
		);
		$this->expectException( PasswordError::class );
		$this->expectExceptionMessage( 'Unknown or unsupported algo: fail' );
		$password->crypt( 'whatever' );
	}

	public function testCryptThrowsOnInvalidCost() {
		$factory = new PasswordFactory();
		$class = static::getPbkdf2PasswordClass();
		$password = new $class(
			$factory,
			[
				'type' => 'pbkdf2',
				'algo' => 'sha256',
				'cost' => '0',
				'length' => '128',
			]
		);
		$this->expectException( PasswordError::class );
		$this->expectExceptionMessage( 'Invalid number of rounds.' );
		$password->crypt( 'whatever' );
	}

	public function testCryptThrowsOnInvalidLength() {
		$factory = new PasswordFactory();
		$class = static::getPbkdf2PasswordClass();
		$password = new $class(
			$factory,
			[
				'type' => 'pbkdf2',
				'algo' => 'sha256',
				'cost' => '10000',
				'length' => '0',
			]
		);
		$this->expectException( PasswordError::class );
		$this->expectExceptionMessage( 'Invalid length.' );
		$password->crypt( 'whatever' );
	}
}
