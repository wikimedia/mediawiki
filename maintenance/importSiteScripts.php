<?php
/**
 * Import all scripts in the MediaWiki namespace from a local site.
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to import all scripts in the MediaWiki namespace from a
 * local site.
 *
 * @ingroup Maintenance
 */
class ImportSiteScripts extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Import site scripts from a site';
		$this->addArg( 'api', 'API base url' );
		$this->addArg( 'index', 'index.php base url' );
		$this->addOption( 'username', 'User name of the script importer' );
	}

	public function execute() {
		global $wgUser;

		$user = User::newFromName( $this->getOption( 'username', 'ScriptImporter' ) );
		$wgUser = $user;

		$baseUrl = $this->getArg( 1 );
		$pageList = $this->fetchScriptList();
		$this->output( 'Importing ' . count( $pageList ) . " pages\n" );

		foreach ( $pageList as $page ) {
			$title = Title::makeTitleSafe( NS_MEDIAWIKI, $page );
			if ( !$title ) {
				$this->error( "$page is an invalid title; it will not be imported\n" );
				continue;
			}

			$this->output( "Importing $page\n" );
			$url = wfAppendQuery( $baseUrl, array(
				'action' => 'raw',
				'title' => "MediaWiki:{$page}" ) );
			$text = Http::get( $url, array(), __METHOD__ );

			$wikiPage = WikiPage::factory( $title );
			$content = ContentHandler::makeContent( $text, $wikiPage->getTitle() );
			$wikiPage->doEditContent( $content, "Importing from $url", 0, false, $user );
		}
	}

	protected function fetchScriptList() {
		$data = array(
			'action' => 'query',
			'format' => 'json',
			'list' => 'allpages',
			'apnamespace' => '8',
			'aplimit' => '500',
			'continue' => '',
		);
		$baseUrl = $this->getArg( 0 );
		$pages = array();

		while ( true ) {
			$url = wfAppendQuery( $baseUrl, $data );
			$strResult = Http::get( $url, array(), __METHOD__ );
			$result = FormatJson::decode( $strResult, true );

			$page = null;
			foreach ( $result['query']['allpages'] as $page ) {
				if ( substr( $page['title'], -3 ) === '.js' ) {
					strtok( $page['title'], ':' );
					$pages[] = strtok( '' );
				}
			}

			if ( $page !== null ) {
				$this->output( "Fetched list up to {$page['title']}\n" );
			}

			if ( isset( $result['continue'] ) ) { // >= 1.21
				$data = array_replace( $data, $result['continue'] );
			} elseif ( isset( $result['query-continue']['allpages'] ) ) { // <= 1.20
				$data = array_replace( $data, $result['query-continue']['allpages'] );
			} else {
				break;
			}
		}

		return $pages;
	}
}

$maintClass = 'ImportSiteScripts';
require_once RUN_MAINTENANCE_IF_MAIN;
