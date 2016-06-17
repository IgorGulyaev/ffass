<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Page
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Top menu block
 *
 * @package    Ecomitize_All
 * @author     Ecomitizet Magento Team <>
 * @copyright  Copyright (c) 2015 Ecomitize http://www.ecomitize.com
 */
class Ecomitize_Navigation_Block_Page_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{
    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param Varien_Data_Tree_Node $menuTree
     * @param string $childrenWrapClass
     * @return string
     */
    protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
    {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $lastNode = end($children->getNodes());
        $childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

        $counter = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        foreach ($children as $child) {

            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($child->hasChildren()) {
                $parentClass = 'parent dropdown-toggle';
                $dataHover = 'data-hover="dropdown"';
            } else {
                $parentClass = '';
                $dataHover = '';
            }

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . ($child->hasChildren() ? ' dropdown-toggle" data-hover="dropdown" ' : '"');
                $child->setClass($outermostClass);
            }

            $html .= '<li ' . ($childLevel == 0 ? $this->getTopClassName($counter, $childrenCount, $this->_getRenderedMenuItemAttributes($child)) : $this->_getRenderedMenuItemAttributes($child)) . '>';

            $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode  . ' class="' . $parentClass . '" '. $dataHover .'>' . $this->escapeHtml($child->getName()) . '</a>';

            if ($child->hasChildren()) {
                if (!empty($childrenWrapClass)) {
                    $html .= '<div class="' . $childrenWrapClass . '">';
                }

                if($childLevel < 3) {
                    $html .= '<ul class="level' . $childLevel . ' dropdown-menu">';
                    $html .= $this->_getHtml($child, $childrenWrapClass);
                    $html .= '</ul>';
                }

                if (!empty($childrenWrapClass)) {
                    $html .= '</div>';
                }
            }

            $html .= '</li>';

            if($childLevel > 0) {
                if($child->getId() === $lastNode->getId() && !is_null($child->getId())){
                    $html .= '<li ' . 'class="' . 'level'.$childLevel.' btn-show-all'. '">';
                    $html .= '<a href="' . $child->getParent()->getUrl() . '" ' . '>' . 'Show All Products' . ' ' . $child->getParent()->getName() . '</a>';
                    $html .= '</li>';
                }
            }


            $counter++;
        }

        return $html;
    }

    /**
     * Returns array of menu item's classes
     *
     * @param Varien_Data_Tree_Node $item
     * @return array
     */
    protected function _getMenuItemClasses(Varien_Data_Tree_Node $item)
    {
        $classes = array();

        $classes[] = 'level' . $item->getLevel();
        $classes[] = $item->getPositionClass();

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'dropdown';
        }

        return $classes;
    }

    public function getTopClassName($counter, $childrenCount, $class){

        $quarter = $counter / $childrenCount *100;
        if($quarter < 50){
            $class = str_replace('class="','class="dropdown-left ',$class);
            return $class;
        }
        elseif($quarter > 50){
            $class = str_replace('class="','class="dropdown-right ',$class);
            return $class;
        }
    }

}