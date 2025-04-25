<?php

use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\TitleValue;
use Wikimedia\ObjectCache\EmptyBagOStuff;

/**
 * @coversDefaultClass \MediaWiki\Page\File\BadFileLookup
 */
class BadFileLookupTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;

	/** Shared with GlobalWithDBTest */
	public const BAD_FILE_LIST = <<<WIKITEXT
Comment line, no effect [[File:Good.jpg]]
 * Indented list is also a comment [[File:Good.jpg]]
* [[File:Bad.jpg]] except [[Nasty page]]
*[[Image:Bad2.jpg]] also works
* So does [[Bad3.jpg]]
* [[User:Bad4.jpg]] works although it is silly
* [[File:Redirect to good.jpg]] does not do anything if RepoGroup is working, because we only look at
  the final name, but will work if RepoGroup returns null
* List line with no link
* [[Malformed title<>]] does not break anything, the line is ignored [[File:Good.jpg]]
* [[File:Bad5.jpg]] before [[malformed title<>]] does not ignore the line
WIKITEXT;

	private HookContainer $hookContainer;

	/**
	 * Shared with GlobalWithDBTest
	 * @param string $name
	 * @param bool &$bad
	 * @return bool
	 */
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
		$mock = $this->createNoOpMock( RepoGroup::class, [ 'findFile' ] );
		$mock->expects( $this->once() )->method( 'findFile' )
			->willReturnCallback( function ( $name ) {
				$mockFile = $this->createNoOpMock( File::class, [ 'getTitle' ] );
				$mockFile->expects( $this->once() )->method( 'getTitle' )
					->willReturnCallback( static function () use ( $name ) {
						$redirectMap = [
							'Redirect to bad.jpg' => 'Bad.jpg',
							'Redirect_to_good.jpg' => 'Good.jpg',
							'Redirect to hook bad.jpg' => 'Hook_bad.jpg',
							'Redirect to hook good.jpg' => 'Hook_good.jpg',
						];
						$redirectTarget = $redirectMap[$name] ?? $name;
						return new TitleValue( NS_FILE, $redirectTarget );
					} );
				return $mockFile;
			} );

		return $mock;
	}

	protected function setUp(): void {
		parent::setUp();
		$this->hookContainer = $this->createHookContainer( [
			'BadImage' => __CLASS__ . '::badImageHook'
		] );
	}

	/**
	 * @dataProvider provideIsBadFile
	 * @covers ::__construct
	 * @covers ::isBadFile
	 */
	public function testIsBadFile( $name, $title, $expected ) {
		$bfl = new BadFileLookup(
			static function () {
				return self::BAD_FILE_LIST;
			},
			new EmptyBagOStuff,
			$this->getMockRepoGroup(),
			$this->getDummyTitleParser( [ 'throwMockExceptions' => true ] ),
			$this->hookContainer
		);

		$this->assertSame( $expected, $bfl->isBadFile( $name, $title ) );
	}

	/**
	 * @dataProvider provideIsBadFile
	 * @covers ::__construct
	 * @covers ::isBadFile
	 */
	public function testIsBadFile_nullRepoGroup( $name, $title, $expected ) {
		$nullRepoGroup = $this->createNoOpMock( RepoGroup::class, [ 'findFile' ] );
		$nullRepoGroup->expects( $this->once() )->method( 'findFile' )->willReturn( null );

		$bfl = new BadFileLookup(
			static function () {
				return self::BAD_FILE_LIST;
			},
			new EmptyBagOStuff,
			$nullRepoGroup,
			$this->getDummyTitleParser( [ 'throwMockExceptions' => true ] ),
			$this->hookContainer
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
			'Malformed title does not break the line' => [ 'Bad5.jpg', null, true ],
		];
	}
}
