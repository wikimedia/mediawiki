<?php
/**
 *
 *
 * Created on Dec 1, 2007
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiQueryBase.php' );
}

/**
 * A query action to return messages from site message cache
 *
 * @ingroup API
 */
class ApiQueryAllmessages extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'am' );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		if ( is_null( $params['lang'] ) ) {
			global $wgLang;
			$langObj = $wgLang;
		} else {
			$langObj = Language::factory( $params['lang'] );
		}

		if ( $params['enableparser'] ) {
			if ( !is_null( $params['title'] ) ) {
				$title = Title::newFromText( $params['title'] );
				if ( !$title ) {
					$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
				}
			} else {
				$title = Title::newFromText( 'API' );
			}
		}

		$prop = array_flip( (array)$params['prop'] );

		// Determine which messages should we print
		if ( in_array( '*', $params['messages'] ) ) {
			$message_names = array_keys( Language::getMessagesFor( 'en' ) );
			sort( $message_names );
			$messages_target = $message_names;
		} else {
			$messages_target = $params['messages'];
		}

		// Filter messages that have the specified prefix
		// Because we sorted the message array earlier, they will appear in a clump:
		if ( isset( $params['prefix'] ) ) {
			$skip = false;
			$messages_filtered = array();
			foreach ( $messages_target as $message ) {
				// === 0: must be at beginning of string (position 0)
				if ( strpos( $message, $params['prefix'] ) === 0 ) {
					if( !$skip ) {
						$skip = true;
					}
					$messages_filtered[] = $message;
				} else if ( $skip ) {
					break;
				}
			}
			$messages_target = $messages_filtered;
		}

		// Filter messages that contain specified string
		if ( isset( $params['filter'] ) ) {
			$messages_filtered = array();
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
			$lang = $langObj->getCode();

			$customisedMessages = AllmessagesTablePager::getCustomisedStatuses(
				array_map( array( $langObj, 'ucfirst'), $messages_target ), $lang, $lang != $wgContLang->getCode() );

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
				$a = array( 'name' => $message );
				$args = array();
				if ( isset( $params['args'] ) && count( $params['args'] ) != 0 ) {
					$args = $params['args'];
				}

				if ( $customiseFilterEnabled ) {
					$messageIsCustomised = isset( $customisedMessages['pages'][ $langObj->ucfirst( $message ) ] );
					if ( $customised === $messageIsCustomised && $customised ) {
						if ( $customised ) {
							$a['customised'] = '';
						}
					} else {
						continue;
					}
				}

				$msg = wfMessage( $message, $args )->inLanguage( $langObj );

				if ( !$msg->exists() ) {
					$a['missing'] = '';
				} else {
					// Check if the parser is enabled:
					if ( $params['enableparser'] ) {
						$msgString = $msg->title( $title )->text();
					} else {
						$msgString = $msg->plain();
					}
					ApiResult::setContent( $a, $msgString );
					if ( isset( $prop['default'] ) ) {
						$default = wfMessage( $message )->inLanguage( $langObj )->useDatabase( false );
						if ( !$default->exists() ) {
							$a['defaultmissing'] = '';
						} elseif ( $default->plain() != $msgString ) {
							$a['default'] = $default->plain();
						}
					}
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $a );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'from', $message );
					break;
				}
			}
		}
		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'message' );
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
		return array(
			'messages' => array(
				ApiBase::PARAM_DFLT => '*',
				ApiBase::PARAM_ISMULTI => true,
			),
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'default'
				)
			),
			'enableparser' => false,
			'args' => array(
				ApiBase::PARAM_ISMULTI => true
			),
			'filter' => array(),
			'customised' => array(
				ApiBase::PARAM_DFLT => 'all',
				ApiBase::PARAM_TYPE => array(
					'all',
					'modified',
					'unmodified'
				)
			),
			'lang' => null,
			'from' => null,
			'to' => null,
			'title' => null,
			'prefix' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'messages' => 'Which messages to output. "*" (default) means all messages',
			'prop' => 'Which properties to get',
			'enableparser' => array( 'Set to enable parser, will preprocess the wikitext of message',
							  'Will substitute magic words, handle templates etc.' ),
			'title' => 'Page name to use as context when parsing message (for enableparser option)',
			'args' => 'Arguments to be substituted into message',
			'prefix' => 'Return messages with this prefix',
			'filter' => 'Return only messages with names that contain this string',
			'customised' => 'Return only messages in this customisation state',
			'lang' => 'Return messages in this language',
			'from' => 'Return messages starting at this message',
			'to' => 'Return messages ending at this message',
		);
	}

	public function getDescription() {
		return 'Return messages from this site';
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&meta=allmessages&amprefix=ipb-',
			'api.php?action=query&meta=allmessages&ammessages=august|mainpage&amlang=de',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
