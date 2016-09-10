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
 * @license GPL 2+
 * @author Addshore
 */
namespace MediaWiki\Linker;

use Hooks;
use HtmlArmor;
use IContextSource;
use IP;
use SpecialPage;
use TitleValue;
use User;

class UserLinkHelper {

	/**
	 * Flags for makeUserToolLinks()
	 */

	/**
	 * Don't include a block link
	 */
	const TOOL_LINKS_NOBLOCK = 1;
	/**
	 * Don't include an email link
	 */
	const TOOL_LINKS_EMAIL = 2;

	/**
	 * Make the contribs link red if they have no edits
	 */
	const TOOL_LINKS_REDCONTRIBS = 3;


	/**
	 * @var LinkRenderer
	 */
	private $linkRenderer;

	/**
	 * @var IContextSource
	 */
	private $context;

	public function __construct( LinkRenderer $linkRenderer, IContextSource $context ) {
		$this->linkRenderer = $linkRenderer;
		$this->context = $context;
	}

	/**
	 * Make user link (or user contributions for unregistered users)
	 * @param int $userId User id in database.
	 * @param string $userName User name in database.
	 * @param string|bool $displayText Text to display instead of the user name (optional)
	 * @return string HTML link
	 */
	public function makeUserLink( $userId, $userName, $displayText = false ) {
		$classes = 'mw-userlink';
		if ( $userId == 0 ) {
			$page = SpecialPage::getTitleValueFor( 'Contributions', $userName );
			if ( $displayText === false ) {
				$displayText = IP::prettifyIP( $userName );
			}
			$classes .= ' mw-anonuserlink'; // Separate link class for anons (bug 43179)
		} else {
			$page = new TitleValue( NS_USER, str_replace( ' ', '_', $userName ) );
			if ( $displayText === false ) {
				$displayText = $userName;
			}
		}

		// Wrap the output with <bdi> tags for directionality isolation
		return $this->linkRenderer->makeLink(
			$page,
			new HtmlArmor( '<bdi>' . htmlspecialchars( $displayText ) . '</bdi>' ),
			[ 'class' => $classes ]
		);
	}

	/**
	 * @param string $userName User name in database.
	 * @return string HTML link
	 */
	public function makeUserTalkLink( $userName ) {
		return $this->linkRenderer->makeLink(
			new TitleValue( NS_USER_TALK, str_replace( ' ', '_', $userName ) ),
			$this->context->msg( 'talkpagelinktext' )->text()
		);
	}

	/**
	 * @param string $userName User name in database.
	 * @return string HTML fragment with block link
	 */
	public function makeBlockLink( $userName ) {
		return $this->linkRenderer->makeLink(
			SpecialPage::getTitleValueFor( 'Block', $userName ),
			$this->context->msg( 'blocklink' )->text()
		);
	}

	/**
	 * @param string $userName User name in database.
	 * @return string HTML fragment with e-mail user link
	 */
	public function makeEmailLink( $userName ) {
		return $this->linkRenderer->makeLink(
			SpecialPage::getTitleValueFor( 'Emailuser', $userName ),
			$this->context->msg( 'emaillink' )->text()
		);
	}

	/**
	 * Generate standard user tool links (talk, contributions, block link, etc.)
	 *
	 * @since 1.16.3
	 * @param int $userId User identifier
	 * @param string $userName User name or IP address
	 * @param int $flags Customisation flags (e.g. self::TOOL_LINKS_NOBLOCK,
	 *   self::TOOL_LINKS_EMAIL, self::TOOL_LINKS_REDCONTRIBS).
	 * @param int $edits User edit count (optional, for performance)
	 * @return string HTML fragment
	 */
	public function makeUserToolLinks(
		$userId, $userName, $flags = 0, $edits = null
	) {
		$items = [];

		// If the user is anonymous, check that anon talk pages are enabled
		if ( !( $this->context->getConfig()->get( 'DisableAnonTalk' )
			&& $userId == 0 )
		) {
			$items[] = $this->makeUserTalkLink( $userName );
		}
		if ( $userId ) {
			// check if the user has an edit
			$hasContribs = true;
			if ( $flags & self::TOOL_LINKS_REDCONTRIBS ) {
				if ( $edits === null ) {
					$user = User::newFromId( $userId );
					$edits = $user->getEditCount();
					if ( $edits == 0 ) {
						$hasContribs = false;
					}
				}
			}

			$contribsPage = SpecialPage::getTitleValueFor( 'Contributions', $userName );
			$contribsText = $this->context->msg( 'contribslink' )->text();
			if ( $hasContribs ) {
				$items[] = $this->linkRenderer->makeKnownLink(
					$contribsPage, $contribsText
				);
			} else {
				$items[] = $this->linkRenderer->makeBrokenLink(
					$contribsPage, $contribsText
				);
			}
		}

		if ( !( $flags & self::TOOL_LINKS_NOBLOCK )
			&& $this->context->getUser()->isAllowed( 'block' )
		) {
			$items[] = $this->makeBlockLink( $userName );
		}

		// If the user is logged-in, the flag is set,
		// and the current user can send email
		if ( $userId && $flags & self::TOOL_LINKS_EMAIL
			&& $this->context->getUser()->canSendEmail()
		) {
			$items[] = $this->makeEmailLink( $userName );
		}

		Hooks::run( 'UserToolLinksEdit', [ $userId, $userName, &$items ] );

		if ( $items ) {
			return $this->context->msg( 'word-separator' )->escaped()
				. '<span class="mw-usertoollinks">'
				. $this->context->msg( 'parentheses' )
				->rawParams( $this->context->getLanguage()->pipeList( $items ) )
				->escaped()
				. '</span>';
		} else {
			return '';
		}
	}


}
