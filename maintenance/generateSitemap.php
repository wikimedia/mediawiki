<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * Creates a Google sitemap.
 * https://www.google.com/webmasters/sitemaps/docs/en/about.html
 */

# Copyright (C) 2005 Jens Frank <jeluf@gmx.de>, Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

if ( $argc < 2) {
	print "Usage: php generateSitemap.php servername [options]\n";
	print " servername is the name of the website, e.g. mywiki.mydomain.org\n";
	exit ;
}
$_SERVER['HOSTNAME'] = $argv[1];
print $argv[1] . "\n";


/** */
require_once( "commandLine.inc" );
 print "DB name: $wgDBname\n";
 print "DB user: $wgDBuser\n";

$priorities = array (
        NS_MAIN             => 0.9,
        NS_TALK             => 0.4,
        NS_USER             => 0.3,
        NS_USER_TALK        => 0.3,
        NS_PROJECT          => 0.5,
        NS_PROJECT_TALK     => 0.2,
        NS_IMAGE            => 0.2,
        NS_IMAGE_TALK       => 0.1,
        NS_MEDIAWIKI        => 0.1,
        NS_MEDIAWIKI_TALK   => 0.1,
        NS_TEMPLATE         => 0.1,
        NS_TEMPLATE_TALK    => 0.1,
        NS_HELP             => 0.3,
        NS_HELP_TALK        => 0.1,
        NS_CATEGORY         => 0.3,
        NS_CATEGORY_TALK    => 0.1,
);

$dbr =& wfGetDB( DB_SLAVE );
$page = $dbr->tableName( 'page' );
$rev = $dbr->tableName( 'revision' );

$findex = fopen( "sitemap-index-$wgDBname.xml", "wb" );
fwrite( $findex, '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . 
'<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n" );

foreach ( $priorities as $ns => $priority) {
	$sql = "SELECT page_namespace,page_title,page_is_redirect,rev_timestamp  FROM $page, $rev ".
		"WHERE page_namespace = $ns AND page_latest = rev_id ";
	print "DB query : $sql\nprocessing ...";
	$res = $dbr->query( $sql );
	print " done\n";

	$gzfile = false;
	$rowcount=0;
	$sitemapcount=0;
	while ( $row = $dbr->fetchObject( $res ) ) {
		if ( $rowcount % 9000 == 0 ) {
			if ( $gzfile !== false ) {
				gzwrite( $gzfile, '</urlset>' );
				gzclose( $gzfile );
			}
			$sitemapcount ++;
			$fname = "sitemap-{$wgDBname}-NS{$ns}-{$sitemapcount}.xml.gz";
			$gzfile = gzopen( $fname, "wb" );
			gzwrite( $gzfile, '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . 
				'<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n" );
			fwrite( $findex, "\t<sitemap>\n\t\t<loc>$wgServer/$fname</loc>\n\t</sitemap>\n" );
			print "$fname\n";
		}
		$rowcount ++;
		$nt = Title::makeTitle( $row->page_namespace, $row->page_title );
		$date = substr($row->rev_timestamp, 0, 4). '-' .
			substr($row->rev_timestamp, 4, 2). '-' .
			substr($row->rev_timestamp, 6, 2);
		gzwrite( $gzfile, "\t<url>\n\t\t<loc>" . $nt->getFullURL() . 
			  	"</loc>\n\t\t<lastmod>$date</lastmod>\n" .
				"\t\t<priority>$priority</priority>\n" .
				"\t</url>\n" );
	}
	if ( $gzfile ) {
		gzwrite( $gzfile, "</urlset>\n" );
		gzclose( $gzfile );
	}
	print "\n";
}
fwrite( $findex, "</sitemapindex>\n" );
fclose( $findex );


?>
