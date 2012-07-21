<?php
/**
 * Implements Special:EditWatchlistGroup.
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
 * @ingroup Watchlist
 */

/**
 * Provides the UI through which users can modify their watchlist groups.
 *
 * @ingroup SpecialPage
 * @ingroup Watchlist
 * @author Aaron Pramana <aaron@sociotopia.com>
 */
class SpecialEditWatchlistGroup extends UnlistedSpecialPage {

	/**
	 * Holds a WatchlistGroup object
	 */
	protected $wg_obj;

	public function __construct() {
		parent::__construct( 'EditWatchlistGroup' );
	}

	/**
	 * Main execution point.
	 */
	public function execute($subPage) {
		$out = $this->getOutput();

		# Anons do not have a watchlist
		if( $this->getUser()->isAnon() ) {
			$out->setPageTitle( $this->msg( 'watchnologin' ) );
			$llink = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Userlogin' ),
				$this->msg( 'loginreqlink' )->escaped(),
				array(),
				array( 'returnto' => $this->getTitle()->getPrefixedText() )
			);
			$out->addHTML( $this->msg( 'watchlistanontext' )->rawParams( $llink )->parse() );
			return;
		}

		$this->wg_obj = WatchlistGroup::newFromUser( $this->getUser() );

		$this->setHeaders();
		$this->outputHeader();

		$out->addSubtitle( $this->msg( 'watchlistfor2', $this->getUser()->getName()
			)->rawParams( SpecialEditWatchlist::buildTools( null ) ) );

		$form = $this->getForm();
		if( $form->show() ) {
			$out->addHTML( $this->successMessage );
			$out->returnToMain();
		}
	}

	/**
	 * Get a form for editing watchlist groups
	 *
	 * @return HTMLForm
	 */
	protected function getForm() {
		$fields = array();
		$groups = $this->wg_obj->getGroups();

		// fields for each of the existing groups
		foreach( $groups as $id => $info ) {

			$fields['groupaction_' . $id] = array(
				'type' => 'select',
				'options' => array( $this->msg( 'wlgroup-noaction' )->escaped() => 0,
					$this->msg( 'wlgroup-rename' )->escaped() => 1,
					$this->msg( 'wlgroup-changeperm' )->escaped() => 2,
					$this->msg( 'wlgroup-delete' )->escaped() => -1 ),
				'label' => $this->msg( 'actions' )->parse(),
				'section' => $info[0]
			);

			$fields['grouprename_' . $id] = array(
				'type' => 'text',
				'label' => $this->msg( 'wlgroup-renameto' )->rawParams( $info[0] )->parse(),
				'size' => '15',
				'section' => $info[0]
			);

			$fields['groupperm_' . $id] = array(
				'type' => 'select',
				'options' => array( $this->msg( 'wlgroup-permprivate' )->parse() => 0,
					$this->msg( 'wlgroup-permpublic' )->parse() => 1 ),
				'default' => $info[1],
				'label' => $this->msg( 'wlgroup-perm' )->parse(),
				'section' => $info[0]
			);
		}

		// field used to add a new group
		$fields['groupnew'] = array(
			'type' => 'text',
			'label' => $this->msg( 'wlgroup-createnew' ),
			'size' => '15'
		);

		$form = new EditWatchlistGroupHTMLForm( $fields, $this->getContext() );
		$form->setTitle( $this->getTitle() )
			->setSubmitTextMsg( 'wlgroup-submit' )
			->setSubmitTooltip('wlgroup-submit')
			->setWrapperLegendMsg( 'wlgroup-legend' )
			->addHeaderText( $this->msg( 'wlgroup-explain' )->parse() )
			->setSubmitCallback( array( $this, 'submit' ) )
		;

		return $form;
	}

	/**
	 * The callback function for the watchlist group editing form
	 *
	 * @param $data array
	 */
	public function submit( $data ) {
		$wg = WatchlistGroup::newFromUser($this->getContext()->getUser());
		$status = true;
		// create a new group if requested
		if( $data['groupnew'] != '' ) {
			// for now, group names are limited to a-z, 0-9 - discuss tech. restrictions
			$name = WatchlistGroup::checkValidGroupName( $data['groupnew'] );
			if( $name ) {
				$status = $wg->createGroup( $name );
			} else {
				$status = false;
			}
		}

		foreach( $data as $key => $val ) {
			// rename, change permissions, or delete groups if requested
			if( substr( $key, 0, 12 ) == 'groupaction_' && intval( $val ) != 0 ) {
				$group = intval( substr( $key, 12 ) );
				if( intval( $val ) === 1 ) {
					// rename
					$name = WatchlistGroup::checkValidGroupName( $data['grouprename_' . $group] );
					if($name) {
						$status = $wg->renameGroup( $group, $name );
					} else {
						$status = false;
					}
				}
				if( intval( $val ) === 2 ) {
					// change perms
					$permval = intval( $data['groupperm_' . $group] );
					if( $permval === 0 || $permval === 1 ) {
						$status = $wg->changePerm( $group, $permval );
					} else {
						$status = false;
					}
				} elseif( intval( $val ) === -1 ) {
					// delete
					$status = $wg->deleteGroup( $group );
				}
			}
		}
		if( $status ) {
			$this->successMessage = $this->msg( 'wlgroup-success' )->escaped();
			return true;
		}
		return false;
	}
}

class EditWatchlistGroupHTMLForm extends HTMLForm {

	protected $mSubSectionBeforeFields = false;

	public function getLegend( $group ) {
		return $group;
	}

}
