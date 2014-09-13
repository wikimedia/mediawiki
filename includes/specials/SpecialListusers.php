<?php
/**
 * Implements Special:Listusers
 *
 * Copyright © 2004 Brion Vibber, lcrocker, Tim Starling,
 * Domas Mituzas, Antoine Musso, Jens Frank, Zhengzhu,
 * 2006 Rob Church <robchur@gmail.com>
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
 * This class is used to get a list of user. The ones with specials
 * rights (sysop, bureaucrat, developer) will have them displayed
 * next to their names.
 *
 * @ingroup SpecialPage
 */
class UsersPager extends AlphabeticPager {

	/**
	 * @param $context IContextSource
	 * @param array $par (Default null)
	 * @param $including boolean Whether this page is being transcluded in
	 * another page
	 */
	function __construct( IContextSource $context = null, $par = null, $including = null ) {
		if ( $context ) {
			$this->setContext( $context );
		}

		$request = $this->getRequest();
		$par = ( $par !== null ) ? $par : '';
		$parms = explode( '/', $par );
		$symsForAll = array( '*', 'user' );

		if ( $parms[0] != '' &&
			( in_array( $par, User::getAllGroups() ) || in_array( $par, $symsForAll ) )
		) {
			$this->requestedGroup = $par;
			$un = $request->getText( 'username' );
		} elseif ( count( $parms ) == 2 ) {
			$this->requestedGroup = $parms[0];
			$un = $parms[1];
		} else {
			$this->requestedGroup = $request->getVal( 'group' );
			$un = ( $par != '' ) ? $par : $request->getText( 'username' );
		}

		if ( in_array( $this->requestedGroup, $symsForAll ) ) {
			$this->requestedGroup = '';
		}
		$this->editsOnly = $request->getBool( 'editsOnly' );
		$this->creationSort = $request->getBool( 'creationSort' );
		$this->including = $including;
		$this->mDefaultDirection = $request->getBool( 'desc' );

		$this->requestedUser = '';

		if ( $un != '' ) {
			$username = Title::makeTitleSafe( NS_USER, $un );

			if ( !is_null( $username ) ) {
				$this->requestedUser = $username->getText();
			}
		}

		parent::__construct();
	}

	/**
	 * @return string
	 */
	function getIndexField() {
		return $this->creationSort ? 'user_id' : 'user_name';
	}

	/**
	 * @return Array
	 */
	function getQueryInfo() {
		$dbr = wfGetDB( DB_SLAVE );
		$conds = array();

		// Don't show hidden names
		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$conds[] = 'ipb_deleted IS NULL OR ipb_deleted = 0';
		}

		$options = array();

		if ( $this->requestedGroup != '' ) {
			$conds['ug_group'] = $this->requestedGroup;
		}

		if ( $this->requestedUser != '' ) {
			# Sorted either by account creation or name
			if ( $this->creationSort ) {
				$conds[] = 'user_id >= ' . intval( User::idFromName( $this->requestedUser ) );
			} else {
				$conds[] = 'user_name >= ' . $dbr->addQuotes( $this->requestedUser );
			}
		}

		if ( $this->editsOnly ) {
			$conds[] = 'user_editcount > 0';
		}

		$options['GROUP BY'] = $this->creationSort ? 'user_id' : 'user_name';

		$query = array(
			'tables' => array( 'user', 'user_groups', 'ipblocks' ),
			'fields' => array(
				'user_name' => $this->creationSort ? 'MAX(user_name)' : 'user_name',
				'user_id' => $this->creationSort ? 'user_id' : 'MAX(user_id)',
				'edits' => 'MAX(user_editcount)',
				'numgroups' => 'COUNT(ug_group)',
				'singlegroup' => 'MAX(ug_group)', // the usergroup if there is only one
				'creation' => 'MIN(user_registration)',
				'ipb_deleted' => 'MAX(ipb_deleted)' // block/hide status
			),
			'options' => $options,
			'join_conds' => array(
				'user_groups' => array( 'LEFT JOIN', 'user_id=ug_user' ),
				'ipblocks' => array(
					'LEFT JOIN', array(
						'user_id=ipb_user',
						'ipb_auto' => 0
					)
				),
			),
			'conds' => $conds
		);

