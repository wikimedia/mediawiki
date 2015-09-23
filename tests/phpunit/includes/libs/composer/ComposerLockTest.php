<?php

class ComposerLockTest extends MediaWikiTestCase {

	private $lock;

	public function setUp() {
		parent::setUp();
		global $IP;
		$this->lock = "$IP/tests/phpunit/data/composer/composer.lock";
	}

	/**
	 * @covers ComposerLock::getHash
	 */
	public function testGetHash() {
		$lock = new ComposerLock( $this->lock );
		$this->assertEquals( 'a3bb80b0ac4c4a31e52574d48c032923', $lock->getHash() );
	}

	/**
	 * @covers ComposerLock::getInstalledDependencies
	 */
	public function testGetInstalledDependencies() {
		$lock = new ComposerLock( $this->lock );
		$this->assertArrayEquals( array(
			'wikimedia/cdb' => array(
				'version' => '1.0.1',
				'type' => 'library',
				'licenses' => array( 'GPL-2.0' ),
				'authors' => array(
					array(
						'name' => 'Tim Starling',
						'email' => 'tstarling@wikimedia.org',
					),
					array(
						'name' => 'Chad Horohoe',
						'email' => 'chad@wikimedia.org',
					),
				),
				'description' => 'Constant Database (CDB) wrapper library for PHP. Provides pure-PHP fallback when dba_* functions are absent.',
			),
			'cssjanus/cssjanus' => array(
				'version' => '1.1.1',
				'type' => 'library',
				'licenses' => array( 'Apache-2.0' ),
				'authors' => array(),
				'description' => 'Convert CSS stylesheets between left-to-right and right-to-left.',
			),
			'leafo/lessphp' => array(
				'version' => '0.5.0',
				'type' => 'library',
				'licenses' => array( 'MIT', 'GPL-3.0' ),
				'authors' => array(
					array(
						'name' => 'Leaf Corcoran',
						'email' => 'leafot@gmail.com',
						'homepage' => 'http://leafo.net',
					),
				),
				'description' => 'lessphp is a compiler for LESS written in PHP.',
			),
			'psr/log' => array(
				'version' => '1.0.0',
				'type' => 'library',
				'licenses' => array( 'MIT' ),
				'authors' => array(
					array(
						'name' => 'PHP-FIG',
						'homepage' => 'http://www.php-fig.org/',
					),
				),
				'description' => 'Common interface for logging libraries',
			),
			'oojs/oojs-ui' => array(
				'version' => '0.6.0',
				'type' => 'library',
				'licenses' => array( 'MIT' ),
				'authors' => array(),
				'description' => '',
			),
			'composer/installers' => array(
				'version' => '1.0.19',
				'type' => 'composer-installer',
				'licenses' => array( 'MIT' ),
				'authors' => array(
					array(
						'name' => 'Kyle Robinson Young',
						'email' => 'kyle@dontkry.com',
						'homepage' => 'https://github.com/shama',
					),
				),
				'description' => 'A multi-framework Composer library installer',
			),
			'mediawiki/translate' => array(
				'version' => '2014.12',
				'type' => 'mediawiki-extension',
				'licenses' => array( 'GPL-2.0+' ),
				'authors' => array(
					array(
						'name' => 'Niklas Laxström',
						'email' => 'niklas.laxstrom@gmail.com',
						'role' => 'Lead nitpicker',
					),
					array(
						'name' => 'Siebrand Mazeland',
						'email' => 's.mazeland@xs4all.nl',
						'role' => 'Developer',
					),
				),
				'description' => 'The only standard solution to translate any kind of text with an avant-garde web interface within MediaWiki, including your documentation and software',
			),
			'mediawiki/universal-language-selector' => array(
				'version' => '2014.12',
				'type' => 'mediawiki-extension',
				'licenses' => array( 'GPL-2.0+', 'MIT' ),
				'authors' => array(),
				'description' => 'The primary aim is to allow users to select a language and configure its support in an easy way. Main features are language selection, input methods and web fonts.',
			),
		), $lock->getInstalledDependencies(), false, true );
	}

}
