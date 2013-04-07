<?php
/*
Lots of this file is heavily based on CentralAuth's SpecialGlobalGroupPermissionss
TODO: Move loads of i18n stuff to core
*/
class SpecialGroupPermissions extends SpecialPage {
	public function __construct() {
		parent::__construct( 'GroupPermissions' );
	}

	public function execute( $subpage ) {
		if ( !$this->userCanExecute( $this->getUser() ) ) {
			$this->displayRestrictionError();
			return;
		}

		$this->getOutput()->setPageTitle( $this->msg( 'grouppermissions' ) );
		$this->getOutput()->setRobotPolicy( "noindex,nofollow" );
		$this->getOutput()->setArticleRelated( false );
		$this->getOutput()->enableClientCache( false );

		if ( $subpage == '' ) {
			$subpage = $this->getRequest()->getVal( 'wpGroup' );
		}

		if ( $subpage != '' && $this->getUser()->matchEditToken( $this->getRequest()->getVal( 'wpEditToken' ) ) ) {
			$this->doSubmit( $subpage );
		} elseif ( $subpage != '' ) {
			$this->buildGroupView( $subpage );
		} else {
			$this->buildMainView();
		}
	}

	private function userCanEdit( User $user ) {
		return $user->isAllowed( 'editgrouppermissions' );
	}

	private function buildMainView() {
		global $wgScript, $wgGroupPermissions;

		$groups = array_keys( $wgGroupPermissions );

		// Existing groups
		$html = Xml::fieldset( $this->msg( 'centralauth-existinggroup-legend' )->text() );

		$this->getOutput()->addHTML( $html );

		if ( count( $groups ) ) {
			$this->getOutput()->addWikiMsg( 'grouppermissions-grouplistheader' );
			$this->getOutput()->addHTML( '<ul>' );

			foreach ( $groups as $group ) {
				$text = $this->msg(
					'grouppermissions-grouplistitem',
					$group == '*' ? '<nowiki>*</nowiki>' : User::getGroupName( $group ),
					$group,
					'<span class="centralauth-globalgroupperms-groupname">' . $group . '</span>'
				)->parse();

				$this->getOutput()->addHTML( "<li> $text </li>" );
			}
		} else {
			$this->getOutput()->addWikiMsg( 'centralauth-globalgroupperms-nogroups' );
		}

		$this->getOutput()->addHTML( Xml::closeElement( 'ul' ) . Xml::closeElement( 'fieldset' ) );

		if ( $this->userCanEdit( $this->getUser() ) ) {
			// "Create a group" prompt
			$html = Xml::fieldset( $this->msg( 'centralauth-newgroup-legend' )->text() );
			$html .= $this->msg( 'centralauth-newgroup-intro' )->parseAsBlock();
			$html .= Xml::openElement( 'form', array( 'method' => 'post', 'action' => $wgScript, 'name' => 'centralauth-globalgroups-newgroup' ) );
			$html .= Html::hidden( 'title',  SpecialPage::getTitleFor( 'GroupPermissions' )->getPrefixedText() );

			$fields = array( 'centralauth-globalgroupperms-newgroupname' => Xml::input( 'wpGroup' ) );

			$html .= Xml::buildForm( $fields, 'centralauth-globalgroupperms-creategroup-submit' );
			$html .= Xml::closeElement( 'form' );
			$html .= Xml::closeElement( 'fieldset' );

			$this->getOutput()->addHTML( $html );
		}
	}

	/**
	 * @param $group
	 */
	private function buildGroupView( $group ) {
		global $wgImplicitGroups;
		$editable = $this->userCanEdit( $this->getUser() );

		$subtitleMessage = $editable ? 'centralauth-editgroup-subtitle' : 'centralauth-editgroup-subtitle-readonly';
		$this->getOutput()->setSubtitle( $this->msg( $subtitleMessage, $group ) );

		$fieldsetClass = $editable ? 'mw-centralauth-editgroup' : 'mw-centralauth-editgroup-readonly';
		$html = Xml::fieldset( $this->msg( 'centralauth-editgroup-fieldset', $group )->text(), false, array( 'class' => $fieldsetClass ) );

		if ( $editable ) {
			$html .= Xml::openElement( 'form', array( 'method' => 'post', 'action' => SpecialPage::getTitleFor( 'GroupPermissions', $group )->getLocalUrl(), 'name' => 'centralauth-globalgroups-newgroup' ) );
			$html .= Html::hidden( 'wpGroup', $group );
			$html .= Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() );
		}

