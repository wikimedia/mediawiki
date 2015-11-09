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
 * @author Eric Evans
 */

/**
 * Formats a notification into the EventBus JSON format
 *
 * @since 1.27
 */
class EventBusFeedFormatter extends MachineReadableRCFeedFormatter {

	protected function formatArray( array $packet ) {
		$event = array(
			// TODO: do.
			'meta' => array(
				'uri' => '/not/areal/uri',
				'request_id' => UIDGenerator::newUUIDv4(),	// FIXME: taken from request header
				'id' => UIDGenerator::newUUIDv4(),	  // FIXME: should be v1
				'dt' => date('c', time()),
				'domain' => $packet['server_name'],
			),
			'user_id' => $packet['user_id'],
			'user_text' => $packet['user'],
		);

		switch ( $packet['type'] ) {
			case 'edit':
				// Edit
				$event['meta']['token'] = 'mwEdit';
				$event['title'] = $packet['title'];
				$event['page_id'] = $packet['page_id'];
				$event['namespace'] = $packet['namespace'];
				$event['revision'] = $packet['revision']['new'];
				$event['base_revision'] = $packet['revision']['old'];
				$event['save_dt'] = date('c', $packet['timestamp']);
				$event['summary'] = $packet['comment'];
				break;
			case 'log':
				// Not Edit
				switch ( $packet['log_type'] ) {
					case 'delete':
						// Delete and restore
						if ( $packet['log_action'] == 'delete') {
							$event['meta']['token'] = 'mwDelete';
							$event['title'] = $packet['title'];
							$event['page_id'] = $packet['page_id'];
							$event['summary'] = $packet['comment'];
						} elseif ( $packet['log_action'] == 'restore') {
							// FIXME: where to get `new_page_id' and `old_page_id'?
							$event['meta']['token'] = 'mwRestore';
							$event['namespace'] = $packet['namespace'];
							$event['summary'] = $packet['comment'];
						} else {
							return null;
						}
						break;
					case 'move':
						// Move
						$event['meta']['token'] = 'mwMove';
						$event['new_title'] = $packet['log_params']['target'];
						$event['old_title'] = $packet['title'];
						$event['new_revision'] = $packet['revision']['new'];
						$event['old_revision'] = $packet['revision']['old'];
						$event['summary'] = $packet['comment'];
						break;
					case 'protect':
						// Visibility
						// TODO: do.
						$event['meta']['token'] = 'mwProtect';
						$event['revision'] = '';
						$event['hidden'] = array(
							'sha1' => '',
							'text' => '',
							'user' => '',
							'comment' => '',
						);
						break;
					default:
						return null;
				}
				break;
			default:
				return null;
		}

		return FormatJson::encode( $event );
	}
}
