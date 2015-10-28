<?php
/**
 * @group BagOStuff
 */
class MemcachedBagOStuffTest extends MediaWikiTestCase {
	/** @var MemcachedBagOStuff */
	private $cache;

	protected function setUp() {
		parent::setUp();
		$this->cache = new MemcachedBagOStuff( array( 'keyspace' => 'test' ) );
	}

	/**
	 * @covers MemcachedBagOStuff::makeKeyInternal
	 */
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
			'test:this:key:contains:#c118f92685a635cb843039de50014c9c',
			$this->cache->makeKey( 'this', 'key', 'contains', '𝕥𝕠𝕠 𝕞𝕒𝕟𝕪 𝕞𝕦𝕝𝕥𝕚𝕓𝕪𝕥𝕖 𝕔𝕙𝕒𝕣𝕒𝕔𝕥𝕖𝕣𝕤' )
		);

		$this->assertEquals(
			'test:##5820ad1d105aa4dc698585c39df73e19',
			$this->cache->makeKey( '𝕖𝕧𝕖𝕟', '𝕚𝕗', '𝕨𝕖', '𝕄𝔻𝟝', '𝕖𝕒𝕔𝕙',
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
			'test:long_key_part_hashed:#0244f7b1811d982dd932dd7de01465ac',
			$this->cache->makeKey( 'long_key_part_hashed', str_repeat( 'y', 500 ) )
		);
	}

	/**
	 * @dataProvider validKeyProvider
	 */
	public function testValidateKeyEncoding( $key ) {
		$this->assertSame( $key, $this->cache->validateKeyEncoding( $key ) );
	}

	public function validKeyProvider() {
		return array(
			'empty' => array( '' ),
			'space' => array( ' ' ),
			'digits' => array( '09' ),
			'letters' => array( 'AZaz' ),
			'ASCII special characters' => array( '!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~' ),
		);
	}

	/**
	 * @dataProvider invalidKeyProvider
	 */
	public function testValidateKeyEncodingThrowsException( $key ) {
		$this->setExpectedException( 'Exception' );
		$this->cache->validateKeyEncoding( $key );
	}

	public function invalidKeyProvider() {
		return array(
			array( "\x00" ),
			array( "\x1F" ),
			array( "\x7F" ),
			array( "\x80" ),
			array( "\xFF" ),
		);
	}
}
