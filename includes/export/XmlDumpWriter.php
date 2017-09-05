<?php
/**
 * XmlDumpWriter
 *
 * Copyright © 2003, 2005, 2006 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 */

/**
 * @ingroup Dump
 */
class XmlDumpWriter {
	/**
	 * Opens the XML output stream's root "<mediawiki>" element.
	 * This does not include an xml directive, so is safe to include
	 * as a subelement in a larger XML stream. Namespace and XML Schema
	 * references are included.
	 *
	 * Output will be encoded in UTF-8.
	 *
	 * @return string
	 */
	function openStream() {
		global $wgLanguageCode;
		$ver = WikiExporter::schemaVersion();
		return Xml::element( 'mediawiki', [
			'xmlns'              => "http://www.mediawiki.org/xml/export-$ver/",
			'xmlns:xsi'          => "http://www.w3.org/2001/XMLSchema-instance",
			/*
			 * When a new version of the schema is created, it needs staging on mediawiki.org.
			 * This requires a change in the operations/mediawiki-config git repo.
			 *
			 * Create a changeset like https://gerrit.wikimedia.org/r/#/c/149643/ in which
			 * you copy in the new xsd file.
			 *
			 * After it is reviewed, merged and deployed (sync-docroot), the index.html needs purging.
			 * echo "http://www.mediawiki.org/xml/index.html" | mwscript purgeList.php --wiki=aawiki
			 */
			'xsi:schemaLocation' => "http://www.mediawiki.org/xml/export-$ver/ " .
				"http://www.mediawiki.org/xml/export-$ver.xsd",
			'version'            => $ver,
			'xml:lang'           => $wgLanguageCode ],
			null ) .
			"\n" .
			$this->siteInfo();
	}

	/**
	 * @return string
	 */
	function siteInfo() {
		$info = [
			$this->sitename(),
			$this->dbname(),
			$this->homelink(),
			$this->generator(),
			$this->caseSetting(),
			$this->namespaces() ];
		return "  <siteinfo>\n    " .
			implode( "\n    ", $info ) .
			"\n  </siteinfo>\n";
	}

	/**
	 * @return string
	 */
	function sitename() {
		global $wgSitename;
		return Xml::element( 'sitename', [], $wgSitename );
	}

	/**
	 * @return string
	 */
	function dbname() {
		global $wgDBname;
		return Xml::element( 'dbname', [], $wgDBname );
	}

	/**
	 * @return string
	 */
	function generator() {
		global $wgVersion;
		return Xml::element( 'generator', [], "MediaWiki $wgVersion" );
	}

	/**
	 * @return string
	 */
	function homelink() {
		return Xml::element( 'base', [], Title::newMainPage()->getCanonicalURL() );
	}

	/**
	 * @return string
	 */
	function caseSetting() {
		global $wgCapitalLinks;
		// "case-insensitive" option is reserved for future
		$sensitivity = $wgCapitalLinks ? 'first-letter' : 'case-sensitive';
		return Xml::element( 'case', [], $sensitivity );
	}

	/**
	 * @return string
	 */
	function namespaces() {
		global $wgContLang;
		$spaces = "<namespaces>\n";
		foreach ( $wgContLang->getFormattedNamespaces() as $ns => $title ) {
			$spaces .= '      ' .
				Xml::element( 'namespace',
					[
						'key' => $ns,
						'case' => MWNamespace::isCapitalized( $ns ) ? 'first-letter' : 'case-sensitive',
					], $title ) . "\n";
		}
		$spaces .= "    </namespaces>";
		return $spaces;
	}

	/**
	 * Closes the output stream with the closing root element.
	 * Call when finished dumping things.
	 *
	 * @return string
	 */
	function closeStream() {
		return "</mediawiki>\n";
	}

	/**
	 * Opens a "<page>" section on the output stream, with data
	 * from the given database row.
	 *
	 * @param object $row
	 * @return string
	 */
	public function openPage( $row ) {
		$out = "  <page>\n";
		$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		$out .= '    ' . Xml::elementClean( 'title', [], self::canonicalTitle( $title ) ) . "\n";
		$out .= '    ' . Xml::element( 'ns', [], strval( $row->page_namespace ) ) . "\n";
		$out .= '    ' . Xml::element( 'id', [], strval( $row->page_id ) ) . "\n";
		if ( $row->page_is_redirect ) {
			$page = WikiPage::factory( $title );
			$redirect = $page->getRedirectTarget();
			if ( $redirect instanceof Title && $redirect->isValidRedirectTarget() ) {
				$out .= '    ';
				$out .= Xml::element( 'redirect', [ 'title' => self::canonicalTitle( $redirect ) ] );
				$out .= "\n";
			}
		}

		if ( $row->page_restrictions != '' ) {
			$out .= '    ' . Xml::element( 'restrictions', [],
				strval( $row->page_restrictions ) ) . "\n";
		}

		Hooks::run( 'XmlDumpWriterOpenPage', [ $this, &$out, $row, $title ] );

		return $out;
	}

