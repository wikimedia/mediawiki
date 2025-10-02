<?php
/**
 * Formatter for protect log entries.
 *
 * @license GPL-2.0-or-later
 * @file
 * @license GPL-2.0-or-later
 * @since 1.26
 */

namespace MediaWiki\Logging;

use MediaWiki\Message\Message;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;

/**
 * This class formats protect log entries.
 *
 * @since 1.26
 */
class ProtectLogFormatter extends LogFormatter {
	private TitleParser $titleParser;

	public function __construct(
		LogEntry $entry,
		TitleParser $titleParser
	) {
		parent::__construct( $entry );
		$this->titleParser = $titleParser;
	}

	/** @inheritDoc */
	public function getPreloadTitles() {
		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'move_prot' ) {
			$params = $this->extractParameters();
			try {
				return [ $this->titleParser->parseTitle( $params[3] ) ];
			} catch ( MalformedTitleException ) {
			}
		}
		return [];
	}

	/** @inheritDoc */
	protected function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->extractParameters();
		if ( isset( $params[4] ) && $params[4] ) {
			// Messages: logentry-protect-protect-cascade, logentry-protect-modify-cascade
			$key .= '-cascade';
		}

		return $key;
	}

	/** @inheritDoc */
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'protect' || $subtype === 'modify' ) {
			$rawParams = $this->entry->getParameters();
			if ( isset( $rawParams['details'] ) ) {
				$params[3] = $this->createProtectDescription( $rawParams['details'] );
			} elseif ( isset( $params[3] ) ) {
				// Old way of Restrictions and expiries
				$params[3] = $this->context->getLanguage()->getDirMark() . $params[3];
			} else {
				// Very old way (nothing set)
				$params[3] = '';
			}
			// Cascading flag
			if ( isset( $params[4] ) ) {
				// handled in getMessageKey
				unset( $params[4] );
			}
		} elseif ( $subtype === 'move_prot' ) {
			$oldname = $this->makePageLink( Title::newFromText( $params[3] ), [ 'redirect' => 'no' ] );
			$params[3] = Message::rawParam( $oldname );
		}

		return $params;
	}

	/** @inheritDoc */
	public function getActionLinks() {
		$linkRenderer = $this->getLinkRenderer();
		$subtype = $this->entry->getSubtype();
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| $subtype === 'move_prot' // the move log entry has the right action link
		) {
			return '';
		}

		// Show history link for pages that exist otherwise show nothing
		$title = $this->entry->getTarget();
		$links = [];
		if ( $title->exists() ) {
			$links[] = $linkRenderer->makeLink( $title,
				$this->msg( 'hist' )->text(),
				[],
				[
					'action' => 'history',
					'offset' => $this->entry->getTimestamp(),
				]
			);
		}

		// Show change protection link
		if ( $this->context->getAuthority()->isAllowed( 'protect' ) ) {
			$links[] = $linkRenderer->makeKnownLink(
				$title,
				$this->msg( 'protect_change' )->text(),
				[],
				[ 'action' => 'protect' ]
			);
		}

		if ( !$links ) {
			return '';
		} else {
			return $this->msg( 'parentheses' )->rawParams(
				$this->context->getLanguage()->pipeList( $links )
			)->escaped();
		}
	}

	/** @inheritDoc */
	protected function getParametersForApi() {
		$entry = $this->entry;
		$subtype = $this->entry->getSubtype();
		$params = $entry->getParameters();

		$map = [];
		if ( $subtype === 'protect' || $subtype === 'modify' ) {
			$map = [
				'4::description',
				'5:bool:cascade',
				'details' => ':array:details',
			];
		} elseif ( $subtype === 'move_prot' ) {
			$map = [
				'4:title:oldtitle',
				'4::oldtitle' => '4:title:oldtitle',
			];
		}
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		// Change string to explicit boolean
		if ( isset( $params['5:bool:cascade'] ) && is_string( $params['5:bool:cascade'] ) ) {
			$params['5:bool:cascade'] = $params['5:bool:cascade'] === 'cascade';
		}

		return $params;
	}

	/** @inheritDoc */
	public function formatParametersForApi() {
		$ret = parent::formatParametersForApi();
		if ( isset( $ret['details'] ) && is_array( $ret['details'] ) ) {
			$contLang = $this->getContentLanguage();
			foreach ( $ret['details'] as &$detail ) {
				if ( isset( $detail['expiry'] ) ) {
					$detail['expiry'] = $contLang->
						formatExpiry( $detail['expiry'], TS_ISO_8601, 'infinite' );
				}
			}
		}

		return $ret;
	}

	/**
	 * Create the protect description to show in the log formatter
	 *
	 * @param array[] $details
	 * @return string
	 */
	public function createProtectDescription( array $details ) {
		$protectDescription = '';

		foreach ( $details as $param ) {
			$expiryText = $this->formatExpiry( $param['expiry'] );

			// Messages: restriction-edit, restriction-move, restriction-create,
			// restriction-upload
			$action = $this->context->msg( 'restriction-' . $param['type'] )->escaped();

			$protectionLevel = $param['level'];
			// Messages: protect-level-autoconfirmed, protect-level-sysop
			$message = $this->context->msg( 'protect-level-' . $protectionLevel );
			if ( $message->isDisabled() ) {
				// Require "$1" permission
				$restrictions = $this->context->msg( "protect-fallback", $protectionLevel )->parse();
			} else {
				$restrictions = $message->escaped();
			}

			if ( $protectDescription !== '' ) {
				$protectDescription .= $this->context->msg( 'word-separator' )->escaped();
			}

			$protectDescription .= $this->context->msg( 'protect-summary-desc' )
				->params( $action, $restrictions, $expiryText )->escaped();
		}

		return $protectDescription;
	}

	private function formatExpiry( string $expiry ): string {
		if ( wfIsInfinity( $expiry ) ) {
			return $this->context->msg( 'protect-expiry-indefinite' )->text();
		}
		$lang = $this->context->getLanguage();
		$user = $this->context->getUser();
		return $this->context->msg(
			'protect-expiring-local',
			$lang->userTimeAndDate( $expiry, $user ),
			$lang->userDate( $expiry, $user ),
			$lang->userTime( $expiry, $user )
		)->text();
	}

}

/** @deprecated class alias since 1.44 */
class_alias( ProtectLogFormatter::class, 'ProtectLogFormatter' );
