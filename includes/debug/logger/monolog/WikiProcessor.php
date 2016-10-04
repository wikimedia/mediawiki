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
 */

namespace MediaWiki\Logger\Monolog;

/**
 * Annotate log records with request-global metadata, such as the hostname,
 * wiki / request ID, and MediaWiki version.
 *
 * @since 1.25
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2013 Bryan Davis and Wikimedia Foundation.
 */
class WikiProcessor {
	/** @var array Keys which should not be used in log context */
	protected $reservedKeys = [
		// from monolog:src/Monolog/Formatter/LogstashFormatter.php#L71-L88
		'message', 'channel', 'level', 'type',
		// from WebProcessor
		'url', 'ip', 'http_method', 'server', 'referrer',
		// from WikiProcessor
		'host', 'wiki', 'reqId', 'mwversion',
		// from config magic
		'normalized_message',
	];

	/**
	 * @param array $record
	 * @return array
	 */
	public function __invoke( array $record ) {
		global $wgVersion;

		// some log aggregators such as Logstash will merge the log context into the main
		// metadata and end up overwriting the data coming from processors
		foreach ( $this->reservedKeys as $key ) {
			if ( isset( $record['context'][$key] ) ) {
				wfLogWarning( __METHOD__ . ": '$key' key overwritten in log context." );
			}
		}

		$record['extra'] = array_merge(
			$record['extra'],
			[
				'host' => wfHostname(),
				'wiki' => wfWikiID(),
				'mwversion' => $wgVersion,
				'reqId' => \WebRequest::getRequestId(),
			]
		);
		return $record;
	}
}
