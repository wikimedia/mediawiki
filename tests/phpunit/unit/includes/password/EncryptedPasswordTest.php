<?php

use MediaWiki\Password\EncryptedPassword;
use MediaWiki\Password\PasswordError;
use MediaWiki\Password\Pbkdf2PasswordUsingHashExtension;

/**
 * @covers \MediaWiki\Password\EncryptedPassword
 * @covers \MediaWiki\Password\ParameterizedPassword
 * @covers \MediaWiki\Password\Password
 */
class EncryptedPasswordTest extends PasswordTestCase {
	protected static function getTypeConfigs() {
		return [
			'both' => [
				'class' => EncryptedPassword::class,
				'underlying' => 'pbkdf2',
				'secrets' => [
					md5( 'secret1' ),
					md5( 'secret2' ),
				],
				'cipher' => 'aes-256-cbc',
			],
			'secret1' => [
				'class' => EncryptedPassword::class,
				'underlying' => 'pbkdf2',
				'secrets' => [
					md5( 'secret1' ),
				],
				'cipher' => 'aes-256-cbc',
			],
			'secret2' => [
				'class' => EncryptedPassword::class,
				'underlying' => 'pbkdf2',
				'secrets' => [
					md5( 'secret2' ),
				],
				'cipher' => 'aes-256-cbc',
			],
			'pbkdf2' => [
				'class' => Pbkdf2PasswordUsingHashExtension::class,
				'algo' => 'sha256',
				'cost' => '10',
				'length' => '64',
			],
		];
	}

	public static function providePasswordTests() {
		return [
			// Encrypted with secret1
			[ true, ':both:aes-256-cbc:0:izBpxujqC1YbzpCB3qAzgg==:ZqHnitT1pL4YJqKqFES2KEevZYSy2LtlibW5+IMi4XKOGKGy6sE638BXyBbLQQsBtTSrt+JyzwOayKtwIfRbaQsBridx/O1JwBSai1TkGkOsYMBXnlu2Bu/EquCBj5QpjYh7p3Uq4rpiop1KQlin1BJMwnAa1PovhxjpxnYhlhkM4X5ALoGi3XM0bapN48vt', 'password' ],
			[ true, ':secret1:aes-256-cbc:0:izBpxujqC1YbzpCB3qAzgg==:ZqHnitT1pL4YJqKqFES2KEevZYSy2LtlibW5+IMi4XKOGKGy6sE638BXyBbLQQsBtTSrt+JyzwOayKtwIfRbaQsBridx/O1JwBSai1TkGkOsYMBXnlu2Bu/EquCBj5QpjYh7p3Uq4rpiop1KQlin1BJMwnAa1PovhxjpxnYhlhkM4X5ALoGi3XM0bapN48vt', 'password' ],
			[ false, ':secret1:aes-256-cbc:0:izBpxujqC1YbzpCB3qAzgg==:ZqHnitT1pL4YJqKqFES2KEevZYSy2LtlibW5+IMi4XKOGKGy6sE638BXyBbLQQsBtTSrt+JyzwOayKtwIfRbaQsBridx/O1JwBSai1TkGkOsYMBXnlu2Bu/EquCBj5QpjYh7p3Uq4rpiop1KQlin1BJMwnAa1PovhxjpxnYhlhkM4X5ALoGi3XM0bapN48vt', 'notpassword' ],

			// Encrypted with secret2
			[ true, ':both:aes-256-cbc:1:m1LCnQVIakfYBNlr9KEgQg==:5yPTctqrzsybdgaMEag18AZYbnL37pAtXVBqmWxkjXbnNmiDH+1bHoL8lsEVTH/sJntC82kNVgE7zeiD8xUVLYF2VUnvB5+sU+aysE45/zwsCu7a22TaischMAOWrsHZ/tIgS/TnZY2d+HNyxgsEeeYf/QoL+FhmqHquK02+4SRbA5lLuj9niYy1r5CoM9cQ', 'password' ],
			[ true, ':secret2:aes-256-cbc:0:m1LCnQVIakfYBNlr9KEgQg==:5yPTctqrzsybdgaMEag18AZYbnL37pAtXVBqmWxkjXbnNmiDH+1bHoL8lsEVTH/sJntC82kNVgE7zeiD8xUVLYF2VUnvB5+sU+aysE45/zwsCu7a22TaischMAOWrsHZ/tIgS/TnZY2d+HNyxgsEeeYf/QoL+FhmqHquK02+4SRbA5lLuj9niYy1r5CoM9cQ', 'password' ],
		];
		// phpcs:enable
	}

	/**
	 * Wrong encryption key selected
	 */
	public function testDecryptionError() {
		$hash = ':secret1:aes-256-cbc:0:m1LCnQVIakfYBNlr9KEgQg==:5yPTctqrzsybdgaMEag18AZYbnL37pAtXVBqmWxkjXbnNmiDH+1bHoL8lsEVTH/sJntC82kNVgE7zeiD8xUVLYF2VUnvB5+sU+aysE45/zwsCu7a22TaischMAOWrsHZ/tIgS/TnZY2d+HNyxgsEeeYf/QoL+FhmqHquK02+4SRbA5lLuj9niYy1r5CoM9cQ';
		$password = $this->passwordFactory->newFromCiphertext( $hash );
		$this->expectException( PasswordError::class );
		$password->crypt( 'password' );
	}

	public function testUpdate() {
		$hash = ':both:aes-256-cbc:0:izBpxujqC1YbzpCB3qAzgg==:ZqHnitT1pL4YJqKqFES2KEevZYSy2LtlibW5+IMi4XKOGKGy6sE638BXyBbLQQsBtTSrt+JyzwOayKtwIfRbaQsBridx/O1JwBSai1TkGkOsYMBXnlu2Bu/EquCBj5QpjYh7p3Uq4rpiop1KQlin1BJMwnAa1PovhxjpxnYhlhkM4X5ALoGi3XM0bapN48vt';
		$fromHash = $this->passwordFactory->newFromCiphertext( $hash );
		$this->assertTrue( $fromHash->needsUpdate() );
		$this->assertTrue( $fromHash->update() );

		$serialized = $fromHash->toString();
		$this->assertMatchesRegularExpression( '/^:both:aes-256-cbc:1:/', $serialized );
		$fromNewHash = $this->passwordFactory->newFromCiphertext( $serialized );
		$fromPlaintext = $this->passwordFactory->newFromPlaintext( 'password', $fromNewHash );
		$this->assertTrue( $fromPlaintext->verify( 'password' ) );
		$this->assertTrue( $fromHash->verify( 'password' ) );
		$this->assertFalse( $fromPlaintext->needsUpdate() );
	}
}
