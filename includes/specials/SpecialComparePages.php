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
			),
			'Revision1' => array(
				'type' => 'int',
				'name' => 'rev1',
				'label-message' => 'compare-rev1',
				'size' => '8',
				'section' => 'page1',
			),
			'Page2' => array(
				'type' => 'text',
				'name' => 'page2',
				'label-message' => 'compare-page2',
				'size' => '40',
				'section' => 'page2',
			),
			'Revision2' => array(
				'type' => 'int',
				'name' => 'rev2',
				'label-message' => 'compare-rev2',
				'size' => '8',
				'section' => 'page2',
			),
			'Action' => array(
				'type' => 'hidden',
				'name' => 'action',
			),
			'Diffonly' => array(
				'type' => 'hidden',
				'name' => 'diffonly',
			),
		), 'compare' );
		$form->setSubmitText( wfMsg( 'compare-submit' ) );
		$form->suppressReset();
		$form->setMethod( 'get' );
		$form->setTitle( $this->getTitle() );

		$form->loadData();
		$form->displayForm( '' );

		self::showDiff( $form->mFieldData );
	}

	public static function showDiff( $data ){
		$rev1 = self::revOrTitle( $data['Revision1'], $data['Page1'] );
		$rev2 = self::revOrTitle( $data['Revision2'], $data['Page2'] );

		if( $rev1 && $rev2 ) {
			$de = new DifferenceEngine( null,
				$rev1,
				$rev2,
				null, // rcid
				( $data["Action"] == 'purge' ),
				false );
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
}
