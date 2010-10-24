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

	var $skin;

	/**
	 * Constructor
	 */
	function __construct() {
		global $wgUser;
		parent::__construct( 'Listgrouprights' );
		$this->skin = $wgUser->getSkin();
	}

	/**
	 * Show the special page
	 */
	public function execute( $par ) {
		global $wgOut, $wgImplicitGroups;
		global $wgGroupPermissions, $wgRevokePermissions, $wgAddGroups, $wgRemoveGroups;
		global $wgGroupsAddToSelf, $wgGroupsRemoveFromSelf;

		$this->setHeaders();
		$this->outputHeader();

		$wgOut->addHTML(
			Xml::openElement( 'table', array( 'class' => 'wikitable mw-listgrouprights-table' ) ) .
				'<tr>' .
					Xml::element( 'th', null, wfMsg( 'listgrouprights-group' ) ) .
					Xml::element( 'th', null, wfMsg( 'listgrouprights-rights' ) ) .
				'</tr>'
		);

		$allGroups = array_merge( array_keys( $wgGroupPermissions ), 
				array_keys( $wgRevokePermissions ) );
		foreach ( $allGroups as $group ) {
			$permissions = isset( $wgGroupPermissions[$group] ) ? 
					$wgGroupPermissions[$group] : array();
			$groupname = ( $group == '*' ) ? 'all' : $group; // Replace * with a more descriptive groupname

			$msg = wfMsg( 'group-' . $groupname );
			if ( wfEmptyMsg( 'group-' . $groupname, $msg ) || $msg == '' ) {
				$groupnameLocalized = $groupname;
			} else {
				$groupnameLocalized = $msg;
			}

			$msg = wfMsgForContent( 'grouppage-' . $groupname );
			if ( wfEmptyMsg( 'grouppage-' . $groupname, $msg ) || $msg == '' ) {
				$grouppageLocalized = MWNamespace::getCanonicalName( NS_PROJECT ) . ':' . $groupname;
			} else {
				$grouppageLocalized = $msg;
			}

			if( $group == '*' ) {
				// Do not make a link for the generic * group
				$grouppage = htmlspecialchars( $groupnameLocalized );
			} else {
				$grouppage = $this->skin->link(
					Title::newFromText( $grouppageLocalized ),
					htmlspecialchars( $groupnameLocalized )
				);
			}

			if ( $group === 'user' ) {
				// Link to Special:listusers for implicit group 'user'
				$grouplink = '<br />' . $this->skin->link(
					SpecialPage::getTitleFor( 'Listusers' ),
					wfMsgHtml( 'listgrouprights-members' ),
					array(),
					array(),
					array( 'known', 'noclasses' )
				);
			} elseif ( !in_array( $group, $wgImplicitGroups ) ) {
				$grouplink = '<br />' . $this->skin->link(
					SpecialPage::getTitleFor( 'Listusers' ),
					wfMsgHtml( 'listgrouprights-members' ),
					array(),
					array( 'group' => $group ),
					array( 'known', 'noclasses' )
				);
			} else {
				// No link to Special:listusers for other implicit groups as they are unlistable
				$grouplink = '';
			}

			$revoke = isset( $wgRevokePermissions[$group] ) ? $wgRevokePermissions[$group] : array();
			$addgroups = isset( $wgAddGroups[$group] ) ? $wgAddGroups[$group] : array();
			$removegroups = isset( $wgRemoveGroups[$group] ) ? $wgRemoveGroups[$group] : array();
			$addgroupsSelf = isset( $wgGroupsAddToSelf[$group] ) ? $wgGroupsAddToSelf[$group] : array();
			$removegroupsSelf = isset( $wgGroupsRemoveFromSelf[$group] ) ? $wgGroupsRemoveFromSelf[$group] : array();

			$id = $group == '*' ? false : Sanitizer::escapeId( $group );
			$wgOut->addHTML( Html::rawElement( 'tr', array( 'id' => $id ),
				"
				<td>$grouppage$grouplink</td>
					<td>" .
						self::formatPermissions( $permissions, $revoke, $addgroups, $removegroups, $addgroupsSelf, $removegroupsSelf ) .
					'</td>
				'
			) );
		}
		$wgOut->addHTML(
			Xml::closeElement( 'table' ) . "\n<br /><hr />\n"
		);
		$wgOut->wrapWikiMsg( "<div class=\"mw-listgrouprights-key\">\n$1\n</div>", 'listgrouprights-key' );
	}

	/**
	 * Create a user-readable list of permissions from the given array.
	 *
	 * @param $permissions Array of permission => bool (from $wgGroupPermissions items)
	 * @param $revoke Array of permission => bool (from $wgRevokePermissions items)
	 * @param $add Array of groups this group is allowed to add or true
	 * @param $remove Array of groups this group is allowed to remove or true
	 * @param $addSelf Array of groups this group is allowed to add to self or true
	 * @param $removeSelf Array of group this group is allowed to remove from self or true
	 * @return string List of all granted permissions, separated by comma separator
	 */
	 private static function formatPermissions( $permissions, $revoke, $add, $remove, $addSelf, $removeSelf ) {
	 	global $wgLang;

		$r = array();
		foreach( $permissions as $permission => $granted ) {
			//show as granted only if it isn't revoked to prevent duplicate display of permissions
			if( $granted && ( !isset( $revoke[$permission] ) || !$revoke[$permission] ) ) {
				$description = wfMsgExt( 'listgrouprights-right-display', array( 'parseinline' ),
					User::getRightDescription( $permission ),
					'<span class="mw-listgrouprights-right-name">' . $permission . '</span>'
				);
				$r[] = $description;
			}
		}
		foreach( $revoke as $permission => $revoked ) {
			if( $revoked ) {
				$description = wfMsgExt( 'listgrouprights-right-revoked', array( 'parseinline' ),
					User::getRightDescription( $permission ),
					'<span class="mw-listgrouprights-right-name">' . $permission . '</span>'
				);
				$r[] = $description;
			}
		}
		sort( $r );
		if( $add === true ){
			$r[] = wfMsgExt( 'listgrouprights-addgroup-all', array( 'escape' ) );
		} else if( is_array( $add ) && count( $add ) ) {
			$add = array_values( array_unique( $add ) );
			$r[] = wfMsgExt( 'listgrouprights-addgroup', array( 'parseinline' ), $wgLang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $add ) ), count( $add ) );
		}
		if( $remove === true ){
			$r[] = wfMsgExt( 'listgrouprights-removegroup-all', array( 'escape' ) );
		} else if( is_array( $remove ) && count( $remove ) ) {
			$remove = array_values( array_unique( $remove ) );
			$r[] = wfMsgExt( 'listgrouprights-removegroup', array( 'parseinline' ), $wgLang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $remove ) ), count( $remove ) );
		}
		if( $addSelf === true ){
			$r[] = wfMsgExt( 'listgrouprights-addgroup-self-all', array( 'escape' ) );
		} else if( is_array( $addSelf ) && count( $addSelf ) ) {
			$addSelf = array_values( array_unique( $addSelf ) );
			$r[] = wfMsgExt( 'listgrouprights-addgroup-self', array( 'parseinline' ), $wgLang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $addSelf ) ), count( $addSelf ) );
		}
		if( $removeSelf === true ){
			$r[] = wfMsgExt( 'listgrouprights-removegroup-self-all', array( 'escape' ) );
		} else if( is_array( $removeSelf ) && count( $removeSelf ) ) {
			$removeSelf = array_values( array_unique( $removeSelf ) );
			$r[] = wfMsgExt( 'listgrouprights-removegroup-self', array( 'parseinline' ), $wgLang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $removeSelf ) ), count( $removeSelf ) );
		}
		if( empty( $r ) ) {
			return '';
		} else {
			return '<ul><li>' . implode( "</li>\n<li>", $r ) . '</li></ul>';
		}
	}
}
