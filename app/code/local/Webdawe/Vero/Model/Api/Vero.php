<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Api_Vero
{
	private $client;
    const ERROR_STATUS = 450321;
    const SUCCESS_STATUS = 200;

    const SUCCESS_MESSAGE = 'Success.';
    const ERROR_MESSAGE = 'Error.';
    /**
     * Webdawe_Vero_Model_Api_Vero constructor.
     */
    public function __construct()
	{
        $this->client = new  Webdawe_Vero_Model_Api_Client();
	}

    /**
     * Identify Subscriber
     * @param $userId
     * @param string $email
     * @param array $data
     * @return mixed
     * @return mixed|object
     */
    public function identify($userId, $email = null, $data = array())
    {
        if (!$userId)
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' => self::ERROR_MESSAGE,'text' => 'Identify requires a user id');
            $result = json_encode($result);
        }
        else
        {
            $result = $this->client->identify($userId, $email, $data);
        }

        return json_decode($result);
    }

    /**
     * Re Identify Subscriber
     * @param $userId
     * @param $newUserId
     * @return mixed
     * @return mixed|object
     */
    public function reidentify($userId, $newUserId) {

        if (!$userId || !$newUserId)
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE ,'text' => 'Reidentify requires a user id AND a new user id');
            $result = json_encode($result);
        }
        else
        {
            $result = $this->client->reidentify($userId, $newUserId);
        }

        return json_decode($result);
    }

    /**
     * Update Subscriber Information
     * @param $userId
     * @param array $changes
     * @return mixed
     * @return mixed|object
     */
    public function update($userId, $changes = array()) {

        if (!$userId)
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE, 'text' => 'Update requires a user id');
            $result = json_encode($result);
        }
        else if (!count($changes))
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE, 'text' => 'Update requires changes data');
            $result = json_encode($result);
        }
        else
        {
            $result = $this->client->update($userId, $changes);
        }

        return json_decode($result);
    }

    /**
     * Add/ Remove Tags for subscriber
     * @param $userId
     * @param array $add
     * @param array $remove
     * @return mixed
     * @return mixed|object
     */
    public function tags($userId, $add = array(), $remove = array())
    {
        if (!$userId)
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE, 'text' => 'Tags requires a user id');
            $result = json_encode($result);
        }
        else if (!count($add) && !count($remove))
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE, 'text' => 'Update requires either add or remove param');
            $result = json_encode($result);
        }
        else
        {
            $result = $this->client->tags($userId, $add, $remove);
        }

        return json_decode($result);
    }

    /**
     * Unsubscribe Subscriber
     * @param $userId
     * @return mixed
     */
    public function unsubscribe($userId)
    {
        if (!$userId)
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE, 'text' => 'Unsubscribe requires a user id');
            $result = json_encode($result);
        }
        else
        {
            $result = $this->client->unsubscribe($userId);
        }

        return json_decode($result);
    }

    /**
     * Re Subscriber Subscriber
     * @param $userId
     * @return mixed
     */
    public function resubscribe($userId)
    {
        if (!$userId)
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE, 'text' => 'Resubscribe requires a user id');
            $result = json_encode($result);
        }
        else
        {
            $result = $this->client->resubscribe($userId);
        }

        return json_encode($result);

    }

    /**
     * Delete Subscriber
     * @param $userId
     * @return mixed
     */
    public function delete($userId)
    {
        if (!$userId)
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE, 'text' => 'Delete requires a user id');
            $result = json_encode($result);
        }
        else
        {
            $result = $this->client->delete($userId);
        }

        return json_encode($result);

    }


    /**
     * Track Subscriber Event
     * @param $eventName
     * @param array $identity
     * @param array $data
     * @param array $extras
     * @return string
     */
    public function track($eventName, $identity = array(), $data = array(), $extras = array())
    {
        if (!$eventName)
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE, 'text' => 'Track requires an event name');
            $result = json_encode($result);
        }
        else if (($identity == array()) || ((gettype($identity) == 'array') && (!$identity['id'])))
        {
            $result = array('status'=> self::ERROR_STATUS,  'message' =>  self::ERROR_MESSAGE, 'text' => 'Update requires an identity profile with at least an id property');
            $result = json_encode($result);
        }
        else
        {
            $result = $this->client->track($eventName, $identity, $data, $extras);
        }

        return json_encode($result);
    }
}