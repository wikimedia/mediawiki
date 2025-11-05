<?php
/**
 * @license GPL-3.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use InvalidArgumentException;
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
use Wikimedia\Codex\Utility\Codex;

/**
 * A special page for viewing a user's watchlist labels and performing CRUD operations on them.
 *
 * @ingroup SpecialPage
 * @ingroup Watchlist
 */
class SpecialWatchlistLabels extends SpecialPage {

	private const SUBPAGE_EDIT = 'edit';

	use WatchlistSpecialPage;

	/** @inheritDoc */
	public function __construct(
		private WatchlistLabelStore $labelStore,
		$name = 'WatchlistLabels',
		$restriction = 'viewmywatchlist',
	) {
		parent::__construct( $name, $restriction, false );
	}

	/** @inheritDoc */
	public function execute( $subPage ) {
		$output = $this->getOutput();
		$output->setPageTitleMsg( $this->msg( 'watchlistlabels-title' ) );
		$this->addHelpLink( 'Help:Watchlist labels' );
		if ( !$this->getConfig()->get( MainConfigNames::EnableWatchlistLabels ) ) {
			$output->addHTML( Html::errorBox( $this->msg( 'watchlistlabels-not-enabled' )->escaped() ) );
			return;
		}
		$output->addSubtitle( $this->getWatchlistOwnerHtml() );
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
		$descriptor = [
			'name' => [
				'type' => 'text',
				'label-message' => 'watchlistlabels-form-field-name',
				'validation-callback' => [ $this, 'validateName' ],
				'required' => true,
			],
		];
		$form = HTMLForm::factory( 'codex', $descriptor, $this->getContext() )
			// @todo Change to watchlistlabels-form-header-edit when editing
			->setHeaderHtml( Html::element( 'h3', [], $this->msg( 'watchlistlabels-form-header-new' )->text() ) )
			->showCancel( true )
			->setCancelTarget( $this->getPageTitle() )
			// @todo Change to watchlistlabels-form-submit-edit when editing
			->setSubmitTextMsg( 'watchlistlabels-form-submit-new' )
			->setSubmitCallback( [ $this, 'onSubmit' ] );
		$form->show();
	}

	/**
	 * @param mixed $value
	 * @param ?array $alldata
	 * @param ?HTMLForm $form
	 *
	 * @return (StatusValue|string|bool|Message)|null
	 */
	public function validateName( $value, ?array $alldata, ?HTMLForm $form ) {
		$length = strlen( $value );
		if ( $length > 255 ) {
			return Status::newFatal( $this->msg( 'watchlistlabels-form-name-too-long', $length ) );
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
		if ( !isset( $data['name'] ) ) {
			throw new InvalidArgumentException( 'No name data submitted.' );
		}
		$label = new WatchlistLabel( $this->getUser(), $data['name'] );
		$this->labelStore->save( $label );
		$this->getOutput()->redirect( $this->getPageTitle()->getLocalURL() );
		return Status::newGood();
	}

	/**
	 * Show the table of all labels.
	 */
	private function showTable() {
		$codex = new Codex();
		$this->getOutput()->addModuleStyles( 'codex-styles' );

		// Page title and description.
		$this->getOutput()->addHTML(
			Html::element( 'h3', [], $this->msg( 'watchlistlabels-table-title' )->text() )
			. Html::element( 'p', [], $this->msg( 'watchlistlabels-table-description' )->text() ),
		);

		// Buttons in the table header.
		$href = $this->getPageTitle( self::SUBPAGE_EDIT )->getLinkURL();
		$addNew = Html::element( 'a', [ 'href' => $href ], $this->msg( 'watchlistlabels-table-new-link' )->text() );

		// Data.
		$data = [];
		foreach ( $this->labelStore->loadAllForUser( $this->getUser() ) as $label ) {
			$data[] = [
				'name' => $label->getName(),
			];
		}

		// Put it all together in the table.
		$table = $codex->table()
			->setCaption( $this->msg( 'watchlistlabels-table-header' )->text() )
			->setHeaderContent( $addNew )
			->setColumns( [
				[ 'id' => 'name', 'label' => $this->msg( 'watchlistlabels-table-col-name' )->escaped() ],
			] )
			->setData( $data )
			->setPaginate( false )
			->build();
		$this->getOutput()->addHTML( $table->getHtml() );
	}
}
