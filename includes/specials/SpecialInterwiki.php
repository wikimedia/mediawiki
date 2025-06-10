<?php

namespace MediaWiki\Specials;

use LogicException;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Output\OutputPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:Interwiki
 *
 * @since 1.44
 * @ingroup SpecialPage
 */
class SpecialInterwiki extends SpecialPage {
	private Language $contLang;
	private InterwikiLookup $interwikiLookup;
	private LanguageNameUtils $languageNameUtils;
	private UrlUtils $urlUtils;
	private IConnectionProvider $dbProvider;
	private array $virtualDomainsMapping;
	private bool $interwikiMagic;

	public function __construct(
		Language $contLang,
		InterwikiLookup $interwikiLookup,
		LanguageNameUtils $languageNameUtils,
		UrlUtils $urlUtils,
		IConnectionProvider $dbProvider
	) {
		parent::__construct( 'Interwiki' );

		$this->contLang = $contLang;
		$this->interwikiLookup = $interwikiLookup;
		$this->languageNameUtils = $languageNameUtils;
		$this->urlUtils = $urlUtils;
		$this->dbProvider = $dbProvider;
		$this->virtualDomainsMapping = $this->getConfig()->get( MainConfigNames::VirtualDomainsMapping ) ?? [];
		$this->interwikiMagic = $this->getConfig()->get( MainConfigNames::InterwikiMagic ) ?? true;
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * Different description will be shown on Special:SpecialPage depending on
	 * whether the user can modify the data.
	 *
	 * @return Message
	 */
	public function getDescription() {
		return $this->msg( $this->canModify() ? 'interwiki' : 'interwiki-title-norights' );
	}

	public function getSubpagesForPrefixSearch() {
		// delete, edit both require the prefix parameter.
		return [ 'add' ];
	}

	/**
	 * Show the special page
	 *
	 * @param string|null $par parameter passed to the page or null
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$request = $this->getRequest();

		$out->addModuleStyles( 'mediawiki.special.interwiki' );

		$action = $par ?? $request->getRawVal( 'action' );

		if ( in_array( $action, [ 'add', 'edit', 'delete' ] ) && $this->canModify( $out ) ) {
			$this->showForm( $action );
		} else {
			$this->showList();
		}
	}

	/**
	 * Returns boolean whether the user can modify the data.
	 *
	 * @param OutputPage|bool $out If an OutputPage object given, it adds the respective error message.
	 * @return bool
	 * @throws PermissionsError|ReadOnlyError
	 */
	private function canModify( $out = false ) {
		if ( !$this->getUser()->isAllowed( 'interwiki' ) ) {
			// Check permissions
			if ( $out ) {
				throw new PermissionsError( 'interwiki' );
			}

			return false;
		} elseif ( $this->getConfig()->get( MainConfigNames::InterwikiCache ) ) {
			// Editing the interwiki cache is not supported
			if ( $out ) {
				$out->addWikiMsg( 'interwiki-cached' );
			}

			return false;
		} else {
			if ( $out ) {
				$this->checkReadOnly();
			}
		}

		return true;
	}

	/**
	 * @param string $action The action of the form
	 */
	protected function showForm( $action ) {
		$formDescriptor = [];
		$hiddenFields = [
			'action' => $action,
		];

		$status = Status::newGood();
		$request = $this->getRequest();
		$prefix = $request->getText( 'prefix' );

		switch ( $action ) {
			case 'add':
			case 'edit':
				$formDescriptor = [
					'prefix' => [
						'type' => 'text',
						'label-message' => 'interwiki-prefix-label',
						'name' => 'prefix',
						'autofocus' => true,
					],

					'local' => [
						'type' => 'check',
						'id' => 'mw-interwiki-local',
						'label-message' => 'interwiki-local-label',
						'name' => 'local',
					],

					'trans' => [
						'type' => 'check',
						'id' => 'mw-interwiki-trans',
						'label-message' => 'interwiki-trans-label',
						'name' => 'trans',
					],

					'url' => [
						'type' => 'url',
						'id' => 'mw-interwiki-url',
						'label-message' => 'interwiki-url-label',
						'maxlength' => 200,
						'name' => 'url',
						'size' => 60,
					],

					'api' => [
						'type' => 'url',
						'id' => 'mw-interwiki-api',
						'label-message' => 'interwiki-api-label',
						'maxlength' => 200,
						'name' => 'api',
						'size' => 60,
					],

					'reason' => [
						'type' => 'text',
						'id' => "mw-interwiki-{$action}reason",
						'label-message' => 'interwiki_reasonfield',
						'maxlength' => 200,
						'name' => 'reason',
						'size' => 60,
					],
				];

				break;
			case 'delete':
				$formDescriptor = [
					'prefix' => [
						'type' => 'hidden',
						'name' => 'prefix',
						'default' => $prefix,
					],

					'reason' => [
						'type' => 'text',
						'name' => 'reason',
						'label-message' => 'interwiki_reasonfield',
					],
				];

				break;
			default:
				throw new LogicException( "Unexpected action: {$action}" );
		}

		if ( $action === 'edit' || $action === 'delete' ) {
			$dbr = $this->dbProvider->getReplicaDatabase();
			$row = $dbr->newSelectQueryBuilder()
				->select( '*' )
				->from( 'interwiki' )
				->where( [ 'iw_prefix' => $prefix ] )
				->caller( __METHOD__ )->fetchRow();

			if ( $action === 'edit' ) {
				if ( !$row ) {
					$status->fatal( 'interwiki_editerror', $prefix );
				} else {
					$formDescriptor['prefix']['disabled'] = true;
					$formDescriptor['prefix']['default'] = $prefix;
					$hiddenFields['prefix'] = $prefix;
					$formDescriptor['url']['default'] = $row->iw_url;
					$formDescriptor['api']['default'] = $row->iw_api;
					$formDescriptor['trans']['default'] = $row->iw_trans;
					$formDescriptor['local']['default'] = $row->iw_local;
				}
			} else { // $action === 'delete'
				if ( !$row ) {
					$status->fatal( 'interwiki_delfailed', $prefix );
				}
			}
		}

		if ( !$status->isOK() ) {
			$formDescriptor = [];
		}

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm
			->addHiddenFields( $hiddenFields )
			->setSubmitCallback( $this->onSubmit( ... ) );

		if ( $status->isOK() ) {
			if ( $action === 'delete' ) {
				$htmlForm->setSubmitDestructive();
			}

			$htmlForm->setSubmitTextMsg( $action !== 'add' ? $action : 'interwiki_addbutton' )
				->setPreHtml( $this->msg( $action !== 'delete' ? "interwiki_{$action}intro" :
					'interwiki_deleting', $prefix )->escaped() )
				->show();
		} else {
			$htmlForm->suppressDefaultSubmit()
				->prepareForm()
				->displayForm( $status );
		}

		$this->getOutput()->addBacklinkSubtitle( $this->getPageTitle() );
	}

	/**
	 * @param array $data
	 * @return Status
	 */
	private function onSubmit( array $data ) {
		$status = Status::newGood();
		$request = $this->getRequest();
		$config = $this->getConfig();
		$prefix = $data['prefix'];
		$do = $request->getRawVal( 'action' );
		// Show an error if the prefix is invalid (only when adding one).
		// Invalid characters for a title should also be invalid for a prefix.
		// Whitespace, ':', '&' and '=' are invalid, too.
		// (Bug 30599).
		$validPrefixChars = preg_replace( '/[ :&=]/', '', Title::legalChars() );
		if ( $do === 'add' && preg_match( "/\s|[^$validPrefixChars]/", $prefix ) ) {
			$status->fatal( 'interwiki-badprefix', htmlspecialchars( $prefix ) );
			return $status;
		}
		// Disallow adding local interlanguage definitions if using global
		if (
			$do === 'add'
			&& $this->isLanguagePrefix( $prefix )
			&& isset( $this->virtualDomainsMapping['virtual-interwiki-interlanguage'] )
		) {
			$status->fatal( 'interwiki-cannotaddlocallanguage', htmlspecialchars( $prefix ) );
			return $status;
		}
		$reason = $data['reason'];
		$selfTitle = $this->getPageTitle();
		$dbw = $this->dbProvider->getPrimaryDatabase();
		switch ( $do ) {
			case 'delete':
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'interwiki' )
					->where( [ 'iw_prefix' => $prefix ] )
					->caller( __METHOD__ )->execute();

				if ( $dbw->affectedRows() === 0 ) {
					$status->fatal( 'interwiki_delfailed', $prefix );
				} else {
					$this->getOutput()->addWikiMsg( 'interwiki_deleted', $prefix );
					$log = new ManualLogEntry( 'interwiki', 'iw_delete' );
					$log->setTarget( $selfTitle );
					$log->setComment( $reason );
					$log->setParameters( [
						'4::prefix' => $prefix
					] );
					$log->setPerformer( $this->getUser() );
					$log->insert();
					$this->interwikiLookup->invalidateCache( $prefix );
				}
				break;
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'add':
				$prefix = $this->contLang->lc( $prefix );
				// Fall through
			case 'edit':
				// T374771: Trim the URL and API URLs to reduce confusion when
				// the URLs are accidentally provided with extra whitespace
				$theurl = trim( $data['url'] );
				$api = trim( $data['api'] ?? '' );
				$local = $data['local'] ? 1 : 0;
				$trans = $data['trans'] ? 1 : 0;
				$row = [
					'iw_prefix' => $prefix,
					'iw_url' => $theurl,
					'iw_api' => $api,
					'iw_wikiid' => '',
					'iw_local' => $local,
					'iw_trans' => $trans
				];

				if ( $prefix === '' || $theurl === '' ) {
					$status->fatal( 'interwiki-submit-empty' );
					break;
				}

				// Simple URL validation: check that the protocol is one of
				// the supported protocols for this wiki.
				// (T32600)
				if ( !$this->urlUtils->parse( $theurl ) ) {
					$status->fatal( 'interwiki-submit-invalidurl' );
					break;
				}

				if ( $do === 'add' ) {
					$dbw->newInsertQueryBuilder()
						->insertInto( 'interwiki' )
						->row( $row )
						->ignore()
						->caller( __METHOD__ )->execute();
				} else { // $do === 'edit'
					$dbw->newUpdateQueryBuilder()
						->update( 'interwiki' )
						->set( $row )
						->where( [ 'iw_prefix' => $prefix ] )
						->ignore()
						->caller( __METHOD__ )->execute();
				}

				// used here: interwiki_addfailed, interwiki_added, interwiki_edited
				if ( $dbw->affectedRows() === 0 ) {
					$status->fatal( "interwiki_{$do}failed", $prefix );
				} else {
					$this->getOutput()->addWikiMsg( "interwiki_{$do}ed", $prefix );
					$log = new ManualLogEntry( 'interwiki', 'iw_' . $do );
					$log->setTarget( $selfTitle );
					$log->setComment( $reason );
					$log->setParameters( [
						'4::prefix' => $prefix,
						'5::url' => $theurl,
						'6::trans' => $trans,
						'7::local' => $local
					] );
					$log->setPerformer( $this->getUser() );
					$log->insert();
					$this->interwikiLookup->invalidateCache( $prefix );
				}
				break;
			default:
				throw new LogicException( "Unexpected action: {$do}" );
		}

		return $status;
	}

