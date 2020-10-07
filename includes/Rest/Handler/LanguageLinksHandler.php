<?php

namespace MediaWiki\Rest\Handler;

use MalformedTitleException;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use RequestContext;
use Title;
use TitleFormatter;
use TitleParser;
use User;
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

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var LanguageNameUtils */
	private $languageNameUtils;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var TitleFormatter */
	private $titleFormatter;

	/** @var TitleParser */
	private $titleParser;

	/** @var User */
	private $user;

	/**
	 * @var Title|bool|null
	 */
	private $title = null;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param LanguageNameUtils $languageNameUtils
	 * @param PermissionManager $permissionManager
	 * @param TitleFormatter $titleFormatter
	 * @param TitleParser $titleParser
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		LanguageNameUtils $languageNameUtils,
		PermissionManager $permissionManager,
		TitleFormatter $titleFormatter,
		TitleParser $titleParser
	) {
		$this->loadBalancer = $loadBalancer;
		$this->languageNameUtils = $languageNameUtils;
		$this->permissionManager = $permissionManager;
		$this->titleFormatter = $titleFormatter;
		$this->titleParser = $titleParser;

		// @todo Inject this, when there is a good way to do that
		$this->user = RequestContext::getMain()->getUser();
	}

	/**
	 * @return Title|bool Title or false if unable to retrieve title
	 */
	private function getTitle() {
		if ( $this->title === null ) {
			$this->title = Title::newFromText( $this->getValidatedParams()['title'] ) ?? false;
		}
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$titleObj = $this->getTitle();
		if ( !$titleObj || !$titleObj->getArticleID() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-title',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ]
				),
				404
			);
		}
		if ( !$this->permissionManager->userCan( 'read', $this->user, $titleObj ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-permission-denied-title',
					[ new ScalarParam( ParamType::PLAINTEXT, $title ) ] ),
				403
			);
		}

		return $this->getResponseFactory()
			->createJson( $this->fetchLinks( $titleObj->getArticleID() ) );
	}

	private function fetchLinks( $pageId ) {
		$result = [];
		$res = $this->loadBalancer->getConnectionRef( DB_REPLICA )
			->select(
				'langlinks',
				'*',
				[ 'll_from' => $pageId ],
				__METHOD__,
				[ 'ORDER BY' => 'll_lang' ]
			);
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
	 * @throws LocalizedHttpException
	 */
	protected function getETag(): ?string {
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() ) {
			return null;
		}

		// XXX: use hash of the rendered HTML?
		return '"' . $title->getLatestRevID() . '@' . wfTimestamp( TS_MW, $title->getTouched() ) . '"';
	}

	/**
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getLastModified(): ?string {
		$title = $this->getTitle();
		if ( !$title || !$title->getArticleID() ) {
			return null;
		}

		return $title->getTouched();
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		$title = $this->getTitle();
		return $title ? $title->exists() : false;
	}

}
