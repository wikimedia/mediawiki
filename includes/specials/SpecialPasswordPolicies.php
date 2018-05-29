<?php
/**
 * Implements Special:PasswordPolicies
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
 * This special page lists the defined password policies for user groups.
 * See also @ref $wgPasswordPolicy.
 *
 * @ingroup SpecialPage
 * @since 1.32
 */
class SpecialPasswordPolicies extends SpecialPage {
	public function __construct() {
		parent::__construct( 'PasswordPolicies' );
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
			Xml::openElement( 'table', [ 'class' => 'wikitable mw-passwordpolicies-table' ] ) .
				'<tr>' .
				Xml::element( 'th', null, $this->msg( 'passwordpolicies-group' )->text() ) .
				Xml::element( 'th', null, $this->msg( 'passwordpolicies-policies' )->text() ) .
				'</tr>'
		);

		$config = $this->getConfig();
		$policies = $config->get( 'PasswordPolicy' );

		$groupPermissions = $config->get( 'GroupPermissions' );
		$revokePermissions = $config->get( 'RevokePermissions' );
		$addGroups = $config->get( 'AddGroups' );
		$removeGroups = $config->get( 'RemoveGroups' );
		$groupsAddToSelf = $config->get( 'GroupsAddToSelf' );
		$groupsRemoveFromSelf = $config->get( 'GroupsRemoveFromSelf' );
		$allGroups = array_unique( array_merge(
			array_keys( $groupPermissions ),
			array_keys( $revokePermissions ),
			array_keys( $addGroups ),
			array_keys( $removeGroups ),
			array_keys( $groupsAddToSelf ),
			array_keys( $groupsRemoveFromSelf )
		) );
		asort( $allGroups );

		$linkRenderer = $this->getLinkRenderer();

		foreach ( $allGroups as $group ) {
			if ( $group == '*' ) {
				continue;
			}

			$groupnameLocalized = UserGroupMembership::getGroupName( $group );

			$grouppageLocalizedTitle = UserGroupMembership::getGroupPage( $group )
				?: Title::newFromText( MWNamespace::getCanonicalName( NS_PROJECT ) . ':' . $group );

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
			} elseif ( !in_array( $group, $config->get( 'ImplicitGroups' ) ) ) {
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

		$out->addHTML( Xml::closeElement( 'table' ) );
	}

	/**
	 * Create a HTML list of password policies for $group
	 *
	 * @param array $policies Original $wgPasswordPolicy array
	 * @param array $group Group to format password policies for
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
		foreach ( $groupPolicies as $gp => $val ) {
			if ( $val === false ) {
				// Policy isn't enabled, so no need to dislpay it
				continue;
			} elseif ( $val === true ) {
				$msg = $this->msg( 'passwordpolicies-policy-' . strtolower( $gp ) );
			} else {
				$msg = $this->msg( 'passwordpolicies-policy-' . strtolower( $gp ) )->numParams( $val );
			}
			$ret[] = $this->msg(
				'passwordpolicies-policy-display',
				$msg,
				'<span class="mw-passwordpolicies-policy-name">' . $gp . '</span>'
			)->parse();
		}
		if ( !count( $ret ) ) {
			return '';
		} else {
			return '<ul><li>' . implode( "</li>\n<li>", $ret ) . '</li></ul>';
		}
	}

	protected function getGroupName() {
		return 'users';
	}
}
