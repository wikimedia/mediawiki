<?php

use Wikimedia\ObjectCache\MemcachedBagOStuff;
use Wikimedia\ObjectCache\MemcachedPhpBagOStuff;

/**
 * @covers \Wikimedia\ObjectCache\MemcachedBagOStuff
 * @group BagOStuff
 */
class MemcachedBagOStuffTest extends \MediaWikiUnitTestCase {
	/** @var MemcachedBagOStuff */
	private $cache;

	protected function setUp(): void {
		parent::setUp();
		$this->cache = new MemcachedPhpBagOStuff( [ 'keyspace' => 'test', 'servers' => [] ] );
	}

	public function testKeyNormalization() {
		$this->assertEquals(
			'test:vanilla',
			$this->cache->makeKey( 'vanilla' )
		);

		$this->assertEquals(
			'test:punctuation_marks_are_ok:!@$^&*()',
			$this->cache->makeKey( 'punctuation_marks_are_ok', '!@$^&*()' )
		);

		$this->assertEquals(
			'test:but_spaces:hashes%23:and%0Anewlines:are_not',
			$this->cache->makeKey( 'but spaces', 'hashes#', "and\nnewlines", 'are_not' )
		);

		$this->assertEquals(
			'test:this:key:contains:%F0%9D%95%9E%F0%9D%95%A6%F0%9D%95%9D%F0%9D%95%A5%F0%9' .
				'D%95%9A%F0%9D%95%93%F0%9D%95%AA%F0%9D%95%A5%F0%9D%95%96:characters',
			$this->cache->makeKey( 'this', 'key', 'contains', '𝕞𝕦𝕝𝕥𝕚𝕓𝕪𝕥𝕖', 'characters' )
		);

		$this->assertEquals(
			'test:this:#457c1ec5348b0320a2e073a73d26bda354aaa367c371c8ca0f5d116ac033b5b8',
			$this->cache->makeKey( 'this', 'key', 'contains', '𝕥𝕠𝕠 𝕞𝕒𝕟𝕪 𝕞𝕦𝕝𝕥𝕚𝕓𝕪𝕥𝕖 𝕔𝕙𝕒𝕣𝕒𝕔𝕥𝕖𝕣𝕤' )
		);

		$this->assertEquals(
			'test:doublestruck:#913632ba0d544f1df98b75e541a66bc703db65a0cb4457079ea3592ffc7eacef',
			$this->cache->makeKey( 'doublestruck', '𝕖𝕧𝕖𝕟', '𝕚𝕗', '𝕨𝕖', '𝕙𝕒𝕤𝕙', '𝕖𝕒𝕔𝕙',
				'𝕒𝕣𝕘𝕦𝕞𝕖𝕟𝕥', '𝕥𝕙𝕚𝕤', '𝕜𝕖𝕪', '𝕨𝕠𝕦𝕝𝕕', '𝕤𝕥𝕚𝕝𝕝', '𝕓𝕖', '𝕥𝕠𝕠', '𝕝𝕠𝕟𝕘' )
		);

		$this->assertEquals(
			'test:BagOStuff-long-key:##d00de278a2c85e9768c150e8c7f004e0f8dd136a061a84fd541e7c4cfc83514f',
			$this->cache->makeKey( '𝕖𝕧𝕖𝕟 𝕚𝕗 𝕨𝕖 𝕙𝕒𝕤𝕙', '𝕖𝕒𝕔𝕙',
				'𝕒𝕣𝕘𝕦𝕞𝕖𝕟𝕥', '𝕥𝕙𝕚𝕤', '𝕜𝕖𝕪', '𝕨𝕠𝕦𝕝𝕕', '𝕤𝕥𝕚𝕝𝕝', '𝕓𝕖', '𝕥𝕠𝕠', '𝕝𝕠𝕟𝕘' )
		);

		$this->assertEquals(
			'test:%23%235820ad1d105aa4dc698585c39df73e19',
			$this->cache->makeKey( '##5820ad1d105aa4dc698585c39df73e19' )
		);

		$this->assertEquals(
			'test:percent_is_escaped:!@$%25^&*()',
			$this->cache->makeKey( 'percent_is_escaped', '!@$%^&*()' )
		);

		$this->assertEquals(
			'test:colon_is_escaped:!@$%3A^&*()',
			$this->cache->makeKey( 'colon_is_escaped', '!@$:^&*()' )
		);

		$this->assertEquals(
			'test:long_key_part_hashed:#1da89b61428deef9340719031384f9a915ac5a57599183fdfa319f6ab79911bd',
			$this->cache->makeKey( 'long_key_part_hashed', str_repeat( 'y', 500 ) )
		);
	}

	/**
	 * @dataProvider validKeyProvider
	 */
	public function testValidateKeyEncoding( $key ) {
		$this->assertSame( $key, $this->cache->validateKeyEncoding( $key ) );
	}

	public static function validKeyProvider() {
		return [
			'empty' => [ '' ],
			'digits' => [ '09' ],
			'letters' => [ 'AZaz' ],
			'ASCII special characters' => [ '!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~' ],
		];
	}

	/**
	 * @dataProvider invalidKeyProvider
	 */
	public function testValidateKeyEncodingThrowsException( $key ) {
		$this->expectException( Exception::class );
		$this->cache->validateKeyEncoding( $key );
	}

	public static function invalidKeyProvider() {
		return [
			[ "\x00" ],
			[ ' ' ],
			[ "\x1F" ],
			[ "\x7F" ],
			[ "\x80" ],
			[ "\xFF" ],
		];
	}
}
