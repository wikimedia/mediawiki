<?php
/**
 * Implements Special:Filepath
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * A special page that redirects to the URL of a given file
 *
 * @ingroup SpecialPage
 */
class SpecialFilepath extends RedirectSpecialPage {

	/** @var SearchEngineFactory */
	private $searchEngineFactory;

	/**
	 * @param SearchEngineFactory $searchEngineFactory
	 */
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

	protected function getGroupName() {
		return 'media';
	}
}
