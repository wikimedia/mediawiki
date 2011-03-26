<?php
/**
 * Implements Special:Newimages
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
class SpecialNewFiles extends IncludableSpecialPage {

	public function __construct(){
		parent::__construct( 'Newimages' );
	}

	public function execute( $par ){
		global $wgOut, $wgLang;
		$this->setHeaders();

		$pager = new NewFilesPager( $par );

		$form = $pager->getForm();
		$wgOut->addWikiMsg( 'newimages-text' );
		$form->prepareForm();
		$form->displayForm( '' );
		$wgOut->addHTML( $pager->getBody() . $pager->getNavigationBar() );
	}
}


/**
 * @ingroup SpecialPage Pager
 */
class NewFilesPager extends ReverseChronologicalPager {

	function __construct( $par = null ) {
		global $wgRequest, $wgUser;

		$this->like = $wgRequest->getText( 'like' );
		$this->showbots = $wgRequest->getBool( 'showbots' , 0 );
		$this->skin = $wgUser->getSkin();

		parent::__construct();
	}

	function getQueryInfo() {
		global $wgMiserMode;
		$conds = $jconds = array();
		$tables = array( 'image' );

		if( !$this->showbots ) {
			$tables[] = 'user_groups';
			$conds[] = 'ug_group IS NULL';
			$jconds['user_groups'] = array(
				'LEFT JOIN',
				array(
					'ug_group' => User::getGroupsWithPermission( 'bot' ),
					'ug_user = img_user'
				)
			);
		}

		if( !$wgMiserMode && $this->like !== null ){
			$dbr = wfGetDB( DB_SLAVE );
			$likeObj = Title::newFromURL( $this->like );
			if( $likeObj instanceof Title ){
				$like = $dbr->buildLike( $dbr->anyString(), strtolower( $likeObj->getDBkey() ), $dbr->anyString() );
				$conds[] = "LOWER(img_name) $like";
			}
		}

		$query = array(
			'tables' => $tables,
			'fields' => '*',
			'join_conds' => $jconds,
			'conds' => $conds
		);

		return $query;
	}

	function getIndexField(){
		return 'img_timestamp';
	}

	function getStartBody(){
		$this->gallery = new ImageGallery();
	}

	function getEndBody(){
		return $this->gallery->toHTML();
	}

	function formatRow( $row ) {
		global $wgLang;

		$name = $row->img_name;
		$user = User::newFromId( $row->img_user );

		$title = Title::newFromText( $name, NS_FILE );
		$ul = $this->skin->link( $user->getUserpage(), $user->getName() );

		$this->gallery->add(
			$title,
			"$ul<br />\n<i>"
				. htmlspecialchars( $wgLang->timeanddate( $row->img_timestamp, true ) )
				. "</i><br />\n"
		);
	}

	function getForm() {
		global $wgRequest, $wgMiserMode;

		$fields = array(
			'like' => array(
				'type' => 'text',
				'label-message' => 'newimages-label',
				'name' => 'like',
			),
			'showbots' => array(
				'type' => 'check',
				'label' => wfMessage( 'showhidebots', wfMsg( 'show' ) ),
				'name' => 'showbots',
			#	'default' => $wgRequest->getBool( 'showbots', 0 ),
			),
			'limit' => array(
				'type' => 'hidden',
				'default' => $wgRequest->getText( 'limit' ),
				'name' => 'limit',
			),
			'offset' => array(
				'type' => 'hidden',
				'default' => $wgRequest->getText( 'offset' ),
				'name' => 'offset',
			),
		);

		if( $wgMiserMode ){
			unset( $fields['like'] );
		}

		$form = new HTMLForm( $fields );
		$form->setTitle( $this->getTitle() );
		$form->setSubmitText( wfMsg( 'ilsubmit' ) );
		$form->setMethod( 'get' );
		$form->setWrapperLegend( wfMsg( 'newimages-legend' ) );

		return $form;
	}
}