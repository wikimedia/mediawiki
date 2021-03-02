<?php

use MediaWiki\Interwiki\InterwikiLookup;
use PHPUnit\Framework\MockObject\MockObject;

trait MockTitleTrait {

	/** @var int */
	private $pageIdCounter = 0;

	/**
	 * @param string $text
	 * @param array $props Additional properties to set. Supported keys:
	 *        - id: int
	 *        - namespace: int
	 *        - language: Language
	 * 		  - contentModel: string
	 *
	 * @return Title|MockObject
	 */
	private function makeMockTitle( $text, array $props = [] ) {
		$id = $props['id'] ?? ++$this->pageIdCounter;
		$ns = $props['namespace'] ?? 0;
		$nsName = $ns ? "ns$ns:" : '';

		$preText = $text;
		$text = preg_replace( '/^[\w ]*?:/', '', $text );

		// If no namespace prefix was given, add one if needed.
		if ( $preText == $text && $ns ) {
			$preText = $nsName . $text;
		}

		/** @var Title|MockObject $title */
		$title = $this->createMock( Title::class );

		$title->method( 'getText' )->willReturn( str_replace( '_', ' ', $text ) );
		$title->method( 'getDBkey' )->willReturn( str_replace( ' ', '_', $text ) );

		$title->method( 'getPrefixedText' )->willReturn( str_replace( '_', ' ', $preText ) );
		$title->method( 'getPrefixedDBkey' )->willReturn( str_replace( ' ', '_', $preText ) );

		$title->method( 'getArticleID' )->willReturn( $id );
		$title->method( 'getId' )->willReturn( $id );
		$title->method( 'getNamespace' )->willReturn( $ns );
		$title->method( 'exists' )->willReturn( $id > 0 );
		$title->method( 'getTouched' )->willReturn( $id ? '20200101223344' : false );
		$title->method( 'getPageLanguage' )->willReturn( $props['language'] ?? 'qqx' );
		$title->method( 'getContentModel' )
			->willReturn( $props['contentModel'] ?? CONTENT_MODEL_WIKITEXT );
		$title->method( 'getRestrictions' )->willReturn( [] );
		$title->method( 'isSamePageAs' )->willReturnCallback( static function ( $other ) use ( $id ) {
			return $other && $id === $other->getArticleId();
		} );
		$title->method( 'isSameLinkAs' )->willReturnCallback( static function ( $other ) use ( $ns, $text ) {
			return $other && $text === $other->getDBkey() && $ns === $other->getNamespace();
		} );

		return $title;
	}

	/**
	 * @return MediaWikiTitleCodec
	 */
	private function makeMockTitleCodec() {
		/** @var Language|MockObject $language */
		$language = $this->createNoOpMock( Language::class, [ 'ucfirst' ] );
		$language->method( 'ucfirst' )->willReturnCallback( 'ucfirst' );

		/** @var GenderCache|MockObject $genderCache */
		$genderCache = $this->createNoOpMock( GenderCache::class );

		/** @var InterwikiLookup|MockObject $interwikiLookup */
		$interwikiLookup = $this->createNoOpMock( InterwikiLookup::class );

		/** @var NamespaceInfo|MockObject $namespaceInfo */
		$namespaceInfo = $this->createNoOpMock( NamespaceInfo::class, [ 'isCapitalized' ] );
		$namespaceInfo->method( 'isCapitalized' )->willReturn( true );

		$titleCodec = new MediaWikiTitleCodec(
			$language,
			$genderCache,
			[ 'en' ],
			$interwikiLookup,
			$namespaceInfo
		);

		return $titleCodec;
	}

	/**
	 * Expected to be provided by the class, probably inherited from TestCase.
	 *
	 * @param string $originalClassName
	 *
	 * @return MockObject
	 */
	abstract protected function createMock( $originalClassName ): MockObject;

	/**
	 * Expected to be provided by the class, probably MediaWikiTestCaseTrait.
	 *
	 * @param string $type
	 * @param string[] $allow methods to allow
	 *
	 * @return MockObject
	 */
	abstract protected function createNoOpMock( $type, $allow = [] );
}
