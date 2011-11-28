<?php
/**
 * Implements Special:Protectedtitles
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
 * A special page that list protected titles from creation
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedtitles extends SpecialPage {

	public function __construct() {
		parent::__construct( 'Protectedtitles' );
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Title::purgeExpiredRestrictions();
		}

		$pager = new ProtectedTitlesPager( $this );
		$this->getOutput()->addHTML( $pager->buildHTMLForm() );

		if ( $pager->getNumRows() ) {
			$s = $pager->getNavigationBar();
			$s .= "<ul>" .
				$pager->getBody() .
				"</ul>";
			$s .= $pager->getNavigationBar();
		} else {
			$s = '<p>' . wfMsgHtml( 'protectedtitlesempty' ) . '</p>';
		}
		$this->getOutput()->addHTML( $s );
	}

	/**
	 * Callback function to output a restriction
	 *
	 * @param $row
	 * @return string
	 */
	function formatRow( $row ) {
		wfProfileIn( __METHOD__ );

		static $infinity = null;

		if( is_null( $infinity ) ){
			$infinity = wfGetDB( DB_SLAVE )->getInfinity();
		}

		$title = Title::makeTitleSafe( $row->pt_namespace, $row->pt_title );
		$link = Linker::link( $title );

		$description_items = array ();

		$protType = wfMsgHtml( 'restriction-level-' . $row->pt_create_perm );

		$description_items[] = $protType;

		$lang = $this->getLanguage();
		$expiry = strlen( $row->pt_expiry ) ? $lang->formatExpiry( $row->pt_expiry, TS_MW ) : $infinity;
		if( $expiry != $infinity ) {
			$expiry_description = wfMsg(
				'protect-expiring-local',
				$lang->timeanddate( $expiry, true ),
				$lang->date( $expiry, true ),
				$lang->time( $expiry, true )
			);

			$description_items[] = htmlspecialchars($expiry_description);
		}

		wfProfileOut( __METHOD__ );

		return '<li>' . $lang->specialList( $link, implode( $description_items, ', ' ) ) . "</li>\n";
	}
}

/**
 * @todo document
 * @ingroup Pager
 */
class ProtectedTitlesPager extends AlphabeticPager {
	/**
	 * @var SpecialProtectedtitles
	 */
	public $mForm;

	/**
	 * @var array
	 */
	public $mConds;

	/**
	 * @param $form SpecialProtectedtitles
	 * @param $conds array
	 */
	function __construct( $form, $conds = array() ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		parent::__construct( $form->getContext() );
	}

	function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		$this->mResult->seek( 0 );
		$lb = new LinkBatch;

		foreach ( $this->mResult as $row ) {
			$lb->add( $row->pt_namespace, $row->pt_title );
		}

		$lb->execute();
		wfProfileOut( __METHOD__ );
		return '';
	}

	/**
	 * @return Title
	 */
	function getTitle() {
		return SpecialPage::getTitleFor( 'Protectedtitles' );
	}

	function formatRow( $row ) {
		return $this->mForm->formatRow( $row );
	}

	/**
	 * @return array
	 */
	function getQueryInfo() {
		$conds = $this->mConds;
		$conds[] = 'pt_expiry>' . $this->mDb->addQuotes( $this->mDb->timestamp() );

		if ( $this->mHTMLForm->getVal( 'Level' ) ) {
			$conds['pt_create_perm'] = $this->mHTMLForm->getVal( 'Level' );
		}
		if ( $this->mHTMLForm->getVal( 'Namespace' ) !== '' ) {
			$conds['pt_namespace'] = $this->mHTMLForm->getVal( 'Namespace' );
		}

		return array(
			'tables' => 'protected_titles',
			'fields' => 'pt_namespace,pt_title,pt_create_perm,pt_expiry,pt_timestamp',
			'conds' => $conds
		);
	}

	function getIndexField() {
		return 'pt_timestamp';
	}

	protected function getHTMLFormFields() {
		return array(
			 'Namespace' => array(
				 'type' => 'namespaces',
				 'label-message' => 'namespace',
			 ),
			 'Level' => array(
				 'type' => 'restrictionlevels',
				 'label-message' => 'restriction-level',
			 ),
		 );
	}

	protected function getHTMLFormSubmit() {
		return 'allpagessubmit';
	}

	protected function getHTMLFormLegend() {
		return 'protectedtitles';
	}


}

