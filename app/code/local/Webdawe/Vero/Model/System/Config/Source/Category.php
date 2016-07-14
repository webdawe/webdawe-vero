<?php
class Webdawe_Vero_Model_System_Config_Source_Category
{
	public function toOptionArray()
	{
		$collection = Mage::getResourceModel('catalog/category_collection');

		$collection->addAttributeToSelect('name')
		->addAttributeToSelect('is_active')
		->addAttributeToSelect('level')
		->addAttributeToFilter('level', array('gt' => 1))
		->addAttributeToFilter('is_active',1)
		->load();

		$options = array();
	
		foreach ($collection as $category) {
			$options[] = array(
					'label' => str_repeat('-', $category->getLevel()-2) . $category->getName(),
					'value' => $category->getId()
			);
		}
		
		return $options;
	}
}