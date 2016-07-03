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
 * @ingroup Deployment
 */

abstract class WebInstallerDocument extends WebInstallerPage {

	/**
	 * @return string
	 */
	abstract protected function getFileName();

	public function execute() {
		$text = $this->getFileContents();
		$text = InstallDocFormatter::format( $text );
		$this->parent->output->addWikiText( $text );
		$this->startForm();
		$this->endForm( false );
	}

	/**
	 * @return string
	 */
	public function getFileContents() {
		$file = __DIR__ . '/../../' . $this->getFileName();
		if ( !file_exists( $file ) ) {
			return wfMessage( 'config-nofile', $file )->plain();
		}

		return file_get_contents( $file );
	}

}
