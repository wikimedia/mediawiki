<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\HTMLForm\Field\HTMLMultiSelectField;
use MediaWiki\HTMLForm\Field\HTMLSelectNamespace;
use MediaWiki\HTMLForm\Field\HTMLSizeFilterField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\ProtectedPagesPager;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * A special page that lists protected pages
 *
 * @ingroup SpecialPage
 */
class SpecialProtectedPages extends SpecialPage {
	private LinkBatchFactory $linkBatchFactory;
	private IConnectionProvider $dbProvider;
	private CommentStore $commentStore;
	private RowCommentFormatter $rowCommentFormatter;
	private RestrictionStore $restrictionStore;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		CommentStore $commentStore,
		RowCommentFormatter $rowCommentFormatter,
		RestrictionStore $restrictionStore
	) {
		parent::__construct( 'Protectedpages' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->dbProvider = $dbProvider;
		$this->commentStore = $commentStore;
		$this->rowCommentFormatter = $rowCommentFormatter;
		$this->restrictionStore = $restrictionStore;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );
		$this->addHelpLink( 'Help:Protected_pages' );

		$request = $this->getRequest();
		$type = $request->getVal( 'type' );
		$level = $request->getVal( 'level' );
		$sizetype = $request->getVal( 'size-mode' );
		$size = $request->getIntOrNull( 'size' );
		$ns = $request->getIntOrNull( 'namespace' );

		$filters = $request->getArray( 'wpfilters', [] );
		$indefOnly = in_array( 'indefonly', $filters );
		$cascadeOnly = in_array( 'cascadeonly', $filters );
		$noRedirect = in_array( 'noredirect', $filters );

		$pager = new ProtectedPagesPager(
			$this->getContext(),
			$this->commentStore,
			$this->linkBatchFactory,
			$this->getLinkRenderer(),
			$this->dbProvider,
			$this->rowCommentFormatter,
			$type,
			$level,
			$ns,
			$sizetype,
			$size,
			$indefOnly,
			$cascadeOnly,
			$noRedirect
		);

		$this->getOutput()->addHTML( $this->showOptions(
			$type,
			$level,
			$filters
		) );

		if ( $pager->getNumRows() ) {
			$this->getOutput()->addModuleStyles( 'mediawiki.interface.helpers.styles' );
			$this->getOutput()->addParserOutputContent(
				$pager->getFullOutput(),
				ParserOptions::newFromContext( $this->getContext() )
			);
		} else {
			$this->getOutput()->addWikiMsg( 'protectedpagesempty' );
		}
	}

	/**
	 * @param string $type Restriction type
	 * @param string $level Restriction level
	 * @param array $filters Filters set for the pager: indefOnly,
	 *   cascadeOnly, noRedirect
	 * @return string Input form
	 */
	protected function showOptions( $type, $level, $filters ) {
		$formDescriptor = [
			'namespace' => [
				'class' => HTMLSelectNamespace::class,
				'name' => 'namespace',
				'id' => 'namespace',
				'cssclass' => 'namespaceselector',
				'all' => '',
				'label' => $this->msg( 'namespace' )->text(),
			],
			'typemenu' => $this->getTypeMenu( $type ),
			'levelmenu' => $this->getLevelMenu( $level ),
			'filters' => [
				'class' => HTMLMultiSelectField::class,
				'label' => $this->msg( 'protectedpages-filters' )->text(),
				'flatlist' => true,
				'options-messages' => [
					'protectedpages-indef' => 'indefonly',
					'protectedpages-cascade' => 'cascadeonly',
					'protectedpages-noredirect' => 'noredirect',
				],
				'default' => $filters,
			],
			'sizelimit' => [
				'class' => HTMLSizeFilterField::class,
				'name' => 'size',
			]
		];
		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setMethod( 'get' )
			->setWrapperLegendMsg( 'protectedpages' )
			->setSubmitTextMsg( 'protectedpages-submit' );

		return $htmlForm->prepareForm()->getHTML( false );
	}

	/**
	 * Creates the input label of the restriction type
	 * @param string $pr_type Protection type
	 * @return array
	 */
	protected function getTypeMenu( $pr_type ) {
		$m = []; // Temporary array
		$options = [];

		// First pass to load the log names
		foreach ( $this->restrictionStore->listAllRestrictionTypes( true ) as $type ) {
			// Messages: restriction-edit, restriction-move, restriction-create, restriction-upload
			$text = $this->msg( "restriction-$type" )->text();
			$m[$text] = $type;
		}

		// Third pass generates sorted XHTML content
		foreach ( $m as $text => $type ) {
			$options[$text] = $type;
		}

		return [
			'type' => 'select',
			'options' => $options,
			'label' => $this->msg( 'restriction-type' )->text(),
			'name' => 'type',
			'id' => 'type',
		];
	}

	/**
	 * Creates the input label of the restriction level
	 * @param string $pr_level Protection level
	 * @return array
	 */
	protected function getLevelMenu( $pr_level ) {
		$options = [ 'restriction-level-all' => 0 ];

		// Load the log names as options
		foreach ( $this->getConfig()->get( MainConfigNames::RestrictionLevels ) as $type ) {
			if ( $type != '' && $type != '*' ) {
				// Messages: restriction-level-sysop, restriction-level-autoconfirmed
				$options["restriction-level-$type"] = $type;
			}
		}

		return [
			'type' => 'select',
			'options-messages' => $options,
			'label-message' => 'restriction-level',
			'name' => 'level',
			'id' => 'level',
		];
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialProtectedPages::class, 'SpecialProtectedpages' );
