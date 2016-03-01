<?php

require_once 'Mage/Catalog/controllers/CategoryController.php';
class Ecomitize_All_Catalog_CategoryController extends Mage_Catalog_CategoryController
{
    /**
     * Category more products action
     */
    public function moreAction()
    {
        $nextPage = $this->getRequest()->getParam('page');
        $pageSize = $this->getRequest()->getParam('page_size');
        $categoryId = $this->getRequest()->getParam('id');
        $response = array('status' => 'ok', 'err_msg' => '', 'products' => array());
        if ($nextPage && $categoryId) {
            if ($category = $this->_initCatagory()) {
                $design = Mage::getSingleton('catalog/design');
                $settings = $design->getDesignSettings($category);

                // apply custom design
                if ($settings->getCustomDesign()) {
                    $design->applyCustomDesign($settings->getCustomDesign());
                }

                Mage::getSingleton('catalog/session')->setLastViewedCategoryId($category->getId());

                $update = $this->getLayout()->getUpdate();
                $update->addHandle('default');

                if (!$category->hasChildren()) {
                    $update->addHandle('catalog_category_layered_nochildren');
                }

                $this->addActionLayoutHandles();
                $update->addHandle($category->getLayoutUpdateHandle());
                $update->addHandle('CATEGORY_' . $category->getId());
                $this->loadLayoutUpdates();

                // apply custom layout update once layout is loaded
                if ($layoutUpdates = $settings->getLayoutUpdates()) {
                    if (is_array($layoutUpdates)) {
                        foreach($layoutUpdates as $layoutUpdate) {
                            $update->addUpdate($layoutUpdate);
                        }
                    }
                }

                $this->generateLayoutXml()->generateLayoutBlocks();
                // apply custom layout (page) template once the blocks are generated
                if ($settings->getPageLayout()) {
                    $this->getLayout()->helper('page/layout')->applyTemplate($settings->getPageLayout());
                }

                if ($root = $this->getLayout()->getBlock('root')) {
                    $root->addBodyClass('categorypath-' . $category->getUrlPath())
                        ->addBodyClass('category-' . $category->getUrlKey());
                }

                $this->_initLayoutMessages('catalog/session');
                $this->_initLayoutMessages('checkout/session');

                // Prepare ajax response
                $list = $this->getLayout()->getBlock('product_list');
                $coll = $list->getLoadedProductCollection();
                $coll->setPageSize($pageSize);
                $coll->setCurPage($nextPage);
                $coll->clear();
                $renderer = $list->getChild('product_renderer');
                if ($coll->count() > 0) {
                    foreach ($coll as $product) {
                        $renderer->setProduct($product);
                        $response['products'][] = $renderer->toHtml();
                    }
                } else {
                    $response['status']  = 'error';
                    $response['err_msg'] = Mage::helper('ecomitize_all')
                        ->__('Could not load products.');
                }

            } else {
                $response['status']  = 'error';
                $response['err_msg'] = Mage::helper('ecomitize_all')
                    ->__('Could not load products. Invalid category.');
            }
        }

        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode($response)
        );
    }
}
