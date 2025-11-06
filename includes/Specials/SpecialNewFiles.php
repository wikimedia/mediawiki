<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLUserTextField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Pager\NewFilesPager;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Request\DerivativeRequest;
use MediaWiki\SpecialPage\IncludableSpecialPage;
use Wikimedia\Mime\MimeAnalyzer;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:Newimages
 *
 * @ingroup SpecialPage
 */
class SpecialNewFiles extends IncludableSpecialPage {
	/** @var FormOptions */
	protected $opts;

	/** @var string[] */
	protected $mediaTypes;

	private GroupPermissionsLookup $groupPermissionsLookup;
	private IConnectionProvider $dbProvider;
	private LinkBatchFactory $linkBatchFactory;

	public function __construct(
		MimeAnalyzer $mimeAnalyzer,
		GroupPermissionsLookup $groupPermissionsLookup,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Newimages' );
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->dbProvider = $dbProvider;
		$this->mediaTypes = $mimeAnalyzer->getMediaTypes();
		$this->linkBatchFactory = $linkBatchFactory;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$context = new DerivativeContext( $this->getContext() );

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$this->addHelpLink( 'Help:New images' );

		$opts = new FormOptions();

		$opts->add( 'user', '' );
		$opts->add( 'showbots', false );
		$opts->add( 'hidepatrolled', false );
		$opts->add( 'mediatype', $this->mediaTypes );
		$opts->add( 'limit', 50 );
		$opts->add( 'offset', '' );
		$opts->add( 'start', '' );
		$opts->add( 'end', '' );

		$opts->fetchValuesFromRequest( $this->getRequest() );

		if ( $par !== null ) {
			$opts->setValue( 'limit', $par );
		}

		// If start date comes after end date chronologically, swap them.
		// They are swapped in the interface by JS.
		$start = $opts->getValue( 'start' );
		$end = $opts->getValue( 'end' );
		if ( $start !== '' && $end !== '' && $start > $end ) {
			$temp = $end;
			$end = $start;
			$start = $temp;

			$opts->setValue( 'start', $start, true );
			$opts->setValue( 'end', $end, true );

			// also swap values in request object, which is used by HTMLForm
			// to pre-populate the fields with the previous input
			$request = $context->getRequest();
			$context->setRequest( new DerivativeRequest(
				$request,
				[ 'start' => $start, 'end' => $end ] + $request->getValues(),
				$request->wasPosted()
			) );
		}

		// Avoid unexpected query or query errors to assoc array input, or nested arrays via
		// URL query params. Keep only string values (T321133).
		$mediaTypes = $opts->getValue( 'mediatype' );
		$mediaTypes = array_filter( $mediaTypes, 'is_string' );
		// Avoid unbounded query size with bogus values. Keep only known types.
		$mediaTypes = array_values( array_intersect( $this->mediaTypes, $mediaTypes ) );
		// Optimization: Remove redundant IN() query condition if all types are checked.
		if ( !array_diff( $this->mediaTypes, $mediaTypes ) ) {
			$mediaTypes = [];
		}
		$opts->setValue( 'mediatype', $mediaTypes );

		$opts->validateIntBounds( 'limit', 0, 500 );

		$this->opts = $opts;

		if ( !$this->including() ) {
			$this->setTopText();
			$this->buildForm( $context );
		}

		$pager = new NewFilesPager(
			$context,
			$this->groupPermissionsLookup,
			$this->linkBatchFactory,
			$this->getLinkRenderer(),
			$this->dbProvider,
			$opts
		);

		$out->addHTML( $pager->getBody() );
		if ( !$this->including() ) {
			$out->addHTML( $pager->getNavigationBar() );
		}
	}

	protected function buildForm( IContextSource $context ) {
		$mediaTypesText = array_map( function ( $type ) {
			// mediastatistics-header-unknown, mediastatistics-header-bitmap,
			// mediastatistics-header-drawing, mediastatistics-header-audio,
			// mediastatistics-header-video, mediastatistics-header-multimedia,
			// mediastatistics-header-office, mediastatistics-header-text,
			// mediastatistics-header-executable, mediastatistics-header-archive,
			// mediastatistics-header-3d,
			return $this->msg( 'mediastatistics-header-' . strtolower( $type ) )->escaped();
		}, $this->mediaTypes );
		$mediaTypesOptions = array_combine( $mediaTypesText, $this->mediaTypes );
		ksort( $mediaTypesOptions );

		$formDescriptor = [
			'user' => [
				'class' => HTMLUserTextField::class,
				'label-message' => 'newimages-user',
				'name' => 'user',
			],

			'showbots' => [
				'type' => 'check',
				'label-message' => 'newimages-showbots',
				'name' => 'showbots',
			],

			'hidepatrolled' => [
				'type' => 'check',
				'label-message' => 'newimages-hidepatrolled',
				'name' => 'hidepatrolled',
			],

			'mediatype' => [
				'type' => 'multiselect',
				'flatlist' => true,
				'name' => 'mediatype',
				'label-message' => 'newimages-mediatype',
				'options' => $mediaTypesOptions,
				'default' => $this->mediaTypes,
			],

			'limit' => [
				'type' => 'hidden',
				'default' => $this->opts->getValue( 'limit' ),
				'name' => 'limit',
			],

			'offset' => [
				'type' => 'hidden',
				'default' => $this->opts->getValue( 'offset' ),
				'name' => 'offset',
			],

			'start' => [
				'type' => 'date',
				'label-message' => 'date-range-from',
				'name' => 'start',
			],

			'end' => [
				'type' => 'date',
				'label-message' => 'date-range-to',
				'name' => 'end',
			],
		];

		if ( !$this->getUser()->useFilePatrol() ) {
			unset( $formDescriptor['hidepatrolled'] );
		}

		HTMLForm::factory( 'ooui', $formDescriptor, $context )
			// For the 'multiselect' field values to be preserved on submit
			->setFormIdentifier( 'specialnewimages' )
			->setWrapperLegendMsg( 'newimages-legend' )
			->setSubmitTextMsg( 'ilsubmit' )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'changes';
	}

	/**
	 * Send the text to be displayed above the options
	 */
	public function setTopText() {
		$message = $this->msg( 'newimagestext' )->inContentLanguage();
		if ( !$message->isDisabled() ) {
			$contLang = $this->getContentLanguage();
			$this->getOutput()->addWikiTextAsContent(
				Html::rawElement( 'div',
					[
						'lang' => $contLang->getHtmlCode(),
						'dir' => $contLang->getDir()
					],
					"\n" . $message->plain() . "\n"
				)
			);
		}
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialNewFiles::class, 'SpecialNewFiles' );
