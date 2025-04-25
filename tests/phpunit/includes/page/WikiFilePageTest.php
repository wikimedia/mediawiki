<?php

use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\WikiFilePage;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;

/**
 * @covers \MediaWiki\Page\WikiFilePage
 * @group Database
 */
class WikiFilePageTest extends MediaWikiLangTestCase {

	public static function provideFollowRedirect() {
		yield 'local nonexisting' => [ null, [ 'exists' => false ], false ];
		yield 'local existing' => [ 'Bla bla', [], false ];
		yield 'local redirect' => [
			'#REDIRECT [[Image:Target.png]]',
			[],
			new TitleValue( NS_FILE, 'Target.png' ),
		];

		yield 'remote nonexisting' => [ null,
			[
				'isLocal' => false,
				'exists' => false,
			],
			false,
		];
		yield 'remote existing' => [
			null,
			[ 'isLocal' => false, ],
			false,
		];
		yield 'remote redirect' => [
			null,
			[
				'isLocal' => false,
				'redirectedFrom' => 'Test.png',
				'name' => 'Target.png',
			],
			new TitleValue( NS_FILE, 'Target.png' ),
		];
	}

	/**
	 * @dataProvider provideFollowRedirect
	 */
	public function testFollowRedirect( ?string $content, array $fileProps, $expected ) {
		$fileProps += [ 'name' => 'Test.png' ];
		$this->installMockFileRepo( $fileProps );

		if ( $content === null ) {
			$pageIdentity = $this->getNonexistingTestPage( 'Image:Test.png' );
		} else {
			$status = $this->editPage( 'Image:Test.png', $content );
			$pageIdentity = $status->getNewRevision()->getPage();
		}

		$page = new WikiFilePage( Title::newFromPageIdentity( $pageIdentity ) );
		$target = $page->followRedirect();

		if ( $expected instanceof LinkTarget ) {
			$this->assertTrue( $expected->isSameLinkAs( $target ) );
		} else {
			$this->assertSame( $expected, $target );
		}
	}

	private function installMockFileRepo( array $props = [] ): void {
		$repo = $this->createNoOpMock(
			FileRepo::class,
			[]
		);
		$file = $this->createNoOpMock(
			File::class,
			[
				'isLocal',
				'exists',
				'getRepo',
				'getRedirected',
				'getName',
			]
		);
		$file->method( 'isLocal' )->willReturn( $props['isLocal'] ?? true );
		$file->method( 'exists' )->willReturn( $props['exists'] ?? true );
		$file->method( 'getRepo' )->willReturn( $repo );
		$file->method( 'getRedirected' )->willReturn( $props['redirectedFrom'] ?? null );
		$file->method( 'getName' )->willReturn( $props['name'] ?? 'Test.png' );

		$localRepo = $this->createNoOpMock(
			FileRepo::class,
			[ 'invalidateImageRedirect' ]
		);

		$repoGroup = $this->createNoOpMock(
			RepoGroup::class,
			[ 'findFile', 'getLocalRepo' ]
		);
		$repoGroup->method( 'getLocalRepo' )->willReturn( $localRepo );
		$repoGroup->method( 'findFile' )->willReturn( $file );

		$this->setService(
			'RepoGroup',
			$repoGroup
		);
	}

}
