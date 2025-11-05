<?php

namespace MediaWiki\Tests\Unit\Languages;

use LocalisationCache;
use MediaWiki\Language\Language;
use MediaWikiUnitTestCase;
use MessageCache;

/**
 * Validate the Messages*.php files
 * @coversNothing -- no way to cover non-class files
 */
class MessagesStructureTest extends MediaWikiUnitTestCase {
	/** @var string */
	private $langCode;
	/** @var array */
	private static $enData;

	public static function provideMessagesFiles() {
		$n = 0;
		foreach ( glob( MW_INSTALL_PATH . '/languages/messages/Messages*.php' ) as $path ) {
			$fileName = basename( $path );
			yield [ $fileName ];
			$n++;
		}
		if ( $n === 0 ) {
			throw new \UnderflowException( 'Not enough files' );
		}
	}

	/**
	 * @dataProvider provideMessagesFiles
	 * @param string $fileName
	 */
	public function testMessageFile( $fileName ) {
		$this->langCode = Language::getCodeFromFileName( $fileName, 'Messages' );

		// Like isValidBuiltInCode()
		$this->assertMatchesRegularExpression( '/^[a-z0-9-]{2,}$/', $this->langCode );

		// Check for unrecognised variable names
		$path = MW_INSTALL_PATH . '/languages/messages/' . $fileName;
		$vars = $this->readFile( $path );
		$unknownVars = array_diff(
			array_keys( $vars ),
			LocalisationCache::ALL_KEYS
		);
		$this->assertSame( [], $unknownVars, 'unknown variables' );

		foreach ( $vars as $name => $value ) {
			$method = 'validate' . ucfirst( $name );
			if ( method_exists( $this, $method ) ) {
				$this->$method( $value );
			}
		}
	}

	private function readFile( $_path ) {
		require $_path;
		$vars = get_defined_vars();
		unset( $vars['_path'] );
		return $vars;
	}

	private function getEnData() {
		if ( self::$enData === null ) {
			self::$enData = $this->readFile( MW_INSTALL_PATH . '/languages/messages/MessagesEn.php' );
		}
		return self::$enData;
	}

	private function validateFallback( $value ) {
		if ( $this->langCode === 'en' ) {
			$this->assertFalse( $value, 'fallback for en must be false' );
			return;
		}
		$fallbacks = array_map( 'trim', explode( ',', $value ) );
		$this->assertLessThanOrEqual(
			MessageCache::MAX_REQUEST_LANGUAGES - 2,
			count( $fallbacks ),
			'fallback chain is too long (T310532)'
		);
		foreach ( $fallbacks as $code ) {
			// Like isValidBuiltInCode()
			$this->assertMatchesRegularExpression( '/^[a-z0-9-]{2,}$/', $code );
		}
	}

	private function validateNamespaceNames( $names ) {
		$enNames = $this->getEnData()['namespaceNames'];
		$this->assertSame(
			[],
			array_diff( array_keys( $names ), array_keys( $enNames ) ),
			'unrecognised namespace IDs'
		);
		foreach ( $names as $id => $name ) {
			$this->assertIsString( $name );
			if ( $id !== NS_MAIN ) {
				$this->assertNotSame( '', $name );
			}
			$this->assertStringNotContainsString( ' ', $name, 'Use underscores in namespace names' );
		}
	}

	private function validateNamespaceAliases( $aliases ) {
		foreach ( $aliases as $alias => $id ) {
			$this->assertIsString( $alias );
			$this->assertNotSame( '', $alias );
			$this->assertStringNotContainsString( ' ', $alias, 'Use underscores in namespace aliases' );
		}
	}

	private function validateMagicWords( $magicWords ) {
		$enWords = $this->getEnData()['magicWords'];
		$this->assertSame(
			[],
			array_diff( array_keys( $magicWords ), array_keys( $enWords ) ),
			'unrecognised magic word IDs'
		);
		foreach ( $magicWords as $id => $parts ) {
			// Ideally the case should be an integer, but some script writes it as a string
			$case = $parts[0];
			$this->assertThat(
				[ 0, 1, '0', '1' ],
				$this->containsIdentical( $case ),
				"$id case should be 0 or 1"
			);
			array_shift( $parts );
			foreach ( $parts as $i => $syn ) {
				$this->assertIsString( $syn, "$id syn $i should be string" );
				$this->assertNotSame( '', $syn, "$id syn $i should not be empty" );
			}
			$canonical = $enWords[$id][1];
			$this->assertContains( $canonical, $parts,
				"$id should contain English synonym $canonical" );
		}
	}

	private function validateSpecialPageAliases( $pages ) {
		$enPages = $this->getEnData()['specialPageAliases'];
		$this->assertSame(
			[],
			array_diff( array_keys( $pages ), array_keys( $enPages ) ),
			'unrecognised special page IDs'
		);
		foreach ( $pages as $pageName => $aliases ) {
			foreach ( $aliases as $i => $alias ) {
				$this->assertIsString( $alias, "$pageName alias $i should be string" );
				$this->assertNotSame( '', $alias,
					"$pageName alias $i should not be empty" );
				$this->assertStringNotContainsString( ' ', $alias, 'Use underscores in specialpage alias' );
			}
		}
	}

	private function validateLinkTrail( $linkTrail ) {
		$this->assertIsString( $linkTrail );
		$result = preg_match( $linkTrail, 'test' );
		if ( $result === false ) {
			$this->fail( "linkTrail regex match failed with code " . preg_last_error() );
		}
	}

}
