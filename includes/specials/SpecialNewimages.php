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
		$this->setHeaders();
		$this->outputHeader();

		$pager = new NewFilesPager( $this->getContext(), $par );

		if ( !$this->including() ) {
			$form = $pager->getForm();
			$form->prepareForm();
			$form->displayForm( '' );
		}
		$this->getOutput()->addHTML( $pager->getBody() );
		if ( !$this->including() ) {
			$this->getOutput()->addHTML( $pager->getNavigationBar() );
		}
	}
}


/**
 * @ingroup SpecialPage Pager
 */
class NewFilesPager extends ReverseChronologicalPager {

	/**
	 * @var ImageGallery
	 */
	var $gallery;

	function __construct( IContextSource $context, $par = null ) {
		$this->like = $context->getRequest()->getText( 'like' );
		$this->showbots = $context->getRequest()->getBool( 'showbots' , 0 );
		if ( is_numeric( $par ) ) {
			$this->setLimit( $par );
		}

		parent::__construct( $context );
	}

	function getQueryInfo() {
		global $wgMiserMode;
		$conds = $jconds = array();
		$tables = array( 'image' );

		if( !$this->showbots ) {
			$groupsWithBotPermission = User::getGroupsWithPermission( 'bot' );
			if( count( $groupsWithBotPermission ) ) {
				$tables[] = 'user_groups';
				$conds[] = 'ug_group IS NULL';
				$jconds['user_groups'] = array(
					'LEFT JOIN',
					array(
						'ug_group' => $groupsWithBotPermission,
						'ug_user = img_user'
					)
				);
			}
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
		if ( !$this->gallery ) {
			$this->gallery = new ImageGallery();
		}
		return '';
	}

	function getEndBody(){
		return $this->gallery->toHTML();
	}

	function formatRow( $row ) {
		$name = $row->img_name;
		$user = User::newFromId( $row->img_user );

		$title = Title::makeTitle( NS_FILE, $name );
		$ul = Linker::link( $user->getUserpage(), $user->getName() );

		$this->gallery->add(
			$title,
			"$ul<br />\n<i>"
				. htmlspecialchars( $this->getLanguage()->userTimeAndDate( $row->img_timestamp, $this->getUser() ) )
				. "</i><br />\n"
		);
	}

	function getForm() {
		global $wgMiserMode;

		$fields = array(
			'like' => array(
				'type' => 'text',
				'label-message' => 'newimages-label',
				'name' => 'like',
			),
			'showbots' => array(
				'type' => 'check',
				'label' => $this->msg( 'showhidebots', $this->msg( 'show' )->plain() )->escaped(),
				'name' => 'showbots',
			#	'default' => $this->getRequest()->getBool( 'showbots', 0 ),
			),
			'limit' => array(
				'type' => 'hidden',
				'default' => $this->mLimit,
				'name' => 'limit',
			),
			'offset' => array(
				'type' => 'hidden',
				'default' => $this->getRequest()->getText( 'offset' ),
				'name' => 'offset',
			),
		);

		if( $wgMiserMode ){
			unset( $fields['like'] );
		}

		$form = new HTMLForm( $fields, $this->getContext() );
		$form->setTitle( $this->getTitle() );
		$form->setSubmitTextMsg( 'ilsubmit' );
		$form->setMethod( 'get' );
		$form->setWrapperLegendMsg( 'newimages-legend' );

		return $form;
	}
}
