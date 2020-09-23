<?php

/*
 * vanilla-thunder/oxid-module-devutils
 * developent utilities for OXID eShop V6.2 and newer
 *
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 *  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>
 */

namespace VanillaThunder\Easytplblocks\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class Tplblockmanager extends \OxidEsales\Eshop\Application\Controller\Admin\AdminController
{
    protected $_sThisTemplate = 'easytplblocks_manager.tpl';

    public function getTplBLocks() {

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)->create();

        $queryBuilder->select('*')->from('easytplblocks')->orderBy("OXTEMPLATE")->addOrderBy("OXBLOCKNAME");
        $aEasytplblocks = $queryBuilder->execute()->fetchAll();

        die(json_encode($aEasytplblocks));
    }

    public function getOxtemplateAutocomplete() {
        $oConfig = Registry::getConfig();
        $oConfig->setAdminMode(false);
        $activeThemeIds = oxNew(\OxidEsales\Eshop\Core\Theme::class)->getActiveThemesList();
        $sTplPath = $oConfig->getDir(null, "tpl", false, null, null,$activeThemeIds[0]);

        $aTemplates = [];
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sTplPath));
        foreach ($rii as $file) if ($file->isFile() && $file->getExtension() === "tpl") $aTemplates[] = str_replace($sTplPath,"",$file->getPathname());

        sort($aTemplates);

        return json_encode($aTemplates);
    }
    public function getOxblocknameAutocomplete() {
        $aPayload = json_decode(file_get_contents('php://input'), true);
        $sTemplate = $aPayload["oxtemplate"];
        //var_dump($sTemplate);

        //$activeThemeIds = oxNew(\OxidEsales\Eshop\Core\Theme::class)->getActiveThemesList();

        $oConfig = Registry::getConfig();
        $sTempaltePath = $oConfig->getTemplatePath($sTemplate,false);

        $sTemplateContent = file_get_contents($sTempaltePath);

        $regex = '/\[\{\s*block\s+name\s*=\s*([\'"])([a-z0-9_]+)\1\s*\}\]/gsi';
        preg_match_all('/\[\{\s*block\s+name\s*=\s*([\'"])([a-z0-9_]+)\1\s*\}\]/',$sTemplateContent,$aBlocks);

        print json_encode((count($aBlocks[0]) > 0 ? $aBlocks[2] : []));
        die();

        //$aTempalteBlocks =
    }

    public function toggleBlock() {
        $aPayload = json_decode(file_get_contents('php://input'), true);
        if(!($sOxid = $aPayload["oxid"])) die("no");

        $oTplBlock = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oTplBlock->init("easytplblocks");
        $oTplBlock->load($sOxid);
        $oTplBlock->assign(["easytplblocks__oxactive" => !$oTplBlock->easytplblocks__oxactive->value]);
        $oTplBlock->save();

        die("ok");
    }
    public function saveBlock() {

        $aPayload = json_decode(file_get_contents('php://input'), true);
        $aBlock = $aPayload["block"];

        $oTplBlock = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oTplBlock->init("easytplblocks");

        if($aBlock["OXID"]) $oTplBlock->load($aBlock["OXID"]);

        $oTplBlock->assign([
            "easytplblocks__oxactive" => $aBlock["OXACTIVE"],
            "easytplblocks__oxtemplate" => $aBlock["OXTEMPLATE"],
            "easytplblocks__oxblockname" => $aBlock["OXBLOCKNAME"],
            "easytplblocks__oxcontent" => $aBlock["OXCONTENT"],
        ]);
        $oTplBlock->save();

        die("ok");
    }
    public function deleteBlock() {
        $aPayload = json_decode(file_get_contents('php://input'), true);
        $sOxid = $aPayload["oxid"];

        $oTplBlock = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oTplBlock->init("easytplblocks");
        $oTplBlock->load($sOxid);
        $oTplBlock->delete();

        die("ok");
    }
}
