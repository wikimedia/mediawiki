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
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$titleObj = Title::newFromText( $title );
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
}
