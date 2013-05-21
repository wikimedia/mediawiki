<?php
/**
 * Recent changes tagging.
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
 * API module that allows users to view/add ChangeTags for a specific
 * revision/recent change/log entry.
 *
 * @see ChangeTags
 * @since 1.22
 * @ingroup API
 */
class ApiChangeTags extends ApiBase {
	function execute() {
		$params = $this->extractRequestParams();
		$this->requireAtLeastOneParameter( $params, 'rcid', 'revid', 'logid' );

		// Try to get a match for each parameter.
		$valid = ChangeTags::fetchTagIds( $params['rcid'], $params['revid'], $params['logid'], true );
		if ( !$valid ) {
			$this->dieUsage( 'No valid revid, rcid, or logid was supplied.', 'invalid' );
		}

		// Get the title for permission checks.
		$title = Revision::newFromId( $params['revid'] )->getTitle();

		$wasPosted = $this->getRequest()->wasPosted();

		if ( $wasPosted ) {
			// Need the changetags permission to change tags
			$permission = 'changetags';
		} else {
			// Otherwise, anybody who can read the page can see the tags in the history anyway
			$permission = 'read';
		}

		$errors = $title->getUserPermissionsErrors( $permission, $this->getUser() );
		if ( $errors ) {
			$this->dieUsageMsg( $errors[0] );
		}

		// Actually get/change the tags.
		$currentTags = array();
		if ( $wasPosted ) {
			ChangeTags::addTags( $params['tags'], $params['rcid'], $params['revid'], $params['logid'], $currentTags );
		}

		$currentTags = ChangeTags::getTags( $params['rcid'], $params['revid'], $params['logid'], $wasPosted );

		$info = array(
			'rcid' => $params['rcid'],
			'revid' => $params['revid'],
			'logid' => $params['logid'],
		);
		foreach ( $currentTags as $tag => $params ) {
			$info[] = array( 'name' => $tag, '*' => $params );
		}

		$result = $this->getResult();
		$result->setIndexedTagName( $info, 'tag' );
		$this->getResult()->addValue( null, $this->getModuleName(), $info );
	}

	function getDescription() {
		return 'Add change tags to a certain revision, recent change, or log entry.';
	}

	function getAllowedParams() {
		global $wgApiChangeTags;

		return array(
			'revid' => array(
				self::PARAM_TYPE => 'integer',
			),
			'rcid' => array(
				self::PARAM_TYPE => 'integer',
			),
			'logid' => array(
				self::PARAM_TYPE => 'integer',
			),
			'tags' => array(
				self::PARAM_TYPE => array_intersect(
					ChangeTags::listDefinedTags(),
					$wgApiChangeTags
				),
				self::PARAM_ISMULTI => true
			)
		);
	}

	function getParamDescription() {
		return array(
			'revid' => 'ID of the revision to add tags to',
			'rcid' => 'ID of the recent change to add tags to',
			'logid' => 'ID of the log entry to add tags to',
			'tags' => 'Tags to add to the specified object',
		);
	}

	function getResultProperties() {
		return array( '' => array(
			'revid' => array(
				self::PROP_TYPE => 'integer',
				self::PROP_NULLABLE => true,
			),
			'rcid' => array(
				self::PROP_TYPE => 'integer',
				self::PROP_NULLABLE => true,
			),
			'logid' => array(
				self::PROP_TYPE => 'integer',
				self::PROP_NULLABLE => true,
			),
			'tags' => array(
				self::PROP_TYPE => 'string',
				self::PROP_LIST => true,
			)
		) );
	}

	function getPossibleErrors() {
		return array_merge(
			parent::getPossibleErrors(),
			self::getRequireAtLeastOneParameterErrorMessages( 'revid', 'rcid', 'logid' )
		);
	}

	function getExamples() {
		return array(
			'api.php?action=changetags&rcid=105',
			'api.php?action=changetags&rcid=107&logid=106&revid=32'
		);
	}

	function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:ChangeTags';
	}
}