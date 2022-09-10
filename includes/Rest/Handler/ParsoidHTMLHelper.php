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
 * @ingroup Page
 */
namespace MediaWiki\Rest\Handler;

use IBufferingStatsdDataFactory;
use Language;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageRecord;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Revision\RevisionRecord;
use ParserOptions;
use ParserOutput;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Helper for getting output of a given wikitext page rendered by parsoid.
 *
 * @since 1.36
 *
 * @unstable Pending consolidation of the Parsoid extension with core code.
 *           Part of this class should probably become a service.
 */
class ParsoidHTMLHelper {
	/**
	 * @internal
	 * @var string[]
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ParsoidCacheConfig
	];

	/** @var ParsoidOutputStash */
	private $parsoidOutputStash;

	/** @var PageIdentity|null */
	private $page = null;

	/** @var RevisionRecord|null */
	private $revision = null;

	/** @var Language|null */
	private $pageLanguage = null;

	/** @var ?string [ 'view', 'stash' ] are the supported flavors for now */
	private $flavor = null;

	/** @var bool */
	private $stash = false;

	/** @var IBufferingStatsdDataFactory */
	private $stats;

	/** @var User */
	private $user;

	/** @var ParsoidOutputAccess */
	private $parsoidOutputAccess;

	/** @var ParserOutput */
	private $parserOutput;

	/**
	 * @param ParsoidOutputStash $parsoidOutputStash
	 * @param IBufferingStatsdDataFactory $statsDataFactory
	 * @param ParsoidOutputAccess $parsoidOutputAccess
	 */
	public function __construct(
		ParsoidOutputStash $parsoidOutputStash,
		IBufferingStatsdDataFactory $statsDataFactory,
		ParsoidOutputAccess $parsoidOutputAccess
	) {
		$this->parsoidOutputStash = $parsoidOutputStash;
		$this->stats = $statsDataFactory;
		$this->parsoidOutputAccess = $parsoidOutputAccess;
	}

	/**
	 * @param PageIdentity $page
	 * @param array $parameters
	 * @param User $user
	 * @param RevisionRecord|null $revision
	 * @param Language|null $pageLanguage
	 */
	public function init(
		PageIdentity $page,
		array $parameters,
		User $user,
		?RevisionRecord $revision = null,
		?Language $pageLanguage = null
	) {
		$this->page = $page;
		$this->user = $user;
		$this->revision = $revision;
		$this->pageLanguage = $pageLanguage;
		$this->stash = $parameters['stash'];
		$this->flavor = $parameters['stash'] ? 'stash' : 'view'; // more to come, T308743
	}

	/**
	 * @return ParserOutput a tuple with html and content-type
	 * @throws LocalizedHttpException
	 */
	public function getHtml(): ParserOutput {
		$parserOutput = $this->getParserOutput();

		if ( $this->stash ) {
			if ( $this->user->pingLimiter( 'stashbasehtml' ) ) {
				throw new LocalizedHttpException(
					MessageValue::new( 'parsoid-stash-rate-limit-error' ),
					// See https://www.rfc-editor.org/rfc/rfc6585#section-4
					429,
					[ 'reason' => 'Rate limiter tripped, wait for a few minutes and try again' ]
				);
			}

			$parsoidStashKey = ParsoidRenderID::newFromKey(
				$this->parsoidOutputAccess->getParsoidRenderID( $parserOutput )
			);
			$stashSuccess = $this->parsoidOutputStash->set(
				$parsoidStashKey,
				$this->parsoidOutputAccess->getParsoidPageBundle( $parserOutput )
			);
			if ( !$stashSuccess ) {
				$this->stats->increment( 'parsoidhtmlhelper.stash.fail' );
				throw new LocalizedHttpException(
					MessageValue::new( 'rest-html-backend-error' ),
					500,
					[ 'reason' => 'Failed to stash parser output' ]
				);
			}
			$this->stats->increment( 'parsoidhtmlhelper.stash.save' );
		}

		return $parserOutput;
	}

	/**
	 * Returns an ETag uniquely identifying the HTML output.
	 *
	 * @param string $suffix A suffix to attach to the etag.
	 *
	 * @return string|null
	 */
	public function getETag( string $suffix = '' ): ?string {
		$parserOutput = $this->getParserOutput();

		$renderID = $this->parsoidOutputAccess->getParsoidRenderID( $parserOutput )->getKey();

		if ( $suffix !== '' ) {
			$eTag = "$renderID/{$this->flavor}/$suffix";
		} else {
			$eTag = "$renderID/{$this->flavor}";
		}

		return "\"{$eTag}\"";
	}

	/**
	 * Returns the time at which the HTML was rendered.
	 *
	 * @return string|null
	 */
	public function getLastModified(): ?string {
		return $this->getParserOutput()->getCacheTime();
	}

	/**
	 * @return array
	 */
	public function getParamSettings(): array {
		return [
			'stash' => [
				Handler::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_REQUIRED => false,
			]
		];
	}

	/**
	 * @return ParserOutput
	 */
	private function getParserOutput(): ParserOutput {
		if ( !$this->parserOutput ) {
			$parserOptions = ParserOptions::newFromAnon();

			if ( $this->pageLanguage ) {
				$parserOptions->setTargetLanguage( $this->pageLanguage );
			}

			if ( $this->page instanceof PageRecord && $this->page->exists() ) {
				$status = $this->parsoidOutputAccess->getParserOutput(
					$this->page,
					$parserOptions,
					$this->revision
				);
			} else {
				$status = $this->parsoidOutputAccess->parse(
					$this->page,
					$parserOptions,
					$this->revision
				);
			}

			if ( !$status->isOK() ) {
				if ( $status->hasMessage( 'parsoid-client-error' ) ) {
					throw new LocalizedHttpException(
						MessageValue::new( 'rest-html-backend-error' ),
						400,
						[ 'reason' => $status->getErrors() ]
					);
				} elseif ( $status->hasMessage( 'parsoid-resource-limit-exceeded' ) ) {
					throw new LocalizedHttpException(
						MessageValue::new( 'rest-resource-limit-exceeded' ),
						413,
						[ 'reason' => $status->getErrors() ]
					);
				} else {
					throw new LocalizedHttpException(
						MessageValue::new( 'rest-html-backend-error' ),
						500,
						[ 'reason' => $status->getErrors() ]
					);
				}
			}

			$this->parserOutput = $status->getValue();
		}

		return $this->parserOutput;
	}

}
