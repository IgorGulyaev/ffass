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
 * @category    Softprodigy
 * @package     Softprodigy_Giftwrap
 * @copyright   Copyright (c) 2013 Softprodigy System Solutions Pvt. Ltd (http://www.softprodigy.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Softprodigy_Giftwrap_Adminhtml_GiftwrapController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('catalog/giftwraps')
			->_addBreadcrumb(Mage::helper('adminhtml')->__("Gift Wraps Manager"), Mage::helper('adminhtml')->__("Gift Wraps Manager"));
		$this->getLayout()->getBlock('head')->setTitle($this->__('Manage Gift Wraps / Softprodigy / Magento Admin'));
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('giftwrap/giftwrap_product')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('giftwrap_data', $model);

			$this->loadLayout();
			if($id == "")
			{
				$this->getLayout()->getBlock('head')->setTitle($this->__("New Gift Wrap / Manage Gift Wraps / Softprodigy / Magento Admin"));
			}
			else
			{
				$this->getLayout()->getBlock('head')->setTitle($this->__($model->getGiftwrapName()." / Manage Gift Wraps / Softprodigy / Magento Admin"));
			}
			$this->_setActiveMenu('catalog/giftwraps');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__("Gift Wraps Manager"), Mage::helper('adminhtml')->__("Gift Wraps Manager"));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('giftwrap/adminhtml_giftwrap_edit'))
				->_addLeft($this->getLayout()->createBlock('giftwrap/adminhtml_giftwrap_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftwrap')->__('Gift Wraps does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		
		$giftwrapCollection = Mage::getModel('giftwrap/giftwrap_product')->getCollection();
		if (!is_dir(Mage::getBaseDir().'/media/giftwrap/')) {
				mkdir(Mage::getBaseDir().'/media/giftwrap/', 0777);
		} 
		$path = Mage::getBaseDir('media') ."/giftwrap/" ;
		$supported_extensions = array('jpg', 'png', 'gif', 'jpeg', 'bmp');
		
		if ($data = $this->getRequest()->getPost()) {
					$id = $this->getRequest()->getParam('id');
					if($id != "") {
						$write = Mage::getSingleton('core/resource')->getConnection('core_write');
						$write->query("delete from `giftwrap_entity_datetime` where `entity_id` = '".$id."'");
						$write->query("delete from `giftwrap_entity_decimal` where `entity_id` = '".$id."'");
						$write->query("delete from `giftwrap_entity_int` where `entity_id` = '".$id."'");
						$write->query("delete from `giftwrap_entity_int` where `entity_id` = '".$id."'");
					}
					$model = Mage::getModel('giftwrap/giftwrap_product')->load($id);
					$upload_key = 1;
					
					foreach($_POST as $field => $val)
					{
						$value = $val;
						$removeunderscore = str_replace('_', ' ', $field);
						$ucwordss = ucwords($removeunderscore);
						$func = str_replace(' ', '', $ucwordss);
						$fname = "set".$func;
						$model->$fname($value);
					}
					
					foreach($_FILES as $field1 => $val1)
					{
						if($val1['error'] == 0)
						{
							$time = time();
							$removeunderscore1 = str_replace('_', ' ', $field1);
							$ucwordss1 = ucwords($removeunderscore1);
							$imgname = str_replace(' ', '_', $ucwordss1);
							$imgname = $time.$imgname;
							$func1 = str_replace(' ', '', $ucwordss1);
							$fname1 = "set".$func1;
							$slider_extension = end(explode('.', $val1['name']));
							if(isset($val1['name']) && $val1['name'] != '') {
								if(in_array($slider_extension, $supported_extensions)) {
									$sliderimage = str_replace(' ', '_', $imgname);
									$slider_uploader = new Varien_File_Uploader($field1);
									$slider_uploader->setAllowedExtensions($supported_extensions);
									$slider_uploader->setAllowRenameFiles(true);
									$slider_uploader->setFilesDispersion(false);
									$slider_uploader->save($path, $sliderimage.".".$slider_extension );
									$dbval = "giftwrap/".$sliderimage.".".$slider_extension;
									$model->$fname1($dbval);
									unset($slider_uploader);
								} else {
									$upload_key = 0;
								}
							}
						}
					}
				
					
					
					try {
						if ($model->getGiftwrapCreatedAt == NULL) {
							$model->setGiftwrapCreatedAt(now());
						}
						if($upload_key == 1) {
							$model->save();
							Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('giftwrap')->__('Gift Wrap was successfully saved'));
							Mage::getSingleton('adminhtml/session')->setFormData(false);
						} else {
							Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftwrap')->__('Invalid file type. Please upload any image with jpg, png, gif, jpeg or bmp format.'));
							Mage::getSingleton('adminhtml/session')->setFormData($data);
							$this->_redirect('*/*/');
						}

						if ($this->getRequest()->getParam('back')) {
							$this->_redirect('*/*/edit', array('id' => $model->getId()));
							return;
						}
						$this->_redirect('*/*/');
						return;
					} catch (Exception $e) {
						Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						Mage::getSingleton('adminhtml/session')->setFormData($data);
						$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
						return;
					}
			}
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftwrap')->__('Unable to find Gift Wrap to save'));
			$this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('giftwrap/giftwrap_product');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Gift Wrap was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $webIds = $this->getRequest()->getParam('giftwrap');
        if(!is_array($webIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Gift Wrap(s)'));
        } else {
            try {
                foreach ($webIds as $webId) {
                    $web = Mage::getModel('giftwrap/giftwrap_product')->load($webId);
                    $web->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($webIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $webIds = $this->getRequest()->getParam('giftwrap');
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $attr_query = $read->fetchall("select * from `eav_attribute` where `attribute_code`='giftwrap_status'");
        $attr_id = $attr_query[0]['attribute_id'];
		if(!is_array($webIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Gift Wrap(s)'));
        } else {
            try {
                foreach ($webIds as $webId) {
					if($webId!="")
					{
						$write = Mage::getSingleton('core/resource')->getConnection('core_write');
						$query = "delete from `giftwrap_entity_int` where `entity_id`='".$webId."' and `attribute_id`='".$attr_id."'";
						$write->query($query);
					}
					$web = Mage::getModel('giftwrap/giftwrap_product')->load($webId);
					$web->setGiftwrapStatus($this->getRequest()->getParam('giftwrap_status'));
					$web->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($webIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    public function addGiftWrapAction() {
		$giftwraps = array(1,2);
		Mage::getSingleton('core/session')->setGiftWraps($giftwraps);
	}
}
