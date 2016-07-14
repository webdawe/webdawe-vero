<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Model_Api_Client
{
	private $auth_token;

    /*Endpoints*/
	const IDENTIFY_ENDPOINT 		= 'https://api.getvero.com/api/v2/users/track.json';
	const REIDENTIFY_ENDPOINT 		= 'https://api.getvero.com/api/v2/users/reidentify.json';
	const UPDATE_ENDPOINT 			= 'https://api.getvero.com/api/v2/users/edit.json';
	const TAGS_ENDPOINT 			= 'https://api.getvero.com/api/v2/users/tags/edit.json';
	const UNSUBSCRIBE_ENDPOINT 		= 'https://api.getvero.com/api/v2/users/unsubscribe.json';
	const RESUBSCRIBE_ENDPOINT 		= 'https://api.getvero.com/api/v2/users/resubscribe.json';
	const TRACK_ENDPOINT			= 'https://api.getvero.com/api/v2/events/track.json';
    const DELETE_ENDPOINT           = 'https://api.getvero.com/api/v2/events/delete.json';
    /**
     * Webdawe_Vero_Model_Api_Client constructor.
     */
    public function __construct()
	{
        $this->authToken = Mage::helper('webdawe_vero/config')->getApiAuthToken();
    }


    /**
     * Identify Customer
     * @param $userId
     * @param $email
     * @param $data
     * @return mixed
     */
    public function identify($userId, $email, $data)
	{

        $endpoint = self::IDENTIFY_ENDPOINT;

        $request_data = array(
        'auth_token'        => $this->authToken,
        'id'                => $userId,
        'data'              => ($data == array() ? NULL : $data)
        );

        if ($email)
        {
        $request_data['email'] = $email;
        }

        return $this->_send($endpoint, $request_data);
    }

    /**
     * Reidentify Customer
     * @param $userId
     * @param $newUserId
     * @return mixed
     */
    public function reidentify($userId, $newUserId)
    {
        $endpoint = self::REIDENTIFY_ENDPOINT;

        $request_data = array(
        'auth_token'        => $this->authToken,
        'id'                => $userId,
        'new_id'            => $newUserId
        );

        return $this->_send($endpoint, $request_data, 'put');
    }

    /**
     * Update Customer
     * @param $userId
     * @param $changes
     * @return mixed
     */
    public function update($userId, $changes)
	{
        $endpoint = self::UPDATE_ENDPOINT;

        $request_data = array(
        'auth_token'        => $this->authToken,
        'id'                => $userId,
        'changes'           => $changes
        );

        return $this->_send($endpoint, $request_data, 'put');
    }

    /**
     * Add / Remove Tags
     * @param $userId
     * @param $add
     * @param $remove
     * @return mixed
     */
    public function tags($userId, $add, $remove)
	{
      
        $endpoint = self::TAGS_ENDPOINT;

        $request_data = array(
        'auth_token'        => $this->authToken,
        'id'                => $userId,
        'add'               => $add,
        'remove'            => $remove
        );

        return $this->_send($endpoint, $request_data, 'put');

    }

    /**
     * Unsubscriber Customer
     * @param $userId
     * @return mixed
     */
    public function unsubscribe($userId)
	{
        $endpoint = self::UNSUBSCRIBE_ENDPOINT;
        $request_data = array(
        'auth_token'        => $this->authToken,
        'id'                => $userId
        );

        return $this->_send($endpoint, $request_data);
    }

    /**
     * Resubscribe
     * @param $userId
     * @return mixed
     */
    public function resubscribe($userId)
	{
        $endpoint = self::RESUBSCRIBE_ENDPOINT;
        $request_data = array(
        'auth_token'        => $this->authToken,
        'id'                => $userId
        );

        return $this->_send($endpoint, $request_data);
    }

    /**
     * Delete Subscriber
     * @param $userId
     * @return mixed
     */
    public function delete($userId)
    {
        $endpoint = self::DELETE_ENDPOINT;
        $request_data = array(
            'auth_token'        => $this->authToken,
            'id'                => $userId
        );

        return $this->_send($endpoint, $request_data);
    }

    /**
     * Track Customer Behaviour
     * @param $eventName
     * @param $identity
     * @param $data
     * @param array $extras
     * @return mixed
     */
    public function track($eventName, $identity, $data, $extras = array())
	{
        $endpoint = self::TRACK_ENDPOINT;

        $request_data = array(
        'auth_token'        => $this->authToken,
        'identity'          => $identity,
        'event_name'        => $eventName,
        'data'              => ($data == array() ? NULL : $data),
        'extras'            => ($extras == array() ? NULL : $extras)
        );

        return $this->_send($endpoint, $request_data);
    }

    /**
     * Send to Vero
     * @param $endpoint
     * @param $requestData
     * @param string $requestType
     * @return mixed
     */
    private function _send($endpoint, $requestData, $requestType = 'post')
	{
        $requestData = json_encode($requestData);
        $headers  = array('Accept: application/json', 'Content-Type: application/json');

        try
        {
            $handle = @curl_init();
            curl_setopt($handle, CURLOPT_URL, $endpoint);
            curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

            if ($requestType == 'post')
            {
            curl_setopt($handle, CURLOPT_POST, true);
            }
            elseif ($requestType == 'put')
            {
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "PUT");
            }
            curl_setopt($handle, CURLOPT_POSTFIELDS, $requestData);

            $result = curl_exec($handle);

            $code   = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            curl_close($handle);

        }
        catch (Exception $error)
        {
            $result =  $error->getMessage();
        }

        return $result;

    }
}