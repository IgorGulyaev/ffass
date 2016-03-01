<?php

    class Bc_Brand_Block_Adminhtml_Brand_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $form = new Varien_Data_Form();
            $this->setForm($form);
            $fieldset = $form->addFieldset('brand_form', array('legend'=>Mage::helper('brand')->__('Brand information')));
            //get Manufecturer's Attribut options
            //DebugBreak();
            $attribute_code=Mage::getModel('eav/entity_attribute')->getIdByCode('catalog_product', "brand");
            $attributeInfo = Mage::getModel('eav/entity_attribute')->load($attribute_code);
            $attribute_table = Mage::getModel('eav/entity_attribute_source_table')->setAttribute($attributeInfo);
            $attributeOptions = $attribute_table->getAllOptions(false);
            $default=array('value'=>'','label'=>'Choose Brand');
            $i=0;
            $manufacturer[$i]=$default;
            foreach($attributeOptions as $key=>$value){
                $i++;
                $manufacturer[$i]=$value;
            }
            //End
            $fieldset->addField('brand_name', 'select', array(
                    'label'     => Mage::helper('brand')->__('Select Brand'),
                    'class'     => 'required-entry',
                    'required'  => true,
                    'name'      => 'brand_name',
                    'values'    =>$manufacturer,
                ));
                
              $fieldset->addField('legend', 'text', array(
                    'label'     => Mage::helper('brand')->__('Legend'),
                    'required'  => false,
                    'name'      => 'legend',
                )); 


            //At the time of insert of manufacturer the image uploaded is reqired
            //While time of Edit the control can be blank
            if(Mage::registry('brand_data')->getData('filename')!=""){
                $fieldset->addField('filename', 'file', array(
                        'label'     => Mage::helper('brand/data')->__('Brand Logo'),
                        'required'  => false,
                        'name'      => 'filename',
                        'after_element_html' => Mage::registry('brand_data')->getData('filename') != "" ?
                            '<span class="hint"><img src="'.Mage::getBaseUrl('media')."Brand/".Mage::registry('brand_data')->getData('filename').'" width="25" height="25" name="brand_image" style="vertical-align: middle;" /><span style="color: #eb5e00; font-size: 1.25em;">Please use images with transparent background only !</span></span>':'',
                    ));
            }else{
                $fieldset->addField('filename', 'file', array(
                        'label'     => Mage::helper('brand/data')->__('Brand Logo'),
                        'class'     => 'required-entry',
                        'required'  => true,
                        'name'      => 'filename',
                        'after_element_html' => Mage::registry('brand_data')->getData('filename') != "" ?
                            '<span class="hint"><img src="'.Mage::getBaseUrl('media')."Brand/".Mage::registry('brand_data')->getData('filename').'" width="25" height="25" name="brand_image" style="vertical-align: middle;" /><span style="color: #eb5e00; font-size: 1.25em;">Please use images with transparent background only !</span></span>':'',
                    ));
            }
            //<br/><input type="checkbox" name="image_chk" id="image_chk"/><label for="image_chk"> Delete Image</label>

            $fieldset->addField('status', 'select', array(
                    'label'     => Mage::helper('brand')->__('Status'),
                    'name'      => 'status',
                    'values'    => array(
                        array(
                            'value'     => 1,
                            'label'     => Mage::helper('brand')->__('Enabled'),
                        ),

                        array(
                            'value'     => 2,
                            'label'     => Mage::helper('brand')->__('Disabled'),
                        ),
                    ),
                ));



            if ( Mage::getSingleton('adminhtml/session')->getBrandData() )
            {
                $form->setValues(Mage::getSingleton('adminhtml/session')->getBrandData());
                Mage::getSingleton('adminhtml/session')->setBrandData(null);
            } elseif ( Mage::registry('brand_data') ) {
                $form->setValues(Mage::registry('brand_data')->getData());
            }
            return parent::_prepareForm();
        }
}