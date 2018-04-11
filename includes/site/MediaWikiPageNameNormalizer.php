<?php

namespace MediaWiki\Site;

use FormatJson;
use Http;
use UtfNormal\Validator;

/**
 * Service for normalizing a page name using a MediaWiki api.
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
 * @since 1.27
 *
 * @license GNU GPL v2+
 * @author John Erling Blad < jeblad@gmail.com >
 * @author Daniel Kinzler
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Marius Hoch
 */
class MediaWikiPageNameNormalizer {

	/**
	 * @var Http
	 */
	private $http;

	/**
	 * @param Http|null $http
	 */
	public function __construct( Http $http = null ) {
		if ( !$http ) {
			$http = new Http();
		}

		$this->http = $http;
	}

	/**
	 * Returns the normalized form of the given page title, using the
	 * normalization rules of the given site. If the given title is a redirect,
	 * the redirect will be resolved and the redirect target is returned.
	 * Only titles of existing pages will be returned.
	 *
	 * @note This actually makes an API request to the remote site, so beware
	 *   that this function is slow and depends on an external service.
	 *
	 * @see Site::normalizePageName
	 *
	 * @since 1.27
	 *
	 * @param string $pageName
	 * @param string $apiUrl
	 *
	 * @return string|false The normalized form of the title,
	 * or false to indicate an invalid title, a missing page,
	 * or some other kind of error.
	 * @throws \MWException
	 */
	public function normalizePageName( $pageName, $apiUrl ) {
		// Check if we have strings as arguments.
		if ( !is_string( $pageName ) ) {
			throw new \MWException( '$pageName must be a string' );
		}

		// Go on call the external site

		// Make sure the string is normalized into NFC (due to T42017)
		// but do nothing to the whitespaces, that should work appropriately.
		// @see https://phabricator.wikimedia.org/T42017
		$pageName = Validator::cleanUp( $pageName );

		// Build the args for the specific call
		$args = [
			'action' => 'query',
			'prop' => 'info',
			'redirects' => true,
			'converttitles' => true,
			'format' => 'json',
			'titles' => $pageName,
			// @todo options for maxlag and maxage
			// Note that maxlag will lead to a long delay before a reply is made,
			// but that maxage can avoid the extreme delay. On the other hand
			// maxage could be nice to use anyhow as it stops unnecessary requests.
			// Also consider smaxage if maxage is used.
		];

		$url = wfAppendQuery( $apiUrl, $args );

		// Go on call the external site
		// @todo we need a good way to specify a timeout here.
		$ret = $this->http->get( $url, [], __METHOD__ );

		if ( $ret === false ) {
			wfDebugLog( "MediaWikiSite", "call to external site failed: $url" );
			return false;
		}

		$data = FormatJson::decode( $ret, true );

		if ( !is_array( $data ) ) {
			wfDebugLog( "MediaWikiSite", "call to <$url> returned bad json: " . $ret );
			return false;
		}

		$page = static::extractPageRecord( $data, $pageName );

		if ( isset( $page['missing'] ) ) {
			wfDebugLog( "MediaWikiSite", "call to <$url> returned a marker for a missing page title! "
				. $ret );
			return false;
		}

		if ( isset( $page['invalid'] ) ) {
			wfDebugLog( "MediaWikiSite", "call to <$url> returned a marker for an invalid page title! "
				. $ret );
			return false;
		}

		if ( !isset( $page['title'] ) ) {
			wfDebugLog( "MediaWikiSite", "call to <$url> did not return a page title! " . $ret );
			return false;
		}

		return $page['title'];
	}

	/**
	 * Get normalization record for a given page title from an API response.
	 *
	 * @param array $externalData A reply from the API on a external server.
	 * @param string $pageTitle Identifies the page at the external site, needing normalization.
	 *
	 * @return array|bool A 'page' structure representing the page identified by $pageTitle.
	 */
	private static function extractPageRecord( $externalData, $pageTitle ) {
		// If there is a special case with only one returned page
		// we can cheat, and only return
		// the single page in the "pages" substructure.
		if ( isset( $externalData['query']['pages'] ) ) {
			$pages = array_values( $externalData['query']['pages'] );
			if ( count( $pages ) === 1 ) {
				return $pages[0];
			}
		}
		// This is only used during internal testing, as it is assumed
		// a more optimal (and lossfree) storage.
		// Make initial checks and return if prerequisites are not meet.
		if ( !is_array( $externalData ) || !isset( $externalData['query'] ) ) {
			return false;
		}
		// Loop over the tree different named structures, that otherwise are similar
		$structs = [
			'normalized' => 'from',
			'converted' => 'from',
			'redirects' => 'from',
			'pages' => 'title'
		];
		foreach ( $structs as $listId => $fieldId ) {
			// Check if the substructure exist at all.
			if ( !isset( $externalData['query'][$listId] ) ) {
				continue;
			}
			// Filter the substructure down to what we actually are using.
			$collectedHits = array_filter(
				array_values( $externalData['query'][$listId] ),
				function ( $a ) use ( $fieldId, $pageTitle ) {
					return $a[$fieldId] === $pageTitle;
				}
			);
			// If still looping over normalization, conversion or redirects,
			// then we need to keep the new page title for later rounds.
			if ( $fieldId === 'from' && is_array( $collectedHits ) ) {
				switch ( count( $collectedHits ) ) {
					case 0:
						break;
					case 1:
						$pageTitle = $collectedHits[0]['to'];
						break;
					default:
						return false;
				}
			} elseif ( $fieldId === 'title' && is_array( $collectedHits ) ) {
				// If on the pages structure we should prepare for returning.

				switch ( count( $collectedHits ) ) {
					case 0:
						return false;
					case 1:
						return array_shift( $collectedHits );
					default:
						return false;
				}
			}
		}
		// should never be here
		return false;
	}

}
