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

/**
 * @ingroup SpecialPage
 */
class MediaStatisticsPage extends QueryPage {
	protected $totalCount = 0, $totalBytes = 0;

	function __construct( $name = 'MediaStatistics' ) {
		parent::__construct( $name );
		// Generally speaking there is only a small number of file types,
		// so just show all of them.
		$this->limit = 5000;
		$this->shownavigation = false;
	}

	function isExpensive() {
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
	 */
	public function getQueryInfo() {
		$dbr = wfGetDB( DB_SLAVE );
		$fakeTitle = $dbr->buildConcat( array(
			'img_media_type',
			$dbr->addQuotes( ';' ),
			'img_major_mime',
			$dbr->addQuotes( '/' ),
			'img_minor_mime',
			$dbr->addQuotes( ';' ),
			'COUNT(*)',
			$dbr->addQuotes( ';' ),
			'SUM( img_size )'
		) );
		return array(
			'tables' => array( 'image' ),
			'fields' => array(
				'title' => $fakeTitle,
				'namespace' => NS_MEDIA, /* needs to be something */
				'value' => '1'
			),
			'options' => array(
				'GROUP BY' => array(
					'img_media_type',
					'img_major_mime',
					'img_minor_mime',
				)
			)
		);
	}

	/**
	 * How to sort the results
	 *
	 * It's important that img_media_type come first, otherwise the
	 * tables will be fragmented.
	 * @return Array Fields to sort by
	 */
	function getOrderFields() {
		return array( 'img_media_type', 'count(*)', 'img_major_mime', 'img_minor_mime' );
	}

	/**
	 * Output the results of the query.
	 *
	 * @param $out OutputPage
	 * @param $skin Skin (deprecated presumably)
	 * @param $dbr DatabaseBase
	 * @param $res ResultWrapper Results from query
	 * @param $num integer Number of results
	 * @param $offset integer Paging offset (Should always be 0 in our case)
	 */
	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		$prevMediaType = null;
		foreach ( $res as $row ) {
			list( $mediaType, $mime, $totalCount, $totalBytes ) = $this->splitFakeTitle( $row->title );
			if ( $prevMediaType !== $mediaType ) {
				if ( $prevMediaType !== null ) {
					// We're not at beginning, so we have to
					// close the previous table.
					$this->outputTableEnd();
				}
				$this->outputMediaType( $mediaType );
				$this->outputTableStart( $mediaType );
				$prevMediaType = $mediaType;
			}
			$this->outputTableRow( $mime, intval( $totalCount ), intval( $totalBytes ) );
		}
		if ( $prevMediaType !== null ) {
			$this->outputTableEnd();
		}
	}

	/**
	 * Output closing </table>
	 */
	protected function outputTableEnd() {
		$this->getOutput()->addHtml( Html::closeElement( 'table' ) );
	}

	/**
	 * Output a row of the stats table
	 *
	 * @param $mime String mime type (e.g. image/jpeg)
	 * @param $count integer Number of images of this type
	 * @param $totalBytes integer Total space for images of this type
	 */
	protected function outputTableRow( $mime, $count, $bytes ) {
		$mimeSearch = SpecialPage::getTitleFor( 'MIMEsearch', $mime );
		$row = Html::rawElement(
			'td',
			array(),
			Linker::link( $mimeSearch, htmlspecialchars( $mime ) )
		);
		$row .= Html::element(
			'td',
			array(),
			$this->getExtensionList( $mime )
		);
		$row .= Html::rawElement(
			'td',
			array(),
			$this->msg( 'mediastatistics-nfiles' )
				->numParams( $count )
				/** @todo Check to be sure this really should have number formatting */
				->numParams( $this->makePercentPretty( $count / $this->totalCount ) )
				->parse()
		);
		$row .= Html::rawElement(
			'td',
			// Make sure js sorts it in numeric order
			array( 'data-sort-value' =>  $bytes ),
			$this->msg( 'mediastatistics-nbytes' )
				->numParams( $bytes )
				->sizeParams( $bytes )
				/** @todo Check to be sure this really should have number formatting */
				->numParams( $this->makePercentPretty( $bytes / $this->totalBytes ) )
				->parse()
		);

		$this->getOutput()->addHTML( Html::rawElement( 'tr', array(), $row ) );
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
		$percent = sprintf( "%." . max( 0, 2 - floor( log10( $decimal ) ) ) . "f", $decimal );
		// Then remove any trailing 0's
		return preg_replace( '/\.?0*$/', '', $percent );
	}

	/**
	 * Given a mime type, return a comma separated list of allowed extensions.
	 *
	 * @param $mime String mime type
	 * @return String Comma separated list of allowed extensions (e.g. ".ogg, .oga")
	 */
	private function getExtensionList( $mime ) {
		$exts = MimeMagic::singleton()->getExtensionsForType( $mime );
		if ( $exts === null ) {
			return '';
		}
		$extArray = explode( ' ', $exts );
		$extArray = array_unique( $extArray );
		foreach ( $extArray as &$ext ) {
			$ext = '.' . $ext;
		}

		return $this->getLanguage()->commaList( $extArray );
	}

	/**
	 * Output the start of the table
	 *
	 * Including opening <table>, and first <tr> with column headers.
	 */
	protected function outputTableStart( $mediaType ) {
		$this->getOutput()->addHTML(
			Html::openElement(
				'table',
				array( 'class' => array(
					'mw-mediastats-table',
					'mw-mediastats-table-' . strtolower( $mediaType ),
					'sortable',
					'wikitable'
				))
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
		$headers = array( 'mimetype', 'extensions', 'count', 'totalbytes' );
		$ths = '';
		foreach ( $headers as $header ) {
			$ths .= Html::rawElement(
				'th',
				array(),
				// for grep:
				// mediastatistics-table-mimetype, mediastatistics-table-extensions
				// tatistics-table-count, mediastatistics-table-totalbytes
				$this->msg( 'mediastatistics-table-' . $header )->parse()
			);
		}
		return Html::rawElement( 'tr', array(), $ths );
	}

	/**
	 * Output a header for a new media type section
	 *
	 * @param $mediaType string A media type (e.g. from the MEDIATYPE_xxx constants)
	 */
	protected function outputMediaType( $mediaType ) {
		$this->getOutput()->addHTML(
			Html::element(
				'h2',
				array( 'class' => array(
					'mw-mediastats-mediatype',
					'mw-mediastats-mediatype-' . strtolower( $mediaType )
				)),
				// for grep
				// mediastatistics-header-unknown, mediastatistics-header-bitmap,
				// mediastatistics-header-drawing, mediastatistics-header-audio,
				// mediastatistics-header-video, mediastatistics-header-multimedia,
				// mediastatistics-header-office, mediastatistics-header-text,
				// mediastatistics-header-executable, mediastatistics-header-archive,
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
	 * @param $fakeTitle String A string formatted as <media type>;<mime type>;<count>;<bytes>
	 * @return Array The constituant parts of $fakeTitle
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
	 * @param $skin Skin
	 * @param $result stdObject Result row
	 */
	public function formatResult( $skin, $result ) {
		throw new MWException( "unimplemented" );
	}

	/**
	 * Initialize total values so we can figure out percentages later.
	 *
	 * @param $dbr DatabaseBase
	 * @param $res ResultWrapper
	 */
	public function preprocessResults( $dbr, $res ) {
		$this->totalCount = $this->totalBytes = 0;
		foreach ( $res as $row ) {
			list( , , $count, $bytes ) = $this->splitFakeTitle( $row->title );
			$this->totalCount += $count;
			$this->totalBytes += $bytes;
		}
		$res->seek( 0 );
	}
}
