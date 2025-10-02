<?php
/**
 * Copyright © 2008 Soxred93
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\SpecialPage\WantedQueryPage;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of the most linked non-existent files.
 *
 * @ingroup SpecialPage
 * @author Soxred93 <soxred93@gmail.com>
 */
class SpecialWantedFiles extends WantedQueryPage {

	private RepoGroup $repoGroup;
	private int $migrationStage;

	public function __construct(
		RepoGroup $repoGroup,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Wantedfiles' );
		$this->repoGroup = $repoGroup;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
	}

	/** @inheritDoc */
	protected function getPageHeader() {
		# Specifically setting to use "Wanted Files" (NS_MAIN) as title, so as to get what
		# category would be used on main namespace pages, for those tricky wikipedia
		# admins who like to do {{#ifeq:{{NAMESPACE}}|foo|bar|....}}.
		$catMessage = $this->msg( 'broken-file-category' )
			->page( PageReferenceValue::localReference( NS_MAIN, "Wanted Files" ) )
			->inContentLanguage();

		if ( !$catMessage->isDisabled() ) {
			$category = Title::makeTitleSafe( NS_CATEGORY, $catMessage->text() );
		} else {
			$category = false;
		}

		$noForeign = '';
		if ( !$this->likelyToHaveFalsePositives() ) {
			// Additional messages for grep:
			// wantedfiletext-cat-noforeign, wantedfiletext-nocat-noforeign
			$noForeign = '-noforeign';
		}

		if ( $category ) {
			return $this
				->msg( 'wantedfiletext-cat' . $noForeign )
				->params( $category->getFullText() )
				->parseAsBlock();
		} else {
			return $this
				->msg( 'wantedfiletext-nocat' . $noForeign )
				->parseAsBlock();
		}
	}

	/**
	 * Whether foreign repos are likely to cause false positives
	 *
	 * In its own function to allow subclasses to override.
	 * @see SpecialWantedFilesGUOverride in GlobalUsage extension.
	 * @since 1.24
	 * @return bool
	 */
	protected function likelyToHaveFalsePositives() {
		return $this->repoGroup->hasForeignRepos();
	}

	/**
	 * KLUGE: The results may contain false positives for files
	 * that exist e.g. in a shared repo.  Setting this at least
	 * keeps them from showing up as redlinks in the output, even
	 * if it doesn't fix the real problem (T8220).
	 *
	 * @note could also have existing links here from broken file
	 * redirects.
	 * @return bool
	 */
	protected function forceExistenceCheck() {
		return true;
	}

	/**
	 * Does the file exist?
	 *
	 * Use findFile() so we still think file namespace pages without files
	 * are missing, but valid file redirects and foreign files are ok.
	 *
	 * @param Title $title
	 * @return bool
	 */
	protected function existenceCheck( Title $title ) {
		return (bool)$this->repoGroup->findFile( $title );
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		if ( $this->migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$fileTable = 'image';
			$nameField = 'img_name';
			$extraConds1 = [];
			$extraConds2 = [];
		} else {
			$fileTable = 'file';
			$nameField = 'file_name';
			$extraConds1 = [ 'img1.file_deleted' => 0 ];
			$extraConds2 = [ 'img2.file_deleted' => 0 ];
		}
		return [
			'tables' => [
				'imagelinks',
				'page',
				'redirect',
				'img1' => $fileTable,
				'img2' => $fileTable,
			],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => 'il_to',
				'value' => 'COUNT(*)'
			],
			'conds' => [
				'img1.' . $nameField => null,
				// We also need to exclude file redirects
				'img2.' . $nameField => null,
			],
			'options' => [ 'GROUP BY' => 'il_to' ],
			'join_conds' => [
				'img1' => [ 'LEFT JOIN',
					array_merge( [ 'il_to = img1.' . $nameField ], $extraConds1 ),
				],
				'page' => [ 'LEFT JOIN', [
					'il_to = page_title',
					'page_namespace' => NS_FILE,
				] ],
				'redirect' => [ 'LEFT JOIN', [
					'page_id = rd_from',
					'rd_namespace' => NS_FILE,
					'rd_interwiki' => ''
				] ],
				'img2' => [ 'LEFT JOIN',
					array_merge( [ 'rd_title = img2.' . $nameField ], $extraConds2 ),
				]
			]
		];
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialWantedFiles::class, 'WantedFilesPage' );
