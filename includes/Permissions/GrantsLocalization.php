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

use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\HtmlArmor\HtmlArmor;

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
	private GrantsInfo $grantsInfo;
	private LinkRenderer $linkRenderer;
	private LanguageFactory $languageFactory;
	private Language $contentLanguage;

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
	 * @return string[] Corresponding grant descriptions, keyed by grant name
	 */
	public function getGrantDescriptions( array $grants, $lang = null ): array {
		$ret = [];

		foreach ( $grants as $grant ) {
			$ret[$grant] = $this->getGrantDescription( $grant, $lang );
		}
		return $ret;
	}

	/**
	 * Fetch the descriptions for the grants, like getGrantDescriptions, but with HTML classes
	 * for styling. The HTML is wikitext-compatible.
	 * @param string[] $grants
	 * @param Language|string|null $lang
	 * @return string[] Grant description HTML for each grant, in the same order
	 */
	public function getGrantDescriptionsWithClasses( array $grants, $lang = null ): array {
		$riskGroupsByGrant = $this->grantsInfo->getRiskGroupsByGrant( 'unknown' );
		$grantDescriptions = $this->getGrantDescriptions( $grants, $lang );
		$results = [];
		foreach ( $grantDescriptions as $grant => $description ) {
			$riskGroup = $riskGroupsByGrant[$grant] ?? 'unknown';
			// Messages used here: grantriskgroup-vandalism, grantriskgroup-security,
			// grantriskgroup-internal
			$riskGroupMsg = wfMessage( "grantriskgroup-$riskGroup" );
			if ( $lang ) {
				$riskGroupMsg->inLanguage( $lang );
			}
			if ( $riskGroupMsg->exists() ) {
				$riskDescription = $riskGroupMsg->text();
				$riskDescriptionHTML = ' ' .
					Html::element( 'span', [ 'class' => "mw-grant mw-grantriskgroup-$riskGroup" ], $riskDescription );
			} else {
				$riskDescription = '';
				$riskDescriptionHTML = '';
			}
			$results[] = htmlspecialchars( $description ) . $riskDescriptionHTML;
		}
		return $results;
	}

	/**
	 * Generate a link to Special:ListGrants for a particular grant name.
	 *
	 * This can be used to link end users to a full description of what
	 * rights they are giving when they authorize a grant.
	 *
	 * @param string $grant the grant name
	 * @param Language|string|null $lang
	 * @return string (proto-relative) HTML link
	 */
	public function getGrantsLink( string $grant, $lang = null ): string {
		$riskGroupsByGrant = $this->grantsInfo->getRiskGroupsByGrant( 'unknown' );
		$riskGroup = $riskGroupsByGrant[$grant] ?? 'unknown';
		return $this->linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Listgrants', false, $grant ),
			new HtmlArmor( $this->getGrantDescriptionsWithClasses( [ $grant ], $lang )[ 0 ] )
		);
	}

	/**
	 * Generate wikitext to display a list of grants. It will be in the format
	 *     * <grant-group-$group>
	 *     : <grant-$grant>; <grant-$grant>; ...
	 *     * ...
	 * with some HTML classes for styling.
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
			$grantDescriptionsWithClasses = $this->getGrantDescriptionsWithClasses( $grants, $lang );
			// Give grep a chance to find the usages:
			// grant-group-page-interaction, grant-group-file-interaction
			// grant-group-watchlist-interaction, grant-group-email,
			// grant-group-high-volume, grant-group-customization,
			// grant-group-administration, grant-group-private-information,
			// grant-group-other
			$s .= "*<span class=\"mw-grantgroup\">" .
				// TODO: replace wfMessage with something that can be injected like TextFormatter
				wfMessage( "grant-group-$group" )->inLanguage( $lang )->text() . "</span>\n";
			$s .= ":" . $lang->semicolonList( $grantDescriptionsWithClasses ) . "\n";
		}
		return "$s\n";
	}
}
