<?php

namespace MediaWiki\Tests\Rest\Handler;

use LocalFile;
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
			[ 'getTitle', 'exists', 'getDescriptionUrl' ]
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
			[ 'getTitle', 'getDescriptionUrl', 'exists', 'userCan', 'getUser', 'getUploader', 'getTimestamp',
				'getMediaType', 'getSize', 'getHeight', 'getWidth', 'getDisplayWidthHeight',
				'getLength', 'getUrl', 'allowInlineDisplay', 'transform', 'getSha1' ]
		);
		$file->method( 'getTitle' )->willReturn( $title );
		$file->method( 'exists' )->willReturn( true );
		$file->method( 'userCan' )->willReturn( true );
		$file->method( 'getUser' )->willReturnCallback( static function ( $type ) {
			return $type === 'id' ? 7 : 'Alice';
		} );
		$file->method( 'getUploader' )
			->willReturn( UserIdentityValue::newRegistered( 7, 'Alice' ) );
		$file->method( 'getTimestamp' )->willReturn( '20200102030405' );
		$file->method( 'getMediaType' )->willReturn( 'test' );
		$file->method( 'getSize' )->willReturn( 12345 );
		$file->method( 'getHeight' )->willReturn( 400 );
		$file->method( 'getWidth' )->willReturn( 600 );
		$file->method( 'getDisplayWidthHeight' )->willReturn( [ 600, 400 ] );
		$file->method( 'getLength' )->willReturn( 678 );
		$file->method( 'getSha1' )->willReturn( 'DEADBEEF' );
		$file->method( 'allowInlineDisplay' )->willReturn( true );
		$file->method( 'getUrl' )->willReturn(
			'https://media.example.com/static/' . $title->getDBkey()
		);
		$file->method( 'getDescriptionUrl' )->willReturn(
			'https://example.com/wiki/' . $title->getPrefixedDBkey()
		);

		$thumbnail = new ThumbnailImage(
			$file,
			'https://media.example.com/static/thumb/' . $title->getDBkey(),
			false,
			[ 'width' => 64, 'height' => 64, ]
		);
		$file->method( 'transform' )->willReturn( $thumbnail );

		return $file;
	}

	/**
	 * @return MockObject|RepoGroup
	 */
	private function makeMockRepoGroup() {
		$findFile = function ( $title ) {
			$title = $title instanceof PageReference
				? $this->makeMockTitle( $title->getDBkey(), [ 'namespace' => $title->getNamespace() ] )
				: $this->makeMockTitle( 'File:' . $title, [ 'namespace' => NS_FILE ] );

			if ( preg_match( '/missing/i', $title->getText() )
				|| $title->getNamespace() !== NS_FILE
			) {
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

		/** @var RepoGroup|MockObject $repoGroup */
		$repoGroup = $this->createNoOpMock( RepoGroup::class, [ 'findFiles', 'findFile' ] );
		$repoGroup->method( 'findFile' )->willReturnCallback( $findFile );
		$repoGroup->method( 'findFiles' )->willReturnCallback( $findFiles );

		return $repoGroup;
	}

}
