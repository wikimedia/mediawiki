<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\SpecialPage\RedirectSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use SearchEngineFactory;

/**
 * Redirects to the URL of a thumbnail for the given file.
 *
 * @ingroup SpecialPage
 */
class SpecialFilepath extends RedirectSpecialPage {

	private SearchEngineFactory $searchEngineFactory;

	public function __construct(
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( 'Filepath' );
		$this->mAllowedRedirectParams = [ 'width', 'height' ];
		$this->searchEngineFactory = $searchEngineFactory;
	}

	/**
	 * Implement by redirecting through Special:Redirect/file.
	 *
	 * @param string|null $par
	 * @return Title
	 */
	public function getRedirect( $par ) {
		$file = $par ?: $this->getRequest()->getText( 'file' );

		$redirect = null;
		if ( $file ) {
			$redirect = SpecialPage::getSafeTitleFor( 'Redirect', "file/$file" );
		}
		if ( $redirect === null ) {
			// The user input is empty or an invalid title,
			// redirect to form of Special:Redirect with the invalid value prefilled
			$this->mAddedRedirectParams['wpvalue'] = $file;
			$redirect = SpecialPage::getSafeTitleFor( 'Redirect', 'file' );
		}
		// @phan-suppress-next-line PhanTypeMismatchReturnNullable Known to be valid
		return $redirect;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$title = Title::newFromText( $search, NS_FILE );
		if ( !$title || $title->getNamespace() !== NS_FILE ) {
			// No prefix suggestion outside of file namespace
			return [];
		}
		$searchEngine = $this->searchEngineFactory->create();
		$searchEngine->setLimitOffset( $limit, $offset );
		// Autocomplete subpage the same as a normal search, but just for files
		$searchEngine->setNamespaces( [ NS_FILE ] );
		$result = $searchEngine->defaultPrefixSearch( $search );

		return array_map( static function ( Title $t ) {
			// Remove namespace in search suggestion
			return $t->getText();
		}, $result );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'media';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialFilepath::class, 'SpecialFilepath' );
