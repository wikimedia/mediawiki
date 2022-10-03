<?php

namespace MediaWiki\Tests\Rest\Handler;

use LocalFile;
use LocalRepo;
use MediaWiki\Page\PageReference;
use MediaWiki\User\UserIdentityValue;
use MockTitleTrait;
use PHPUnit\Framework\MockObject\MockObject;
use RepoGroup;
use ThumbnailImage;

/**
 * A trait providing utility functions for mocking media-related objects.
 *
 * @package MediaWiki\Tests\Rest\Handler
 */
trait MediaTestTrait {

	use HandlerTestTrait;
	use MockTitleTrait;

	/**
	 * @param PageReference|string $title
	 *
	 * @return LocalFile|MockObject
	 */
	private function makeMissingMockFile( $title ) {
		$title = $title instanceof PageReference
			? $this->makeMockTitle( $title->getDBkey(), [ 'namespace' => $title->getNamespace() ] )
			: $this->makeMockTitle( 'File:' . $title, [ 'namespace' => NS_FILE ] );

		/** @var MockObject|LocalFile $file */
		$file = $this->createNoOpMock(
			LocalFile::class,
			[ 'getTitle', 'exists', 'getDescriptionUrl', 'load' ]
		);
		$file->method( 'getTitle' )->willReturn( $title );
		$file->method( 'exists' )->willReturn( false );
		$file->method( 'getDescriptionUrl' )->willReturn(
			'https://example.com/wiki/' . $title->getPrefixedDBkey()
		);

		return $file;
	}

	/**
	 * @param PageReference|string $title
	 *
	 * @return LocalFile|MockObject
	 */
	private function makeMockFile( $title ) {
		$title = $title instanceof PageReference
			? $this->makeMockTitle( $title->getDBkey(), [ 'namespace' => $title->getNamespace() ] )
			: $this->makeMockTitle( 'File:' . $title, [ 'namespace' => NS_FILE ] );

		/** @var MockObject|LocalFile $file */
		$file = $this->createNoOpMock(
			LocalFile::class,
			[ 'getName', 'getTitle', 'getDescriptionUrl', 'exists', 'userCan', 'getUploader', 'getTimestamp',
				'getMediaType', 'getSize', 'getHeight', 'getWidth', 'getDisplayWidthHeight',
				'getLength', 'getUrl', 'allowInlineDisplay', 'transform', 'getSha1', 'load', 'getMimeType' ]
		);
		$file->method( 'getName' )->willReturn( ucfirst( $title->getDBkey() ) );
		$file->method( 'getTitle' )->willReturn( $title );
		$file->method( 'exists' )->willReturn( true );
		$file->method( 'userCan' )->willReturn( true );
		$file->method( 'getUploader' )
			->willReturn( UserIdentityValue::newRegistered( 7, 'Alice' ) );
		$file->method( 'getTimestamp' )->willReturn( '20200102030405' );
		$file->method( 'getMediaType' )->willReturn( 'test' );
		$file->method( 'getSize' )->willReturn( 12345 );
		$file->method( 'getHeight' )->willReturn( 400 );
		$file->method( 'getWidth' )->willReturn( 600 );
		$file->method( 'getLength' )->willReturn( 678 );
		$file->method( 'getSha1' )->willReturn( 'DEADBEEF' );
		$file->method( 'allowInlineDisplay' )->willReturn( true );
		$file->method( 'getUrl' )->willReturn(
			'https://media.example.com/static/' . $title->getDBkey()
		);
		$file->method( 'getDescriptionUrl' )->willReturn(
			'https://example.com/wiki/' . $title->getPrefixedDBkey()
		);
		$file->method( 'getMimeType' )->willReturn( 'image/jpeg' );

		$getDisplayWidthHeight = static function ( $maxWidth, $maxHeight, $page = 1 ) use ( $file ) {
			$width = $file->getWidth( $page );
			$height = $file->getHeight( $page );
			$ratio = $width / $height;
			if ( $width / $maxWidth > $height / $maxHeight ) {
				return [ $maxWidth, round( $maxWidth / $ratio ) ];
			}
			return [ round( $maxHeight * $ratio ), $maxHeight ];
		};
		$file->method( 'getDisplayWidthHeight' )->willReturnCallback( $getDisplayWidthHeight );

		$transform = static function ( $params ) use ( $file, $title ) {
			return new ThumbnailImage(
				$file,
				'https://media.example.com/static/thumb/' . $title->getDBkey(),
				false,
				[
					'width' => $params['width'] ?? 64,
					'height' => $params['height'] ?? 64,
					'page' => $params['page'] ?? false
				]
			);
		};
		$file->method( 'transform' )->willReturnCallback( $transform );

		return $file;
	}

	/**
	 * @param array $existingFileDBKeys
	 * @return MockObject|RepoGroup
	 */
	private function makeMockRepoGroup( array $existingFileDBKeys ) {
		$findFile = function ( $title ) use ( $existingFileDBKeys ) {
			$title = $title instanceof PageReference
				? $this->makeMockTitle( $title->getDBkey(), [ 'namespace' => $title->getNamespace() ] )
				: $this->makeMockTitle( 'File:' . $title, [ 'namespace' => NS_FILE ] );

			if ( !in_array( $title->getDBkey(), $existingFileDBKeys ) || $title->getNamespace() !== NS_FILE ) {
				return $this->makeMissingMockFile( $title );
			} else {
				return $this->makeMockFile( $title );
			}
		};

		$findFiles = static function ( array $inputItems ) use ( $findFile ) {
			$files = [];
			foreach ( $inputItems as $item ) {
				$files[] = ( $findFile )( $item['title'] );
			}

			return $files;
		};

		$mockLocalRepo = $this->createMock( LocalRepo::class );
		$mockLocalRepo->method( 'newFile' )->willReturnCallback( $findFile );

		/** @var RepoGroup|MockObject $repoGroup */
		$repoGroup = $this->createNoOpMock( RepoGroup::class, [ 'findFiles', 'findFile', 'getLocalRepo' ] );
		$repoGroup->method( 'findFile' )->willReturnCallback( $findFile );
		$repoGroup->method( 'findFiles' )->willReturnCallback( $findFiles );
		$repoGroup->method( 'getLocalRepo' )->willReturn( $mockLocalRepo );

		return $repoGroup;
	}

}
