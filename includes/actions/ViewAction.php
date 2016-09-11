<?php
/**
 * An action that views article content
 *
 * Copyright © 2012 Timo Tijhof
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 * @author Timo Tijhof
 */

/**
 * An action that views article content
 *
 * This is a wrapper that will call Article::render().
 *
 * @ingroup Actions
 */
class ViewAction extends FormlessAction {

	public function getName() {
		return 'view';
	}

	public function onView() {
		return null;
	}

	public function show() {
		$config = $this->context->getConfig();

		if (
			$config->get( 'DebugToolbar' ) == false && // don't let this get stuck on pages
			$this->page->checkTouched() // page exists and is not a redirect
		) {
			// Include any redirect in the last-modified calculation
			$redirFromTitle = $this->page->getRedirectedFrom();
			if ( !$redirFromTitle ) {
				$touched = $this->page->getTouched();
			} elseif ( $config->get( 'MaxRedirects' ) <= 1 ) {
				$touched = max( $this->page->getTouched(), $redirFromTitle->getTouched() );
			} else {
				// Don't bother following the chain and getting the max mtime
				$touched = null;
			}

			// If a page was purged on HTTP GET, relect that timestamp to avoid sending 304s
			$touched = max( $touched, $this->page->getLastPurgeTimestamp() );

			// Send HTTP 304 if the IMS matches or otherwise set expiry/last-modified headers
			if ( $touched && $this->getOutput()->checkLastModified( $touched ) ) {
				wfDebug( __METHOD__ . ": done 304\n" );
				return;
			}
		}

		$this->page->view();
	}
}
