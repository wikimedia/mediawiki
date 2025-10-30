<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Site;

use InvalidArgumentException;
use MediaWiki\Xml\Xml;

/**
 * Utility for exporting site entries to XML.
 *
 * For the output file format, see docs/sitelist.md and docs/sitelist-1.0.xsd.
 *
 * @since 1.25
 * @ingroup Site
 * @author Daniel Kinzler
 */
class SiteExporter {

	/**
	 * @var resource
	 */
	private $sink;

	/**
	 * @param resource $sink A file handle open for writing
	 */
	public function __construct( $sink ) {
		// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.is_resource
		if ( !is_resource( $sink ) || get_resource_type( $sink ) !== 'stream' ) {
			throw new InvalidArgumentException( '$sink must be a file handle' );
		}

		$this->sink = $sink;
	}

	/**
	 * Writes a <site> tag for each Site object in $sites, and encloses the entire list
	 * between <sites> tags.
	 *
	 * @param Site[]|SiteList $sites
	 */
	public function exportSites( $sites ) {
		$attributes = [
			'version' => '1.0',
			'xmlns' => 'http://www.mediawiki.org/xml/sitelist-1.0/',
		];

		fwrite( $this->sink, Xml::openElement( 'sites', $attributes ) . "\n" );

		foreach ( $sites as $site ) {
			$this->exportSite( $site );
		}

		fwrite( $this->sink, Xml::closeElement( 'sites' ) . "\n" );
		fflush( $this->sink );
	}

	/**
	 * Writes a <site> tag representing the given Site object.
	 */
	private function exportSite( Site $site ) {
		if ( $site->getType() !== Site::TYPE_UNKNOWN ) {
			$siteAttr = [ 'type' => $site->getType() ];
		} else {
			$siteAttr = null;
		}

		fwrite( $this->sink, "\t" . Xml::openElement( 'site', $siteAttr ) . "\n" );

		fwrite( $this->sink, "\t\t" . Xml::element( 'globalid', null, $site->getGlobalId() ) . "\n" );

		if ( $site->getGroup() !== Site::GROUP_NONE ) {
			fwrite( $this->sink, "\t\t" . Xml::element( 'group', null, $site->getGroup() ) . "\n" );
		}

		if ( $site->getSource() !== Site::SOURCE_LOCAL ) {
			fwrite( $this->sink, "\t\t" . Xml::element( 'source', null, $site->getSource() ) . "\n" );
		}

		if ( $site->shouldForward() ) {
			fwrite( $this->sink, "\t\t" . Xml::element( 'forward', null, '' ) . "\n" );
		}

		foreach ( $site->getAllPaths() as $type => $path ) {
			fwrite( $this->sink, "\t\t" . Xml::element( 'path', [ 'type' => $type ], $path ) . "\n" );
		}

		foreach ( $site->getLocalIds() as $type => $ids ) {
			foreach ( $ids as $id ) {
				fwrite( $this->sink, "\t\t" . Xml::element( 'localid', [ 'type' => $type ], $id ) . "\n" );
			}
		}

		// @todo: export <data>
		// @todo: export <config>

		fwrite( $this->sink, "\t" . Xml::closeElement( 'site' ) . "\n" );
	}

}

/** @deprecated class alias since 1.42 */
class_alias( SiteExporter::class, 'SiteExporter' );
