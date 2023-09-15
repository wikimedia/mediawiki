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
use MediaWiki\SpecialPage\SpecialPage;

/**
 * This separate service is needed because the ::getGrantsLink method requires a LinkRenderer
 * and if we tried to inject a LinkRenderer into the GrantsInfo service, it would result in
 * recursive service instantiation for sessions using the BotPasswordSessionProvider, as a
 * result of injecting the LinkRenderer when trying to use a GrantsInfo method that doesn't
 * even need it.
 *
 * @since 1.38
 */
class GrantsLocalization {
	/** @var GrantsInfo */
	private $grantsInfo;

	/** @var LinkRenderer */
	private $linkRenderer;

	/** @var LanguageFactory */
	private $languageFactory;

	/** @var Language */
	private $contentLanguage;

	/**
	 * @param GrantsInfo $grantsInfo
	 * @param LinkRenderer $linkRenderer
	 * @param LanguageFactory $languageFactory
	 * @param Language $contentLanguage
	 */
	public function __construct(
		GrantsInfo $grantsInfo,
		LinkRenderer $linkRenderer,
		LanguageFactory $languageFactory,
		Language $contentLanguage
	) {
		$this->grantsInfo = $grantsInfo;
		$this->linkRenderer = $linkRenderer;
		$this->languageFactory = $languageFactory;
		$this->contentLanguage = $contentLanguage;
	}

	/**
	 * Fetch the description of the grant.
	 * @param string $grant
	 * @param Language|string|null $lang
	 * @return string Grant description
	 */
	public function getGrantDescription( string $grant, $lang = null ): string {
		// Give grep a chance to find the usages:
		// grant-blockusers, grant-createeditmovepage, grant-delete,
		// grant-editinterface, grant-editmycssjs, grant-editmywatchlist,
		// grant-editsiteconfig, grant-editpage, grant-editprotected,
		// grant-highvolume, grant-oversight, grant-patrol, grant-protect,
		// grant-rollback, grant-sendemail, grant-uploadeditmovefile,
		// grant-uploadfile, grant-basic, grant-viewdeleted,
		// grant-viewmywatchlist, grant-createaccount, grant-mergehistory,
		// grant-import

		// TODO: replace wfMessage with something that can be injected like TextFormatter
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
	public function getGrantDescriptions( array $grants, $lang = null ): array {
		$ret = [];

		foreach ( $grants as $grant ) {
			$ret[] = $this->getGrantDescription( $grant, $lang );
		}
		return $ret;
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
	public function getGrantsLink( string $grant, $lang = null ): string {
		return $this->linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Listgrants', false, $grant ),
			$this->getGrantDescription( $grant, $lang )
		);
	}

	/**
	 * Generate wikitext to display a list of grants.
	 * @param string[]|null $grantsFilter If non-null, only display these grants.
	 * @param Language|string|null $lang
	 * @return string Wikitext
	 */
	public function getGrantsWikiText( $grantsFilter, $lang = null ): string {
		if ( is_string( $lang ) ) {
			$lang = $this->languageFactory->getLanguage( $lang );
		} elseif ( $lang === null ) {
			$lang = $this->contentLanguage;
		}

		$s = '';
		foreach ( $this->grantsInfo->getGrantGroups( $grantsFilter ) as $group => $grants ) {
			if ( $group === 'hidden' ) {
				continue; // implicitly granted
			}
			// Give grep a chance to find the usages:
			// grant-group-page-interaction, grant-group-file-interaction
			// grant-group-watchlist-interaction, grant-group-email,
			// grant-group-high-volume, grant-group-customization,
			// grant-group-administration, grant-group-private-information,
			// grant-group-other
			$s .= "*<span class=\"mw-grantgroup\">" .
				// TODO: replace wfMessage with something that can be injected like TextFormatter
				wfMessage( "grant-group-$group" )->inLanguage( $lang )->text() . "</span>\n";
			$s .= ":" . $lang->semicolonList( $this->getGrantDescriptions( $grants, $lang ) ) . "\n";
		}
		return "$s\n";
	}
}
