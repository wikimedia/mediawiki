<?php
/**
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
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

class WebInstallerComplete extends WebInstallerPage {

	public function execute() {
		// Pop up a dialog box, to make it difficult for the user to forget
		// to download the file
		$lsUrl = $this->getVar( 'wgServer' ) . $this->parent->getUrl( [ 'localsettings' => 1 ] );
		$this->parent->request->response()->header( "Refresh: 0;url=$lsUrl" );
		$this->startForm();
		$this->parent->disableLinkPopups();
		$location = $this->parent->getLocalSettingsLocation();
		$msg = 'config-install-done';
		if ( $location !== false ) {
			$msg = 'config-install-done-path';
		}
		$this->parent->showSuccess( $msg,
			$lsUrl,
			$this->getVar( 'wgServer' ) .
				$this->getVar( 'wgScriptPath' ) . '/index.php',
			"[$lsUrl " . wfMessage( 'config-download-localsettings' )->plain() . ']',
			$location ?: ''
		);
		$this->addHTML( $this->parent->getInfoBox(
			wfMessage( 'config-extension-link' )->plain() ) );

		$this->parent->restoreLinkPopups();
		$this->endForm( false, false );
		return '';
	}

}
