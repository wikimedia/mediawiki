<?php
/**
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
 */

namespace MediaWiki\Permissions;

use Language;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Linker\LinkRenderer;
use SpecialPage;

/**
 * Service to deal with grants
 *
 * @since 1.37
 */
class GrantsInfo {
	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var LanguageFactory */
	private $languageFactory;

	/** @var Language */
	private $contentLanguage;

	/** @var array */
	private $grantPermissions;

	/** @var array */
	private $grantPermissionGroups;

	/**
	 * @param LinkRenderer $linkRenderer
	 * @param LanguageFactory $languageFactory
	 * @param Language $contentLanguage
	 * @param array $grantPermissions
	 * @param array $grantPermissionGroups
	 */
	public function __construct(
		LinkRenderer $linkRenderer,
		LanguageFactory $languageFactory,
		Language $contentLanguage,
		array $grantPermissions,
		array $grantPermissionGroups
	) {
		$this->linkRenderer = $linkRenderer;
		$this->languageFactory = $languageFactory;
		$this->contentLanguage = $contentLanguage;
		$this->grantPermissions = $grantPermissions;
		$this->grantPermissionGroups = $grantPermissionGroups;
	}

	/**
	 * List all known grants.
	 * @return string[]
	 */
	public function getValidGrants() : array {
		return array_keys( $this->grantPermissions );
	}

	/**
	 * Map all grants to corresponding user rights.
	 * @return string[][] grant => array of rights in the grant
	 */
	public function getRightsByGrant() : array {
		$res = [];
		foreach ( $this->grantPermissions as $grant => $rights ) {
			$res[$grant] = array_keys( array_filter( $rights ) );
		}
		return $res;
	}

	/**
	 * Fetch the description of the grant.
	 * @param string $grant
	 * @param Language|string|null $lang
	 * @return string Grant description
	 */
	public function getGrantDescription( string $grant, $lang = null ) : string {
		// Give grep a chance to find the usages:
		// grant-blockusers, grant-createeditmovepage, grant-delete,
		// grant-editinterface, grant-editmycssjs, grant-editmywatchlist,
		// grant-editsiteconfig, grant-editpage, grant-editprotected,
		// grant-highvolume, grant-oversight, grant-patrol, grant-protect,
		// grant-rollback, grant-sendemail, grant-uploadeditmovefile,
		// grant-uploadfile, grant-basic, grant-viewdeleted,
		// grant-viewmywatchlist, grant-createaccount, grant-mergehistory,
		// grant-import

		// TODO replace wfMessage with something that can be injected like TextFormatter
		$msg = wfMessage( "grant-$grant" );

		if ( $lang ) {
			$msg->inLanguage( $lang );
		}

		if ( !$msg->exists() ) {
			$msg = $lang
				? wfMessage( 'grant-generic', $grant )->inLanguage( $lang )
				: wfMessage( 'grant-generic', $grant );
		}

		return $msg->text();
	}

	/**
	 * Fetch the descriptions for the grants.
	 * @param string[] $grants
	 * @param Language|string|null $lang
	 * @return string[] Corresponding grant descriptions
	 */
	public function getGrantDescriptions( array $grants, $lang = null ) : array {
		$ret = [];

		foreach ( $grants as $grant ) {
			$ret[] = $this->getGrantDescription( $grant, $lang );
		}
		return $ret;
	}

	/**
	 * Fetch the rights allowed by a set of grants.
	 * @param string[]|string $grants
	 * @return string[]
	 */
	public function getGrantRights( $grants ) : array {
		$rights = [];
		foreach ( (array)$grants as $grant ) {
			if ( isset( $this->grantPermissions[$grant] ) ) {
				$rights = array_merge(
					$rights,
					array_keys( array_filter( $this->grantPermissions[$grant] ) )
				);
			}
		}
		return array_unique( $rights );
	}

	/**
	 * Test that all grants in the list are known.
	 * @param string[] $grants
	 * @return bool
	 */
	public function grantsAreValid( array $grants ) : bool {
		return array_diff( $grants, $this->getValidGrants() ) === [];
	}

	/**
	 * Divide the grants into groups.
	 * @param string[]|null $grantsFilter
	 * @return string[][] Map of (group => (grant list))
	 */
	public function getGrantGroups( array $grantsFilter = null ) : array {
		if ( is_array( $grantsFilter ) ) {
			$grantsFilter = array_flip( $grantsFilter );
		}

		$groups = [];
		foreach ( $this->grantPermissions as $grant => $rights ) {
			if ( $grantsFilter !== null && !isset( $grantsFilter[$grant] ) ) {
				continue;
			}
			if ( isset( $this->grantPermissionGroups[$grant] ) ) {
				$groups[$this->grantPermissionGroups[$grant]][] = $grant;
			} else {
				$groups['other'][] = $grant;
			}
		}

		return $groups;
	}

	/**
	 * Get the list of grants that are hidden and should always be granted.
	 * @return string[]
	 */
	public function getHiddenGrants() : array {
		$grants = [];
		foreach ( $this->grantPermissionGroups as $grant => $group ) {
			if ( $group === 'hidden' ) {
				$grants[] = $grant;
			}
		}
		return $grants;
	}

	/**
	 * Generate a link to Special:ListGrants for a particular grant name.
	 *
	 * This should be used to link end users to a full description of what
	 * rights they are giving when they authorize a grant.
	 *
	 * @param string $grant the grant name
	 * @param Language|string|null $lang
	 * @return string (proto-relative) HTML link
	 */
	public function getGrantsLink( string $grant, $lang = null ) : string {
		return $this->linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Listgrants', false, $grant ),
			$this->getGrantDescription( $grant, $lang )
		);
	}

	/**
	 * Generate wikitext to display a list of grants
	 * @param string[]|null $grantsFilter If non-null, only display these grants.
	 * @param Language|string|null $lang
	 * @return string Wikitext
	 */
	public function getGrantsWikiText( $grantsFilter, $lang = null ) : string {
		if ( is_string( $lang ) ) {
			$lang = $this->languageFactory->getLanguage( $lang );
		} elseif ( $lang === null ) {
			$lang = $this->contentLanguage;
		}

		$s = '';
		foreach ( $this->getGrantGroups( $grantsFilter ) as $group => $grants ) {
			if ( $group === 'hidden' ) {
				continue; // implicitly granted
			}
			$s .= "*<span class=\"mw-grantgroup\">" .
				wfMessage( "grant-group-$group" )->inLanguage( $lang )->text() . "</span>\n";
			$s .= ":" . $lang->semicolonList( $this->getGrantDescriptions( $grants, $lang ) ) . "\n";
		}
		return "$s\n";
	}
}
