<?php

use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageStoreRecord;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Creates semi-sane Title mocks for tests.
 * @stable to use since 1.41
 */
trait MockTitleTrait {

	/** @var int */
	private $pageIdCounter = 0;

	/**
	 * @param string $text
	 * @param array $props Additional properties to set. Supported keys:
	 *        - id: int
	 *        - namespace: int
	 *        - fragment: string
	 *        - interwiki: string
	 *        - redirect: bool
	 *        - language: Language
	 *        - contentModel: string
	 *        - revision: int
	 *        - validRedirect: bool
	 *
	 * @return Title|MockObject
	 */
	private function makeMockTitle( $text, array $props = [] ) {
		$ns = $props['namespace'] ?? 0;
		if ( $ns < 0 ) {
			$id = 0;
		} else {
			$id = $props['id'] ?? ++$this->pageIdCounter;
		}
		$nsName = $ns < 0 ? "Special:" : ( $ns ? "ns$ns:" : '' );

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
		$title->method( 'inNamespace' )->willReturnCallback( static function ( $namespace ) use ( $ns ) {
			return $namespace === $ns;
		} );
		$title->method( 'isSpecialPage' )->willReturn( $ns === NS_SPECIAL );
		$title->method( 'getFragment' )->willReturn( $props['fragment'] ?? '' );
		$title->method( 'hasFragment' )->willReturn( !empty( $props['fragment'] ) );
		$title->method( 'getInterwiki' )->willReturn( $props['interwiki'] ?? '' );
		$title->method( 'exists' )->willReturn( $id > 0 );
		$title->method( 'isRedirect' )->willReturn( $props['redirect'] ?? false );
		$title->method( 'isValidRedirectTarget' )->willReturn( $props['validRedirect'] ?? true );
		$title->method( 'getTouched' )->willReturn( $id ? '20200101223344' : false );

		// TODO getPageLanguage should return a Language object, 'qqx' is a string
		$title->method( 'getPageLanguage' )->willReturn( $props['language'] ?? 'qqx' );
		$contentModel = $props['contentModel'] ?? CONTENT_MODEL_WIKITEXT;
		$title->method( 'getContentModel' )->willReturn( $contentModel );
		$title->method( 'hasContentModel' )->willReturnCallback(
			static fn ( $id ) => $id === $contentModel );
		$title->method( 'getTitleProtection' )->willReturn( false );
		$title->method( 'canExist' )
			->willReturn( $ns >= 0 && empty( $props['interwiki'] ) && $text !== '' );
		$title->method( 'getWikiId' )->willReturn( Title::LOCAL );
		$title->method( 'getLinkURL' )->willReturn( "/wiki/" . str_replace( ' ', '_', $preText ) );
		if ( isset( $props['revision'] ) ) {
			$title->method( 'getLatestRevId' )->willReturn( $props['revision'] );
		} else {
			$title->method( 'getLatestRevId' )->willReturn( $id === 0 ? 0 : 43 );
		}
		$title->method( 'isContentPage' )->willReturn( true );
		$title->method( 'isSamePageAs' )->willReturnCallback( static function ( $other ) use ( $id ) {
			return $other && $id === $other->getArticleId();
		} );
		$title->method( 'isSameLinkAs' )->willReturnCallback( static function ( $other ) use ( $ns, $text ) {
			return $other && $text === $other->getDBkey() && $ns === $other->getNamespace();
		} );
		$title->method( 'equals' )->willReturnCallback( static function ( $other ) use ( $preText ) {
			return $other->getNamespace() ? 'ns' . $other->getNamespace() . ':' : '' . $other->getDBkey() ===
				str_replace( ' ', '_', $preText );
		} );
		$title->method( '__toString' )->willReturn( "MockTitle:{$preText}" );

		$title->method( 'toPageIdentity' )->willReturnCallback( static function () use ( $title ) {
			return new PageIdentityValue(
				$title->getId(),
				$title->getNamespace(),
				$title->getDBkey(),
				PageIdentity::LOCAL
			);
		} );

		$title->method( 'toPageRecord' )->willReturnCallback( static function () use ( $title ) {
			return new PageStoreRecord(
				(object)[
					'page_id' => $title->getArticleID(),
					'page_namespace' => $title->getNamespace(),
					'page_title' => $title->getDBkey(),
					'page_wiki_id' => $title->getWikiId(),
					'page_latest' => $title->getLatestRevID(),
					'page_is_new' => $title->isNewPage(),
					'page_is_redirect' => $title->isRedirect(),
					'page_touched' => $title->getTouched(),
					'page_lang' => $title->getPageLanguage() ?: null,
				],
				PageIdentity::LOCAL
			);
		} );

		return $title;
	}

	private function makeMockTitleFactory(): TitleFactory {
		$factory = $this->createNoOpMock(
			TitleFactory::class,
			[ 'newFromText' ]
		);

		$factory->method( 'newFromText' )->willReturnCallback(
			function ( $text ) {
				return $this->makeMockTitle( $text );
			}
		);

		return $factory;
	}
}
