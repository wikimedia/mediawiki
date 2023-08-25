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

namespace MediaWiki\CommentStore;

/**
 * @defgroup CommentStore CommentStore
 *
 * The Comment store in MediaWiki is responsible for storing edit summaries,
 * log action comments and other such short strings (referred to as "comments").
 *
 * The CommentStore class handles the database abstraction for reading
 * and writing comments, which are represented by CommentStoreComment objects.
 *
 * Data is internally stored in the `comment` table.
 */

/**
 * Handle database storage of comments such as edit summaries and log reasons.
 *
 * @ingroup CommentStore
 * @since 1.30
 */
class CommentStore extends CommentStoreBase {
}

/**
 * @deprecated since 1.40
 */
class_alias( CommentStore::class, 'CommentStore' );
