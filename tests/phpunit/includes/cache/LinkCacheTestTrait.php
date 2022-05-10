<?php

use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;

/**
 * A trait providing utility functions for testing LinkCache.
 * This trait is intended to be used on subclasses of
 * MediaWikiIntegrationTestCase.
 *
 * @stable to use
 * @since 1.37
 */

trait LinkCacheTestTrait {

	/**
	 * Force information about a page into the cache, pretending it exists.
	 *
	 * @param int $id Page's ID
	 * @param LinkTarget|PageReference $page The page to set cached info for.
	 * @param int $len Text's length
	 * @param int|null $redir Whether the page is a redirect
	 * @param int $revision Latest revision's ID
	 * @param string|null $model Latest revision's content model ID
	 * @param string|null $lang Language code of the page, if not the content language
	 */
	public function addGoodLinkObject(
		$id, $page, $len = -1, $redir = null, $revision = 0, $model = null, $lang = null
	) {
		MediaWikiServices::getInstance()->getLinkCache()->addGoodLinkObjFromRow( $page, (object)[
			'page_id' => (int)$id,
			'page_namespace' => $page->getNamespace(),
			'page_title' => $page->getDBkey(),
			'page_len' => (int)$len,
			'page_is_redirect' => (int)$redir,
			'page_latest' => (int)$revision,
			'page_content_model' => $model ? (string)$model : null,
			'page_lang' => $lang ? (string)$lang : null,
			'page_is_new' => 0,
			'page_touched' => '',
		] );
	}

}
