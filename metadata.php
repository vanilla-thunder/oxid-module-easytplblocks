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


$sMetadataVersion = '2.1';
$aModule = [
    'id' => 'vt-easytplblocks',
    'title' => '[vt] Easy Template Blocks',
    'description' => 'manage custom template blocks in your OXID eShop V6.2',
    'thumbnail' => 'thumbnail.jpg',
    'version' => '0.0.1',
    'author' => 'Marat Bedoev',
    'email' => openssl_decrypt("Az6pE7kPbtnTzjHlPhPCa4ktJLphZ/w9gKgo5vA//p4=", str_rot13("nrf-128-pop"), str_rot13("gvalzpr")),
    'url' => 'https://github.com/vanilla-thunder/oxid-module-easytplblocks',
    'extend' => [
        \OxidEsales\Eshop\Core\UtilsView::class => VanillaThunder\Easytplblocks\Application\Extend\UtilsView::class
    ],
    'controllers' => [
        'easytplblocks' => VanillaThunder\Easytplblocks\Application\Controller\Admin\Tplblockmanager::class
    ],
    'templates' => [
        'easytplblocks_manager.tpl' => 'vt/easytplblocks/Application/views/admin/easytplblocks_manager.tpl'
    ],
    'events' => [
        'onActivate' => 'VanillaThunder\Easytplblocks\Application\Events::onActivate',
        'onDeactivate' => 'VanillaThunder\Easytplblocks\Application\Events::onDeactivate'
    ]
];
