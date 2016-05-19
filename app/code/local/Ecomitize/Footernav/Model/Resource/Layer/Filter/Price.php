<?php
/**
 * Created by PhpStorm.
 * User: Vladimir
 * Date: 5/18/2016
 * Time: 5:12 PM
 */

class Ecomitize_Footernav_Model_Resource_Layer_Filter_Price extends Mage_Catalog_Model_Resource_Layer_Filter_Price
{
    /**
     * Rewrite core method , exclude featured and onsale attributes from select
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @return Varien_Db_Select
     */
    protected function _getSelect($filter)
    {
        $route = Mage::app()->getRequest()->getRouteName();

        if($route == 'offers'){
            $collection = $filter->getLayer()->getProductCollection();
            $collection->addPriceData($filter->getCustomerGroupId(), $filter->getWebsiteId());

            if (!is_null($collection->getCatalogPreparedSelect())) {
                $select = clone $collection->getCatalogPreparedSelect();
            } else {
                $select = clone $collection->getSelect();
            }

            // reset columns, order and limitation conditions
            $select->reset(Zend_Db_Select::COLUMNS);
            $select->reset(Zend_Db_Select::ORDER);
            $select->reset(Zend_Db_Select::LIMIT_COUNT);
            $select->reset(Zend_Db_Select::LIMIT_OFFSET);

            // remove join with main table
            $fromPart = $select->getPart(Zend_Db_Select::FROM);
            if (!isset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS])
                || !isset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS])
            ) {
                return $select;
            }

            // processing FROM part
            $priceIndexJoinPart = $fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS];
            $priceIndexJoinConditions = explode('AND', $priceIndexJoinPart['joinCondition']);
            $priceIndexJoinPart['joinType'] = Zend_Db_Select::FROM;
            $priceIndexJoinPart['joinCondition'] = null;
            $fromPart[Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS] = $priceIndexJoinPart;
            unset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS]);
            $select->setPart(Zend_Db_Select::FROM, $fromPart);
            foreach ($fromPart as $key => $fromJoinItem) {
                $fromPart[$key]['joinCondition'] = $this->_replaceTableAlias($fromJoinItem['joinCondition']);
            }
            $select->setPart(Zend_Db_Select::FROM, $fromPart);

            // processing WHERE part
            $wherePart = $select->getPart(Zend_Db_Select::WHERE);
            $excludedWherePart = Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS . '.status';
            foreach ($wherePart as $key => $wherePartItem) {
                if (strpos($wherePartItem, $excludedWherePart) !== false) {
                    $wherePart[$key] = new Zend_Db_Expr('1=1');
                    continue;
                }
                if (strpos($wherePartItem, 'special') !== false) {
                    $wherePart[$key] = new Zend_Db_Expr(' AND 1=1');
                    continue;
                }
                if (strpos($wherePartItem, 'featured') !== false) {
                    $wherePart[$key] = new Zend_Db_Expr(' AND 1=1');
                    continue;
                }
                $wherePart[$key] = $this->_replaceTableAlias($wherePartItem);
            }
            $select->setPart(Zend_Db_Select::WHERE, $wherePart);
            $excludeJoinPart = Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS . '.entity_id';
            foreach ($priceIndexJoinConditions as $condition) {
                if (strpos($condition, $excludeJoinPart) !== false) {
                    continue;
                }
                $select->where($this->_replaceTableAlias($condition));
            }
            $select->where($this->_getPriceExpression($filter, $select) . ' IS NOT NULL');

            return $select;
        }
        else{
            return parent::_getSelect($filter);
        }

    }
}



