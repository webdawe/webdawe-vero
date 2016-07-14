<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Adminhtml_Source_Order_Fields
{
    /**
     * Returns custom order attributes
     * @return array
     */
    public function toOptionArray()
    {
        $fields = Mage::getResourceModel('webdawe_vero/order')->getOrderTableDescription();

        $customFields = array();
        foreach ($fields as $key => $field) {
            $customFields[] = array(
                'value' => $field['COLUMN_NAME'],
                'label' => Mage::helper('webdawe_vero')->getCamelCasedText($field['COLUMN_NAME'])
            );
        }
        return $customFields;
    }
}