<?php

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
		global $wgOut, $wgImplicitGroups, $wgMessageCache;
		global $wgGroupPermissions, $wgAddGroups, $wgRemoveGroups;
		$wgMessageCache->loadAllMessages();

		$this->setHeaders();
		$this->outputHeader();

		$wgOut->addHTML(
			Xml::openElement( 'table', array( 'class' => 'mw-listgrouprights-table' ) ) .
				'<tr>' .
					Xml::element( 'th', null, wfMsg( 'listgrouprights-group' ) ) .
					Xml::element( 'th', null, wfMsg( 'listgrouprights-rights' ) ) .
				'</tr>'
		);

		foreach( $wgGroupPermissions as $group => $permissions ) {
			$groupname = ( $group == '*' ) ? 'all' : htmlspecialchars( $group ); // Replace * with a more descriptive groupname

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
				$grouppage = $groupnameLocalized;
			} else {
				$grouppage = $this->skin->makeLink( $grouppageLocalized, $groupnameLocalized );
			}

			if ( $group === 'user' ) {
				// Link to Special:listusers for implicit group 'user'
				$grouplink = '<br />' . $this->skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Listusers' ), wfMsgHtml( 'listgrouprights-members' ), ''  );
			} elseif ( !in_array( $group, $wgImplicitGroups ) ) {
				$grouplink = '<br />' . $this->skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Listusers' ), wfMsgHtml( 'listgrouprights-members' ), 'group=' . $group );
			} else {
				// No link to Special:listusers for other implicit groups as they are unlistable
				$grouplink = '';
			}

			$addgroups = isset( $wgAddGroups[$group] ) ? $wgAddGroups[$group] : array();
			$removegroups = isset( $wgRemoveGroups[$group] ) ? $wgRemoveGroups[$group] : array();

			$wgOut->addHTML(
				'<tr>
					<td>' .
						$grouppage . $grouplink .
					'</td>
					<td>' .
						self::formatPermissions( $permissions, $addgroups, $removegroups ) .
					'</td>
				</tr>'
			);
		}
		$wgOut->addHTML(
			Xml::closeElement( 'table' ) . "\n"
		);
	}

	/**
	 * Create a user-readable list of permissions from the given array.
	 *
	 * @param $permissions Array of permission => bool (from $wgGroupPermissions items)
	 * @return string List of all granted permissions, separated by comma separator
	 */
	 private static function formatPermissions( $permissions, $add, $remove ) {
	 	global $wgLang;
		$r = array();
		foreach( $permissions as $permission => $granted ) {
			if ( $granted ) {
				$description = wfMsgExt( 'listgrouprights-right-display', array( 'parseinline' ),
					User::getRightDescription( $permission ),
					$permission
				);
				$r[] = $description;
			}
		}
		sort( $r );
		if( $add === true ){
			$r[] = wfMsgExt( 'listgrouprights-addgroup-all', array( 'escape' ) );
		} else if( is_array( $add ) && count( $add ) ) {
			$r[] = wfMsgExt( 'listgrouprights-addgroup', array( 'parseinline' ), $wgLang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $add ) ), count( $add ) );
		}
		if( $remove === true ){
			$r[] = wfMsgExt( 'listgrouprights-removegroup-all', array( 'escape' ) );
		} else if( is_array( $remove ) && count( $remove ) ) {
			$r[] = wfMsgExt( 'listgrouprights-removegroup', array( 'parseinline' ), $wgLang->listToText( array_map( array( 'User', 'makeGroupLinkWiki' ), $remove ) ), count( $remove ) );
		}
		if( empty( $r ) ) {
			return '';
		} else {
			return '<ul><li>' . implode( "</li>\n<li>", $r ) . '</li></ul>';
		}
	}
}
