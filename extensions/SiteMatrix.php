<?php

# Make an HTML table showing all the wikis on the site


$wgExtensionFunctions[] = "wfSiteMatrix";

function wfSiteMatrix() {
class SiteMatrixPage extends SpecialPage
{
	function SiteMatrixPage() {
		SpecialPage::SpecialPage("SiteMatrix");
	}

	function execute( $par ) {
		global $wgRequest, $wgOut, $wgTitle, $wgLocalDatabases;
		$this->setHeaders();

		$langlist = array_map( 'trim', file( '/home/wikipedia/common/langlist' ) );
		sort( $langlist );
		$xLanglist = array_flip( $langlist );

		$sites = array( 'wiki', 'wiktionary', 'wikibooks', 'wikiquote' );
		$names = array( 
			'wiki' => 'Wikipedia<br />w',
			'wiktionary' => 'Wiktionary<br />wikt',
			'wikibooks' => 'Wikibooks<br />b',
			'wikiquote' => 'Wikiquote<br />q'
		);
		$hosts = array(
			'wiki' => 'wikipedia.org',
			'wiktionary' => 'wiktionary.org',
			'wikibooks' => 'wikibooks.org',
			'wikiquote' => 'wikiquote.org'
		);
		
		# Tabulate the matrix
		$specials = array();
		$matrix = array();
		foreach( $wgLocalDatabases as $db ) {
			# Find suffix
			foreach ( $sites as $site ) {
				if ( preg_match( "/(.*)$site\$/", $db, $m ) ) {
					$lang = $m[1];
					if ( empty( $xLanglist[$lang] ) && $site == 'wiki' ) {
						$specials[] = $lang;
					} else {
						$matrix[$site][$lang] = 1;
					}
					break;
				}
			}
		}

		# Construct the HTML

		# Header row
		$s = "<table><tr>";
		foreach ( $names as $name ) {
			$s .= "<td><strong>$name</strong></td>";
		}
		$s .= "</tr>\n";

		# Bulk of table
		foreach ( $langlist as $lang ) {
			$s .= "<tr>";
			foreach ( $names as $site => $name ) {
				$url = "http://$lang." . $hosts[$site] . "/";
				if ( empty( $matrix[$site][$lang] ) ) {
					# Non-existent wiki
					$s .= "<td><a href=\"$url\" class=\"new\">$lang</a></td>";
				} else {
					# Wiki exists
					$s .= "<td><a href=\"$url\">$lang</a></td>";
				}
			}
			$s .= "</tr>\n";
		}
		$s .= "</table>\n";

		# Specials
		$s .= "<ul>";
		foreach ( $specials as $lang ) {
			$s .= "<li><a href=\"http://$lang.wikipedia.org/\">$lang</a></li>\n";
		}
		$s .= "</ul>";
		$wgOut->addHTML( $s );
	}
}

SpecialPage::addPage( new SiteMatrixPage );
global $wgMessageCache;
$wgMessageCache->addMessage( "sitematrix", "List of Wikimedia wikis" );

} # End of extension function
?>
