<?php

/**
 * vanilla-thunder/oxid-module-easytplblocks
 * manage custom template blocks in your OXID eShop V6.2
 *
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 *  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>
 */

namespace VanillaThunder\Easytplblocks\Application;

use OxidEsales\Eshop\Core\Registry;

class Events
{
    public static function onActivate()
    {
        $query = "CREATE TABLE IF NOT EXISTS `easytplblocks` (
              `OXID` char(32) NOT NULL,
              `OXACTIVE` tinyint(1) NOT NULL DEFAULT '1',
              `OXTEMPLATE` varchar(128) COLLATE latin1_general_ci NOT NULL,
              `OXBLOCKNAME` varchar(128) COLLATE latin1_general_ci NOT NULL,
              `OXPOS` int(11) NOT NULL,
              `OXCONTENT` text COLLATE utf8_general_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";

        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($query);
    }

    public static function onDeactivate()
    {
        // clear cache
        //$oUtils = Registry::getUtils();
        //$oUtils->resetTemplateCache();
    }
}
