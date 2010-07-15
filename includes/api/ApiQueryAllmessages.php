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

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ('ApiQueryBase.php');
}

/**
 * A query action to return messages from site message cache
 *
 * @ingroup API
 */
class ApiQueryAllmessages extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'am');
	}

	public function execute() {
		global $wgMessageCache;
		$params = $this->extractRequestParams();

		if(!is_null($params['lang']))
		{
			global $wgLang;
			$wgLang = Language::factory($params['lang']);
		} else if ( is_null( $params['lang'] ) ) {
			// Language not determined by URL but by user preferences, so don't cache
			$this->getMain()->setVaryCookie();
		}

		//Determine which messages should we print
		$messages_target = array();
		if( $params['messages'] == '*' ) {
			$wgMessageCache->loadAllMessages();
			$message_names = array_keys( array_merge( Language::getMessagesFor( 'en' ), $wgMessageCache->getExtensionMessagesFor( 'en' ) ) );
			sort( $message_names );
			$messages_target = $message_names;
		} else {
			$messages_target = explode( '|', $params['messages'] );
		}

		//Filter messages
		if( isset( $params['filter'] ) ) {
			$messages_filtered = array();
			foreach( $messages_target as $message ) {
				if( strpos( $message, $params['filter'] ) !== false ) {	//!== is used because filter can be at the beginnig of the string
					$messages_filtered[] = $message;
				}
			}
			$messages_target = $messages_filtered;
		}

		//Get all requested messages
		$messages = array();
		$skip = !is_null($params['from']);
		foreach( $messages_target as $message ) {
			// Skip all messages up to $params['from']
			if($skip && $message === $params['from'])
				$skip = false;
			if(!$skip)
				$messages[$message] = wfMsg( $message );
		}

		//Print the result
		$result = $this->getResult();
		$messages_out = array();
		foreach( $messages as $name => $value ) {
			$message = array();
			$message['name'] = $name;
			if( wfEmptyMsg( $name, $value ) ) {
				$message['missing'] = '';
			} else {
				$result->setContent( $message, $value );
			}
			$fit = $result->addValue(array('query', $this->getModuleName()), null, $message);
			if(!$fit)
			{
				$this->setContinueEnumParameter('from', $name);
				break;
			}
		}
		$result->setIndexedTagName_internal(array('query', $this->getModuleName()), 'message');
	}

	public function getAllowedParams() {
		return array (
			'messages' => array (
				ApiBase :: PARAM_DFLT => '*',
			),
			'filter' => array(),
			'lang' => null,
			'from' => null,
		);
	}

	public function getParamDescription() {
		return array (
			'messages' => 'Which messages to output. "*" means all messages',
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