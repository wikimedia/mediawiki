<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageLookup;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\Helper\PageRedirectHelper;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleParser;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Class LanguageLinksHandler
 * REST API handler for /page/{title}/links/language endpoint.
 *
 * @package MediaWiki\Rest\Handler
 */
class LanguageLinksHandler extends SimpleHandler {

	private IConnectionProvider $dbProvider;
	private LanguageNameUtils $languageNameUtils;
	private TitleFormatter $titleFormatter;
	private TitleParser $titleParser;
	private PageLookup $pageLookup;
	private PageRestHelperFactory $helperFactory;

	/**
	 * @var ExistingPageRecord|false|null
	 */
	private $page = false;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param LanguageNameUtils $languageNameUtils
	 * @param TitleFormatter $titleFormatter
	 * @param TitleParser $titleParser
	 * @param PageLookup $pageLookup
	 * @param PageRestHelperFactory $helperFactory
	 */
	public function __construct(
		IConnectionProvider $dbProvider,
		LanguageNameUtils $languageNameUtils,
		TitleFormatter $titleFormatter,
		TitleParser $titleParser,
		PageLookup $pageLookup,
		PageRestHelperFactory $helperFactory
	) {
		$this->dbProvider = $dbProvider;
		$this->languageNameUtils = $languageNameUtils;
		$this->titleFormatter = $titleFormatter;
		$this->titleParser = $titleParser;
		$this->pageLookup = $pageLookup;
		$this->helperFactory = $helperFactory;
	}

	private function getRedirectHelper(): PageRedirectHelper {
		return $this->helperFactory->newPageRedirectHelper(
			$this->getResponseFactory(),
			$this->getRouter(),
			$this->getPath(),
			$this->getRequest()
		);
	}

	private function getPage(): ?ExistingPageRecord {
		if ( $this->page === false ) {
			$this->page = $this->pageLookup->getExistingPageByText(
					$this->getValidatedParams()['title']
				);
		}
		return $this->page;
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$page = $this->getPage();
		$params = $this->getValidatedParams();

		if ( !$page ) {
			throw new LocalizedHttpException(
				( new MessageValue( 'rest-nonexistent-title' ) )->plaintextParams( $title ),
				404
			);
		}

		'@phan-var \MediaWiki\Page\ExistingPageRecord $page';
		$redirectResponse = $this->getRedirectHelper()->createNormalizationRedirectResponseIfNeeded(
			$page,
			$params['title'] ?? null
		);

		if ( $redirectResponse !== null ) {
			return $redirectResponse;
		}

		if ( !$this->getAuthority()->authorizeRead( 'read', $page ) ) {
			throw new LocalizedHttpException(
				( new MessageValue( 'rest-permission-denied-title' ) )->plaintextParams( $title ),
				403
			);
		}

		return $this->getResponseFactory()
			->createJson( $this->fetchLinks( $page->getId() ) );
	}

	private function fetchLinks( int $pageId ): array {
		$result = [];
		$res = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( [ 'll_title', 'll_lang' ] )
			->from( 'langlinks' )
			->where( [ 'll_from' => $pageId ] )
			->orderBy( 'll_lang' )
			->caller( __METHOD__ )->fetchResultSet();
		foreach ( $res as $item ) {
			try {
				$targetTitle = $this->titleParser->parseTitle( $item->ll_title );
				$result[] = [
					'code' => $item->ll_lang,
					'name' => $this->languageNameUtils->getLanguageName( $item->ll_lang ),
					'key' => $this->titleFormatter->getPrefixedDBkey( $targetTitle ),
					'title' => $this->titleFormatter->getPrefixedText( $targetTitle )
				];
			} catch ( MalformedTitleException ) {
				// skip malformed titles
			}
		}
		return $result;
	}

	public function needsWriteAccess() {
		return false;
	}

	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-language-links-title' ),
			],
		];
	}

	protected function getETag(): ?string {
		$page = $this->getPage();
		if ( !$page ) {
			return null;
		}

		// XXX: use hash of the rendered HTML?
		return '"' . $page->getLatest() . '@' . wfTimestamp( TS_MW, $page->getTouched() ) . '"';
	}

	protected function getLastModified(): ?string {
		$page = $this->getPage();
		return $page ? $page->getTouched() : null;
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		return (bool)$this->getPage();
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/PageLanguageLinks.json';
	}
}
