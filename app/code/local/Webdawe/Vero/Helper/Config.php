<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Helper_Config extends Mage_Core_Helper_Abstract
{
	const XML_PATH_VERO_GENERAL_ENABLED 					= 'webdawe_vero/general/enabled';
	const XML_PATH_VERO_GENERAL_DEBUG						= 'webdawe_vero/general/debug';
	const XML_PATH_VERO_GENERAL_WEBSITE_PREFIX				= 'webdawe_vero/general/website_prefix';

	const XML_PATH_VERO_API_KEY 							= 'webdawe_vero/api/key';
	const XML_PATH_VERO_API_AUTH_TOKEN  					= 'webdawe_vero/api/auth_token';
	const XML_PATH_VERO_SYNC_CUSTOMER_ATTRIBUTES 			= 'webdawe_vero/sync_settings/customer_data_fields';
	const XML_PATH_VERO_SYNC_CUSTOMER_ADDRESS_ATTRIBUTES 	= 'webdawe_vero/sync_settings/customer_address_fields';
	const XML_PATH_VERO_SYNC_ORDER_ATTRIBUTES 				= 'webdawe_vero/sync_settings/order_data_fields';
	const XML_PATH_VERO_SYNC_QUOTE_ATTRIBUTES 				= 'webdawe_vero/sync_settings/quote_data_fields';
	const XML_PATH_VERO_SYNC_MAXIMUM_ATTEMPTS				= 'webdawe_vero/sync_settings/maximum_attempts';
	const XML_PATH_VERO_SYNC_MAXIMUM_RECORDS				= 'webdawe_vero/sync_settings/maximum_records';

	const XML_PATH_VERO_NOTIFY_SUCCESS_EMAILS				= 'webdawe_vero/notification_settings/notify_success_emails';
	const XML_PATH_VERO_NOTIFY_ERROR_EMAILS					= 'webdawe_vero/notification_settings/notify_error_emails';

	const DEFAULT_SYNC_MAXIMUM_ATTEMPTS 					= 1;
	const DEFAULT_SYNC_MAXIMUM_RECORDS 						= 500;

	/**
	 * Check whether module is enabled or not
	 * @param null $store
	 * @return int
	 */
	public function getEnabled($store = null)
	{
		return $this->getConfiguration(self::XML_PATH_VERO_GENERAL_ENABLED, $store);
	}

	/**
	 * Retrieve Debug : Enabled / Disabled
	 * @param null $store
	 * @return mixed
	 */
	public function getDebug($store = null)
	{
		return $this->getConfiguration(self::XML_PATH_VERO_GENERAL_DEBUG, $store);
	}

	/**
	 * Retireve Website Prefix
	 * @param null $store
	 * @return mixed
	 */
	public function getWebsitePrefix($store = null)
	{
		return $this->getConfiguration(self::XML_PATH_VERO_GENERAL_WEBSITE_PREFIX, $store);
	}

	/**
	 * Retrieve API Key
	 * @param null $store
	 * @return mixed
	 */
	public function getApiKey($store = null)
	{
		return $this->getConfiguration(self::XML_PATH_VERO_API_KEY, $store);
	}

	/**
	 * Retrieve API Auth Token
	 * @param null $store
	 * @return mixed
	 */
	public function getApiAuthToken($store = null)
	{
		return $this->getConfiguration(self::XML_PATH_VERO_API_AUTH_TOKEN, $store);
	}

	/**
	 * Retrieve Maximum Attempts
	 * @param null $store
	 * @return mixed
	 */
	public function getMaximumAttempts($store = null)
	{
		$maximumAttempts =  self::DEFAULT_SYNC_MAXIMUM_ATTEMPTS;

		if ($this->getConfiguration(self::XML_PATH_VERO_SYNC_MAXIMUM_ATTEMPTS, $store))
		{
			$maximumAttempts = $this->getConfiguration(self::XML_PATH_VERO_SYNC_MAXIMUM_ATTEMPTS, $store);
		}

		return $maximumAttempts;
	}

	/**
	 * Retrieve Maximum Records to be synced per run
	 * @param null $store
	 * @return int|mixed
	 */
	public function getMaximumRecords($store = null)
	{
		$maximumRecords =  self::DEFAULT_SYNC_MAXIMUM_RECORDS;

		if ($this->getConfiguration(self::XML_PATH_VERO_SYNC_MAXIMUM_RECORDS, $store))
		{
			$maximumRecords = $this->getConfiguration(self::XML_PATH_VERO_SYNC_MAXIMUM_RECORDS, $store);
		}

		return $maximumRecords;
	}

	/**
	 * Retrieve Customer Attributes
	 * @param null $store
	 * @return array
	 */
	public function getCustomerAttributes($store = null)
	{
		return explode(',', $this->getConfiguration(self::XML_PATH_VERO_SYNC_CUSTOMER_ATTRIBUTES, $store));
	}

	/**
	 * Retrieve Customer Address Attributes
	 * @param null $store
	 * @return array
	 */
	public function getCustomerAddressAttributes($store = null)
	{
		return explode(',', $this->getConfiguration(self::XML_PATH_VERO_SYNC_CUSTOMER_ADDRESS_ATTRIBUTES, $store));
	}

	/**
	 * Retrieve Quote Attributes
	 * @param null $store
	 * @return array
	 */
	public function getQuoteAttributes($store = null)
	{
		return explode(',', $this->getConfiguration(self::XML_PATH_VERO_SYNC_QUOTE_ATTRIBUTES, $store));
	}

	/**
	 * Retireve Order Attributes
	 * @param null $store
	 * @return array
	 */
	public function getOrderAttributes($store = null)
	{
		return explode(',', $this->getConfiguration(self::XML_PATH_VERO_SYNC_ORDER_ATTRIBUTES, $store));
	}


	/**
	 * Retrieve Notify Emails
	 * @param int $status
	 * @param null $store
	 * @return mixed
	 */
	public function getNotifyEmails($status, $store = null)
	{
		$configPath = self::XML_PATH_VERO_NOTIFY_ERROR_EMAILS;

		if($status == 1)
		{
			$configPath = self::XML_PATH_VERO_NOTIFY_SUCCESS_EMAILS;
		}

		return explode(',', $this->getConfiguration($configPath, $store));
	}


	/**
	 * Retrieve a configuration value for a given path and optional store.
	 * 
	 * @param string $path
	 * @param mixed $store
	 * @return mixed
	 */
	public function getConfiguration($path, $store = null)
	{
		return Mage::getStoreConfig($path, $store);
	}

	/**
	 * check tbt rewards installed/active status
	 * @return boolean
	 */
	public function isRewardsEnabled()
	{
		return (bool)Mage::getConfig()->getModuleConfig('TBT_Rewards')->is('active', 'true');
	}
}