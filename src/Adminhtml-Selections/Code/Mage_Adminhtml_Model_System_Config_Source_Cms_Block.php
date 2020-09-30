<?php
/**
 * This is not a fix or override, but a completely new feature!
 * - Added the ability to allow specific static block selection within an admin module configuration.
 * - Added the ability to not select anything for default/reset purposes.
 *
 * @version  Magento 1.6.0.0
 * @see      Mage_Adminhtml_Model_System_Config_Source_Cms_Page
 * @see      Mage_Adminhtml_Model_System_Config_Source_Customer_Group
 * @see      Mage_Cms_Model_Resource_Block_Collection
 */
class Mage_Adminhtml_Model_System_Config_Source_Cms_Block
{
    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('cms/block_collection')->load()->toOptionArray();
            array_unshift($this->_options, array('value'=>'', 'label'=>Mage::helper('adminhtml')->__('-- Please Select --')));
        }

        return $this->_options;
    }
}
