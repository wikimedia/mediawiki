<?php
/**
 *
 *
 * Created on Dec 1, 2007
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
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

/**
 * A query action to return messages from site message cache
 *
 * @ingroup API
 */
class ApiQueryAllMessages extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'am' );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		if ( is_null( $params['lang'] ) ) {
			$langObj = $this->getLanguage();
		} elseif ( !Language::isValidCode( $params['lang'] ) ) {
			$this->dieWithError(
				[ 'apierror-invalidlang', $this->encodeParamName( 'lang' ) ], 'invalidlang'
			);
		} else {
			$langObj = Language::factory( $params['lang'] );
		}

		if ( $params['enableparser'] ) {
			if ( !is_null( $params['title'] ) ) {
				$title = Title::newFromText( $params['title'] );
				if ( !$title || $title->isExternal() ) {
					$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['title'] ) ] );
				}
			} else {
				$title = Title::newFromText( 'API' );
			}
		}

		$prop = array_flip( (array)$params['prop'] );

		// Determine which messages should we print
		if ( in_array( '*', $params['messages'] ) ) {
			$message_names = Language::getMessageKeysFor( $langObj->getCode() );
			if ( $params['includelocal'] ) {
				$message_names = array_unique( array_merge(
					$message_names,
					// Pass in the content language code so we get local messages that have a
					// MediaWiki:msgkey page. We might theoretically miss messages that have no
					// MediaWiki:msgkey page but do have a MediaWiki:msgkey/lang page, but that's
					// just a stupid case.
					MessageCache::singleton()->getAllMessageKeys( $this->getConfig()->get( 'LanguageCode' ) )
				) );
			}
			sort( $message_names );
			$messages_target = $message_names;
		} else {
			$messages_target = $params['messages'];
		}

		// Filter messages that have the specified prefix
		// Because we sorted the message array earlier, they will appear in a clump:
		if ( isset( $params['prefix'] ) ) {
			$skip = false;
			$messages_filtered = [];
			foreach ( $messages_target as $message ) {
				// === 0: must be at beginning of string (position 0)
				if ( strpos( $message, $params['prefix'] ) === 0 ) {
					if ( !$skip ) {
						$skip = true;
					}
					$messages_filtered[] = $message;
				} elseif ( $skip ) {
					break;
				}
			}
			$messages_target = $messages_filtered;
		}

		// Filter messages that contain specified string
		if ( isset( $params['filter'] ) ) {
			$messages_filtered = [];
			foreach ( $messages_target as $message ) {
				// !== is used because filter can be at the beginning of the string
				if ( strpos( $message, $params['filter'] ) !== false ) {
					$messages_filtered[] = $message;
				}
			}
			$messages_target = $messages_filtered;
		}

		// Whether we have any sort of message customisation filtering
		$customiseFilterEnabled = $params['customised'] !== 'all';
		if ( $customiseFilterEnabled ) {
			global $wgContLang;

			$customisedMessages = AllMessagesTablePager::getCustomisedStatuses(
				array_map(
					[ $langObj, 'ucfirst' ],
					$messages_target
				),
				$langObj->getCode(),
				!$langObj->equals( $wgContLang )
			);

			$customised = $params['customised'] === 'modified';
		}

		// Get all requested messages and print the result
		$skip = !is_null( $params['from'] );
		$useto = !is_null( $params['to'] );
		$result = $this->getResult();
		foreach ( $messages_target as $message ) {
			// Skip all messages up to $params['from']
			if ( $skip && $message === $params['from'] ) {
				$skip = false;
			}

			if ( $useto && $message > $params['to'] ) {
				break;
			}

			if ( !$skip ) {
				$a = [
					'name' => $message,
					'normalizedname' => MessageCache::normalizeKey( $message ),
				];

				$args = [];
				if ( isset( $params['args'] ) && count( $params['args'] ) != 0 ) {
					$args = $params['args'];
				}

				if ( $customiseFilterEnabled ) {
					$messageIsCustomised = isset( $customisedMessages['pages'][$langObj->ucfirst( $message )] );
					if ( $customised === $messageIsCustomised ) {
						if ( $customised ) {
							$a['customised'] = true;
						}
					} else {
						continue;
					}
				}

				$msg = wfMessage( $message, $args )->inLanguage( $langObj );

				if ( !$msg->exists() ) {
					$a['missing'] = true;
				} else {
					// Check if the parser is enabled:
					if ( $params['enableparser'] ) {
						$msgString = $msg->title( $title )->text();
					} else {
						$msgString = $msg->plain();
					}
					if ( !$params['nocontent'] ) {
						ApiResult::setContentValue( $a, 'content', $msgString );
					}
					if ( isset( $prop['default'] ) ) {
						$default = wfMessage( $message )->inLanguage( $langObj )->useDatabase( false );
						if ( !$default->exists() ) {
							$a['defaultmissing'] = true;
						} elseif ( $default->plain() != $msgString ) {
							$a['default'] = $default->plain();
						}
					}
				}
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $a );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'from', $message );
					break;
				}
			}
		}
		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'message' );
	}

	public function getCacheMode( $params ) {
		if ( is_null( $params['lang'] ) ) {
			// Language not specified, will be fetched from preferences
			return 'anon-public-user-private';
		} elseif ( $params['enableparser'] ) {
			// User-specific parser options will be used
			return 'anon-public-user-private';
		} else {
			// OK to cache
			return 'public';
		}
	}

	public function getAllowedParams() {
		return [
			'messages' => [
				ApiBase::PARAM_DFLT => '*',
				ApiBase::PARAM_ISMULTI => true,
			],
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'default'
				]
			],
			'enableparser' => false,
			'nocontent' => false,
			'includelocal' => false,
			'args' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_ALLOW_DUPLICATES => true,
			],
			'filter' => [],
			'customised' => [
				ApiBase::PARAM_DFLT => 'all',
				ApiBase::PARAM_TYPE => [
					'all',
					'modified',
					'unmodified'
				]
			],
			'lang' => null,
			'from' => null,
			'to' => null,
			'title' => null,
			'prefix' => null,
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&meta=allmessages&amprefix=ipb-'
				=> 'apihelp-query+allmessages-example-ipb',
			'action=query&meta=allmessages&ammessages=august|mainpage&amlang=de'
				=> 'apihelp-query+allmessages-example-de',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Allmessages';
	}
}
