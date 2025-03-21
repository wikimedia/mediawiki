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
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\FileSelectQueryBuilder;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Search the database for files of the requested MIME type, comparing this with the
 * 'img_major_mime' and 'img_minor_mime' fields in the image table.
 *
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */
class SpecialMIMESearch extends QueryPage {
	/** @var string */
	protected $major;
	/** @var string */
	protected $minor;
	/** @var string */
	protected $mime;

	private ILanguageConverter $languageConverter;

	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'MIMEsearch' );
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->languageConverter = $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() );
	}

	public function isExpensive() {
		return false;
	}

	public function isSyndicated() {
		return false;
	}

	public function isCacheable() {
		return false;
	}

	protected function linkParameters() {
		return [ 'mime' => "{$this->major}/{$this->minor}" ];
	}

	public function getQueryInfo() {
		$minorType = [];
		if ( $this->minor !== '*' ) {
			// Allow wildcard searching
			$minorType['img_minor_mime'] = $this->minor;
		}
		$fileQuery = FileSelectQueryBuilder::newForFile( $this->getDatabaseProvider()->getReplicaDatabase() )
			->getQueryInfo();
		$qi = [
			'tables' => $fileQuery['tables'],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => 'img_name',
				// Still have a value field just in case,
				// but it isn't actually used for sorting.
				'value' => 'img_name',
				'img_size',
				'img_width',
				'img_height',
				'img_timestamp'
			],
			'conds' => [
				'img_major_mime' => $this->major,
				// This is in order to trigger using
				// the img_media_mime index in "range" mode.
				// @todo how is order defined? use MimeAnalyzer::getMediaTypes?
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
					MEDIATYPE_3D,
				],
			] + $minorType,
			'join_conds' => $fileQuery['join_conds'],
		];

		if ( isset( $fileQuery['fields']['img_user_text'] ) ) {
			$qi['fields']['img_user_text'] = $fileQuery['fields']['img_user_text'];
		} else {
			// file read new
			$qi['fields'][] = 'img_user_text';
		}

		return $qi;
	}

	/**
	 * The index is on (img_media_type, img_major_mime, img_minor_mime)
	 * which unfortunately doesn't have img_name at the end for sorting.
	 * So tell db to sort it however it wishes (Its not super important
	 * that this report gives results in a logical order). As an additional
	 * note, mysql seems to by default order things by img_name ASC, which
	 * is what we ideally want, so everything works out fine anyhow.
	 * @return array
	 */
	protected function getOrderFields() {
		return [];
	}

	/**
	 * Generate and output the form
	 * @return string
	 */
	protected function getPageHeader() {
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
			->setTitle( $this->getPageTitle() )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );
		return '';
	}

	protected function getSuggestionsForTypes() {
		$queryBuilder = $this->getDatabaseProvider()->getReplicaDatabase()->newSelectQueryBuilder();
		$migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
		if ( $migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$queryBuilder
				// We ignore img_media_type, but using it in the query is needed for MySQL to choose a
				// sensible execution plan
				->select( [ 'img_media_type', 'img_major_mime', 'img_minor_mime' ] )
				->from( 'image' )
				->groupBy( [ 'img_media_type', 'img_major_mime', 'img_minor_mime' ] );
		} else {
			$queryBuilder->select(
				[
					'img_media_type' => 'ft_media_type',
					'img_major_mime' => 'ft_major_mime',
					'img_minor_mime' => 'ft_minor_mime',
				]
			)
				->from( 'filetypes' );
		}

		$result = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		$lastMajor = null;
		$suggestions = [];
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
		$this->addHelpLink( 'Help:Managing_files' );
		$this->mime = $par ?: $this->getRequest()->getText( 'mime' );
		$this->mime = trim( $this->mime );
		[ $this->major, $this->minor ] = File::splitMime( $this->mime );
		$mimeAnalyzer = MediaWikiServices::getInstance()->getMimeAnalyzer();

		if ( $this->major == '' || $this->minor == '' || $this->minor == 'unknown' ||
			!$mimeAnalyzer->isValidMajorMimeType( $this->major )
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
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$linkRenderer = $this->getLinkRenderer();
		$nt = Title::makeTitle( $result->namespace, $result->title );

		$text = $this->languageConverter->convertHtml( $nt->getText() );
		$plink = $linkRenderer->makeLink(
			Title::newFromText( $nt->getPrefixedText() ),
			new HtmlArmor( $text )
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

	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	protected function getGroupName() {
		return 'media';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMIMESearch::class, 'SpecialMIMESearch' );
