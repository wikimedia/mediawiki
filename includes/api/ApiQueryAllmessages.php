<?php

/*
 * Created on Dec 1, 2007
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once ( 'ApiQueryBase.php' );
}

/**
 * A query action to return messages from site message cache
 *
 * @ingroup API
 */
class ApiQueryAllmessages extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, 'am' );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		if ( !is_null( $params['lang'] ) )
		{
			global $wgLang;
			$wgLang = Language::factory( $params['lang'] );
		}
		
		$prop = array_flip( (array)$params['prop'] );

		// Determine which messages should we print
		$messages_target = array();
		if ( in_array( '*', $params['messages'] ) ) {
			$message_names = array_keys( Language::getMessagesFor( 'en' ) );
			sort( $message_names );
			$messages_target = $message_names;
		} else {
			$messages_target = $params['messages'];
		}

		// Filter messages
		if ( isset( $params['filter'] ) ) {
			$messages_filtered = array();
			foreach ( $messages_target as $message ) {
				if ( strpos( $message, $params['filter'] ) !== false ) {	// !== is used because filter can be at the beginnig of the string
					$messages_filtered[] = $message;
				}
			}
			$messages_target = $messages_filtered;
		}

		// Get all requested messages and print the result
		$messages = array();
		$skip = !is_null( $params['from'] );
		$result = $this->getResult();
		foreach ( $messages_target as $message ) {
			// Skip all messages up to $params['from']
			if ( $skip && $message === $params['from'] )
				$skip = false;

			if ( !$skip ) {
				$a = array( 'name' => $message );
				$args = null;
				if ( isset( $params['args'] ) && count( $params['args'] ) != 0 ) {
					$args = $params['args'];
				}
				// Check if the parser is enabled:
				if ( $params['enableparser'] ) {
					$msg = wfMsgExt( $message, array( 'parsemag' ), $args );
				} else if ( $args ) {
					$msgString = wfMsgGetKey( $message, true, false, false );
					$msg = wfMsgReplaceArgs( $msgString, $args );
				} else {
					$msg = wfMsgGetKey( $message, true, false, false );
				}

				if ( wfEmptyMsg( $message, $msg ) )
					$a['missing'] = '';
				else {
					ApiResult::setContent( $a, $msg );
					if ( isset( $prop['default'] ) ) {
						$default = wfMsgGetKey( $message, false, false, false );
						if ( $default !== $msg ) {
							if ( wfEmptyMsg( $message, $default ) )
								$a['defaultmissing'] = '';
							else
								$a['default'] = $default;
						}
					}
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $a );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'from', $name );
					break;
				}
			}
		}
		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'message' );
	}

	public function getAllowedParams() {
		return array (
			'messages' => array (
				ApiBase :: PARAM_DFLT => '*',
				ApiBase :: PARAM_ISMULTI => true,
			),
			'prop' => array(
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array(
					'default'
				)
			),
			'enableparser' => false,
			'args' => array(
				ApiBase :: PARAM_ISMULTI => true
			),
			'filter' => array(),
			'lang' => null,
			'from' => null,
		);
	}

	public function getParamDescription() {
		return array (
			'messages' => 'Which messages to output. "*" means all messages',
			'prop' => 'Which properties to get',
			'enableparser' => array( 'Set to enable parser, will preprocess the wikitext of message',
							  'Will substitute magic words, handle templates etc.' ),
			'args' => 'Arguments to be substituted into message',
			'filter' => 'Return only messages that contain this string',
			'lang' => 'Return messages in this language',
			'from' => 'Return messages starting at this message',
		);
	}

	public function getDescription() {
		return 'Return messages from this site.';
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&meta=allmessages&amfilter=ipb-',
			'api.php?action=query&meta=allmessages&ammessages=august|mainpage&amlang=de',
			);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
