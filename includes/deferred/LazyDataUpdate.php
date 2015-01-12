<?php
/**
 * Base code for update jobs that do something with some secondary
 * data extracted from article.
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
 * @author Aaron Schulz
 */

/**
 * Wrapper class to construct DataUpdate classes that is PHP serializable
 *
 * This can be used to build object to pass to ParserOutput::addSecondaryDataUpdate().
 * When ParserOutput::getSecondaryDataUpdates() is called, it will call the resolve()
 * method to get the DataUpdate.
 *
 * Use this to defer the construct of bulky objects, objects with process caches of
 * changing DB data, DB handles, and other things that serialize poorly.
 */
abstract class LazyDataUpdate {
	/**
	 * @param Title $title
	 * @param ParserOutput $output
	 * @return DataUpdate
	 */
	abstract public function resolve( Title $title, ParserOutput $output );
}
