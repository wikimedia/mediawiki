<?php
/**
 * Implements Special:Listgrouprights
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

use MediaWiki\MediaWikiServices;

/**
 * This special page lists all defined user groups and the associated rights.
 * See also @ref $wgGroupPermissions.
 *
 * @ingroup SpecialPage
 * @author Petr Kadlec <mormegil@centrum.cz>
 */
class SpecialListGroupRights extends SpecialPage {
	public function __construct() {
		parent::__construct( 'Listgrouprights' );
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

		$out->wrapWikiMsg( "<div class=\"mw-listgrouprights-key\">\n$1\n</div>", 'listgrouprights-key' );

		$out->addHTML(
			Xml::openElement( 'table', [ 'class' => 'wikitable mw-listgrouprights-table' ] ) .
				'<tr>' .
				Xml::element( 'th', null, $this->msg( 'listgrouprights-group' )->text() ) .
				Xml::element( 'th', null, $this->msg( 'listgrouprights-rights' )->text() ) .
				'</tr>'
		);

		$config = $this->getConfig();
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
			$permissions = $groupPermissions[$group] ?? [];
			$groupname = ( $group == '*' ) // Replace * with a more descriptive groupname
				? 'all'
				: $group;

			$groupnameLocalized = UserGroupMembership::getGroupName( $groupname );

			$grouppageLocalizedTitle = UserGroupMembership::getGroupPage( $groupname )
				?: Title::newFromText( MWNamespace::getCanonicalName( NS_PROJECT ) . ':' . $groupname );

			if ( $group == '*' || !$grouppageLocalizedTitle ) {
				// Do not make a link for the generic * group or group with invalid group page
				$grouppage = htmlspecialchars( $groupnameLocalized );
			} else {
				$grouppage = $linkRenderer->makeLink(
					$grouppageLocalizedTitle,
					$groupnameLocalized
				);
			}

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

			$revoke = $revokePermissions[$group] ?? [];
			$addgroups = $addGroups[$group] ?? [];
			$removegroups = $removeGroups[$group] ?? [];
			$addgroupsSelf = $groupsAddToSelf[$group] ?? [];
			$removegroupsSelf = $groupsRemoveFromSelf[$group] ?? [];

			$id = $group == '*' ? false : Sanitizer::escapeIdForAttribute( $group );
			$out->addHTML( Html::rawElement( 'tr', [ 'id' => $id ], "
				<td>$grouppage$grouplink</td>
					<td>" .
					$this->formatPermissions( $permissions, $revoke, $addgroups, $removegroups,
						$addgroupsSelf, $removegroupsSelf ) .
					'</td>
				'
			) );
		}
		$out->addHTML( Xml::closeElement( 'table' ) );
		$this->outputNamespaceProtectionInfo();
	}

	private function outputNamespaceProtectionInfo() {
		$out = $this->getOutput();
		$namespaceProtection = $this->getConfig()->get( 'NamespaceProtection' );

		if ( count( $namespaceProtection ) == 0 ) {
			return;
		}

		$header = $this->msg( 'listgrouprights-namespaceprotection-header' )->text();
		$out->addHTML(
			Html::rawElement( 'h2', [], Html::element( 'span', [
				'class' => 'mw-headline',
				'id' => substr( Parser::guessSectionNameFromStrippedText( $header ), 1 )
			], $header ) ) .
			Xml::openElement( 'table', [ 'class' => 'wikitable' ] ) .
			Html::element(
				'th',
				[],
				$this->msg( 'listgrouprights-namespaceprotection-namespace' )->text()
			) .
			Html::element(
				'th',
				[],
				$this->msg( 'listgrouprights-namespaceprotection-restrictedto' )->text()
			)
		);
		$linkRenderer = $this->getLinkRenderer();
		ksort( $namespaceProtection );
		$validNamespaces = MWNamespace::getValidNamespaces();
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		foreach ( $namespaceProtection as $namespace => $rights ) {
			if ( !in_array( $namespace, $validNamespaces ) ) {
				continue;
			}

			if ( $namespace == NS_MAIN ) {
				$namespaceText = $this->msg( 'blanknamespace' )->text();
			} else {
				$namespaceText = $contLang->convertNamespace( $namespace );
			}

			$out->addHTML(
				Xml::openElement( 'tr' ) .
				Html::rawElement(
					'td',
					[],
					$linkRenderer->makeLink(
						SpecialPage::getTitleFor( 'Allpages' ),
						$namespaceText,
						[],
						[ 'namespace' => $namespace ]
					)
				) .
				Xml::openElement( 'td' ) . Xml::openElement( 'ul' )
			);

			if ( !is_array( $rights ) ) {
				$rights = [ $rights ];
			}

			foreach ( $rights as $right ) {
				$out->addHTML(
					Html::rawElement( 'li', [], $this->msg(
						'listgrouprights-right-display',
						User::getRightDescription( $right ),
						Html::element(
							'span',
							[ 'class' => 'mw-listgrouprights-right-name' ],
							$right
						)
					)->parse() )
				);
			}

			$out->addHTML(
				Xml::closeElement( 'ul' ) .
				Xml::closeElement( 'td' ) .
				Xml::closeElement( 'tr' )
			);
		}
		$out->addHTML( Xml::closeElement( 'table' ) );
	}

	/**
	 * Create a user-readable list of permissions from the given array.
	 *
	 * @param array $permissions Array of permission => bool (from $wgGroupPermissions items)
	 * @param array $revoke Array of permission => bool (from $wgRevokePermissions items)
	 * @param array $add Array of groups this group is allowed to add or true
	 * @param array $remove Array of groups this group is allowed to remove or true
	 * @param array $addSelf Array of groups this group is allowed to add to self or true
	 * @param array $removeSelf Array of group this group is allowed to remove from self or true
	 * @return string HTML list of all granted permissions
	 */
	private function formatPermissions( $permissions, $revoke, $add, $remove, $addSelf, $removeSelf ) {
		$r = [];
		foreach ( $permissions as $permission => $granted ) {
			// show as granted only if it isn't revoked to prevent duplicate display of permissions
			if ( $granted && ( !isset( $revoke[$permission] ) || !$revoke[$permission] ) ) {
				$r[] = $this->msg( 'listgrouprights-right-display',
					User::getRightDescription( $permission ),
					'<span class="mw-listgrouprights-right-name">' . $permission . '</span>'
				)->parse();
			}
		}
		foreach ( $revoke as $permission => $revoked ) {
			if ( $revoked ) {
				$r[] = $this->msg( 'listgrouprights-right-revoked',
					User::getRightDescription( $permission ),
					'<span class="mw-listgrouprights-right-name">' . $permission . '</span>'
				)->parse();
			}
		}

		sort( $r );

		$lang = $this->getLanguage();
		$allGroups = User::getAllGroups();

		$changeGroups = [
			'addgroup' => $add,
			'removegroup' => $remove,
			'addgroup-self' => $addSelf,
			'removegroup-self' => $removeSelf
		];

		foreach ( $changeGroups as $messageKey => $changeGroup ) {
			if ( $changeGroup === true ) {
				// For grep: listgrouprights-addgroup-all, listgrouprights-removegroup-all,
				// listgrouprights-addgroup-self-all, listgrouprights-removegroup-self-all
				$r[] = $this->msg( 'listgrouprights-' . $messageKey . '-all' )->escaped();
			} elseif ( is_array( $changeGroup ) ) {
				$changeGroup = array_intersect( array_values( array_unique( $changeGroup ) ), $allGroups );
				if ( count( $changeGroup ) ) {
					$groupLinks = [];
					foreach ( $changeGroup as $group ) {
						$groupLinks[] = UserGroupMembership::getLink( $group, $this->getContext(), 'wiki' );
					}
					// For grep: listgrouprights-addgroup, listgrouprights-removegroup,
					// listgrouprights-addgroup-self, listgrouprights-removegroup-self
					$r[] = $this->msg( 'listgrouprights-' . $messageKey,
						$lang->listToText( $groupLinks ), count( $changeGroup ) )->parse();
				}
			}
		}

		if ( empty( $r ) ) {
			return '';
		} else {
			return '<ul><li>' . implode( "</li>\n<li>", $r ) . '</li></ul>';
		}
	}

	protected function getGroupName() {
		return 'users';
	}
}
