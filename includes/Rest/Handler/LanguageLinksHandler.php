<?php

namespace MediaWiki\Rest\Handler;

use MalformedTitleException;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageLookup;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use TitleFormatter;
use TitleParser;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Class LanguageLinksHandler
 * REST API handler for /page/{title}/links/language endpoint.
 *
 * @package MediaWiki\Rest\Handler
 */
class LanguageLinksHandler extends SimpleHandler {
	use PageRedirectHandlerTrait;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var LanguageNameUtils */
	private $languageNameUtils;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var TitleParser */
	private $titleParser;

	/** @var PageLookup */
	private $pageLookup;

	/**
	 * @var ExistingPageRecord|false|null
	 */
	private $page = false;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param LanguageNameUtils $languageNameUtils
	 * @param TitleFormatter $titleFormatter
	 * @param TitleParser $titleParser
	 * @param PageLookup $pageLookup
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		LanguageNameUtils $languageNameUtils,
		TitleFormatter $titleFormatter,
		TitleParser $titleParser,
		PageLookup $pageLookup
	) {
		$this->loadBalancer = $loadBalancer;
		$this->languageNameUtils = $languageNameUtils;
		$this->titleFormatter = $titleFormatter;
		$this->titleParser = $titleParser;
		$this->pageLookup = $pageLookup;
	}

	/**
	 * @return ExistingPageRecord|null
	 */
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
				new MessageValue( 'rest-nonexistent-title',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ]
				),
				404
			);
		}

		'@phan-var \MediaWiki\Page\ExistingPageRecord $page';
		$redirectResponse = $this->createNormalizationRedirectResponseIfNeeded(
			$page,
			$params['title'] ?? null,
			$this->titleFormatter
		);

		if ( $redirectResponse !== null ) {
			return $redirectResponse;
		}

		if ( !$this->getAuthority()->authorizeRead( 'read', $page ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-title',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ] ),
				403
			);
		}

		return $this->getResponseFactory()
			->createJson( $this->fetchLinks( $page->getId() ) );
	}

	private function fetchLinks( $pageId ) {
		$result = [];
		$res = $this->loadBalancer->getConnection( DB_REPLICA )->newSelectQueryBuilder()
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
			} catch ( MalformedTitleException $e ) {
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
			],
		];
	}

	/**
	 * @return string|null
	 */
	protected function getETag(): ?string {
		$page = $this->getPage();
		if ( !$page ) {
			return null;
		}

		// XXX: use hash of the rendered HTML?
		return '"' . $page->getLatest() . '@' . wfTimestamp( TS_MW, $page->getTouched() ) . '"';
	}

	/**
	 * @return string|null
	 */
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

}