		wfRunHooks( 'SpecialListusersQueryInfo', array( $this, &$query ) );

		return $query;
	}

	/**
	 * @param $row Object
	 * @return String
	 */
	function formatRow( $row ) {
		if ( $row->user_id == 0 ) { #Bug 16487
			return '';
		}

		$userName = $row->user_name;

		$ulinks = Linker::userLink( $row->user_id, $userName );
		$ulinks .= Linker::userToolLinksRedContribs(
			$row->user_id,
			$userName,
			(int)$row->edits
		);

		$lang = $this->getLanguage();

		$groups = '';
		$groups_list = self::getGroups( $row->user_id );

		if ( !$this->including && count( $groups_list ) > 0 ) {
			$list = array();
			foreach ( $groups_list as $group ) {
				$list[] = self::buildGroupLink( $group, $userName );
			}
			$groups = $lang->commaList( $list );
		}

		$item = $lang->specialList( $ulinks, $groups );

		if ( $row->ipb_deleted ) {
			$item = "<span class=\"deleted\">$item</span>";
		}

		$edits = '';
		global $wgEdititis;
		if ( !$this->including && $wgEdititis ) {
			// @fixme i18n issue: Hardcoded square brackets.
			$edits = ' [' .
				$this->msg( 'usereditcount' )->numParams( $row->edits )->escaped() .
				']';
		}

		$created = '';
		# Some rows may be null
		if ( !$this->including && $row->creation ) {
			$user = $this->getUser();
			$d = $lang->userDate( $row->creation, $user );
			$t = $lang->userTime( $row->creation, $user );
			$created = $this->msg( 'usercreated', $d, $t, $row->user_name )->escaped();
			$created = ' ' . $this->msg( 'parentheses' )->rawParams( $created )->escaped();
		}
		$blocked = !is_null( $row->ipb_deleted ) ?
			' ' . $this->msg( 'listusers-blocked', $userName )->escaped() :
			'';

		wfRunHooks( 'SpecialListusersFormatRow', array( &$item, $row ) );

		return Html::rawElement( 'li', array(), "{$item}{$edits}{$created}{$blocked}" );
	}

	function doBatchLookups() {
		$batch = new LinkBatch();
		# Give some pointers to make user links
		foreach ( $this->mResult as $row ) {
			$batch->add( NS_USER, $row->user_name );
			$batch->add( NS_USER_TALK, $row->user_name );
		}
		$batch->execute();
		$this->mResult->rewind();
	}

	/**
	 * @return string
	 */
	function getPageHeader() {
		global $wgScript;

		list( $self ) = explode( '/', $this->getTitle()->getPrefixedDBkey() );

		# Form tag
		$out = Xml::openElement(
			'form',
			array( 'method' => 'get', 'action' => $wgScript, 'id' => 'mw-listusers-form' )
		) .
			Xml::fieldset( $this->msg( 'listusers' )->text() ) .
			Html::hidden( 'title', $self );

		# Username field
		$out .= Xml::label( $this->msg( 'listusersfrom' )->text(), 'offset' ) . ' ' .
			Html::input(
				'username',
				$this->requestedUser,
				'text',
				array(
					'id' => 'offset',
					'size' => 20,
					'autofocus' => $this->requestedUser === ''
				)
			) . ' ';

		# Group drop-down list
		$out .= Xml::label( $this->msg( 'group' )->text(), 'group' ) . ' ' .
			Xml::openElement( 'select', array( 'name' => 'group', 'id' => 'group' ) ) .
			Xml::option( $this->msg( 'group-all' )->text(), '' );
		foreach ( $this->getAllGroups() as $group => $groupText ) {
			$out .= Xml::option( $groupText, $group, $group == $this->requestedGroup );
		}
		$out .= Xml::closeElement( 'select' ) . '<br />';
		$out .= Xml::checkLabel(
			$this->msg( 'listusers-editsonly' )->text(),
			'editsOnly',
			'editsOnly',
			$this->editsOnly
		);
		$out .= '&#160;';
		$out .= Xml::checkLabel(
			$this->msg( 'listusers-creationsort' )->text(),
			'creationSort',
			'creationSort',
			$this->creationSort
		);
		$out .= '&#160;';
		$out .= Xml::checkLabel(
			$this->msg( 'listusers-desc' )->text(),
			'desc',
			'desc',
			$this->mDefaultDirection
		);
		$out .= '<br />';

		wfRunHooks( 'SpecialListusersHeaderForm', array( $this, &$out ) );

		# Submit button and form bottom
		$out .= Html::hidden( 'limit', $this->mLimit );
		$out .= Xml::submitButton( $this->msg( 'allpagessubmit' )->text() );
		wfRunHooks( 'SpecialListusersHeader', array( $this, &$out ) );
		$out .= Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );

		return $out;
	}

	/**
	 * Get a list of all explicit groups
	 * @return array
	 */
	function getAllGroups() {
		$result = array();
		foreach ( User::getAllGroups() as $group ) {
			$result[$group] = User::getGroupName( $group );
		}
		asort( $result );

		return $result;
	}

	/**
	 * Preserve group and username offset parameters when paging
	 * @return array
	 */
	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		if ( $this->requestedGroup != '' ) {
			$query['group'] = $this->requestedGroup;
		}
		if ( $this->requestedUser != '' ) {
			$query['username'] = $this->requestedUser;
		}
		wfRunHooks( 'SpecialListusersDefaultQuery', array( $this, &$query ) );

		return $query;
	}

	/**
	 * Get a list of groups the specified user belongs to
	 *
	 * @param $uid Integer: user id
	 * @return array
	 */
	protected static function getGroups( $uid ) {
		$user = User::newFromId( $uid );
		$groups = array_diff( $user->getEffectiveGroups(), User::getImplicitGroups() );

		return $groups;
	}

	/**
	 * Format a link to a group description page
	 *
	 * @param string $group group name
	 * @param string $username Username
	 * @return string
	 */
	protected static function buildGroupLink( $group, $username ) {
		return User::makeGroupLinkHtml(
			$group,
			htmlspecialchars( User::getGroupMember( $group, $username ) )
		);
	}
}

