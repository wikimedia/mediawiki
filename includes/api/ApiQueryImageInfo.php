<?php

/*
 * Created on July 6, 2007
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
 * A query action to get image information and upload history.
 * 
 * @addtogroup API
 */
class ApiQueryImageInfo extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'ii');
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$history = $params['history'];

		$prop = array_flip($params['prop']);		
		$fld_timestamp = isset($prop['timestamp']);
		$fld_user = isset($prop['user']);
		$fld_comment = isset($prop['comment']);
		$fld_url = isset($prop['url']);
		$fld_size = isset($prop['size']);

		$pageIds = $this->getPageSet()->getAllTitlesByNamespace();
		if (!empty($pageIds[NS_IMAGE])) {
			foreach ($pageIds[NS_IMAGE] as $dbKey => $pageId) {
								
				$title = Title :: makeTitle(NS_IMAGE, $dbKey);
				$img = wfFindFile($title);

				$data = array();
				if ( !$img ) {
					$data['missing'] = '';			
				} else {
					
					$data['repository'] = $img->getRepoName();
					
					$isCur = true;
					while($line = $img->nextHistoryLine()) { // assignment
						$vals = array();

						if ($fld_timestamp)
							$vals['timestamp'] = wfTimestamp(TS_ISO_8601, $line->img_timestamp);
						if ($fld_user)
							$vals['user'] = $line->img_user_text;
						if ($fld_size) {
							$vals['size'] = $line->img_size;
							$vals['width'] = $line->img_width;
							$vals['height'] = $line->img_height;
						}
						if ($fld_url)
							$vals['url'] = $isCur ? $img->getURL() : $img->getArchiveUrl($line->oi_archive_name);
						if ($fld_comment)
							$vals['comment'] = $line->img_description;

						$data[] = $vals;
						
						if (!$history)	// Stop after the first line.
							break;
							
						$isCur = false;
					}
					
					$img->resetHistory();
				}

				$this->addPageSubItems($pageId, $data);
			}
		}
	}

	protected function getAllowedParams() {
		return array (
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_DFLT => 'timestamp|user',
				ApiBase :: PARAM_TYPE => array (
					'timestamp',
					'user',
					'comment',
					'url',
					'size',
				)
			),
			'history' => false,
		);
	}

	protected function getParamDescription() {
		return array (
			'prop' => 'What image information to get.',
			'history' => 'Include upload history',
		);
	}

	protected function getDescription() {
		return array (
			'Returns image information and upload history'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&titles=Image:Albert%20Einstein%20Head.jpg&prop=imageinfo',
			'api.php?action=query&titles=Image:Test.jpg&prop=imageinfo&iihistory&iiprop=timestamp|user|url',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
