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
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use MediaWiki\Context\IContextSource;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\SpecialPage\SpecialPage;
use UnexpectedValueException;
use UploadStash;
use UploadStashFile;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @ingroup Pager
 */
class UploadStashPager extends TablePager {
	private UploadStash $stash;
	private LocalRepo $localRepo;

	/** @var string[]|null */
	protected $mFieldNames = null;

	/** @var File[] */
	private array $files = [];

	public function __construct(
		IContextSource $context,
		LinkRenderer $linkRenderer,
		IConnectionProvider $dbProvider,
		UploadStash $stash,
		LocalRepo $localRepo
	) {
		$this->setContext( $context );

		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbProvider->getReplicaDatabase();

		parent::__construct( $context, $linkRenderer );

		$this->stash = $stash;
		$this->localRepo = $localRepo;
	}

	/** @inheritDoc */
	protected function getFieldNames() {
		if ( !$this->mFieldNames ) {
			$this->mFieldNames = [
				'us_timestamp' => $this->msg( 'uploadstash-header-date' )->text(),
				'us_key' => $this->msg( 'uploadstash-header-filekey' )->text(),
				'thumb' => $this->msg( 'uploadstash-header-thumb' )->text(),
				'us_size' => $this->msg( 'uploadstash-header-dimensions' )->text(),
			];
		}

		return $this->mFieldNames;
	}

	/** @inheritDoc */
	protected function isFieldSortable( $field ) {
		return in_array( $field, [ 'us_timestamp', 'us_key' ] );
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return [
			'tables' => [ 'uploadstash' ],
			'fields' => [
				'us_id',
				'us_timestamp',
				'us_key',
				'us_size',
				'us_path',
				'us_sha1',
				'us_mime',
			],
			'conds' => [ 'us_user' => $this->getUser()->getId() ],
			'options' => [],
			'join_conds' => [],
		];
	}

	/** @inheritDoc */
	public function getIndexField() {
		return [ [ 'us_timestamp', 'us_id' ] ];
	}

	/** @inheritDoc */
	public function getDefaultSort() {
		return 'us_timestamp';
	}

	/**
	 * @param string $field
	 * @param string|null $value
	 * @return string
	 */
	public function formatValue( $field, $value ) {
		$linkRenderer = $this->getLinkRenderer();

		switch ( $field ) {
			case 'us_timestamp':
				// We may want to make this a link to the "old" version when displaying old files
				return htmlspecialchars( $this->getLanguage()->userTimeAndDate( $value, $this->getUser() ) );
			case 'us_key':
				return $this->getLinkRenderer()->makeKnownLink(
					SpecialPage::getTitleFor( 'UploadStash', "file/$value" ),
					$value
				);
			case 'thumb':
				$file = $this->getCurrentFile();
				if ( $file->allowInlineDisplay() ) {
					$thumbnail = $file->transform( [
						'width' => '120',
						'height' => '120',
					] );
					if ( $thumbnail ) {
						return $thumbnail->toHtml( [ 'loading' => 'lazy' ] );
					}
				}
				return $this->msg( 'uploadstash-nothumb' )->escaped();
			case 'us_size':
				$file = $this->getCurrentFile();
				return htmlspecialchars( $file->getDimensionsString() )
					. $this->msg( 'word-separator' )->escaped()
					. Html::element( 'span', [ 'style' => 'white-space: nowrap;' ],
						$this->msg( 'parentheses' )->sizeParams( (int)$value )->text()
					);
			default:
				throw new UnexpectedValueException( "Unknown field '$field'" );
		}
	}

	private function getCurrentFile(): File {
		$fileKey = $this->mCurrentRow->us_key;
		return $this->files[$fileKey]
			?? new UploadStashFile(
				$this->localRepo,
				$this->mCurrentRow->us_path,
				$fileKey,
				$this->mCurrentRow->us_sha1,
				$this->mCurrentRow->us_mime ?? false
			);
	}

	/**
	 * Escape the options list
	 */
	private function getEscapedLimitSelectList(): array {
		$list = $this->getLimitSelectList();
		$result = [];
		foreach ( $list as $key => $value ) {
			$result[htmlspecialchars( $key )] = $value;
		}
		return $result;
	}

	/** @inheritDoc */
	public function getForm() {
		$formDescriptor = [];
		$formDescriptor['limit'] = [
			'type' => 'radio',
			'name' => 'limit',
			'label-message' => 'table_pager_limit_label',
			'options' => $this->getEscapedLimitSelectList(),
			'flatlist' => true,
			'default' => $this->mLimit
		];

		HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setMethod( 'get' )
			->setId( 'mw-uploadstash-form' )
			->setTitle( $this->getTitle() )
			->setSubmitTextMsg( 'uploadstash-pager-submit' )
			->setWrapperLegendMsg( 'uploadstash' )
			->prepareForm()
			->displayForm( '' );
	}

	/** @inheritDoc */
	protected function getTableClass() {
		return parent::getTableClass() . ' uploadstash';
	}

	/** @inheritDoc */
	protected function getNavClass() {
		return parent::getNavClass() . ' uploadstash_nav';
	}

	/** @inheritDoc */
	protected function getSortHeaderClass() {
		return parent::getSortHeaderClass() . ' uploadstash_sort';
	}
}
