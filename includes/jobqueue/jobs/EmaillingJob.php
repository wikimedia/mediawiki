<?php
/**
 * Old job for notification emails.
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
 * @ingroup JobQueue
 */

/**
 * Old job used for sending single notification emails;
 * kept for backwards-compatibility
 *
 * @ingroup JobQueue
 */
class EmaillingJob extends Job {
	function __construct( Title $title = null, array $params ) {
		parent::__construct( 'sendMail', Title::newMainPage(), $params );
	}

	function run() {
		$status = UserMailer::send(
			$this->params['to'],
			$this->params['from'],
			$this->params['subj'],
			$this->params['body'],
			array( 'replyTo' => $this->params['replyto'] )
		);

		return $status->isOK();
	}
}
