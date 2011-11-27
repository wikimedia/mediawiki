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

		$out = $this->getOutput();
		$pager = new NewFilesPager( $this->getContext(), $par );

		if ( !$this->including() ) {
			$out->addHTML( $pager->buildHTMLForm() );
		}
		$out->addHTML( $pager->getBody() );
		if ( !$this->including() ) {
			$out->addHTML( $pager->getNavigationBar() );
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

		parent::__construct( $context );
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
		$name = $row->img_name;
		$user = User::newFromId( $row->img_user );

		$title = Title::makeTitle( NS_FILE, $name );
		$ul = Linker::link( $user->getUserpage(), $user->getName() );

		$this->gallery->add(
			$title,
			"$ul<br />\n<i>"
				. htmlspecialchars( $this->getLanguage()->timeanddate( $row->img_timestamp, true ) )
				. "</i><br />\n"
		);
	}

	protected function getHTMLFormFields() {
		global $wgMiserMode;

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
			),
		);

		if( $wgMiserMode ){
			unset( $fields['like'] );
		}

		return $fields;
	}

	protected function getHTMLFormLegend() {
		return 'newimages-legend';
	}

	protected function getHTMLFormSubmit() {
		return 'ilsubmit';
	}
}