	/**
	 * Closes a "<page>" section on the output stream.
	 *
	 * @access private
	 * @return string
	 */
	function closePage() {
		return "  </page>\n";
	}

	/**
	 * Dumps a "<revision>" section on the output stream, with
	 * data filled in from the given database row.
	 *
	 * @param object $row
	 * @return string
	 * @access private
	 */
	function writeRevision( $row ) {

		$out = "    <revision>\n";
		$out .= "      " . Xml::element( 'id', null, strval( $row->rev_id ) ) . "\n";
		if ( isset( $row->rev_parent_id ) && $row->rev_parent_id ) {
			$out .= "      " . Xml::element( 'parentid', null, strval( $row->rev_parent_id ) ) . "\n";
		}

		$out .= $this->writeTimestamp( $row->rev_timestamp );

		if ( isset( $row->rev_deleted ) && ( $row->rev_deleted & Revision::DELETED_USER ) ) {
			$out .= "      " . Xml::element( 'contributor', [ 'deleted' => 'deleted' ] ) . "\n";
		} else {
			$out .= $this->writeContributor( $row->rev_user, $row->rev_user_text );
		}

		if ( isset( $row->rev_minor_edit ) && $row->rev_minor_edit ) {
			$out .= "      <minor/>\n";
		}
		if ( isset( $row->rev_deleted ) && ( $row->rev_deleted & Revision::DELETED_COMMENT ) ) {
			$out .= "      " . Xml::element( 'comment', [ 'deleted' => 'deleted' ] ) . "\n";
		} elseif ( $row->rev_comment != '' ) {
			$out .= "      " . Xml::elementClean( 'comment', [], strval( $row->rev_comment ) ) . "\n";
		}

		if ( isset( $row->rev_content_model ) && !is_null( $row->rev_content_model ) ) {
			$content_model = strval( $row->rev_content_model );
		} else {
			// probably using $wgContentHandlerUseDB = false;
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$content_model = ContentHandler::getDefaultModelFor( $title );
		}

		$content_handler = ContentHandler::getForModelID( $content_model );

		if ( isset( $row->rev_content_format ) && !is_null( $row->rev_content_format ) ) {
			$content_format = strval( $row->rev_content_format );
		} else {
			// probably using $wgContentHandlerUseDB = false;
			$content_format = $content_handler->getDefaultFormat();
		}

		$out .= "      " . Xml::element( 'model', null, strval( $content_model ) ) . "\n";
		$out .= "      " . Xml::element( 'format', null, strval( $content_format ) ) . "\n";

		$text = '';
		if ( isset( $row->rev_deleted ) && ( $row->rev_deleted & Revision::DELETED_TEXT ) ) {
			$out .= "      " . Xml::element( 'text', [ 'deleted' => 'deleted' ] ) . "\n";
		} elseif ( isset( $row->old_text ) ) {
			// Raw text from the database may have invalid chars
			$text = strval( Revision::getRevisionText( $row ) );
			$text = $content_handler->exportTransform( $text, $content_format );
			$out .= "      " . Xml::elementClean( 'text',
				[ 'xml:space' => 'preserve', 'bytes' => intval( $row->rev_len ) ],
				strval( $text ) ) . "\n";
		} else {
			// Stub output
			$out .= "      " . Xml::element( 'text',
				[ 'id' => $row->rev_text_id, 'bytes' => intval( $row->rev_len ) ],
				"" ) . "\n";
		}

		if ( isset( $row->rev_sha1 )
			&& $row->rev_sha1
			&& !( $row->rev_deleted & Revision::DELETED_TEXT )
		) {
			$out .= "      " . Xml::element( 'sha1', null, strval( $row->rev_sha1 ) ) . "\n";
		} else {
			$out .= "      <sha1/>\n";
		}

		// Avoid PHP 7.1 warning from passing $this by reference
		$writer = $this;
		Hooks::run( 'XmlDumpWriterWriteRevision', [ &$writer, &$out, $row, $text ] );

		$out .= "    </revision>\n";

		return $out;
	}

	/**
	 * Dumps a "<logitem>" section on the output stream, with
	 * data filled in from the given database row.
	 *
	 * @param object $row
	 * @return string
	 * @access private
	 */
	function writeLogItem( $row ) {

		$out = "  <logitem>\n";
		$out .= "    " . Xml::element( 'id', null, strval( $row->log_id ) ) . "\n";

		$out .= $this->writeTimestamp( $row->log_timestamp, "    " );

		if ( $row->log_deleted & LogPage::DELETED_USER ) {
			$out .= "    " . Xml::element( 'contributor', [ 'deleted' => 'deleted' ] ) . "\n";
		} else {
			$out .= $this->writeContributor( $row->log_user, $row->user_name, "    " );
		}

		if ( $row->log_deleted & LogPage::DELETED_COMMENT ) {
			$out .= "    " . Xml::element( 'comment', [ 'deleted' => 'deleted' ] ) . "\n";
		} elseif ( $row->log_comment != '' ) {
			$out .= "    " . Xml::elementClean( 'comment', null, strval( $row->log_comment ) ) . "\n";
		}

		$out .= "    " . Xml::element( 'type', null, strval( $row->log_type ) ) . "\n";
		$out .= "    " . Xml::element( 'action', null, strval( $row->log_action ) ) . "\n";

		if ( $row->log_deleted & LogPage::DELETED_ACTION ) {
			$out .= "    " . Xml::element( 'text', [ 'deleted' => 'deleted' ] ) . "\n";
		} else {
			$title = Title::makeTitle( $row->log_namespace, $row->log_title );
			$out .= "    " . Xml::elementClean( 'logtitle', null, self::canonicalTitle( $title ) ) . "\n";
			$out .= "    " . Xml::elementClean( 'params',
				[ 'xml:space' => 'preserve' ],
				strval( $row->log_params ) ) . "\n";
		}

		$out .= "  </logitem>\n";

		return $out;
	}