/**
 * @ingroup SpecialPage
 */
class SpecialListUsers extends IncludableSpecialPage {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Listusers' );
	}

	/**
	 * Show the special page
	 *
	 * @param string $par (optional) A group to list users from
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$up = new UsersPager( $this->getContext(), $par, $this->including() );

		# getBody() first to check, if empty
		$usersbody = $up->getBody();

		$s = '';
		if ( !$this->including() ) {
			$s = $up->getPageHeader();
		}

		if ( $usersbody ) {
			$s .= $up->getNavigationBar();
			$s .= Html::rawElement( 'ul', array(), $usersbody );
			$s .= $up->getNavigationBar();
		} else {
			$s .= $this->msg( 'listusers-noresult' )->parseAsBlock();
		}

		$this->getOutput()->addHTML( $s );
	}

	protected function getGroupName() {
		return 'users';
	}
}

/**
 * Redirect page: Special:ListAdmins --> Special:ListUsers/sysop.
 *
 * @ingroup SpecialPage
 */
class SpecialListAdmins extends SpecialRedirectToSpecial {
	function __construct() {
		parent::__construct( 'Listadmins', 'Listusers', 'sysop' );
	}
}

/**
 * Redirect page: Special:ListBots --> Special:ListUsers/bot.
 *
 * @ingroup SpecialPage
 */
class SpecialListBots extends SpecialRedirectToSpecial {
	function __construct() {
		parent::__construct( 'Listbots', 'Listusers', 'bot' );
	}
}
