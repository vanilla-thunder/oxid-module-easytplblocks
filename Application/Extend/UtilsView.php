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

namespace VanillaThunder\Easytplblocks\Application\Extend;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class UtilsView extends UtilsView_parent
{
    /**
     * Template blocks getter: retrieve sorted blocks for overriding in templates
     *
     * @param string $templateFileName filename of rendered template
     *
     * @see smarty_prefilter_oxblock
     *
     * @return array
     */
    public function getTemplateBlocks($templateFileName)
    {
        // regular template blocks
        $templateBlocksWithContent = parent::getTemplateBlocks($templateFileName);
        //var_dump($templateBlocksWithContent);

        // custom tempalte blocks
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)->create();
        $queryBuilder
            ->select('*')
            ->from('easytplblocks')
            ->where('oxactive = 1')
            ->andWhere("oxtemplate = :oxtemplate")
            ->setParameters([
                'oxtemplate' => $templateFileName
            ]);
        $blocksData = $queryBuilder->execute()->fetchAll();
        if(count($blocksData)>0) {
            foreach ($blocksData as $block) {
                if (!is_array($templateBlocksWithContent[$block['OXBLOCKNAME']])) $templateBlocksWithContent[$block['OXBLOCKNAME']] = [];
                $templateBlocksWithContent[$block['OXBLOCKNAME']][] = $block['OXCONTENT'];
            }
        };

        return $templateBlocksWithContent;
    }
}
