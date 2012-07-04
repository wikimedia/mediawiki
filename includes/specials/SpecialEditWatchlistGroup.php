<?php
/**
 * Implements Special:EditWatchlistGroup
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
 * Provides the UI through which users can modify their watchlist groups
 *
 * @ingroup SpecialPage
 * @ingroup Watchlist
 * @author Aaron Pramana <aaron@sociotopia.com>
 */
class SpecialEditWatchlistGroup extends UnlistedSpecialPage {
	public function __construct(){
		parent::__construct( 'EditWatchlistGroup' );
	}

	/**
	 * Main execution point
	 *
	 * @param $mode int
	 */
	public function execute() {
		$out = $this->getOutput();

		# Anons don't get a watchlist
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

		$this->setHeaders();
		$out->setPageTitle( $this->msg( 'wlgroup-title' ) );
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
		$groups = WatchlistGroup::getGroups( $this->getContext()->getUser()->getId() );

		// fields for each of the existing groups
		foreach( $groups as $id => $name ) {

			$fields['groupaction_' . $id] = array(
				'type' => 'select',
				'options' => array( $this->msg('wlgroup-noaction')->parse() => 0,
									$this->msg('wlgroup-rename')->parse() => 1,
									$this->msg('wlgroup-delete')->parse() => -1 ),
				'label' => $name . ':'
			);

			$fields['grouprename_' . $id] = array(
				'type' => 'text',
				'label' => $this->msg( 'wlgroup-renameto' )->rawParams( $name )->parse(),
				'size' => '15'
			);
		}
		// leave space
		$fields['blank'] = array(
				'type' => 'info',
				'label' => '&nbsp;'
		);
		// field for new group
		$fields['groupnew'] = array(
				'type' => 'text',
				'label' => $this->msg( 'wlgroup-createnew' ),
				'size' => '15'
		);

		$form = new HTMLForm( $fields, $this->getContext() );
		$form->setTitle( $this->getTitle() );
		$form->setSubmitTextMsg( 'wlgroup-submit' );
		$form->setSubmitTooltip('wlgroup-submit');
		$form->setWrapperLegendMsg( 'wlgroup-legend' );
		$form->addHeaderText( $this->msg( 'wlgroup-explain' )->parse() );
		$form->setSubmitCallback( array( $this, 'submit' ) );
		return $form;
	}

	/**
	 * The callback function for the watchlist group editing form
	 *
	 * @param $data array
	 */
	public function submit( $data ) {
		$status = true;
		// create a new group if requested
		if( $data['groupnew'] != '' ){
			// for now, group names are limited to a-z, 0-9 - discuss tech. restrictions
			$name = WatchlistGroup::checkValidGroupName( $data['groupnew'] );
			if( $name ){
				$status = WatchlistGroup::createGroup( $name, $this->getContext() );
			}
			else{
				$status = false;
			}
		}
		// rename or delete groups if requested
		foreach( $data as $key => $val ){
			if( substr( $key, 0, 12 ) == 'groupaction_' && intval( $val ) != 0 ){
				$group = intval( substr( $key, 12 ) );
				// rename
				if( intval( $val ) === 1 ){
					$name = WatchlistGroup::checkValidGroupName( $data['grouprename_' . $group] );
					if($name){
						$status = WatchlistGroup::renameGroup( $group, $name, $this->getContext() );
					}
					else{
						$status = false;
					}
				}
				// delete
				else if( intval( $val ) === -1 ){
					$status = WatchlistGroup::deleteGroup( $group, $this->getContext() );
				}
			}
		}
		if( $status ){
			$this->successMessage = $this->msg( 'wlgroup-success' )->escaped();
			return true;
		}
		return false;
	}
}