	/**
	 * Determine whether a prefix is a language link
	 *
	 * @param string $prefix
	 * @return bool
	 */
	private function isLanguagePrefix( $prefix ) {
		return $this->interwikiMagic
			&& $this->languageNameUtils->getLanguageName( $prefix );
	}

	protected function showList() {
		$canModify = $this->canModify();

		// Build lists
		$iwPrefixes = $this->interwikiLookup->getAllPrefixes( null );
		$iwGlobalPrefixes = [];
		$iwGlobalLanguagePrefixes = [];
		if ( isset( $this->virtualDomainsMapping['virtual-interwiki'] ) ) {
			// Fetch list from global table
			$dbrCentralDB = $this->dbProvider->getReplicaDatabase( 'virtual-interwiki' );
			$res = $dbrCentralDB->newSelectQueryBuilder()
				->select( '*' )
				->from( 'interwiki' )
				->caller( __METHOD__ )->fetchResultSet();
			$retval = [];
			foreach ( $res as $row ) {
				$row = (array)$row;
				if ( !$this->isLanguagePrefix( $row['iw_prefix'] ) ) {
					$retval[] = $row;
				}
			}
			$iwGlobalPrefixes = $retval;
		}

		// Almost the same loop as above, but for global inter*language* links, whereas the above is for
		// global inter*wiki* links
		$usingGlobalLanguages = isset( $this->virtualDomainsMapping['virtual-interwiki-interlanguage'] );
		if ( $usingGlobalLanguages ) {
			// Fetch list from global table
			$dbrCentralLangDB = $this->dbProvider->getReplicaDatabase( 'virtual-interwiki-interlanguage' );
			$res = $dbrCentralLangDB->newSelectQueryBuilder()
				->select( '*' )
				->from( 'interwiki' )
				->caller( __METHOD__ )->fetchResultSet();
			$retval2 = [];
			foreach ( $res as $row ) {
				$row = (array)$row;
				// Note that the above DB query explicitly *excludes* interlang ones
				// (which makes sense), whereas here we _only_ care about interlang ones!
				if ( $this->isLanguagePrefix( $row['iw_prefix'] ) ) {
					$retval2[] = $row;
				}
			}
			$iwGlobalLanguagePrefixes = $retval2;
		}

		// Split out language links
		$iwLocalPrefixes = [];
		$iwLanguagePrefixes = [];
		foreach ( $iwPrefixes as $iwPrefix ) {
			if ( $this->isLanguagePrefix( $iwPrefix['iw_prefix'] ) ) {
				$iwLanguagePrefixes[] = $iwPrefix;
			} else {
				$iwLocalPrefixes[] = $iwPrefix;
			}
		}

		// If using global interlanguage links, just ditch the data coming from the
		// local table and overwrite it with the global data
		if ( $usingGlobalLanguages ) {
			unset( $iwLanguagePrefixes );
			$iwLanguagePrefixes = $iwGlobalLanguagePrefixes;
		}

		// Page intro content
		$this->getOutput()->addWikiMsg( 'interwiki_intro' );

		// Add 'view log' link
		$logLink = $this->getLinkRenderer()->makeLink(
			SpecialPage::getTitleFor( 'Log', 'interwiki' ),
			$this->msg( 'interwiki-logtext' )->text()
		);
		$this->getOutput()->addHTML(
			Html::rawElement( 'p', [ 'class' => 'mw-interwiki-log' ], $logLink )
		);

		// Add 'add' link
		if ( $canModify ) {
			if ( count( $iwGlobalPrefixes ) !== 0 ) {
				if ( $usingGlobalLanguages ) {
					$addtext = 'interwiki-addtext-local-nolang';
				} else {
					$addtext = 'interwiki-addtext-local';
				}
			} else {
				if ( $usingGlobalLanguages ) {
					$addtext = 'interwiki-addtext-nolang';
				} else {
					$addtext = 'interwiki_addtext';
				}
			}
			$addtext = $this->msg( $addtext )->text();
			$addlink = $this->getLinkRenderer()->makeKnownLink(
				$this->getPageTitle( 'add' ), $addtext );
			$this->getOutput()->addHTML(
				Html::rawElement( 'p', [ 'class' => 'mw-interwiki-addlink' ], $addlink )
			);
		}

		$this->getOutput()->addWikiMsg( 'interwiki-legend' );

		if ( $iwPrefixes === [] && $iwGlobalPrefixes === [] ) {
			// If the interwiki table(s) are empty, display an error message
			$this->error( 'interwiki_error' );
			return;
		}

		// Add the global table
		if ( count( $iwGlobalPrefixes ) !== 0 ) {
			$this->getOutput()->addHTML(
				Html::rawElement(
					'h2',
					[ 'class' => 'interwikitable-global' ],
					$this->msg( 'interwiki-global-links' )->parse()
				)
			);
			$this->getOutput()->addWikiMsg( 'interwiki-global-description' );

			// $canModify is false here because this is just a display of remote data
			$this->makeTable( false, $iwGlobalPrefixes );
		}

		// Add the local table
		if ( count( $iwLocalPrefixes ) !== 0 ) {
			if ( count( $iwGlobalPrefixes ) !== 0 ) {
				$this->getOutput()->addHTML(
					Html::rawElement(
						'h2',
						[ 'class' => 'interwikitable-local' ],
						$this->msg( 'interwiki-local-links' )->parse()
					)
				);
				$this->getOutput()->addWikiMsg( 'interwiki-local-description' );
			} else {
				$this->getOutput()->addHTML(
					Html::rawElement(
						'h2',
						[ 'class' => 'interwikitable-local' ],
						$this->msg( 'interwiki-links' )->parse()
					)
				);
				$this->getOutput()->addWikiMsg( 'interwiki-description' );
			}
			$this->makeTable( $canModify, $iwLocalPrefixes );
		}

		// Add the language table
		if ( count( $iwLanguagePrefixes ) !== 0 ) {
			if ( $usingGlobalLanguages ) {
				$header = 'interwiki-global-language-links';
				$description = 'interwiki-global-language-description';
			} else {
				$header = 'interwiki-language-links';
				$description = 'interwiki-language-description';
			}

			$this->getOutput()->addHTML(
				Html::rawElement(
					'h2',
					[ 'class' => 'interwikitable-language' ],
					$this->msg( $header )->parse()
				)
			);
			$this->getOutput()->addWikiMsg( $description );

			// When using global interlanguage links, don't allow them to be modified
			// except on the source wiki
			$canModify = ( $usingGlobalLanguages ? false : $canModify );
			$this->makeTable( $canModify, $iwLanguagePrefixes );
		}
	}

