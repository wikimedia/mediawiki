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
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Assert\Assert;

/**
 * @ingroup Dump
 */
class XmlDumpWriter {

	/** Output serialized revision content. */
	const WRITE_CONTENT = 0;

	/** Only output subs for revision content. */
	const WRITE_STUB = 1;

	/**
	 * Only output subs for revision content, indicating that the content has been
	 * deleted/suppressed. For internal use only.
	 */
	const WRITE_STUB_DELETED = 2;

	/**
	 * @var string[] the schema versions supported for output
	 * @final
	 */
	public static $supportedSchemas = [
		XML_DUMP_SCHEMA_VERSION_10,
		XML_DUMP_SCHEMA_VERSION_11
	];

	/**
	 * @var string which schema version the generated XML should comply to.
	 * One of the values from self::$supportedSchemas, using the SCHEMA_VERSION_XX
	 * constants.
	 */
	private $schemaVersion;

	/**
	 * Title of the currently processed page
	 *
	 * @var Title|null
	 */
	private $currentTitle = null;

	/**
	 * @var int Whether to output revision content or just stubs. WRITE_CONTENT or WRITE_STUB.
	 */
	private $contentMode;

	/**
	 * XmlDumpWriter constructor.
	 *
	 * @param int $contentMode WRITE_CONTENT or WRITE_STUB.
	 * @param string $schemaVersion which schema version the generated XML should comply to.
	 * One of the values from self::$supportedSchemas, using the XML_DUMP_SCHEMA_VERSION_XX
	 * constants.
	 */
	public function __construct(
		$contentMode = self::WRITE_CONTENT,
		$schemaVersion = XML_DUMP_SCHEMA_VERSION_11
	) {
		Assert::parameter(
			in_array( $contentMode, [ self::WRITE_CONTENT, self::WRITE_STUB ] ),
			'$contentMode',
			'must be one of the following constants: WRITE_CONTENT or WRITE_STUB.'
		);

		Assert::parameter(
			in_array( $schemaVersion, self::$supportedSchemas ),
			'$schemaVersion',
			'must be one of the following schema versions: '
				. implode( ',', self::$supportedSchemas )
		);

		$this->contentMode = $contentMode;
		$this->schemaVersion = $schemaVersion;
	}

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
		$ver = $this->schemaVersion;
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
			 * echo "https://www.mediawiki.org/xml/index.html" | mwscript purgeList.php --wiki=aawiki
			 */
			'xsi:schemaLocation' => "http://www.mediawiki.org/xml/export-$ver/ " .
				"http://www.mediawiki.org/xml/export-$ver.xsd",
			'version' => $ver,
			'xml:lang' => MediaWikiServices::getInstance()->getContentLanguage()->getHtmlCode() ],
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
		$spaces = "<namespaces>\n";
		$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		foreach (
			MediaWikiServices::getInstance()->getContentLanguage()->getFormattedNamespaces()
			as $ns => $title
		) {
			$spaces .= '      ' .
				Xml::element( 'namespace',
					[
						'key' => $ns,
						'case' => $nsInfo->isCapitalized( $ns )
							? 'first-letter' : 'case-sensitive',
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
		$this->currentTitle = Title::newFromRow( $row );
		$canonicalTitle = self::canonicalTitle( $this->currentTitle );
		$out .= '    ' . Xml::elementClean( 'title', [], $canonicalTitle ) . "\n";
		$out .= '    ' . Xml::element( 'ns', [], strval( $row->page_namespace ) ) . "\n";
		$out .= '    ' . Xml::element( 'id', [], strval( $row->page_id ) ) . "\n";
		if ( $row->page_is_redirect ) {
			$page = WikiPage::factory( $this->currentTitle );
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

		Hooks::run( 'XmlDumpWriterOpenPage', [ $this, &$out, $row, $this->currentTitle ] );

		return $out;
	}

	/**
	 * Closes a "<page>" section on the output stream.
	 *
	 * @private
	 * @return string
	 */
	function closePage() {
		if ( $this->currentTitle !== null ) {
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();
			// In rare cases, link cache has the same key for some pages which
			// might be read as part of the same batch. T220424 and T220316
			$linkCache->clearLink( $this->currentTitle );
		}
		return "  </page>\n";
	}

	/**
	 * @return RevisionStore
	 */
	private function getRevisionStore() {
		return MediaWikiServices::getInstance()->getRevisionStore();
	}

	/**
	 * @return SqlBlobStore
	 */
	private function getBlobStore() {
		return MediaWikiServices::getInstance()->getBlobStore();
	}

	/**
	 * Invokes the given method on the given object, catching and logging any storage related
	 * exceptions.
	 *
	 * @param object $obj
	 * @param string $method
	 * @param array $args
	 * @param string $warning The warning to output in case of a storage related exception.
	 *
	 * @return mixed Returns the method's return value,
	 *         or null in case of a storage related exception.
	 * @throws Exception
	 */
	private function invokeLenient( $obj, $method, $args = [], $warning ) {
		try {
			return call_user_func_array( [ $obj, $method ], $args );
		} catch ( SuppressedDataException $ex ) {
			return null;
		} catch ( Exception $ex ) {
			if ( $ex instanceof MWException || $ex instanceof RuntimeException ||
				$ex instanceof InvalidArgumentException ) {
				MWDebug::warning( $warning . ': ' . $ex->getMessage() );
				return null;
			} else {
				throw $ex;
			}
		}
	}

	/**
	 * Dumps a "<revision>" section on the output stream, with
	 * data filled in from the given database row.
	 *
	 * @param object $row
	 * @param null|object[] $slotRows
	 *
	 * @return string
	 * @throws FatalError
	 * @throws MWException
	 * @private
	 */
	function writeRevision( $row, $slotRows = null ) {
		$rev = $this->getRevisionStore()->newRevisionFromRowAndSlots(
			$row,
			$slotRows,
			0,
			$this->currentTitle
		);

		$out = "    <revision>\n";
		$out .= "      " . Xml::element( 'id', null, strval( $rev->getId() ) ) . "\n";

		if ( $rev->getParentId() ) {
			$out .= "      " . Xml::element( 'parentid', null, strval( $rev->getParentId() ) ) . "\n";
		}

		$out .= $this->writeTimestamp( $rev->getTimestamp() );

		if ( $rev->isDeleted( RevisionRecord::DELETED_USER ) ) {
			$out .= "      " . Xml::element( 'contributor', [ 'deleted' => 'deleted' ] ) . "\n";
		} else {
			// empty values get written out as uid 0, see T224221
			$user = $rev->getUser();
			$out .= $this->writeContributor(
				$user ? $user->getId() : 0,
				$user ? $user->getName() : ''
			);
		}

		if ( $rev->isMinor() ) {
			$out .= "      <minor/>\n";
		}
		if ( $rev->isDeleted( RevisionRecord::DELETED_COMMENT ) ) {
			$out .= "      " . Xml::element( 'comment', [ 'deleted' => 'deleted' ] ) . "\n";
		} else {
			if ( $rev->getComment()->text != '' ) {
				$out .= "      "
					. Xml::elementClean( 'comment', [], strval( $rev->getComment()->text ) )
					. "\n";
			}
		}

		$contentMode = $rev->isDeleted( RevisionRecord::DELETED_TEXT ) ? self::WRITE_STUB_DELETED
			: $this->contentMode;

		foreach ( $rev->getSlots()->getSlots() as $slot ) {
			$out .= $this->writeSlot( $slot, $contentMode );
		}

		if ( $rev->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			$out .= "      <sha1/>\n";
		} else {
			$sha1 = $this->invokeLenient(
				$rev,
				'getSha1',
				[],
				'failed to determine sha1 for revision ' . $rev->getId()
			);
			$out .= "      " . Xml::element( 'sha1', null, strval( $sha1 ) ) . "\n";
		}

		// Avoid PHP 7.1 warning from passing $this by reference
		$writer = $this;
		$text = '';
		if ( $contentMode === self::WRITE_CONTENT ) {
			/** @var Content $content */
			$content = $this->invokeLenient(
				$rev,
				'getContent',
				[ SlotRecord::MAIN, RevisionRecord::RAW ],
				'Failed to load main slot content of revision ' . $rev->getId()
			);

			$text = $content ? $content->serialize() : '';
		}
		Hooks::run( 'XmlDumpWriterWriteRevision', [ &$writer, &$out, $row, $text, $rev ] );

		$out .= "    </revision>\n";

		return $out;
	}

	/**
	 * @param SlotRecord $slot
	 * @param int $contentMode see the WRITE_XXX constants
	 *
	 * @return string
	 */
	private function writeSlot( SlotRecord $slot, $contentMode ) {
		$isMain = $slot->getRole() === SlotRecord::MAIN;
		$isV11 = $this->schemaVersion >= XML_DUMP_SCHEMA_VERSION_11;

		if ( !$isV11 && !$isMain ) {
			// ignore extra slots
			return '';
		}

		$out = '';
		$indent = '      ';

		if ( !$isMain ) {
			// non-main slots are wrapped into an additional element.
			$out .= '      ' . Xml::openElement( 'content' ) . "\n";
			$indent .= '  ';
			$out .= $indent . Xml::element( 'role', null, strval( $slot->getRole() ) ) . "\n";
		}

		if ( $isV11 ) {
			$out .= $indent . Xml::element( 'origin', null, strval( $slot->getOrigin() ) ) . "\n";
		}

		$contentModel = $slot->getModel();
		$contentHandler = ContentHandler::getForModelID( $contentModel );
		$contentFormat = $contentHandler->getDefaultFormat();

		// XXX: The content format is only relevant when actually outputting serialized content.
		// It should probably be an attribute on the text tag.
		$out .= $indent . Xml::element( 'model', null, strval( $contentModel ) ) . "\n";
		$out .= $indent . Xml::element( 'format', null, strval( $contentFormat ) ) . "\n";

		$textAttributes = [
			'xml:space' => 'preserve',
			'bytes' => $this->invokeLenient(
				$slot,
				'getSize',
				[],
				'failed to determine size for slot ' . $slot->getRole() . ' of revision '
				. $slot->getRevision()
			) ?: '0'
		];

		if ( $isV11 ) {
			$textAttributes['sha1'] = $this->invokeLenient(
				$slot,
				'getSha1',
				[],
				'failed to determine sha1 for slot ' . $slot->getRole() . ' of revision '
				. $slot->getRevision()
			) ?: '';
		}

		if ( $contentMode === self::WRITE_CONTENT ) {
			$content = $this->invokeLenient(
				$slot,
				'getContent',
				[],
				'failed to load content for slot ' . $slot->getRole() . ' of revision '
				. $slot->getRevision()
			);

			if ( $content === null ) {
				$out .= $indent . Xml::element( 'text', $textAttributes ) . "\n";
			} else {
				$out .= $this->writeText( $content, $textAttributes, $indent );
			}
		} elseif ( $contentMode === self::WRITE_STUB_DELETED ) {
			// write <text> placeholder tag
			$textAttributes['deleted'] = 'deleted';
			$out .= $indent . Xml::element( 'text', $textAttributes ) . "\n";
		} else {
			// write <text> stub tag
			if ( $isV11 ) {
				$textAttributes['location'] = $slot->getAddress();
			}

			// Output the numerical text ID if possible, for backwards compatibility.
			// Note that this is currently the ONLY reason we have a BlobStore here at all.
			// When removing this line, check whether the BlobStore has become unused.
			try {
				// NOTE: this will only work for addresses of the form "tt:12345".
				// If we want to support other kinds of addresses in the future,
				// we will have to silently ignore failures here.
				// For now, this fails for "tt:0", which is present in the WMF production
				// database of of Juli 2019, due to data corruption.
				$textId = $this->getBlobStore()->getTextIdFromAddress( $slot->getAddress() );
			} catch ( InvalidArgumentException $ex ) {
				MWDebug::warning( 'Bad content address for slot ' . $slot->getRole()
					. ' of revision ' . $slot->getRevision() . ': ' . $ex->getMessage() );
				$textId = 0;
			}

			if ( $textId ) {
				$textAttributes['id'] = $textId;
			}

			$out .= $indent . Xml::element( 'text', $textAttributes ) . "\n";
		}

		if ( !$isMain ) {
			$out .= '      ' . Xml::closeElement( 'content' ) . "\n";
		}

		return $out;
	}

	/**
	 * @param Content $content
	 * @param string[] $textAttributes
	 * @param string $indent
	 *
	 * @return string
	 */
	private function writeText( Content $content, $textAttributes, $indent ) {
		$out = '';

		$contentHandler = $content->getContentHandler();
		$contentFormat = $contentHandler->getDefaultFormat();

		if ( $content instanceof TextContent ) {
			// HACK: For text based models, bypass the serialization step. This allows extensions (like Flow)
			// that use incompatible combinations of serialization format and content model.
			$data = $content->getNativeData();
		} else {
			$data = $content->serialize( $contentFormat );
		}

		$data = $contentHandler->exportTransform( $data, $contentFormat );
		$textAttributes['bytes'] = $size = strlen( $data ); // make sure to use the actual size
		$out .= $indent . Xml::elementClean( 'text', $textAttributes, strval( $data ) ) . "\n";

		return $out;
	}

	/**
	 * Dumps a "<logitem>" section on the output stream, with
	 * data filled in from the given database row.
	 *
	 * @param object $row
	 * @return string
	 * @private
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
		} else {
			$comment = CommentStore::getStore()->getComment( 'log_comment', $row )->text;
			if ( $comment != '' ) {
				$out .= "    " . Xml::elementClean( 'comment', null, strval( $comment ) ) . "\n";
			}
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
			$img = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
				->newFile( $row->page_title );
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
			/** @var OldLocalFile $file */
			'@phan-var OldLocalFile $file';
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
			$comment = Xml::elementClean( 'comment', null, strval( $file->getDescription() ) );
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

		$prefix = MediaWikiServices::getInstance()->getContentLanguage()->
			getFormattedNsText( $title->getNamespace() );

		// @todo Emit some kind of warning to the user if $title->getNamespace() !==
		// NS_MAIN and $prefix === '' (viz. pages in an unregistered namespace)

		if ( $prefix !== '' ) {
			$prefix .= ':';
		}

		return $prefix . $title->getText();
	}
}
