<?php
/*
* @category    Webdawe
* @package     Webdawe_Vero
* @author      Anil Paul
* @copyright   Copyright (c) 2016 Webdawe
* @license
*/
class Webdawe_Vero_Helper_Abstract extends Mage_Core_Helper_Abstract
{
    protected $_veroClient;
    protected $_debug;
    protected $_allMessages = array();

    /**
     * Webdawe_Vero_Helper_Abstract constructor.
     */
    public function __construct()
    {
        $this->_debug = Mage::helper('webdawe_vero/config')->getDebug();
    }

    /**
     * Set Debug
     * @param bool $debug
     */
    public function setDebug($debug = false)
    {
        $this->_debug = $debug;
    }

    /**
     * Check Whether Debug Mode
     * @return bool
     */
    protected function isDebug()
    {
        if ($this->_debug)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $info
     * @param $data
     */
    protected function _showDebug($info, $data)
    {
        if ($this->isDebug())
        {
           Mage::log($info . '-' .  print_r($data,true), null, 'vero_debug.log');
        }
    }

    /**
     * Retrieve Vero Client
     * @return Webdawe_Vero_Model_Api_Vero
     */
    protected function _getVeroClient()
    {
        if (!$this->_veroClient instanceof Webdawe_Vero_Model_Api_Vero)
        {
            $this->_veroClient =  Mage::getModel('webdawe_vero/api_vero');
        }

        return $this->_veroClient;
    }

    /**
     * Retrieve Read Connection
     *
     */
    protected function getResource()
    {
        /*@var $resource Mage_Core_Model_Resource*/
        return Mage::getSingleton('core/resource');
    }

    /**
     * Retrieve Read Connection
     *
     */
    protected function getReadConnection()
    {
        return $this->getResource()->getConnection('core_read');
    }

    /**
     * Retrieve Read Connection
     *
     */
    protected function getWriteConnection()
    {
        return $this->getResource()->getConnection('core_write');
    }

    /**
     * Set Import to Array
     * @param array $messages
     * @param string $message
     * @return array $messages
     */
    public function setMessage($messages = array(), $message, $locale = null)
    {

        $date = Mage::getSingleton('core/date')->gmtDate();

        $localeInstance = Mage::app()->getLocale();
        $localDate = $localeInstance->date($date);

        if ($locale !== null)
        {
            $date = $localDate;
        }

        if(!is_array($messages))
        {
            $messages = array();
        }

        array_push($messages, '' . $date .' - ' . $message);

        //push all message to a class array if needed can retrieve after each execution
        array_push($this->_allMessages, '' . $localDate .' - ' . $message);

        //for shell script disaply all messages if its flagged to do so.
        if ($this->isDebug())
        {
            echo  $localDate .' - ' . $message . PHP_EOL;
        }

        return $messages;
    }


    /**
     * Retrieve All Messages
     * @param integer $asHtml
     * @return array | string:
     */
    public function getMessages($asHtml = 0)
    {
        if (!$asHtml)
        {
            return $this->_allMessages;
        }

        return implode('<br>', $this->_allMessages);
    }

    /**
     * Send Notification Emails
     * @param array $emails
     * @param string $subject
     * @param string $message
     * @return bool
     */
    protected function _sendNotification(array $emails, $subject, $message)
    {
        if (!count($emails))
        {
            echo 'NO EMAILS PROVIDED' .PHP_EOL;
            return;
        }

        $templateId = 'webdawe_vero_notification';
        $emailTemplate = Mage::getModel('core/email_template')->loadByCode($templateId);

        // Set Subject, Sender information and Message
        $emailTemplate->setTemplateSubject($subject);

        $emailTemplateVars =  array('message' => $message);

        $success = false;
        foreach ($emails as $email)
        {
            $success = true;

            if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            {
                continue;
            }
            try
            {
                if(!$emailTemplate->sendTransactional($templateId, 'support', $email, null, $emailTemplateVars))
                {
                    $messages = $this->setMessage($message, 'Notification failed :  ' . $subject);
                    $success = false;
                }
            }
            catch(Exception $error)
            {
                $messages = $this->setMessage($error->getMessage(), 'Notification failed :  ' . $subject);
                $success = false;
            }
        }

        return $success;
    }

}