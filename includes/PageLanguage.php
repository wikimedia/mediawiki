<?php

/**
 * This class determines the page language for a certain Title/page.
 *
 * This is the order in which it is determined:
 * 1. If it's a special page, it's the user language ($wgLang); else
 * 2. TODO
 *
 * Use:
 * $pageLang = new PageLanguage( $title );
 * $pageLang->mUseDB = false; # use this to get the default page language
 * $pageLang->mDbValue = $var; # set this if you already called the DB
 * $pageLang = $pageLang->getPageLanguage();
 */
class PageLanguage {

	// Title object for which to get the page language
	public $mTitle;

	// default; ContentHandlers can just give a language code
	public $mPageLanguage;

	// if 'usedefault' it will take the variant of the above language
	public $mPageViewLanguage = 'usedefault';

	// whether it is changeable on-wiki using Special:PageLanguage
	// this should also (especially) be checked for in the frontend
	public $mUseDB = false;

	public $mDbValue;

	/**
	 * @param Title
	 */
	public function __construct( $title ) {
		global $wgContLang;
		$this->mTitle = $title;
		$this->mPageLanguage = $wgContLang;
	}

	/**
	 * This is the main place to determine the page language,
	 * it calls ContentHandler.
	 *
	 * NOTE: ContentHandler may need to load the
	 * content to determine the page language!
	 */
	private function getContentHandler() {
		$contentHandler = ContentHandler::getForTitle( $this->mTitle );

		// backwards compatibility for ContentHandlers that are not yet updated
		if( !method_exists( $contentHandler, 'setPageLanguageSettings' ) ) {
			$this->mPageLanguage = $contentHandler->getPageLanguage( $this->mTitle );
			$this->mPageViewLanguage = $contentHandler->getPageViewLanguage( $this->mTitle );
		} else {
			$contentHandler->setPageLanguageSettings( $this );
		}
	}

	/**
	 * @return bool Whether to use the database
	 */
	public function useDB() {
		global $wgPageLanguageUseDB;
		return $wgPageLanguageUseDB && $this->mUseDB;
	}

	/**
	 * Load the DB value (be sure $wgPageLanguageUseDB is true!)
	 *
	 * @param int $pageID
	 * @return string|null Language code or null
	 */
	public static function loadDB( $pageID ) {
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow(
			'page',
			'page_lang',
			array( 'page_id' => $pageID ),
			__METHOD__
		);
		return $row->page_lang;
	}

	/**
	 * Get the language in which the content of the given page is written.
	 *
	 * This default implementation just returns $wgContLang (except for pages
	 * in the MediaWiki namespace)
	 *
	 * @return Language
	 */
	public function getPageLanguage() {
		wfProfileIn( __METHOD__ );

		if( $this->mTitle->isSpecialPage() ) {
			global $wgLang;
			// special pages are in the user language
			$this->mPageLanguage = $this->mPageViewLanguage = $wgLang;
			return $wgLang;
			wfProfileOut( __METHOD__ );
		}

		// call ContentHandler (which also calls the hook)
		$this->getContentHandler();

		// use the DB value
		if( $this->useDB() ) {
			if( !$this->mDbValue ) {
				$db = self::loadDB( $this->mTitle->getArticleID() );
				$this->mDbValue = $db;
			}
			if( isset( $this->mDbValue ) ) {
				$this->mPageLanguage = $this->mDbValue;
			}
		}

		wfProfileOut( __METHOD__ );

		return wfGetLangObj( $this->mPageLanguage );
	}

	/**
	 * Get the language in which the content of this page is written when
	 * viewed by user. Defaults to $this->getPageLanguage(), but if the user
	 * specified a preferred variant, the variant will be used.
	 *
	 * @return Language
	 */
	function getPageViewLanguage() {
		$pageLang = $this->getPageLanguage();
		// get the final page view language's object
		if( $this->mPageViewLanguage === 'usedefault' ) {
			// if not explicitly set, it should probably assume
			// it is a normal text/string that can be converted
			// when the user chooses a variant.
			// Then the content displayed is actually
			// in a language whose code is the variant code.
			$variant = $pageLang->getPreferredVariant();
			if ( $pageLang->getCode() !== $variant ) {
				$this->mPageViewLanguage = $variant;
			} else {
				$this->mPageViewLanguage = $this->mPageLanguage;
			}
		}
		return wfGetLangObj( $this->mPageViewLanguage );
	}
}
