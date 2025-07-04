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

use LocalisationCache;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\AllMessagesTablePager;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of the MediaWiki interface messages.
 *
 * @ingroup SpecialPage
 */
class SpecialAllMessages extends SpecialPage {

	private LanguageFactory $languageFactory;
	private LanguageNameUtils $languageNameUtils;
	private IConnectionProvider $dbProvider;
	private LocalisationCache $localisationCache;

	public function __construct(
		LanguageFactory $languageFactory,
		LanguageNameUtils $languageNameUtils,
		LocalisationCache $localisationCache,
		IConnectionProvider $dbProvider
	) {
		parent::__construct( 'Allmessages' );
		$this->languageFactory = $languageFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->localisationCache = $localisationCache;
		$this->dbProvider = $dbProvider;
	}

	/**
	 * @param string|null $par Parameter passed to the page or null
	 */
	public function execute( $par ) {
		$out = $this->getOutput();

		$this->setHeaders();

		if ( !$this->getConfig()->get( MainConfigNames::UseDatabaseMessages ) ) {
			$out->addWikiMsg( 'allmessages-not-supported-database' );

			return;
		}

		$out->addModuleStyles( 'mediawiki.special' );
		$this->addHelpLink( 'Help:System message' );

		$contLangCode = $this->getContentLanguage()->getCode();
		$lang = $this->getLanguage();

		$opts = new FormOptions();

		$opts->add( 'prefix', '' );
		$opts->add( 'filter', 'all' );
		$opts->add( 'lang', $contLangCode );
		$opts->add( 'limit', 50 );

		$opts->fetchValuesFromRequest( $this->getRequest() );
		$opts->validateIntBounds( 'limit', 0, 5000 );

		if ( !$this->languageNameUtils->isKnownLanguageTag( $opts->getValue( 'lang' ) ) ) {
			// Show a warning message and fallback to content language
			$out->addHTML(
				Html::warningBox(
					$this->msg( 'allmessages-unknown-language' )
						->plaintextParams( $opts->getValue( 'lang' ) )
						->parse()
				)
			);
			$opts->setValue( 'lang', $contLangCode );
		}

		$pager = new AllMessagesTablePager(
			$this->getContext(),
			$this->getContentLanguage(),
			$this->languageFactory,
			$this->getLinkRenderer(),
			$this->dbProvider,
			$this->localisationCache,
			$opts
		);

		$formDescriptor = [
			'prefix' => [
				'type' => 'text',
				'name' => 'prefix',
				'label-message' => 'allmessages-prefix',
			],

			'filter' => [
				'type' => 'radio',
				'name' => 'filter',
				'label-message' => 'allmessages-filter',
				'options-messages' => [
					'allmessages-filter-unmodified' => 'unmodified',
					'allmessages-filter-all' => 'all',
					'allmessages-filter-modified' => 'modified',
				],
				'default' => 'all',
				'flatlist' => true,
			],

			'lang' => [
				'type' => 'language',
				'name' => 'lang',
				'label-message' => 'allmessages-language',
				'default' => $opts->getValue( 'lang' ),
			],

			'limit' => [
				'type' => 'limitselect',
				'name' => 'limit',
				'label-message' => 'table_pager_limit_label',
				'options' => [
					$lang->formatNum( 20 ) => 20,
					$lang->formatNum( 50 ) => 50,
					$lang->formatNum( 100 ) => 100,
					$lang->formatNum( 250 ) => 250,
					$lang->formatNum( 500 ) => 500,
					$lang->formatNum( 5000 ) => 5000,
				],
				'default' => $opts->getValue( 'limit' ),
			],
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm
			->setMethod( 'get' )
			->setPreHtml( $this->msg( 'allmessagestext' )->parse() )
			->setWrapperLegendMsg( 'allmessages' )
			->setSubmitTextMsg( 'allmessages-filter-submit' )
			->prepareForm()
			->displayForm( false );

		$out->addParserOutputContent(
			$pager->getFullOutput(),
			ParserOptions::newFromContext( $this->getContext() )
		);
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'wiki';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialAllMessages::class, 'SpecialAllMessages' );
