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

use MediaWiki\Api\ApiMessage;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\RawMessage;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use SearchEngineFactory;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;

/**
 * Special page for changing the content language of a page
 *
 * @ingroup SpecialPage
 * @author Kunal Grover
 * @since 1.24
 */
class SpecialPageLanguage extends FormSpecialPage {
	/**
	 * @var string URL to go to if language change successful
	 */
	private $goToUrl;

	private IContentHandlerFactory $contentHandlerFactory;
	private LanguageNameUtils $languageNameUtils;
	private IConnectionProvider $dbProvider;
	private SearchEngineFactory $searchEngineFactory;

	public function __construct(
		IContentHandlerFactory $contentHandlerFactory,
		LanguageNameUtils $languageNameUtils,
		IConnectionProvider $dbProvider,
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( 'PageLanguage', 'pagelang' );
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->dbProvider = $dbProvider;
		$this->searchEngineFactory = $searchEngineFactory;
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
	protected function preHtml() {
		$this->getOutput()->addModules( 'mediawiki.misc-authed-ooui' );
		return parent::preHtml();
	}

	/** @inheritDoc */
	protected function getFormFields() {
		// Get default from the subpage of Special page
		$defaultName = $this->par;
		$title = $defaultName ? Title::newFromText( $defaultName ) : null;
		if ( $title ) {
			$defaultPageLanguage = $this->contentHandlerFactory->getContentHandler( $title->getContentModel() )
				->getPageLanguage( $title );

			$hasCustomLanguageSet = !$defaultPageLanguage->equals( $title->getPageLanguage() );
		} else {
			$hasCustomLanguageSet = false;
		}

		$page = [];
		$page['pagename'] = [
			'type' => 'title',
			'label-message' => 'pagelang-name',
			'default' => $title ? $title->getPrefixedText() : $defaultName,
			'autofocus' => $defaultName === null,
			'exists' => true,
		];

		// Options for whether to use the default language or select language
		$selectoptions = [
			(string)$this->msg( 'pagelang-use-default' )->escaped() => 1,
			(string)$this->msg( 'pagelang-select-lang' )->escaped() => 2,
		];
		$page['selectoptions'] = [
			'id' => 'mw-pl-options',
			'type' => 'radio',
			'options' => $selectoptions,
			'default' => $hasCustomLanguageSet ? 2 : 1
		];

		// Building a language selector
		$userLang = $this->getLanguage()->getCode();
		$languages = $this->languageNameUtils->getLanguageNames( $userLang, LanguageNameUtils::SUPPORTED );
		$options = [];
		foreach ( $languages as $code => $name ) {
			$options["$code - $name"] = $code;
		}

		$page['language'] = [
			'id' => 'mw-pl-languageselector',
			'cssclass' => 'mw-languageselector',
			'type' => 'select',
			'options' => $options,
			'label-message' => 'pagelang-language',
			'default' => $title ?
				$title->getPageLanguage()->getCode() :
				$this->getConfig()->get( MainConfigNames::LanguageCode ),
		];

		// Allow user to enter a comment explaining the change
		$page['reason'] = [
			'type' => 'text',
			'label-message' => 'pagelang-reason'
		];

		return $page;
	}

	/** @inheritDoc */
	protected function postHtml() {
		if ( $this->par ) {
			return $this->showLogFragment( $this->par );
		}
		return '';
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function alterForm( HTMLForm $form ) {
		$this->getHookRunner()->onLanguageSelector( $this->getOutput(), 'mw-languageselector' );
		$form->setSubmitTextMsg( 'pagelang-submit' );
	}

	/**
	 * @param array $data
	 * @return Status
	 */
	public function onSubmit( array $data ) {
		$pageName = $data['pagename'];

		// Check if user wants to use default language
		if ( $data['selectoptions'] == 1 ) {
			$newLanguage = 'default';
		} else {
			$newLanguage = $data['language'];
		}

		try {
			$title = Title::newFromTextThrow( $pageName );
		} catch ( MalformedTitleException $ex ) {
			return Status::newFatal( $ex->getMessageObject() );
		}

		// Check permissions and make sure the user has permission to edit the page
		$status = PermissionStatus::newEmpty();
		if ( !$this->getAuthority()->authorizeWrite( 'edit', $title, $status ) ) {
			$wikitext = $this->getOutput()->formatPermissionStatus( $status );
			// Hack to get our wikitext parsed
			return Status::newFatal( new RawMessage( '$1', [ $wikitext ] ) );
		}

		// Url to redirect to after the operation
		$this->goToUrl = $title->getFullUrlForRedirect(
			$title->isRedirect() ? [ 'redirect' => 'no' ] : []
		);

		return self::changePageLanguage(
			$this->getContext(),
			$title,
			$newLanguage,
			$data['reason'] ?? '',
			[],
			$this->dbProvider->getPrimaryDatabase()
		);
	}

	/**
	 * @since 1.36 Added $dbw parameter
	 *
	 * @param IContextSource $context
	 * @param Title $title
	 * @param string $newLanguage Language code
	 * @param string $reason Reason for the change
	 * @param string[] $tags Change tags to apply to the log entry
	 * @param IDatabase|null $dbw
	 * @return Status
	 */
	public static function changePageLanguage( IContextSource $context, Title $title,
		$newLanguage, $reason = "", array $tags = [], ?IDatabase $dbw = null ) {
		// Get the default language for the wiki
		$defLang = $context->getConfig()->get( MainConfigNames::LanguageCode );

		$pageId = $title->getArticleID();

		// Check if article exists
		if ( !$pageId ) {
			return Status::newFatal(
				'pagelang-nonexistent-page',
				wfEscapeWikiText( $title->getPrefixedText() )
			);
		}

		// Load the page language from DB
		$dbw ??= MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
		$oldLanguage = $dbw->newSelectQueryBuilder()
			->select( 'page_lang' )
			->from( 'page' )
			->where( [ 'page_id' => $pageId ] )
			->caller( __METHOD__ )->fetchField();

		// Check if user wants to use the default language
		if ( $newLanguage === 'default' ) {
			$newLanguage = null;
		}

		// No change in language
		if ( $newLanguage === $oldLanguage ) {
			// Check if old language does not exist
			if ( !$oldLanguage ) {
				return Status::newFatal( ApiMessage::create(
					[
						'pagelang-unchanged-language-default',
						wfEscapeWikiText( $title->getPrefixedText() )
					],
					'pagelang-unchanged-language'
				) );
			}
			return Status::newFatal(
				'pagelang-unchanged-language',
				wfEscapeWikiText( $title->getPrefixedText() ),
				$oldLanguage
			);
		}

		// Hardcoded [def] if the language is set to null
		$logOld = $oldLanguage ?: $defLang . '[def]';
		$logNew = $newLanguage ?: $defLang . '[def]';

		// Writing new page language to database
		$dbw->newUpdateQueryBuilder()
			->update( 'page' )
			->set( [ 'page_lang' => $newLanguage ] )
			->where( [
				'page_id' => $pageId,
				'page_lang' => $oldLanguage,
			] )
			->caller( __METHOD__ )->execute();

		if ( !$dbw->affectedRows() ) {
			return Status::newFatal( 'pagelang-db-failed' );
		}

		// Logging change of language
		$logParams = [
			'4::oldlanguage' => $logOld,
			'5::newlanguage' => $logNew
		];
		$entry = new ManualLogEntry( 'pagelang', 'pagelang' );
		$entry->setPerformer( $context->getUser() );
		$entry->setTarget( $title );
		$entry->setParameters( $logParams );
		$entry->setComment( is_string( $reason ) ? $reason : "" );
		$entry->addTags( $tags );

		$logid = $entry->insert();
		$entry->publish( $logid );

		// Force re-render so that language-based content (parser functions etc.) gets updated
		$title->invalidateCache();

		return Status::newGood( (object)[
			'oldLanguage' => $logOld,
			'newLanguage' => $logNew,
			'logId' => $logid,
		] );
	}

	public function onSuccess() {
		// Success causes a redirect
		$this->getOutput()->redirect( $this->goToUrl );
	}

	private function showLogFragment( string $title ): string {
		$moveLogPage = new LogPage( 'pagelang' );
		$out1 = Html::element( 'h2', [], $moveLogPage->getName()->text() );
		$out2 = '';
		LogEventsList::showLogExtract( $out2, 'pagelang', $title );
		return $out1 . $out2;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset, $this->searchEngineFactory );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pagetools';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialPageLanguage::class, 'SpecialPageLanguage' );