	/**
	 * @param string $timestamp
	 * @param string $indent Default to six spaces
	 * @return string
	 */
	function writeTimestamp( $timestamp, $indent = "      " ) {
		$ts = wfTimestamp( TS_ISO_8601, $timestamp );
		return $indent . Xml::element( 'timestamp', null, $ts ) . "\n";
	}

	/**
	 * @param int $id
	 * @param string $text
	 * @param string $indent Default to six spaces
	 * @return string
	 */
	function writeContributor( $id, $text, $indent = "      " ) {
		$out = $indent . "<contributor>\n";
		if ( $id || !IP::isValid( $text ) ) {
			$out .= $indent . "  " . Xml::elementClean( 'username', null, strval( $text ) ) . "\n";
			$out .= $indent . "  " . Xml::element( 'id', null, strval( $id ) ) . "\n";
		} else {
			$out .= $indent . "  " . Xml::elementClean( 'ip', null, strval( $text ) ) . "\n";
		}
		$out .= $indent . "</contributor>\n";
		return $out;
	}

	/**
	 * Warning! This data is potentially inconsistent. :(
	 * @param object $row
	 * @param bool $dumpContents
	 * @return string
	 */
	function writeUploads( $row, $dumpContents = false ) {
		if ( $row->page_namespace == NS_FILE ) {
			$img = wfLocalFile( $row->page_title );
			if ( $img && $img->exists() ) {
				$out = '';
				foreach ( array_reverse( $img->getHistory() ) as $ver ) {
					$out .= $this->writeUpload( $ver, $dumpContents );
				}
				$out .= $this->writeUpload( $img, $dumpContents );
				return $out;
			}
		}
		return '';
	}

	/**
	 * @param File $file
	 * @param bool $dumpContents
	 * @return string
	 */
	function writeUpload( $file, $dumpContents = false ) {
		if ( $file->isOld() ) {
			$archiveName = "      " .
				Xml::element( 'archivename', null, $file->getArchiveName() ) . "\n";
		} else {
			$archiveName = '';
		}
		if ( $dumpContents ) {
			$be = $file->getRepo()->getBackend();
			# Dump file as base64
			# Uses only XML-safe characters, so does not need escaping
			# @todo Too bad this loads the contents into memory (script might swap)
			$contents = '      <contents encoding="base64">' .
				chunk_split( base64_encode(
					$be->getFileContents( [ 'src' => $file->getPath() ] ) ) ) .
				"      </contents>\n";
		} else {
			$contents = '';
		}
		if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
			$comment = Xml::element( 'comment', [ 'deleted' => 'deleted' ] );
		} else {
			$comment = Xml::elementClean( 'comment', null, $file->getDescription() );
		}
		return "    <upload>\n" .
			$this->writeTimestamp( $file->getTimestamp() ) .
			$this->writeContributor( $file->getUser( 'id' ), $file->getUser( 'text' ) ) .
			"      " . $comment . "\n" .
			"      " . Xml::element( 'filename', null, $file->getName() ) . "\n" .
			$archiveName .
			"      " . Xml::element( 'src', null, $file->getCanonicalUrl() ) . "\n" .
			"      " . Xml::element( 'size', null, $file->getSize() ) . "\n" .
			"      " . Xml::element( 'sha1base36', null, $file->getSha1() ) . "\n" .
			"      " . Xml::element( 'rel', null, $file->getRel() ) . "\n" .
			$contents .
			"    </upload>\n";
	}

	/**
	 * Return prefixed text form of title, but using the content language's
	 * canonical namespace. This skips any special-casing such as gendered
	 * user namespaces -- which while useful, are not yet listed in the
	 * XML "<siteinfo>" data so are unsafe in export.
	 *
	 * @param Title $title
	 * @return string
	 * @since 1.18
	 */
	public static function canonicalTitle( Title $title ) {
		if ( $title->isExternal() ) {
			return $title->getPrefixedText();
		}

		global $wgContLang;
		$prefix = $wgContLang->getFormattedNsText( $title->getNamespace() );

		if ( $prefix !== '' ) {
			$prefix .= ':';
		}

		return $prefix . $title->getText();
	}
}
