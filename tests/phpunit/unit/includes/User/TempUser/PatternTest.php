<?php

namespace MediaWiki\Tests\User\TempUser;

use MediaWiki\User\TempUser\Pattern;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \MediaWiki\User\TempUser\Pattern
 */
class PatternTest extends TestCase {
	use MediaWikiCoversValidator;

	public function testInvalid() {
		$this->expectException( RuntimeException::class );
		$pattern = new Pattern( 'test', 'test' );
		$pattern->isMatch( 'test' );
	}

	public static function provideIsMatch() {
		return [
			'prefix mismatch' => [
				'pattern' => '*$1',
				'name' => 'Test',
				'expected' => false,
			],
			'prefix match' => [
				'pattern' => '*$1',
				'name' => '*Some user',
				'expected' => true,
			],
			'suffix only match' => [
				'pattern' => '$1*',
				'name' => 'Some user*',
				'expected' => true,
			],
			'suffix only mismatch' => [
				'pattern' => '$1*',
				'name' => 'Some user',
				'expected' => false,
			],
			'prefix and suffix match' => [
				'pattern' => '*$1*',
				'name' => '*Unregistered 123*',
				'expected' => true,
			],
			'prefix and suffix mismatch' => [
				'pattern' => '*$1*',
				'name' => 'Unregistered 123*',
				'expected' => false,
			],
			'prefix and suffix zero length match' => [
				'pattern' => '*$1*',
				'name' => '**',
				'expected' => true,
			],
			'prefix and suffix overlapping' => [
				'pattern' => '*$1*',
				'name' => '*',
				'expected' => false,
			],
		];
	}

	/** @dataProvider provideIsMatch */
	public function testIsMatch( $stringPattern, $name, $expected ) {
		$pattern = new Pattern( 'test', $stringPattern );
		$this->assertSame( $expected, $pattern->isMatch( $name ) );
	}

	public static function provideGenerate() {
		return [
			'prefix' => [
				'pattern' => 'x$1',
				'serial' => 'y',
				'expected' => 'xy',
			],
			'suffix' => [
				'pattern' => '$1x',
				'serial' => 'y',
				'expected' => 'yx',
			],
			'both' => [
				'pattern' => '*Unregistered $1*',
				'serial' => '123',
				'expected' => '*Unregistered 123*'
			]
		];
	}

	/** @dataProvider provideGenerate */
	public function testGenerate( $stringPattern, $serial, $expected ) {
		$pattern = new Pattern( 'test', $stringPattern );
		$this->assertSame( $expected, $pattern->generate( $serial ) );
	}

	public function testGenerateWithYear() {
		$pattern = new Pattern( 'test', '*$1*' );
		$this->assertSame(
			'*2000-123*',
			$pattern->generate( '123', '2000' )
		);
	}

	public function testToString() {
		$pattern = new Pattern( 'test', '*$1' );
		$this->assertSame( '*$1', (string)$pattern );
	}
}
