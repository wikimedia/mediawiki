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
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Mime\MimeAnalyzer;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Implements Special:MediaStatistics
 *
 * @ingroup SpecialPage
 * @author Brian Wolff
 */
class SpecialMediaStatistics extends QueryPage {

	public const MAX_LIMIT = 5000;

	protected int $totalCount = 0;
	protected int $totalBytes = 0;

	/**
	 * @var int Combined file size of all files in a section
	 */
	protected $totalPerType = 0;

	/**
	 * @var int Combined file count of all files in a section
	 */
	protected $countPerType = 0;

	/**
	 * @var int Combined file size of all files
	 */
	protected $totalSize = 0;

	private MimeAnalyzer $mimeAnalyzer;
	private int $migrationStage;

	public function __construct(
		MimeAnalyzer $mimeAnalyzer,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'MediaStatistics' );
		// Generally speaking there is only a small number of file types,
		// so just show all of them.
		$this->limit = self::MAX_LIMIT;
		$this->shownavigation = false;
		$this->mimeAnalyzer = $mimeAnalyzer;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/**
	 * Query to do.
	 *
	 * This abuses the query cache table by storing mime types as "titles".
	 *
	 * This will store entries like [[Media:BITMAP;image/jpeg;200;20000]]
	 * where the form is Media type;mime type;count;bytes.
	 *
	 * This relies on the behaviour that when value is tied, the order things
	 * come out of querycache table is the order they went in. Which is hacky.
	 * However, other special pages like Special:Deadendpages and
	 * Special:BrokenRedirects also rely on this.
	 * @return array
	 */
	public function getQueryInfo() {
		$dbr = $this->getDatabaseProvider()->getReplicaDatabase();
		if ( $this->migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$fakeTitle = $dbr->buildConcat( [
				'img_media_type',
				$dbr->addQuotes( ';' ),
				'img_major_mime',
				$dbr->addQuotes( '/' ),
				'img_minor_mime',
				$dbr->addQuotes( ';' ),
				$dbr->buildStringCast( 'COUNT(*)' ),
				$dbr->addQuotes( ';' ),
				$dbr->buildStringCast( 'SUM( img_size )' )
			] );
			return [
				'tables' => [ 'image' ],
				'fields' => [
					'title' => $fakeTitle,
					'namespace' => NS_MEDIA, /* needs to be something */
					'value' => '1'
				],
				'options' => [
					'GROUP BY' => [
						'img_media_type',
						'img_major_mime',
						'img_minor_mime',
					]
				]
			];
		} else {
			$fakeTitle = $dbr->buildConcat( [
				'ft_media_type',
				$dbr->addQuotes( ';' ),
				'ft_major_mime',
				$dbr->addQuotes( '/' ),
				'ft_minor_mime',
				$dbr->addQuotes( ';' ),
				$dbr->buildStringCast( 'COUNT(*)' ),
				$dbr->addQuotes( ';' ),
				$dbr->buildStringCast( 'SUM( fr_size )' )
			] );
			return [
				'tables' => [ 'file', 'filetypes', 'filerevision' ],
				'fields' => [
					'title' => $fakeTitle,
					'namespace' => NS_MEDIA, /* needs to be something */
					'value' => '1'
				],
				'conds' => [
					'file_deleted' => 0
				],
				'options' => [
					'GROUP BY' => [
						'file_type',
						'ft_media_type',
						'ft_major_mime',
						'ft_minor_mime'
					]
				],
				'join_conds' => [
					'filetypes' => [ 'JOIN', 'file_type = ft_id' ],
					'filerevision' => [ 'JOIN', 'file_latest = fr_id' ]
				]
			];
		}
	}

	/**
	 * How to sort the results
	 *
	 * It's important that img_media_type come first, otherwise the
	 * tables will be fragmented.
	 * @return array Fields to sort by
	 */
	protected function getOrderFields() {
		if ( $this->migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			return [ 'img_media_type', 'count(*)', 'img_major_mime', 'img_minor_mime' ];
		} else {
			return [ 'file_type', 'count(*)', 'ft_media_type', 'ft_major_mime', 'ft_minor_mime' ];
		}
	}

