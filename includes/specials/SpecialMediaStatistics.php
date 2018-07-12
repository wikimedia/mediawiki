<?php
/**
 * Implements Special:MediaStatistics
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
 * @ingroup SpecialPage
 * @author Brian Wolff
 */

use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * @ingroup SpecialPage
 */
class MediaStatisticsPage extends QueryPage {
	protected $totalCount = 0, $totalBytes = 0;

	/**
	 * @var int $totalPerType Combined file size of all files in a section
	 */
	protected $totalPerType = 0;

	/**
	 * @var int $totalSize Combined file size of all files
	 */
	protected $totalSize = 0;

	function __construct( $name = 'MediaStatistics' ) {
		parent::__construct( $name );
		// Generally speaking there is only a small number of file types,
		// so just show all of them.
		$this->limit = 5000;
		$this->shownavigation = false;
	}

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
		$dbr = wfGetDB( DB_REPLICA );
		$fakeTitle = $dbr->buildConcat( [
			'img_media_type',
			$dbr->addQuotes( ';' ),
			'img_major_mime',
			$dbr->addQuotes( '/' ),
			'img_minor_mime',
			$dbr->addQuotes( ';' ),
			'COUNT(*)',
			$dbr->addQuotes( ';' ),
			'SUM( img_size )'
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
	}

	/**
	 * How to sort the results
	 *
	 * It's important that img_media_type come first, otherwise the
	 * tables will be fragmented.
	 * @return Array Fields to sort by
	 */
	function getOrderFields() {
		return [ 'img_media_type', 'count(*)', 'img_major_mime', 'img_minor_mime' ];
	}

	/**
	 * Output the results of the query.
	 *
	 * @param OutputPage $out
	 * @param Skin $skin (deprecated presumably)
	 * @param IDatabase $dbr
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
			list( $mediaType, $mime, $totalCount, $totalBytes ) = $mediaStats;
			if ( $prevMediaType !== $mediaType ) {
				if ( $prevMediaType !== null ) {
					// We're not at beginning, so we have to
					// close the previous table.
					$this->outputTableEnd();
				}
				$this->outputMediaType( $mediaType );
				$this->totalPerType = 0;
				$this->outputTableStart( $mediaType );
				$prevMediaType = $mediaType;
			}
			$this->outputTableRow( $mime, intval( $totalCount ), intval( $totalBytes ) );
		}
		if ( $prevMediaType !== null ) {
			$this->outputTableEnd();
			// add total size of all files
			$this->outputMediaType( 'total' );
			$this->getOutput()->addWikiText(
				$this->msg( 'mediastatistics-allbytes' )
					->numParams( $this->totalSize )
					->sizeParams( $this->totalSize )
					->text()
			);
		}
	}

	/**
	 * Output closing </table>
	 */
	protected function outputTableEnd() {
		$this->getOutput()->addHTML( Html::closeElement( 'table' ) );
		$this->getOutput()->addWikiText(
				$this->msg( 'mediastatistics-bytespertype' )
					->numParams( $this->totalPerType )
					->sizeParams( $this->totalPerType )
					->numParams( $this->makePercentPretty( $this->totalPerType / $this->totalBytes ) )
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
		$this->getOutput()->addHTML( Html::rawElement( 'tr', [], $row ) );
	}

	/**
	 * @param float $decimal A decimal percentage (ie for 12.3%, this would be 0.123)
	 * @return String The percentage formatted so that 3 significant digits are shown.
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
		$exts = MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer()
			->getExtensionsForType( $mime );
		if ( $exts === null ) {
			return '';
		}
		$extArray = explode( ' ', $exts );
		$extArray = array_unique( $extArray );
		foreach ( $extArray as &$ext ) {
			$ext = htmlspecialchars( '.' . $ext );
		}

		return $this->getLanguage()->commaList( $extArray );
	}

	/**
	 * Output the start of the table
	 *
	 * Including opening <table>, and first <tr> with column headers.
	 * @param string $mediaType
	 */
	protected function outputTableStart( $mediaType ) {
		$this->getOutput()->addHTML(
			Html::openElement(
				'table',
				[ 'class' => [
					'mw-mediastats-table',
					'mw-mediastats-table-' . strtolower( $mediaType ),
					'sortable',
					'wikitable'
				] ]
			)
		);
		$this->getOutput()->addHTML( $this->getTableHeaderRow() );
	}

	/**
	 * Get (not output) the header row for the table
	 *
	 * @return String the header row of the able
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
				// tatistics-table-count, mediastatistics-table-totalbytes
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
	 * @return array The constituant parts of $fakeTitle
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

	/**
	 * This method isn't used, since we override outputResults, but
	 * we need to implement since abstract in parent class.
	 *
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return bool|string|void
	 * @throws MWException
	 */
	public function formatResult( $skin, $result ) {
		throw new MWException( "unimplemented" );
	}

	/**
	 * Initialize total values so we can figure out percentages later.
	 *
	 * @param IDatabase $dbr
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
