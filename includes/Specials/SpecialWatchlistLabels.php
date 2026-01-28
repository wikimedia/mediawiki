<?php
/**
 * @license GPL-3.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use InvalidArgumentException;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Watchlist\WatchlistLabel;
use MediaWiki\Watchlist\WatchlistLabelStore;
use MediaWiki\Watchlist\WatchlistSpecialPage;
use StatusValue;
use Wikimedia\Codex\Builder\TableBuilder;
use Wikimedia\Codex\Utility\Codex;

/**
 * A special page for viewing a user's watchlist labels and performing CRUD operations on them.
 *
 * @ingroup SpecialPage
 * @ingroup Watchlist
 */
class SpecialWatchlistLabels extends SpecialPage {

	private const SUBPAGE_EDIT = 'edit';
	private const SUBPAGE_DELETE = 'delete';
	private const PARAM_ID = 'wll_id';
	private const PARAM_IDS = 'wll_ids';
	private const PARAM_NAME = 'wll_name';

	use WatchlistSpecialPage;

	private ?WatchlistLabel $watchlistLabel = null;

	/** @inheritDoc */
	public function __construct(
		private WatchlistLabelStore $labelStore,
		$name = 'WatchlistLabels',
		$restriction = 'viewmywatchlist',
	) {
		parent::__construct( $name, $restriction, false );
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
	public function execute( $subPage ) {
		$user = $this->getUser();
		$right = $subPage === self::SUBPAGE_EDIT ? 'editmywatchlist' : 'viewmywatchlist';
		if ( !$user->isRegistered()
			|| ( $user->isTemp() && !$user->isAllowed( $right ) )
		) {
			// The message used here will be one of:
			// * watchlistlabels-not-logged-in
			// * watchlistlabels-not-logged-in-for-temp-user
			throw new UserNotLoggedIn( 'watchlistlabels-not-logged-in' );
		}
		$this->checkPermissions();

		$output = $this->getOutput();
		$output->setPageTitleMsg( $this->msg( 'watchlistlabels-title' ) );
		$this->addHelpLink( 'Help:Watchlist labels' );
		if ( !$this->getConfig()->get( MainConfigNames::EnableWatchlistLabels ) ) {
			$output->addHTML( Html::errorBox( $this->msg( 'watchlistlabels-not-enabled' )->escaped() ) );
			return;
		}

		$this->outputSubtitle();

		if ( $subPage === self::SUBPAGE_EDIT ) {
			$this->showEditForm();
		} elseif ( $subPage === self::SUBPAGE_DELETE ) {
			$this->showDeleteConfirmation();
		} else {
			$this->showTable();
		}
	}

	/**
	 * Get the label editing form.
	 */
	private function showEditForm() {
		$id = $this->getRequest()->getInt( self::PARAM_ID );
		$descriptor = [
			self::PARAM_NAME => [
				'type' => 'text',
				'name' => self::PARAM_NAME,
				'label-message' => 'watchlistlabels-form-field-name',
				'validation-callback' => [ $this, 'validateName' ],
				'filter-callback' => [ $this, 'filterName' ],
				'required' => true,
			],
		];
		$msgSuffix = 'new';
		if ( $id ) {
			$this->watchlistLabel = $this->labelStore->loadById( $this->getUser(), $id );
			if ( $this->watchlistLabel ) {
				$descriptor[self::PARAM_NAME]['default'] = $this->watchlistLabel->getName();
				$descriptor[self::PARAM_ID] = [
					'type' => 'hidden',
					'name' => self::PARAM_ID,
					'default' => $this->watchlistLabel->getId(),
				];
				$msgSuffix = 'edit';
			}
		}
		$form = HTMLForm::factory( 'codex', $descriptor, $this->getContext() )
			// Messages used here:
			// - watchlistlabels-form-header-new
			// - watchlistlabels-form-header-edit
			->setHeaderHtml( Html::element( 'h3', [], $this->msg( "watchlistlabels-form-header-$msgSuffix" )->text() ) )
			->showCancel( true )
			->setCancelTarget( $this->getPageTitle() )
			// Messages used here:
			// - watchlistlabels-form-submit-new
			// - watchlistlabels-form-submit-edit
			->setSubmitTextMsg( "watchlistlabels-form-submit-$msgSuffix" )
			->setSubmitCallback( [ $this, 'onEditFormSubmit' ] );
		$form->show();
	}

	/**
	 * Filter the 'name' field value.
	 *
	 * @param ?mixed $value
	 * @param ?array $alldata
	 * @param ?HTMLForm $form
	 *
	 * @return (StatusValue|string|bool|Message)|null
	 */
	public function filterName( $value, ?array $alldata, ?HTMLForm $form ) {
		$label = new WatchlistLabel( $this->getUser(), $value ?? '' );
		return $label->getName();
	}

	/**
	 * @param mixed $value
	 * @param ?array $alldata
	 * @param ?HTMLForm $form
	 *
	 * @return (StatusValue|string|bool|Message)|null
	 */
	public function validateName( $value, ?array $alldata, ?HTMLForm $form ) {
		$length = strlen( trim( $value ) );
		if ( $length === 0 ) {
			return Status::newFatal( $this->msg( 'watchlistlabels-form-name-too-short', $length ) );
		}
		if ( $length > 255 ) {
			return Status::newFatal( $this->msg( 'watchlistlabels-form-name-too-long', $length ) );
		}
		$existingLabel = $this->labelStore->loadByName( $this->getUser(), $value );
		$thisId = $alldata[self::PARAM_ID] ?? null;
		if ( $existingLabel && $thisId && $existingLabel->getId() !== (int)$thisId ) {
			return Status::newFatal( $this->msg( 'watchlistlabels-form-name-exists', $existingLabel->getName() ) );
		}
		return Status::newGood();
	}

	/**
	 * Handle the form submission, for saving new or existing labels.
	 *
	 * @param mixed $data Form submission data.
	 * @return Status
	 */
	public function onEditFormSubmit( $data ): Status {
		if ( !isset( $data[self::PARAM_NAME] ) ) {
			throw new InvalidArgumentException( 'No name data submitted.' );
		}
		if ( !$this->watchlistLabel ) {
			$this->watchlistLabel = new WatchlistLabel( $this->getUser(), $data[self::PARAM_NAME] );
		} else {
			$this->watchlistLabel->setName( $data[self::PARAM_NAME] );
		}
		$saved = $this->labelStore->save( $this->watchlistLabel );
		if ( $saved->isOK() ) {
			$this->getOutput()->redirect( $this->getPageTitle()->getLocalURL() );
		}
		return $saved;
	}

	private function showDeleteConfirmation(): void {
		$ids = $this->getRequest()->getArray( self::PARAM_IDS ) ?? [];
		$labels = $this->labelStore->loadAllForUser( $this->getUser() );
		$toDelete = array_intersect_key( $labels, array_flip( $ids ) );
		$labelCounts = $this->labelStore->countItems( array_keys( $labels ) );
		$listItems = '';
		foreach ( $toDelete as $label ) {
			$listItems .= Html::rawElement( 'li', [], $this->getDeleteConfirmationListItem( $label, $labelCounts ) );
		}
		$count = count( $toDelete );
		$formattedCount = $this->getLanguage()->formatNum( $count );
		$msg = $this->msg( 'watchlistlabels-delete-warning', $count, $formattedCount )->text();
		$warning = Html::element( 'p', [], $msg );
		$list = Html::rawElement( 'ol', [], $listItems );
		$descriptor = [
			'list' => [
				'type' => 'info',
				'default' => $warning . $list,
				'rawrow' => true,
			],
			self::PARAM_IDS => [
				'type' => 'hidden',
				'name' => self::PARAM_IDS,
				'default' => implode( ',', array_keys( $toDelete ) ),
			],
		];
		$header = $this->msg( 'watchlistlabels-delete-header', $count )->text();
		HTMLForm::factory( 'codex', $descriptor, $this->getContext() )
			->setHeaderHtml( Html::element( 'h3', [], $header ) )
			->showCancel( true )
			->setCancelTarget( $this->getPageTitle() )
			->setSubmitTextMsg( 'delete' )
			->setSubmitDestructive()
			->setSubmitCallback( [ $this, 'onDeleteFormSubmit' ] )
			->show();
	}

	private function getDeleteConfirmationListItem( WatchlistLabel $label, array $labelCounts ): string {
		$id = $label->getId();
		if ( !$id ) {
			return '';
		}
		$itemCount = $labelCounts[ $id ];
		if ( $labelCounts[ $id ] > 0 ) {
			$labelCountMsg = $this->msg(
				'watchlistlabels-delete-count',
				$this->getLanguage()->formatNum( $itemCount ),
				$itemCount
			)->escaped();
		} else {
			$labelCountMsg = $this->msg( 'watchlistlabels-delete-unused' )->escaped();
		}
		return Html::element( 'span', [], $label->getName() )
			. $this->msg( 'word-separator' )->escaped()
			. $this->msg( 'parentheses-start' )->escaped()
			. Html::rawElement( 'em', [], $labelCountMsg )
			. $this->msg( 'parentheses-end' )->escaped();
	}

	/**
	 * Handle the delete confirmation form submission.
	 *
	 * @param mixed $data Form submission data.
	 * @return Status
	 */
	public function onDeleteFormSubmit( $data ) {
		if ( !isset( $data[self::PARAM_IDS] ) ) {
			throw new InvalidArgumentException( 'No name data submitted.' );
		}
		$ids = array_map( 'intval', array_filter( explode( ',', $data[self::PARAM_IDS] ) ) );
		if ( $this->labelStore->delete( $this->getUser(), $ids ) ) {
			$this->getOutput()->redirect( $this->getPageTitle()->getLocalURL() );
			return Status::newGood();
		}
		return Status::newFatal( 'watchlistlabels-delete-failed' );
	}

	/**
	 * Show the table of all labels.
	 */
	private function showTable() {
		$codex = new Codex();
		$this->getOutput()->addModules( 'mediawiki.special.watchlistlabels' );
		$this->getOutput()->addModuleStyles( 'mediawiki.special.watchlistlabels.styles' );

		// Page title and description.
		$this->getOutput()->addHTML(
			Html::element( 'h3', [], $this->msg( 'watchlistlabels-table-title' )->text() )
			. Html::element( 'p', [], $this->msg( 'watchlistlabels-table-description' )->text() ),
		);

		// Buttons in the table header.
		$params = [
			'href' => $this->getPageTitle( self::SUBPAGE_EDIT )->getLinkURL(),
			// @todo Remove Codex classes when T406372 is resolved.
			'class' => 'cdx-button cdx-button--fake-button cdx-button--fake-button--enabled'
				. ' cdx-button--action-progressive cdx-button--weight-primary'
		];
		$createButton = Html::element( 'a', $params, $this->msg( 'watchlistlabels-table-new-link' )->text() );
		$deleteButton = $codex->button()
			->setAttributes( [ 'class' => 'mw-specialwatchlistlabels-delete-button' ] )
			->setLabel( $this->msg( 'delete' )->text() )
			->setIconClass( 'mw-specialwatchlistlabels-icon--trash' )
			->setType( 'submit' )
			->setAction( 'destructive' )
			->build()
			->getHtml();

		// Data.
		$data = [];
		$labels = $this->labelStore->loadAllForUser( $this->getUser() );
		$labelCounts = $this->labelStore->countItems( array_keys( $labels ) );
		$editIcon = Html::element( 'span', [ 'class' => 'cdx-button__icon mw-specialwatchlistlabels-icon--edit' ] );
		foreach ( $labels as $label ) {
			$id = $label->getId();
			if ( !$id ) {
				continue;
			}
			$url = $this->getPageTitle( self::SUBPAGE_EDIT )->getLocalURL( [ self::PARAM_ID => $id ] );
			$params = [
				'href' => $url,
				'role' => 'button',
				'class' => 'cdx-button cdx-button--fake-button cdx-button--fake-button--enabled'
					. ' cdx-button--weight-quiet cdx-button--icon-only cdx-button--size-small',
				'title' => $this->msg( 'watchlistlabels-table-edit' )->text(),
			];
			$checkboxId = self::PARAM_IDS . '_' . $id;
			$labelVal = Html::element( 'bdi', [], $label->getName() );
			// The sortable columns must have matching '*-sort' elements containing unformatted data for sorting.
			$data[] = [
				'select' => $this->getCheckbox( $checkboxId, (string)$id ),
				'name' => Html::rawElement( 'label', [ 'for' => $checkboxId ], $labelVal ),
				'name-sort' => mb_strtolower( $label->getName() ),
				'count' => $this->getLanguage()->formatNum( $labelCounts[ $id ] ),
				'count-sort' => $labelCounts[ $id ],
				'edit' => Html::rawElement( 'a', $params, $editIcon ),
			];
		}

		// Sort by count by default, and others as requested.
		// We sort here rather than in the DB because we're combining multiple queries' data,
		// and there's only ever one page of results to show (up to 100).
		$sortCol = $this->getRequest()->getText( 'sort', 'count' );
		$sortDir = $this->getRequest()->getBool( 'asc' ) ? TableBuilder::SORT_ASCENDING : TableBuilder::SORT_DESCENDING;
		$sortColName = $sortCol . '-sort';
		usort(
			$data,
			static function ( $a, $b ) use ( $sortDir, $sortColName ) {
				if ( !isset( $a[$sortColName] )
					|| !isset( $b[$sortColName] )
					|| $a[$sortColName] === $b[$sortColName]
				) {
					return 0;
				}
				return $sortDir === TableBuilder::SORT_ASCENDING
					? $a[$sortColName] <=> $b[$sortColName]
					: $b[$sortColName] <=> $a[$sortColName];
			}
		);

		// Put it all together in the table.
		$table = $codex->table()
			->setCurrentSortColumn( $sortCol )
			->setCurrentSortDirection( $sortDir )
			->setAttributes( [ 'class' => 'mw-specialwatchlistlabels-table' ] )
			->setCaption( $this->msg( 'watchlistlabels-table-header' )->text() )
			->setHeaderContent( "$createButton $deleteButton" )
			->setColumns( [
				[
					'id' => 'select',
					'label' => '',
				],
				[
					'id' => 'name',
					'label' => $this->msg( 'watchlistlabels-table-col-name' )->escaped(),
					'sortable' => true,
				],
				[
					'id' => 'count',
					'label' => $this->msg( 'watchlistlabels-table-col-count' )->escaped(),
					'sortable' => true,
				],
				[
					'id' => 'edit',
					'label' => $this->msg( 'watchlistlabels-table-col-actions' )->escaped(),
				],
			] )
			->setData( $data )
			->setPaginate( false )
			->build();
		$deleteUrl = $this->getPageTitle( self::SUBPAGE_DELETE )->getLocalURL();
		$form = Html::rawElement( 'form', [ 'action' => $deleteUrl ], $table->getHtml() );
		$this->getOutput()->addHTML( $form );
	}

	/**
	 * Get a Codex-structured HTML checkbox.
	 *
	 * @param string $id
	 * @param string $value
	 *
	 * @return string HTML of the checkbox wrapper.
	 */
	private function getCheckbox( string $id, string $value ): string {
		$checkbox = Html::check(
			self::PARAM_IDS . '[]',
			false,
			[ 'value' => $value, 'class' => 'cdx-checkbox__input', 'id' => $id ]
		);
		$checkboxIcon = Html::element( 'span', [ 'class' => 'cdx-checkbox__icon' ] );
		$checkboxWrapper = Html::rawElement(
			'div',
			[ 'class' => 'cdx-checkbox__wrapper' ],
			$checkbox . $checkboxIcon
		);
		return Html::rawElement( 'div', [ 'class' => 'cdx-checkbox' ], $checkboxWrapper );
	}
}
