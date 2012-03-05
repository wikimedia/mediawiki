<?php
/*
 * Simple entry point to initiate a background download
 *
 * arguments:
 *  --sid {$session_id} --usk {$upload_session_key} --wiki {wfWikiId()}
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class HttpSessionDownload extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Simple entry point to initiate a background download";
		$this->addOption( 'sid',  'Session ID', true, true );
		$this->addOption( 'usk',  'Upload session key', true, true );
	}

	public function execute() {
		wfProfileIn( __METHOD__ );

		// run the download:
		Http::doSessionIdDownload( $this->getOption( 'sid' ), $this->getOption( 'usk' ) );

		// close up shop:
		// Execute any deferred updates
		wfDoUpdates();

		// Log what the user did, for book-keeping purposes.
		wfLogProfilingData();

		// Shut down the database before exit
		wfGetLBFactory()->shutdown();

		wfProfileOut( __METHOD__ );
	}
}

$maintClass = "HttpSessionDownload";
require_once( DO_MAINTENANCE );
