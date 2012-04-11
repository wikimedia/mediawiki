<?php
/**
 * Maintenance script to import all scripts in the MediaWiki namespace from a
 * local site.
 * @file
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

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
			$text = Http::get( $url );

			$wikiPage = WikiPage::factory( $title );
			$wikiPage->doEdit( $text, "Importing from $url", 0, false, $user );
		}
		
	}
	
	protected function fetchScriptList() {
		$data = array( 
			'action' => 'query',
			'format' => 'php',//'json',
			'list' => 'allpages',
			'apnamespace' => '8',
			'aplimit' => '500', 
		);
		$baseUrl = $this->getArg( 0 );
		$pages = array();
		
		do {
			$url = wfAppendQuery( $baseUrl, $data );
			$strResult = Http::get( $url );
			//$result = FormatJson::decode( $strResult ); // Still broken
			$result = unserialize( $strResult );
			
			if ( !empty( $result['query']['allpages'] ) ) {
				foreach ( $result['query']['allpages'] as $page ) {
					if ( substr( $page['title'], -3 ) === '.js' ) {
						strtok( $page['title'], ':' );
						$pages[] = strtok( '' );
					}
				}
			}
			if ( !empty( $result['query-continue'] ) ) {
				$data['apfrom'] = $result['query-continue']['allpages']['apfrom'];
				$this->output( "Fetching new batch from {$data['apfrom']}\n" );
			}
		} while ( isset( $result['query-continue'] ) );
		
		return $pages;
		
	}
}

$maintClass = 'ImportSiteScripts';
require_once( RUN_MAINTENANCE_IF_MAIN );
