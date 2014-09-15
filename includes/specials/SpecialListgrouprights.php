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

/**
 * This special page lists all defined user groups and the associated rights.
 * See also @ref $wgGroupPermissions.
 *
 * @ingroup SpecialPage
 * @author Petr Kadlec <mormegil@centrum.cz>
 */
class SpecialListGroupRights extends SpecialPage {
	function __construct() {
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
			Xml::openElement( 'table', array( 'class' => 'wikitable mw-listgrouprights-table' ) ) .
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

		foreach ( $allGroups as $group ) {
			$permissions = isset( $groupPermissions[$group] )
				? $groupPermissions[$group]
				: array();
			$groupname = ( $group == '*' ) // Replace * with a more descriptive groupname
				? 'all'
				: $group;

			$msg = $this->msg( 'group-' . $groupname );
			$groupnameLocalized = !$msg->isBlank() ? $msg->text() : $groupname;

			$msg = $this->msg( 'grouppage-' . $groupname )->inContentLanguage();
			$grouppageLocalized = !$msg->isBlank() ?
				$msg->text() :
				MWNamespace::getCanonicalName( NS_PROJECT ) . ':' . $groupname;

			if ( $group == '*' ) {
				// Do not make a link for the generic * group
				$grouppage = htmlspecialchars( $groupnameLocalized );
			} else {
				$grouppage = Linker::link(
					Title::newFromText( $grouppageLocalized ),
					htmlspecialchars( $groupnameLocalized )
				);
			}

			if ( $group === 'user' ) {
				// Link to Special:listusers for implicit group 'user'
				$grouplink = '<br />' . Linker::linkKnown(
					SpecialPage::getTitleFor( 'Listusers' ),
					$this->msg( 'listgrouprights-members' )->escaped()
				);
			} elseif ( !in_array( $group, $config->get( 'ImplicitGroups' ) ) ) {
				$grouplink = '<br />' . Linker::linkKnown(
					SpecialPage::getTitleFor( 'Listusers' ),
					$this->msg( 'listgrouprights-members' )->escaped(),
					array(),
					array( 'group' => $group )
				);
			} else {
				// No link to Special:listusers for other implicit groups as they are unlistable
				$grouplink = '';
			}

			$revoke = isset( $revokePermissions[$group] ) ? $revokePermissions[$group] : array();
			$addgroups = isset( $addGroups[$group] ) ? $addGroups[$group] : array();
			$removegroups = isset( $removeGroups[$group] ) ? $removeGroups[$group] : array();
			$addgroupsSelf = isset( $groupsAddToSelf[$group] ) ? $groupsAddToSelf[$group] : array();
			$removegroupsSelf = isset( $groupsRemoveFromSelf[$group] )
				? $groupsRemoveFromSelf[$group]
				: array();

			$id = $group == '*' ? false : Sanitizer::escapeId( $group );
			$out->addHTML( Html::rawElement( 'tr', array( 'id' => $id ), "
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
		global $wgParser, $wgContLang;
		$out = $this->getOutput();
		$namespaceProtection = $this->getConfig()->get( 'NamespaceProtection' );

		if ( count( $namespaceProtection ) == 0 ) {
			return;
		}

		$header = $this->msg( 'listgrouprights-namespaceprotection-header' )->parse();
		$out->addHTML(
			Html::rawElement( 'h2', array(), Html::element( 'span', array(
				'class' => 'mw-headline',
				'id' => $wgParser->guessSectionNameFromWikiText( $header )
			), $header ) ) .
			Xml::openElement( 'table', array( 'class' => 'wikitable' ) ) .
			Html::element(
				'th',
				array(),
				$this->msg( 'listgrouprights-namespaceprotection-namespace' )->text()
			) .
			Html::element(
				'th',
				array(),
				$this->msg( 'listgrouprights-namespaceprotection-restrictedto' )->text()
			)
		);

		ksort( $namespaceProtection );
		foreach ( $namespaceProtection as $namespace => $rights ) {
			if ( !in_array( $namespace, MWNamespace::getValidNamespaces() ) ) {
				continue;
			}

			if ( $namespace == NS_MAIN ) {
				$namespaceText = $this->msg( 'blanknamespace' )->text();
			} else {
				$namespaceText = $wgContLang->convertNamespace( $namespace );
			}

			$out->addHTML(
				Xml::openElement( 'tr' ) .
				Html::rawElement(
					'td',
					array(),
					Linker::link(
						SpecialPage::getTitleFor( 'Allpages' ),
						$namespaceText,
						array(),
						array( 'namespace' => $namespace )
					)
				) .
				Xml::openElement( 'td' ) . Xml::openElement( 'ul' )
			);

			if ( !is_array( $rights ) ) {
				$rights = array( $rights );
			}

			foreach ( $rights as $right ) {
				$out->addHTML(
					Html::rawElement( 'li', array(), $this->msg(
						'listgrouprights-right-display',
						User::getRightDescription( $right ),
						Html::element(
							'span',
							array( 'class' => 'mw-listgrouprights-right-name' ),
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
	 * @return string List of all granted permissions, separated by comma separator
	 */
	private function formatPermissions( $permissions, $revoke, $add, $remove, $addSelf, $removeSelf ) {
		$r = array();
		foreach ( $permissions as $permission => $granted ) {
			//show as granted only if it isn't revoked to prevent duplicate display of permissions
			if ( $granted && ( !isset( $revoke[$permission] ) || !$revoke[$permission] ) ) {
				$description = $this->msg( 'listgrouprights-right-display',
					User::getRightDescription( $permission ),
					'<span class="mw-listgrouprights-right-name">' . $permission . '</span>'
				)->parse();
				$r[] = $description;
			}
		}
		foreach ( $revoke as $permission => $revoked ) {
			if ( $revoked ) {
				$description = $this->msg( 'listgrouprights-right-revoked',
					User::getRightDescription( $permission ),
					'<span class="mw-listgrouprights-right-name">' . $permission . '</span>'
				)->parse();
				$r[] = $description;
			}
		}

		sort( $r );

		$lang = $this->getLanguage();
		$allGroups = User::getAllGroups();

		if ( $add === true ) {
			$r[] = $this->msg( 'listgrouprights-addgroup-all' )->escaped();
		} elseif ( is_array( $add ) ) {
			$add = array_intersect( array_values( array_unique( $add ) ), $allGroups );
			if ( count( $add ) ) {
				$r[] = $this->msg( 'listgrouprights-addgroup',
					$lang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $add ) ),
					count( $add )
				)->parse();
			}
		}

		if ( $remove === true ) {
			$r[] = $this->msg( 'listgrouprights-removegroup-all' )->escaped();
		} elseif ( is_array( $remove ) ) {
			$remove = array_intersect( array_values( array_unique( $remove ) ), $allGroups );
			if ( count( $remove ) ) {
				$r[] = $this->msg( 'listgrouprights-removegroup',
					$lang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $remove ) ),
					count( $remove )
				)->parse();
			}
		}

		if ( $addSelf === true ) {
			$r[] = $this->msg( 'listgrouprights-addgroup-self-all' )->escaped();
		} elseif ( is_array( $addSelf ) ) {
			$addSelf = array_intersect( array_values( array_unique( $addSelf ) ), $allGroups );
			if ( count( $addSelf ) ) {
				$r[] = $this->msg( 'listgrouprights-addgroup-self',
					$lang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $addSelf ) ),
					count( $addSelf )
				)->parse();
			}
		}

		if ( $removeSelf === true ) {
			$r[] = $this->msg( 'listgrouprights-removegroup-self-all' )->parse();
		} elseif ( is_array( $removeSelf ) ) {
			$removeSelf = array_intersect( array_values( array_unique( $removeSelf ) ), $allGroups );
			if ( count( $removeSelf ) ) {
				$r[] = $this->msg( 'listgrouprights-removegroup-self',
					$lang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $removeSelf ) ),
					count( $removeSelf )
				)->parse();
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
