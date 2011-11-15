<?php
/**
 * Implements Special:ComparePages
 *
 * Copyright © 2010 Derk-Jan Hartman <hartman@videolan.org>
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
 * Implements Special:ComparePages
 *
 * @ingroup SpecialPage
 */
class SpecialComparePages extends SpecialPage {

	// Stored objects
	protected $opts, $skin;

	// Some internal settings
	protected $showNavigation = false;

	public function __construct() {
		parent::__construct( 'ComparePages' );
	}

	/**
	 * Show a form for filtering namespace and username
	 *
	 * @param $par String
	 * @return String
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$form = new HTMLForm( array(
			'Page1' => array(
				'type' => 'text',
				'name' => 'page1',
				'label-message' => 'compare-page1',
				'size' => '40',
				'section' => 'page1',
				'validation-callback' => array( $this, 'checkExistingTitle' ),
			),
			'Revision1' => array(
				'type' => 'int',
				'name' => 'rev1',
				'label-message' => 'compare-rev1',
				'size' => '8',
				'section' => 'page1',
				'validation-callback' => array( $this, 'checkExistingRevision' ),
			),
			'Page2' => array(
				'type' => 'text',
				'name' => 'page2',
				'label-message' => 'compare-page2',
				'size' => '40',
				'section' => 'page2',
				'validation-callback' => array( $this, 'checkExistingTitle' ),
			),
			'Revision2' => array(
				'type' => 'int',
				'name' => 'rev2',
				'label-message' => 'compare-rev2',
				'size' => '8',
				'section' => 'page2',
				'validation-callback' => array( $this, 'checkExistingRevision' ),
			),
			'Action' => array(
				'type' => 'hidden',
				'name' => 'action',
			),
			'Diffonly' => array(
				'type' => 'hidden',
				'name' => 'diffonly',
			),
			'Unhide' => array(
				'type' => 'hidden',
				'name' => 'unhide',
			),
		), $this->getContext(), 'compare' );
		$form->setSubmitText( wfMsg( 'compare-submit' ) );
		$form->suppressReset();
		$form->setMethod( 'get' );
		$form->setSubmitCallback( array( __CLASS__, 'showDiff' ) );

		$form->loadData();
		$form->displayForm( '' );
		$form->trySubmit();
	}

	public static function showDiff( $data, HTMLForm $form ){
		$rev1 = self::revOrTitle( $data['Revision1'], $data['Page1'] );
		$rev2 = self::revOrTitle( $data['Revision2'], $data['Page2'] );

		if( $rev1 && $rev2 ) {
			$de = new DifferenceEngine( $form->getContext(),
				$rev1,
				$rev2,
				null, // rcid
				( $data['Action'] == 'purge' ),
				( $data['Unhide'] == '1' )
			);
			$de->showDiffPage( true );
		}
	}

	public static function revOrTitle( $revision, $title ) {
		if( $revision ){
			return $revision;
		} elseif( $title ) {
			$title = Title::newFromText( $title );
			if( $title instanceof Title ){
				return $title->getLatestRevID();
			}
		}
		return null;
	}

	public function checkExistingTitle( $value, $alldata ) {
		if ( $value === '' || $value === null ) {
			return true;
		}
		$title = Title::newFromText( $value );
		if ( !$title instanceof Title ) {
			return wfMsgExt( 'compare-invalid-title', 'parse' );
		}
		if ( !$title->exists() ) {
			return wfMsgExt( 'compare-title-not-exists', 'parse' );
		}
		return true;
	}

	public function checkExistingRevision( $value, $alldata ) {
		if ( $value === '' || $value === null ) {
			return true;
		}
		$revision = Revision::newFromId( $value );
		if ( $revision === null ) {
			return wfMsgExt( 'compare-revision-not-exists', 'parse' );
		}
		return true;
	}
}
