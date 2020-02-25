<?php
/**
 * Implements Special:Listfiles
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

class SpecialListFiles extends IncludableSpecialPage {
	public function __construct() {
		parent::__construct( 'Listfiles' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Managing_files' );

		if ( $this->including() ) {
			$userName = $par;
			$search = '';
			$showAll = false;
		} else {
			$userName = $this->getRequest()->getText( 'user', $par );
			$search = $this->getRequest()->getText( 'ilsearch', '' );
			$showAll = $this->getRequest()->getBool( 'ilshowall', false );
		}
		// Sanitize usernames to avoid symbols in the title of page.
		$sanitizedUserName = User::getCanonicalName( $userName, false );
		if ( $sanitizedUserName ) {
			$userName = $sanitizedUserName;
		}

		if ( $userName ) {
			$pageTitle = $this->msg( 'listfiles_subpage', $userName );
		} else {
			$pageTitle = $this->msg( 'listfiles' );
		}

		$pager = new ImageListPager(
			$this->getContext(),
			$userName,
			$search,
			$this->including(),
			$showAll,
			$this->getLinkRenderer()
		);

		$out = $this->getOutput();
		$out->setPageTitle( $pageTitle );
		$out->addModuleStyles( 'mediawiki.special' );
		if ( $this->including() ) {
			$out->addParserOutputContent( $pager->getBodyOutput() );
		} else {
			$user = $pager->getRelevantUser();
			$this->getSkin()->setRelevantUser( $user );
			$pager->getForm();
			$out->addParserOutputContent( $pager->getFullOutput() );
		}
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
		$user = User::newFromName( $search );
		if ( !$user ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return UserNamePrefixSearch::search( 'public', $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'media';
	}
}
