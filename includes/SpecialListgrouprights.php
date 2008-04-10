<?php

/**
 * This special page lists all defined user groups and the associated rights.
 * See also @ref $wgGroupPermissions.
 *
 * @addtogroup SpecialPage
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
		global $wgOut, $wgGroupPermissions, $wgImplicitGroups;

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
			$groupname = htmlspecialchars( $group );
			if ( in_array( $group, $wgImplicitGroups ) )
				$grouplink = $groupname;
			else
				$grouplink = $this->skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Listusers' ), $groupname, 'group=' . $group );

			$wgOut->addHTML(
				'<tr>
					<td>' .
						$grouplink .
					'</td>
					<td>' .
						SpecialListGroupRights::formatPermissions( $permissions ) .
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
	 private static function formatPermissions( $permissions ) {
		global $wgUser;
		$r = array();
		foreach( $permissions as $permission => $granted ) {
			if ( $granted ) {
				$permission = htmlspecialchars( $permission );
				$r[] = wfMsgExt( 'listgrouprights-link', array( 'parseinline' ), $permission );
			}
		}
		sort( $r );
		$r = implode( wfMsg( 'comma-separator' ), $r );

		return $r;
	}
}
