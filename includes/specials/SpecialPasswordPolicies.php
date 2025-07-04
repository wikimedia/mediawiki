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

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Password\UserPasswordPolicy;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupMembership;

/**
 * This special page lists the defined password policies for user groups.
 *
 * See also @ref $wgPasswordPolicy.
 *
 * @ingroup SpecialPage
 * @since 1.32
 */
class SpecialPasswordPolicies extends SpecialPage {

	private UserGroupManager $userGroupManager;

	public function __construct( UserGroupManager $userGroupManager ) {
		parent::__construct( 'PasswordPolicies' );
		$this->userGroupManager = $userGroupManager;
	}

	/**
	 * Show the special page
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );

		// TODO: Have specific user documentation page for this feature
		$this->addHelpLink( 'Manual:$wgPasswordPolicy' );

		$out->addHTML(
			Html::openElement( 'table', [ 'class' => [ 'wikitable', 'mw-passwordpolicies-table' ] ] ) .
				'<tr>' .
			Html::element( 'th', [], $this->msg( 'passwordpolicies-group' )->text() ) .
			Html::element( 'th', [], $this->msg( 'passwordpolicies-policies' )->text() ) .
				'</tr>'
		);

		$config = $this->getConfig();
		$policies = $config->get( MainConfigNames::PasswordPolicy );

		$implicitGroups = $this->userGroupManager->listAllImplicitGroups();
		$allGroups = array_merge(
			$this->userGroupManager->listAllGroups(),
			$implicitGroups
		);
		asort( $allGroups );

		$linkRenderer = $this->getLinkRenderer();
		$lang = $this->getLanguage();

		foreach ( $allGroups as $group ) {
			if ( $group == '*' ) {
				continue;
			}

			$groupnameLocalized = $lang->getGroupName( $group );

			$grouppageLocalizedTitle = UserGroupMembership::getGroupPage( $group )
				?: Title::makeTitle( NS_PROJECT, $group );

			$grouppage = $linkRenderer->makeLink(
				$grouppageLocalizedTitle,
				$groupnameLocalized
			);

			if ( $group === 'user' ) {
				// Link to Special:listusers for implicit group 'user'
				$grouplink = '<br />' . $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Listusers' ),
					$this->msg( 'listgrouprights-members' )->text()
				);
			} elseif ( !in_array( $group, $implicitGroups ) ) {
				$grouplink = '<br />' . $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Listusers' ),
					$this->msg( 'listgrouprights-members' )->text(),
					[],
					[ 'group' => $group ]
				);
			} else {
				// No link to Special:listusers for other implicit groups as they are unlistable
				$grouplink = '';
			}

			$out->addHTML( Html::rawElement( 'tr', [ 'id' => Sanitizer::escapeIdForAttribute( $group ) ], "
				<td>$grouppage$grouplink</td>
				<td>" . $this->formatPolicies( $policies, $group ) . '</td>
				'
			) );

		}

		$out->addHTML( Html::closeElement( 'table' ) );
	}

	/**
	 * Create a HTML list of password policies for $group
	 *
	 * @param array $policies Original $wgPasswordPolicy array
	 * @param string $group Group to format password policies for
	 *
	 * @return string HTML list of all applied password policies
	 */
	private function formatPolicies( $policies, $group ) {
		$groupPolicies = UserPasswordPolicy::getPoliciesForGroups(
			$policies['policies'],
			[ $group ],
			$policies['policies']['default']
		);

		$ret = [];
		foreach ( $groupPolicies as $gp => $settings ) {
			if ( !is_array( $settings ) ) {
				$settings = [ 'value' => $settings ];
			}
			$val = $settings['value'];
			$flags = array_diff_key( $settings, [ 'value' => true ] );
			if ( !$val ) {
				// Policy isn't enabled, so no need to display it
				continue;
			}

			$msg = $this->msg( 'passwordpolicies-policy-' . strtolower( $gp ) );

			if ( is_numeric( $val ) ) {
				$msg->numParams( $val );
			}

			$flagMsgs = [];
			foreach ( array_filter( $flags ) as $flag => $value ) {
				$flagMsg = $this->msg( 'passwordpolicies-policyflag-' . strtolower( $flag ) );
				$flagMsg->params( $value );
				$flagMsgs[] = $flagMsg;
			}
			if ( $flagMsgs ) {
				$ret[] = $this->msg(
					'passwordpolicies-policy-displaywithflags',
					$msg,
					'<span class="mw-passwordpolicies-policy-name">' . $gp . '</span>',
					$this->getLanguage()->commaList( $flagMsgs )
				)->parse();
			} else {
				$ret[] = $this->msg(
					'passwordpolicies-policy-display',
					$msg,
					'<span class="mw-passwordpolicies-policy-name">' . $gp . '</span>'
				)->parse();
			}
		}
		if ( $ret === [] ) {
			return '';
		} else {
			return '<ul><li>' . implode( "</li>\n<li>", $ret ) . '</li></ul>';
		}
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'users';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPasswordPolicies::class, 'SpecialPasswordPolicies' );