	protected function makeTable( $canModify, $iwPrefixes ) {
		// Output the existing Interwiki prefixes table header
		$out = Html::openElement(
			'table',
			[ 'class' => 'mw-interwikitable wikitable sortable' ]
		) . "\n";
		$out .= Html::openElement( 'thead' ) .
			Html::openElement( 'tr', [ 'class' => 'interwikitable-header' ] ) .
			Html::element( 'th', [], $this->msg( 'interwiki_prefix' )->text() ) .
			Html::element( 'th', [], $this->msg( 'interwiki_url' )->text() ) .
			Html::element( 'th', [], $this->msg( 'interwiki_local' )->text() ) .
			Html::element( 'th', [], $this->msg( 'interwiki_trans' )->text() ) .
			( $canModify ?
				Html::element(
					'th',
					[ 'class' => 'unsortable' ],
					$this->msg( 'interwiki_edit' )->text()
				) :
				''
			);
		$out .= Html::closeElement( 'tr' ) .
			Html::closeElement( 'thead' ) . "\n" .
			Html::openElement( 'tbody' );

		$selfTitle = $this->getPageTitle();

		// Output the existing Interwiki prefixes table rows
		foreach ( $iwPrefixes as $iwPrefix ) {
			$out .= Html::openElement( 'tr', [ 'class' => 'mw-interwikitable-row' ] );
			$out .= Html::element( 'td', [ 'class' => 'mw-interwikitable-prefix' ],
				$iwPrefix['iw_prefix'] );
			$out .= Html::element(
				'td',
				[ 'class' => 'mw-interwikitable-url' ],
				$iwPrefix['iw_url']
			);
			$attribs = [ 'class' => 'mw-interwikitable-local' ];
			// Green background for cells with "yes".
			if ( isset( $iwPrefix['iw_local'] ) && $iwPrefix['iw_local'] ) {
				$attribs['class'] .= ' mw-interwikitable-local-yes';
			}
			// The messages interwiki_0 and interwiki_1 are used here.
			$contents = isset( $iwPrefix['iw_local'] ) ?
				$this->msg( 'interwiki_' . $iwPrefix['iw_local'] )->text() :
				'-';
			$out .= Html::element( 'td', $attribs, $contents );
			$attribs = [ 'class' => 'mw-interwikitable-trans' ];
			// Green background for cells with "yes".
			if ( isset( $iwPrefix['iw_trans'] ) && $iwPrefix['iw_trans'] ) {
				$attribs['class'] .= ' mw-interwikitable-trans-yes';
			}
			// The messages interwiki_0 and interwiki_1 are used here.
			$contents = isset( $iwPrefix['iw_trans'] ) ?
				$this->msg( 'interwiki_' . $iwPrefix['iw_trans'] )->text() :
				'-';
			$out .= Html::element( 'td', $attribs, $contents );

			// Additional column when the interwiki table can be modified.
			if ( $canModify ) {
				$out .= Html::rawElement( 'td', [ 'class' => 'mw-interwikitable-modify' ],
					$this->getLinkRenderer()->makeKnownLink(
						$selfTitle,
						$this->msg( 'edit' )->text(),
						[],
						[ 'action' => 'edit', 'prefix' => $iwPrefix['iw_prefix'] ]
					) .
					$this->msg( 'comma-separator' )->escaped() .
					$this->getLinkRenderer()->makeKnownLink(
						$selfTitle,
						$this->msg( 'delete' )->text(),
						[],
						[ 'action' => 'delete', 'prefix' => $iwPrefix['iw_prefix'] ]
					)
				);
			}
			$out .= Html::closeElement( 'tr' ) . "\n";
		}
		$out .= Html::closeElement( 'tbody' ) .
			Html::closeElement( 'table' );

		$this->getOutput()->addHTML( $out );
		$this->getOutput()->addModuleStyles( 'jquery.tablesorter.styles' );
		$this->getOutput()->addModules( 'jquery.tablesorter' );
	}

	/**
	 * @param string ...$args
	 */
	protected function error( ...$args ) {
		$this->getOutput()->wrapWikiMsg( "<p class='error'>$1</p>", $args );
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
