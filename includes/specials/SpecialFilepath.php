<?php
/**
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
 */

/**
 * @file
 * @ingroup SpecialPage
 */

function wfSpecialFilepath( $par ) {
	global $wgRequest, $wgOut;

	$file = isset( $par ) ? $par : $wgRequest->getText( 'file' );

	$title = Title::makeTitleSafe( NS_FILE, $file );

	if ( ! $title instanceof Title || $title->getNamespace() != NS_FILE ) {
		$cform = new FilepathForm( $title );
		$cform->execute();
	} else {
		$file = wfFindFile( $title );
		if ( $file && $file->exists() ) {
			$wgOut->redirect( $file->getURL() );
		} else {
			$wgOut->setStatusCode( 404 );
			$cform = new FilepathForm( $title );
			$cform->execute();
		}
	}
}

/**
 * @ingroup SpecialPage
 */
class FilepathForm {
	var $mTitle;

	function FilepathForm( &$title ) {
		$this->mTitle =& $title;
	}

	function execute() {
		global $wgOut, $wgScript;

		$wgOut->addHTML(
			Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'id' => 'specialfilepath' ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'filepath' ) ) .
			Xml::hidden( 'title', SpecialPage::getTitleFor( 'Filepath' )->getPrefixedText() ) .
			Xml::inputLabel( wfMsg( 'filepath-page' ), 'file', 'file', 25, is_object( $this->mTitle ) ? $this->mTitle->getText() : '' ) . ' ' .
			Xml::submitButton( wfMsg( 'filepath-submit' ) ) . "\n" .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);
	}
}
