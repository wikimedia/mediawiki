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
	private const PARAM_ID = 'wll_id';
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

		$subtitle = $this->getWatchlistOwnerHtml();
		if ( !$this->getSkin()->supportsMenu( 'associated-pages' ) ) {
			// For legacy skins render the tabs in the subtitle
			$subtitle .= ' ' . $this->buildTools( null );
		}
		$output->addSubtitle( $subtitle );

		if ( $subPage === self::SUBPAGE_EDIT ) {
			$this->showForm();
		} else {
			$this->showTable();
		}
	}

	/**
	 * Get the label editing form.
	 */
	private function showForm() {
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
			->setSubmitCallback( [ $this, 'onSubmit' ] );
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
	public function onSubmit( $data ): Status {
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

	/**
	 * Show the table of all labels.
	 */
	private function showTable() {
		$codex = new Codex();
		$this->getOutput()->addModuleStyles( 'mediawiki.special.watchlistlabels' );

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
		$addNew = Html::element( 'a', $params, $this->msg( 'watchlistlabels-table-new-link' )->text() );

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
			$data[] = [
				'name' => htmlspecialchars( $label->getName() ),
				'count' => $this->getLanguage()->formatNum( $labelCounts[ $id ] ),
				'edit' => Html::rawElement( 'a', $params, $editIcon ),
			];
		}

		// Sort by count by default, and others as requested.
		// We sort here rather than in the DB because we're combining multiple queries' data,
		// and there's only ever one page of results to show (up to 100).
		$sortCol = $this->getRequest()->getText( 'sort', 'count' );
		$sortDir = $this->getRequest()->getBool( 'asc' ) ? TableBuilder::SORT_ASCENDING : TableBuilder::SORT_DESCENDING;
		uasort(
			$data,
			static fn ( $a, $b ) => $sortDir === TableBuilder::SORT_ASCENDING
				? strcasecmp( $a[$sortCol], $b[$sortCol] )
				: strcasecmp( $b[$sortCol], $a[$sortCol] )
		);

		// Put it all together in the table.
		$table = $codex->table()
			->setCurrentSortColumn( $sortCol )
			->setCurrentSortDirection( $sortDir )
			->setAttributes( [ 'class' => 'mw-specialwatchlistlabels-table' ] )
			->setCaption( $this->msg( 'watchlistlabels-table-header' )->text() )
			->setHeaderContent( $addNew )
			->setColumns( [
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
		$this->getOutput()->addHTML( $table->getHtml() );
	}
}
