<?php
/**
 * Implements Special:Filepath
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
 * A special page that redirects to the URL of a given file
 *
 * @ingroup SpecialPage
 */
class SpecialFilepath extends SpecialPage {

	function __construct() {
		parent::__construct( 'Filepath' );
	}

	function execute( $par ) {
		global $wgRequest, $wgOut;

		$this->setHeaders();
		$this->outputHeader();

		$file = !is_null( $par ) ? $par : $wgRequest->getText( 'file' );

		$title = Title::makeTitleSafe( NS_FILE, $file );

		if ( ! $title instanceof Title || $title->getNamespace() != NS_FILE ) {
			$this->showForm( $title );
		} else {
			$file = wfFindFile( $title );

			if ( $file && $file->exists() ) {
				// Default behaviour: Use the direct link to the file.
				$url = $file->getURL();
				$width = $wgRequest->getInt( 'width', -1 );
				$height = $wgRequest->getInt( 'height', -1 );

				// If a width is requested...
				if ( $width != -1 ) {
					$mto = $file->transform( array( 'width' => $width, 'height' => $height ) );
					// ... and we can
					if ( $mto && !$mto->isError() ) {
						// ... change the URL to point to a thumbnail.
						$url = $mto->getURL();
					}
				}
				$wgOut->redirect( $url );
			} else {
				$wgOut->setStatusCode( 404 );
				$this->showForm( $title );
			}
		}
	}

	function showForm( $title ) {
		global $wgOut, $wgScript;

		$wgOut->addHTML(
			Html::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'id' => 'specialfilepath' ) ) .
			Html::openElement( 'fieldset' ) .
			Html::element( 'legend', null, wfMsg( 'filepath' ) ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) .
			Xml::inputLabel( wfMsg( 'filepath-page' ), 'file', 'file', 25, is_object( $title ) ? $title->getText() : '' ) . ' ' .
			Xml::submitButton( wfMsg( 'filepath-submit' ) ) . "\n" .
			Html::closeElement( 'fieldset' ) .
			Html::closeElement( 'form' )
		);
	}
}
