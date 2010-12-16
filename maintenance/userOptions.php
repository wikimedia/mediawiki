<?php
/**
 * Script to change users skins on the fly.
 * This is for at least MediaWiki 1.10alpha (r19611) and have not been
 * tested with previous versions. It should probably work with 1.7+.
 *
 * Made on an original idea by Fooey (freenode)
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
 * @ingroup Maintenance
 * @author Ashar Voultoiz <hashar at free dot fr>
 */

// This is a command line script, load tools and parse args
require_once( 'userOptions.inc' );

// Load up our tool system, exit with usage() if options are not fine
$uo = new userOptions( $options, $args );

$uo->run();

print "Done.\n";

