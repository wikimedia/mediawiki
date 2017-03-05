<?php
/**
 * Implements Special:MIMESearch
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
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */

/**
 * Searches the database for files of the requested MIME type, comparing this with the
 * 'img_major_mime' and 'img_minor_mime' fields in the image table.
 * @ingroup SpecialPage
 */
class MIMEsearchPage extends QueryPage {
	protected $major, $minor, $mime;

	function __construct( $name = 'MIMEsearch' ) {
		parent::__construct( $name );
	}

	public function isExpensive() {
		return false;
	}

	function isSyndicated() {
		return false;
	}

	function isCacheable() {
		return false;
	}

	function linkParameters() {
		return [ 'mime' => "{$this->major}/{$this->minor}" ];
	}

	public function getQueryInfo() {
		$minorType = [];
		if ( $this->minor !== '*' ) {
			// Allow wildcard searching
			$minorType['img_minor_mime'] = $this->minor;
		}
		$qi = [
			'tables' => [ 'image' ],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => 'img_name',
				// Still have a value field just in case,
				// but it isn't actually used for sorting.
				'value' => 'img_name',
				'img_size',
				'img_width',
				'img_height',
				'img_user_text',
				'img_timestamp'
			],
			'conds' => [
				'img_major_mime' => $this->major,
				// This is in order to trigger using
				// the img_media_mime index in "range" mode.
				'img_media_type' => [
					MEDIATYPE_BITMAP,
					MEDIATYPE_DRAWING,
					MEDIATYPE_AUDIO,
					MEDIATYPE_VIDEO,
					MEDIATYPE_MULTIMEDIA,
					MEDIATYPE_UNKNOWN,
					MEDIATYPE_OFFICE,
					MEDIATYPE_TEXT,
					MEDIATYPE_EXECUTABLE,
					MEDIATYPE_ARCHIVE,
				],
			] + $minorType,
		];

		return $qi;
	}

	/**
	 * The index is on (img_media_type, img_major_mime, img_minor_mime)
	 * which unfortunately doesn't have img_name at the end for sorting.
	 * So tell db to sort it however it wishes (Its not super important
	 * that this report gives results in a logical order). As an aditional
	 * note, mysql seems to by default order things by img_name ASC, which
	 * is what we ideally want, so everything works out fine anyhow.
	 * @return array
	 */
	function getOrderFields() {
		return [];
	}

	/**
	 * Generate and output the form
	 */
	function getPageHeader() {
		$formDescriptor = [
			'mime' => [
				'type' => 'combobox',
				'options' => $this->getSuggestionsForTypes(),
				'name' => 'mime',
				'label-message' => 'mimetype',
				'required' => true,
				'default' => $this->mime,
			],
		];

		HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setSubmitTextMsg( 'ilsubmit' )
			->setAction( $this->getPageTitle()->getLocalURL() )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );
	}

	protected function getSuggestionsForTypes() {
		$dbr = wfGetDB( DB_REPLICA );
		$lastMajor = null;
		$suggestions = [];
		$result = $dbr->select(
			[ 'image' ],
			// We ignore img_media_type, but using it in the query is needed for MySQL to choose a
			// sensible execution plan
			[ 'img_media_type', 'img_major_mime', 'img_minor_mime' ],
			[],
			__METHOD__,
			[ 'GROUP BY' => [ 'img_media_type', 'img_major_mime', 'img_minor_mime' ] ]
		);
		foreach ( $result as $row ) {
			$major = $row->img_major_mime;
			$minor = $row->img_minor_mime;
			$suggestions[ "$major/$minor" ] = "$major/$minor";
			if ( $lastMajor === $major ) {
				// If there are at least two with the same major mime type, also include the wildcard
				$suggestions[ "$major/*" ] = "$major/*";
			}
			$lastMajor = $major;
		}
		ksort( $suggestions );
		return $suggestions;
	}

	public function execute( $par ) {
		$this->mime = $par ? $par : $this->getRequest()->getText( 'mime' );
		$this->mime = trim( $this->mime );
		list( $this->major, $this->minor ) = File::splitMime( $this->mime );

		if ( $this->major == '' || $this->minor == '' || $this->minor == 'unknown' ||
			!self::isValidType( $this->major )
		) {
			$this->setHeaders();
			$this->outputHeader();
			$this->getPageHeader();
			return;
		}

		parent::execute( $par );
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgContLang;

		$linkRenderer = $this->getLinkRenderer();
		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getText() );
		$plink = $linkRenderer->makeLink(
			Title::newFromText( $nt->getPrefixedText() ),
			$text
		);

		$download = Linker::makeMediaLinkObj( $nt, $this->msg( 'download' )->escaped() );
		$download = $this->msg( 'parentheses' )->rawParams( $download )->escaped();
		$lang = $this->getLanguage();
		$bytes = htmlspecialchars( $lang->formatSize( $result->img_size ) );
		$dimensions = $this->msg( 'widthheight' )->numParams( $result->img_width,
			$result->img_height )->escaped();
		$user = $linkRenderer->makeLink(
			Title::makeTitle( NS_USER, $result->img_user_text ),
			$result->img_user_text
		);

		$time = $lang->userTimeAndDate( $result->img_timestamp, $this->getUser() );
		$time = htmlspecialchars( $time );

		return "$download $plink . . $dimensions . . $bytes . . $user . . $time";
	}

	/**
	 * @param string $type
	 * @return bool
	 */
	protected static function isValidType( $type ) {
		// From maintenance/tables.sql => img_major_mime
		$types = [
			'unknown',
			'application',
			'audio',
			'image',
			'text',
			'video',
			'message',
			'model',
			'multipart',
			'chemical'
		];

		return in_array( $type, $types );
	}

	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	protected function getGroupName() {
		return 'media';
	}
}
