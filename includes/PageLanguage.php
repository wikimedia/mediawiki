<?php

/**
 * This class determines the page language for a certain Title.
 *
 * This is the order in which it is determined:
 * - If it's a special page, it's the user language ($wgLang); else
 * - It loads the ContentHandler for the Title:
 * 		- for CSS & JS, it returns English
 * 		- default ContentHandler:
 * 			- pages in the MediaWiki NS, it takes the /xyz subpage
 * 			- for all other pages, it loads the hook and
 * 			  the database (if enabled)
 * If none of the above customizations apply, it returns
 * the site content language ($wgContLang) as default.
 *
 * This class should generally not be used except in Title.php.
 * Use:
 * $pageLang = new PageLanguage( $title );
 * $pageLang->setUseDb( false ); # use this to get the default page language
 * $pageLang->setPageLanguage( $var ); # set this if you already called the DB
 * $pageLang = $pageLang->getPageLanguage();
 */
class PageLanguage {

	// @var Title The title this page language corresponds to.
	protected $title;

	// default; ContentHandlers can just give a language code
    protected $pageLanguage;

	// if null it will take the variant of the above language
    protected $pageViewLanguage;

	// whether it is changeable on-wiki using Special:PageLanguage
	// this should also (especially) be checked for in the frontend
    protected $useDB = false;

	// false = not (yet) read from DB
	// null = read and using default Wiki language
	// is a string if set (i.e. a language code)
    protected $dbValue = false;

    /**
     * @param Title $title
     * @param bool $dbValue
     */
	public function __construct( Title $title, $dbValue = false ) {
		global $wgContLang;
		$this->title = $title;
		$this->pageLanguage = $wgContLang;
		// we already loaded the DB value and passed it, so use it
		if ( $dbValue !== false ) {
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
	private function loadContentHandler() {
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
     * @param bool $bool
     * @return bool
     */
	public function setUseDB( $bool ) {
		$this->useDB = $bool;
	}

	/**
	 * @return bool Whether to use the database
	 */
	public function getUseDB() {
		global $wgPageLanguageUseDB;
		return $wgPageLanguageUseDB && $this->useDB;
	}

	/**
	 * Get the DB value (be sure $wgPageLanguageUseDB is enabled)
	 *
	 * @param int $pageID
	 * @return string|null Language code or null
	 */
	public static function getFromDB( $pageID ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->selectField(
			'page',
			'page_lang',
			array( 'page_id' => $pageID ),
			__METHOD__
		);
	}

	/**
	 * Set the actual language
	 *
	 * @param string|Language $lang Language code or Language object
	 */
	public function setPageLanguage( $lang ) {
        $this->pageLanguage = wfGetLangObj($lang);
	}

	/**
	 * @param string|Language $lang Language code or Language object
	 */
	public function setPageViewLanguage( $lang ) {
		$this->pageViewLanguage = wfGetLangObj($lang);
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
            // Using RequestContext instead of $wgLang
            $userLang = RequestContext::getMain()->getLanguage();

			// special pages are in the user language
			$this->pageLanguage = $this->pageViewLanguage = $userLang;
            wfProfileOut( __METHOD__ );
			return $userLang;
		}

		// call ContentHandler (which also calls the hook)
		$this->loadContentHandler();

		// use the DB value
		if ( $this->getUseDB() ) {
			// not yet read from DB
			if ( $this->dbValue === false ) {
				$db = self::getFromDB( $this->title->getArticleID() );
				$this->dbValue = $db;
			}
			// if it's set in DB
			if ( $this->dbValue ) {
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
