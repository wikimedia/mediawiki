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
 * Sends the notification to the specified host as a JSON POST.
 *
 * @since 1.27
 */

class EventBusFeedEngine implements RCFeedEngine {
	/**
	 * @see RCFeedEngine::send
	 */
	public function send( array $feed, $line ) {
		/*
		 * The formatter *can* in fact return null (it's our janky hack for filtering
		 * unwanted events), however, RecentChange should pass on such instances
		 * preventing us from ever seeing a null here.	But Just In Case...
		 */
		if ( is_null( $line ) ) {
			return;
		}

		$http = new MultiHttpClient( array() );
		$url = str_replace('event', 'http', $feed['uri']);
		$http->run( array(
				'url'	  => $url,
				'method'  => 'POST',
				'body'	  => $line,
				'headers' => array( 'content-type' =>  'application/json' ),
		) );

		wfErrorLog( $line, "/var/www/data/debug.log" );
	}
}