	/**
	 * Output the results of the query.
	 *
	 * @param OutputPage $out
	 * @param Skin $skin (deprecated presumably)
	 * @param IReadableDatabase $dbr
	 * @param IResultWrapper $res Results from query
	 * @param int $num Number of results
	 * @param int $offset Paging offset (Should always be 0 in our case)
	 */
	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		$prevMediaType = null;
		foreach ( $res as $row ) {
			$mediaStats = $this->splitFakeTitle( $row->title );
			if ( count( $mediaStats ) < 4 ) {
				continue;
			}
			[ $mediaType, $mime, $totalCount, $totalBytes ] = $mediaStats;
			if ( $prevMediaType !== $mediaType ) {
				if ( $prevMediaType !== null ) {
					// We're not at beginning, so we have to
					// close the previous table.
					$this->outputTableEnd();
				}
				$this->outputMediaType( $mediaType );
				$this->totalPerType = 0;
				$this->countPerType = 0;
				$this->outputTableStart( $mediaType );
				$prevMediaType = $mediaType;
			}
			$this->outputTableRow( $mime, intval( $totalCount ), intval( $totalBytes ) );
		}
		if ( $prevMediaType !== null ) {
			$this->outputTableEnd();
			// add total size of all files
			$this->outputMediaType( 'total' );
			$this->getOutput()->addWikiTextAsInterface(
				$this->msg( 'mediastatistics-allbytes' )
					->numParams( $this->totalSize )
					->sizeParams( $this->totalSize )
					->numParams( $this->totalCount )
					->text()
			);
		}
	}

	/**
	 * Output closing </table>
	 */
	protected function outputTableEnd() {
		$this->getOutput()->addHTML(
			Html::closeElement( 'tbody' ) .
			Html::closeElement( 'table' )
		);
		$this->getOutput()->addWikiTextAsInterface(
				$this->msg( 'mediastatistics-bytespertype' )
					->numParams( $this->totalPerType )
					->sizeParams( $this->totalPerType )
					->numParams( $this->makePercentPretty( $this->totalPerType / $this->totalBytes ) )
					->numParams( $this->countPerType )
					->numParams( $this->makePercentPretty( $this->countPerType / $this->totalCount ) )
					->text()
		);
		$this->totalSize += $this->totalPerType;
	}

	/**
	 * Output a row of the stats table
	 *
	 * @param string $mime mime type (e.g. image/jpeg)
	 * @param int $count Number of images of this type
	 * @param int $bytes Total space for images of this type
	 */
	protected function outputTableRow( $mime, $count, $bytes ) {
		$mimeSearch = SpecialPage::getTitleFor( 'MIMEsearch', $mime );
		$linkRenderer = $this->getLinkRenderer();
		$row = Html::rawElement(
			'td',
			[],
			$linkRenderer->makeLink( $mimeSearch, $mime )
		);
		$row .= Html::rawElement(
			'td',
			[],
			$this->getExtensionList( $mime )
		);
		$row .= Html::rawElement(
			'td',
			// Make sure js sorts it in numeric order
			[ 'data-sort-value' => $count ],
			$this->msg( 'mediastatistics-nfiles' )
				->numParams( $count )
				/** @todo Check to be sure this really should have number formatting */
				->numParams( $this->makePercentPretty( $count / $this->totalCount ) )
				->parse()
		);
		$row .= Html::rawElement(
			'td',
			// Make sure js sorts it in numeric order
			[ 'data-sort-value' => $bytes ],
			$this->msg( 'mediastatistics-nbytes' )
				->numParams( $bytes )
				->sizeParams( $bytes )
				/** @todo Check to be sure this really should have number formatting */
				->numParams( $this->makePercentPretty( $bytes / $this->totalBytes ) )
				->parse()
		);
		$this->totalPerType += $bytes;
		$this->countPerType += $count;
		$this->getOutput()->addHTML( Html::rawElement( 'tr', [], $row ) );
	}

	/**
	 * @param float $decimal A decimal percentage (ie for 12.3%, this would be 0.123)
	 * @return string The percentage formatted so that 3 significant digits are shown.
	 */
	protected function makePercentPretty( $decimal ) {
		$decimal *= 100;
		// Always show three useful digits
		if ( $decimal == 0 ) {
			return '0';
		}
		if ( $decimal >= 100 ) {
			return '100';
		}
		$percent = sprintf( "%." . max( 0, 2 - floor( log10( $decimal ) ) ) . "f", $decimal );
		// Then remove any trailing 0's
		return preg_replace( '/\.?0*$/', '', $percent );
	}

	/**
	 * Given a mime type, return a comma separated list of allowed extensions.
	 *
	 * @param string $mime mime type
	 * @return string Comma separated list of allowed extensions (e.g. ".ogg, .oga")
	 */
	private function getExtensionList( $mime ) {
		$exts = $this->mimeAnalyzer->getExtensionsFromMimeType( $mime );
		if ( !$exts ) {
			return '';
		}
		foreach ( $exts as &$ext ) {
			$ext = htmlspecialchars( '.' . $ext );
		}

		return $this->getLanguage()->commaList( $exts );
	}

	/**
	 * Output the start of the table
	 *
	 * Including opening <table>, and first <tr> with column headers.
	 * @param string $mediaType
	 */
	protected function outputTableStart( $mediaType ) {
		$out = $this->getOutput();
		$out->addModuleStyles( 'jquery.tablesorter.styles' );
		$out->addModules( 'jquery.tablesorter' );
		$out->addHTML(
			Html::openElement(
				'table',
				[ 'class' => [
					'mw-mediastats-table',
					'mw-mediastats-table-' . strtolower( $mediaType ),
					'sortable',
					'wikitable'
				] ]
			) .
			Html::rawElement( 'thead', [], $this->getTableHeaderRow() ) .
			Html::openElement( 'tbody' )
		);
	}

	/**
	 * Get (not output) the header row for the table
	 *
	 * @return string The header row of the table
	 */
	protected function getTableHeaderRow() {
		$headers = [ 'mimetype', 'extensions', 'count', 'totalbytes' ];
		$ths = '';
		foreach ( $headers as $header ) {
			$ths .= Html::rawElement(
				'th',
				[],
				// for grep:
				// mediastatistics-table-mimetype, mediastatistics-table-extensions
				// mediastatistics-table-count, mediastatistics-table-totalbytes
				$this->msg( 'mediastatistics-table-' . $header )->parse()
			);
		}
		return Html::rawElement( 'tr', [], $ths );
	}

	/**
	 * Output a header for a new media type section
	 *
	 * @param string $mediaType A media type (e.g. from the MEDIATYPE_xxx constants)
	 */
	protected function outputMediaType( $mediaType ) {
		$this->getOutput()->addHTML(
			Html::element(
				'h2',
				[ 'class' => [
					'mw-mediastats-mediatype',
					'mw-mediastats-mediatype-' . strtolower( $mediaType )
				] ],
				// for grep
				// mediastatistics-header-unknown, mediastatistics-header-bitmap,
				// mediastatistics-header-drawing, mediastatistics-header-audio,
				// mediastatistics-header-video, mediastatistics-header-multimedia,
				// mediastatistics-header-office, mediastatistics-header-text,
				// mediastatistics-header-executable, mediastatistics-header-archive,
				// mediastatistics-header-3d,
				$this->msg( 'mediastatistics-header-' . strtolower( $mediaType ) )->text()
			)
		);
		/** @todo Possibly could add a message here explaining what the different types are.
		 *    not sure if it is needed though.
		 */
	}

	/**
	 * parse the fake title format that this special page abuses querycache with.
	 *
	 * @param string $fakeTitle A string formatted as <media type>;<mime type>;<count>;<bytes>
	 * @return array The constituent parts of $fakeTitle
	 */
	private function splitFakeTitle( $fakeTitle ) {
		return explode( ';', $fakeTitle, 4 );
	}

	/**
	 * What group to put the page in
	 * @return string
	 */
	protected function getGroupName() {
		return 'media';
	}

	/** @inheritDoc */
	public function formatResult( $skin, $result ) {
		return false;
	}

	/**
	 * Initialize total values so we can figure out percentages later.
	 *
	 * @param IReadableDatabase $dbr
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $dbr, $res ) {
		$this->executeLBFromResultWrapper( $res );
		$this->totalCount = $this->totalBytes = 0;
		foreach ( $res as $row ) {
			$mediaStats = $this->splitFakeTitle( $row->title );
			$this->totalCount += $mediaStats[2] ?? 0;
			$this->totalBytes += $mediaStats[3] ?? 0;
		}
		$res->seek( 0 );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMediaStatistics::class, 'SpecialMediaStatistics' );
