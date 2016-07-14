<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Adminhtml_Source_Customer_Attributes
{

    /**
     * customer attributes.
     *
     * @return array
     */
    public function toOptionArray()
    {

        $options = array();
        //exclude attributes from mapping
        $excluded = array('website_id','store_id','default_billing','default_shipping','disable_auto_group_change','confirmation','email');
        $attributes = Mage::getModel('customer/customer')->getAttributes();

        foreach ($attributes as $attribute) {
            if ($attribute->getFrontendLabel()) {
                $code = $attribute->getAttributeCode();
                //escape the label in case of quotes
                $label = addslashes($attribute->getFrontendLabel());
                if(!in_array($code, $excluded))
                    $options[] = array(
                        'value' => $attribute->getAttributeCode(),
                        'label' => $label
                    );
            }
        }

        return $options;
    }
}