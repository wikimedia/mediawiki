<?php

use MediaWiki\BadFileLookup;
use MediaWiki\MediaWikiServices;

/**
 * @coversDefaultClass MediaWiki\BadFileLookup
 */
class BadFileLookupTest extends MediaWikiUnitTestCase {
	/** Shared with GlobalWithDBTest */
	public const BAD_FILE_LIST = <<<WIKITEXT
Comment line, no effect [[File:Good.jpg]]
 * Indented list is also a comment [[File:Good.jpg]]
* [[File:Bad.jpg]] except [[Nasty page]]
*[[Image:Bad2.jpg]] also works
* So does [[Bad3.jpg]]
* [[User:Bad4.jpg]] works although it is silly
* [[File:Redirect to good.jpg]] doesn't do anything if RepoGroup is working, because we only look at
  the final name, but will work if RepoGroup returns null
* List line with no link
* [[Malformed title<>]] doesn't break anything, the line is ignored [[File:Good.jpg]]
* [[File:Bad5.jpg]] before [[malformed title<>]] doesn't ignore the line
WIKITEXT;

	/** Shared with GlobalWithDBTest */
	public static function badImageHook( $name, &$bad ) {
		switch ( $name ) {
			case 'Hook_bad.jpg':
			case 'Redirect_to_hook_good.jpg':
				$bad = true;
				return false;

			case 'Hook_good.jpg':
			case 'Redirect_to_hook_bad.jpg':
				$bad = false;
				return false;
		}
		return true;
	}

	private function getMockRepoGroup() {
		$mock = $this->createMock( RepoGroup::class );
		$mock->expects( $this->once() )->method( 'findFile' )
			->will( $this->returnCallback( function ( $name ) {
				$mockFile = $this->createMock( File::class );
				$mockFile->expects( $this->once() )->method( 'getTitle' )
					->will( $this->returnCallback( function () use ( $name ) {
						switch ( $name ) {
							case 'Redirect to bad.jpg':
								return new TitleValue( NS_FILE, 'Bad.jpg' );
							case 'Redirect_to_good.jpg':
								return new TitleValue( NS_FILE, 'Good.jpg' );
							case 'Redirect to hook bad.jpg':
								return new TitleValue( NS_FILE, 'Hook_bad.jpg' );
							case 'Redirect to hook good.jpg':
								return new TitleValue( NS_FILE, 'Hook_good.jpg' );
							default:
								return new TitleValue( NS_FILE, $name );
						}
					} ) );
				$mockFile->expects( $this->never() )->method( $this->anythingBut( 'getTitle' ) );
				return $mockFile;
			} ) );
		$mock->expects( $this->never() )->method( $this->anythingBut( 'findFile' ) );

		return $mock;
	}

	/**
	 * Just returns null for every findFile().
	 */
	private function getMockRepoGroupNull() {
		$mock = $this->createMock( RepoGroup::class );
		$mock->expects( $this->once() )->method( 'findFile' )->willReturn( null );
		$mock->expects( $this->never() )->method( $this->anythingBut( 'findFile' ) );

		return $mock;
	}

	private function getMockTitleParser() {
		$mock = $this->createMock( TitleParser::class );
		$mock->method( 'parseTitle' )->will( $this->returnCallback( function ( $text ) {
			if ( strpos( $text, '<' ) !== false ) {
				throw $this->createMock( MalformedTitleException::class );
			}
			if ( strpos( $text, ':' ) === false ) {
				return new TitleValue( NS_MAIN, $text );
			}
			list( $ns, $text ) = explode( ':', $text );
			switch ( $ns ) {
				case 'Image':
				case 'File':
					$ns = NS_FILE;
					break;

				case 'User':
					$ns = NS_USER;
					break;
			}
			return new TitleValue( $ns, $text );
		} ) );
		$mock->expects( $this->never() )->method( $this->anythingBut( 'parseTitle' ) );

		return $mock;
	}

	private function getHookContainer() {
		// FIXME: unit tests should not depend on the global HookContainer.
		// Once the facilities are available, this should create a new
		// HookContainer and register the hook directly into it, instead of using
		// setTemporaryHook()
		return MediaWikiServices::getInstance()->getHookContainer();
	}

	protected function setUp() : void {
		parent::setUp();

		$this->setTemporaryHook( 'BadImage', __CLASS__ . '::badImageHook' );
	}

	/**
	 * @dataProvider provideIsBadFile
	 * @covers ::__construct
	 * @covers ::isBadFile
	 */
	public function testIsBadFile( $name, $title, $expected ) {
		$bfl = new BadFileLookup(
			function () {
				return self::BAD_FILE_LIST;
			},
			new EmptyBagOStuff,
			$this->getMockRepoGroup(),
			$this->getMockTitleParser(),
			$this->getHookContainer()
		);

		$this->assertSame( $expected, $bfl->isBadFile( $name, $title ) );
	}

	/**
	 * @dataProvider provideIsBadFile
	 * @covers ::__construct
	 * @covers ::isBadFile
	 */
	public function testIsBadFile_nullRepoGroup( $name, $title, $expected ) {
		$bfl = new BadFileLookup(
			function () {
				return self::BAD_FILE_LIST;
			},
			new EmptyBagOStuff,
			$this->getMockRepoGroupNull(),
			$this->getMockTitleParser(),
			$this->getHookContainer()
		);

		// Hack -- these expectations are reversed if the repo group returns null. In that case 1)
		// we don't honor redirects, and 2) we don't replace spaces by underscores (which makes the
		// hook not see 'Hook bad.jpg').
		if ( in_array( $name, [
			'Redirect to bad.jpg',
			'Redirect_to_good.jpg',
			'Hook bad.jpg',
			'Redirect to hook bad.jpg',
		] ) ) {
			$expected = !$expected;
		}

		$this->assertSame( $expected, $bfl->isBadFile( $name, $title ) );
	}

	/** Shared with GlobalWithDBTest */
	public static function provideIsBadFile() {
		return [
			'No context page' => [ 'Bad.jpg', null, true ],
			'Context page not whitelisted' =>
				[ 'Bad.jpg', new TitleValue( NS_MAIN, 'A page' ), true ],
			'Good image' => [ 'Good.jpg', null, false ],
			'Whitelisted context page' =>
				[ 'Bad.jpg', new TitleValue( NS_MAIN, 'Nasty page' ), false ],
			'Bad image with Image:' => [ 'Image:Bad.jpg', null, false ],
			'Bad image with File:' => [ 'File:Bad.jpg', null, false ],
			'Bad image with Image: in blacklist' => [ 'Bad2.jpg', null, true ],
			'Bad image without prefix in blacklist' => [ 'Bad3.jpg', null, true ],
			'Bad image with different namespace in blacklist' => [ 'Bad4.jpg', null, true ],
			'Redirect to bad image' => [ 'Redirect to bad.jpg', null, true ],
			'Redirect to good image' => [ 'Redirect_to_good.jpg', null, false ],
			'Hook says bad (with space)' => [ 'Hook bad.jpg', null, true ],
			'Hook says bad (with underscore)' => [ 'Hook_bad.jpg', null, true ],
			'Hook says good' => [ 'Hook good.jpg', null, false ],
			'Redirect to hook bad image' => [ 'Redirect to hook bad.jpg', null, true ],
			'Redirect to hook good image' => [ 'Redirect to hook good.jpg', null, false ],
			'Malformed title doesn\'t break the line' => [ 'Bad5.jpg', null, true ],
		];
	}
}