		$fields = array( 'centralauth-editgroup-name' => $group );

		$fields['grouppermissions-grouptype'] = $this->msg( 'grouppermissions-' . ( in_array( $group, $wgImplicitGroups ) ? 'im' : 'ex' ) . 'plicit' );

		if ( $group != '*' ) {
			if ( $this->getUser()->isAllowed( 'editinterface' ) ) {
				# Show edit link only to user with the editinterface right
				$fields['centralauth-editgroup-display'] = $this->msg( 'centralauth-editgroup-display-edit', $group, User::getGroupName( $group ) )->parse();
				$fields['centralauth-editgroup-member'] = $this->msg( 'centralauth-editgroup-member-edit', $group, User::getGroupMember( $group ) )->parse();
			} else {
				$fields['centralauth-editgroup-display'] = User::getGroupName( $group );
				$fields['centralauth-editgroup-member'] = User::getGroupMember( $group );
			}
		}

		if ( !in_array( $group, $wgImplicitGroups ) ) {
			$fields['centralauth-editgroup-members'] = $this->msg( 'grouppermissions-members-link', $group, User::getGroupMember( $group ) )->parse();
		}
		$fields['centralauth-editgroup-perms'] = $this->buildCheckboxes( $group );

		if ( $editable ) {
			$fields['centralauth-editgroup-reason'] = Xml::input( 'wpReason', 60 );
		}

		$html .= Xml::buildForm( $fields,  $editable ? 'grouppermissions-submit' : null );

		if ( $editable ) {
			$html .= Xml::closeElement( 'form' );
		}

		$html .= Xml::closeElement( 'fieldset' );

		$this->getOutput()->addHTML( $html );

		$this->showLogFragment( $group, $this->getOutput() );
	}

	/**
	 * @param $group
	 * @return string
	 */
	function buildCheckboxes( $group ) {
		global $wgGroupPermissions, $wgGroupConfigSource, $wgRestrictedRights;
		$rights = User::getAllRights();
		$assignedRights = array();
		if ( isset( $wgGroupPermissions[$group] ) ) {
			foreach ( $wgGroupPermissions[$group] as $permission => $value ) {
				if ( $value ) {
					$assignedRights[] = $permission;
				}
			}
		}

		sort( $rights );

		$checkboxes = array();

		foreach ( $rights as $right ) {
			# Build a checkbox.
			$checked = in_array( $right, $assignedRights );

			$desc = $this->getOutput()->parseInline( User::getRightDescription( $right ) ) . ' ' .
						Xml::element( 'code', null, $this->msg( 'parentheses', $right )->text() );

			$disabled = !( $this->getUser()->isAllowed( 'editgrouppermissions' ) && $wgGroupConfigSource == 'database' && !in_array( $right, $wgRestrictedRights ) );
			$checkbox = Xml::check( "wpRightAssigned-$right", $checked,
				array_merge( $disabled ? array( 'disabled' => 'disabled' ) : array(), array( 'id' => "wpRightAssigned-$right" ) ) );
			$label = Xml::tags( 'label', array( 'for' => "wpRightAssigned-$right" ),
					$desc );

			$liClass = $checked ? 'mw-centralauth-editgroup-checked' : 'mw-centralauth-editgroup-unchecked';
			$checkboxes[] = Html::rawElement( 'li', array( 'class' => $liClass ), "$checkbox&#160;$label" );
		}

		$count = count( $checkboxes );

		$firstCol = round( $count / 2 );

		$checkboxes1 = array_slice( $checkboxes, 0, $firstCol );
		$checkboxes2 = array_slice( $checkboxes, $firstCol );

		$html = '<table><tbody><tr><td><ul>';

		foreach ( $checkboxes1 as $cb ) {
			$html .= $cb;
		}

		$html .= '</ul></td><td><ul>';

		foreach ( $checkboxes2 as $cb ) {
			$html .= $cb;
		}

		$html .= '</ul></td></tr></tbody></table>';

		return $html;
	}

	/**
	 * @param $group
	 * @param $output OutputPage
	 */
	protected function showLogFragment( $group, $output ) {
		$title = SpecialPage::getTitleFor( 'ListUsers', $group );
		$logPage = new LogPage( 'rights' );
		$output->addHTML( Xml::element( 'h2', null, $logPage->getName()->text() . "\n" ) );
		LogEventsList::showLogExtract( $output, 'rights', $title->getPrefixedText() );
	}
}
