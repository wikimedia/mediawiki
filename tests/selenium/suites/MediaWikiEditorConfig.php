<?php

/**
 * Selenium server manager
 *
 * @file
 * @ingroup Testing
 * Copyright (C) 2010 Dan Nessett <dnessett@yahoo.com>
 * http://citizendium.org/
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
 *
 * @addtogroup Testing
 *
 */

class MediaWikiEditorConfig {

    public static function getSettings(&$includeFiles, &$globalConfigs) {
        $includes = array(
                //files that needed to be included would go here
                'maintenance/tests/selenium/suites/MediaWikiCommonFunction.php'
        );
        $configs = array(
                'wgPageLoadTime' => "600000"
        );
        $includeFiles = array_merge( $includeFiles, $includes );
        $globalConfigs = array_merge( $globalConfigs, $configs);
        return true;
    }
}



