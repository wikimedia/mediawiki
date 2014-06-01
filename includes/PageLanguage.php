<?php

/**
 * This class determines the page language for a certain Title.
 *
 * This is the order in which it is determined:
 * 1. If it's a special page, it's the user language ($wgLang); else
 * 2. TODO
 *
 * Use:
 * $pageLang = new PageLanguage( $title );
 * $pageLang->mUseDB = false; # use this to get the default page language
 * $pageLang->dbValue = $var; # set this if you already called the DB
 * $pageLang = $pageLang->getPageLanguage();
 */
class PageLanguage {

	// Title object for which to get the page language
	private $title;

	// default; ContentHandlers can just give a language code
	private $pageLanguage;

	// if null it will take the variant of the above language
	private $pageViewLanguage = null;

	// whether it is changeable on-wiki using Special:PageLanguage
	// this should also (especially) be checked for in the frontend
	private $useDB = false;

	// false = not (yet) read from DB
	// null = read but not set
	// is a string if set (i.e. a language code)
	private $dbValue = false;

	/**
	 * @param Title
	 */
	public function __construct( Title $title, $dbValue = false ) {
		global $wgContLang;
		$this->title = $title;
		$this->pageLanguage = $wgContLang;
		// we already loaded the DB value and passed it, so use it
		if( $dbValue !== false ) {
			$this->dbValue = $dbValue;
		}
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * This is the main place to determine the page language,
	 * it calls ContentHandler.
	 *
	 * NOTE: ContentHandler may need to load the
	 * content to determine the page language!
	 */
	private function getContentHandler() {
		$contentHandler = ContentHandler::getForTitle( $this->title );

		// backwards compatibility for ContentHandlers that are not yet updated
		if ( !method_exists( $contentHandler, 'setPageLanguageSettings' ) ) {
			$this->pageLanguage = $contentHandler->getPageLanguage( $this->title );
			$this->pageViewLanguage = $contentHandler->getPageViewLanguage( $this->title );
		} else {
			$contentHandler->setPageLanguageSettings( $this );
		}
	}

	/**
	 * @param boolean $bool
	 */
	public function setUseDB( $bool ) {
		return $this->useDB = $bool;
	}

	/**
	 * @return bool Whether to use the database
	 */
	public function getUseDB() {
		global $wgPageLanguageUseDB;
		return $wgPageLanguageUseDB && $this->useDB;
	}

	/**
	 * Load the DB value (be sure $wgPageLanguageUseDB is true!)
	 *
	 * @param int $pageID
	 * @return string|null Language code or null
	 */
	public static function loadDB( $pageID ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField(
			'page',
			'page_lang',
			array( 'page_id' => $pageID ),
			__METHOD__
		);
	}

	/**
	 * @param $lang
	 */
	public function setPageLanguage( $lang ) {
		$this->pageLanguage = $lang;
	}

	/**
	 * @param $lang
	 */
	public function setPageViewLanguage( $lang ) {
		$this->pageViewLanguage = $lang;
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

		if ( $this->title->isSpecialPage() ) {
			global $wgLang;
			// special pages are in the user language
			$this->pageLanguage = $this->pageViewLanguage = $wgLang;
			return $wgLang;
			wfProfileOut( __METHOD__ );
		}

		// call ContentHandler (which also calls the hook)
		$this->getContentHandler();

		// use the DB value
		if ( $this->getUseDB() ) {
			// not yet read from DB
			if ( $this->dbValue === false ) {
				$db = self::loadDB( $this->title->getArticleID() );
				$this->dbValue = $db;
			}
			// if it's set in DB
			if ( $this->dbValue !== null ) {
				$this->pageLanguage = $this->dbValue;
			}
		}

		wfProfileOut( __METHOD__ );

		return wfGetLangObj( $this->pageLanguage );
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
		if ( $this->pageViewLanguage === null ) {
			// if not explicitly set, it should probably assume
			// it is a normal text/string that can be converted
			// when the user chooses a variant.
			// Then the content displayed is actually
			// in a language whose code is the variant code.
			$variant = $pageLang->getPreferredVariant();
			if ( $pageLang->getCode() !== $variant ) {
				$this->pageViewLanguage = $variant;
			} else {
				$this->pageViewLanguage = $this->pageLanguage;
			}
		}
		return wfGetLangObj( $this->pageViewLanguage );
	}
}
