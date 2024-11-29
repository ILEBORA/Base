<?php
namespace ILEBORA\Services;

abstract class BoraService
{
    private $onSuccess;
    private $onFailure;
    private $backLink;
    
    private $apiKey;
    private $userID;
    private $displayName;
    
    private $apiUrl = 'https://api.boracore.co.ke/v';
    private $apiVersion = '1.1'; // Default version

    public function __construct()
    {
        // Load environment variables directly in the constructor
        $this->apiKey = $_ENV['BORA_SMS_APIKEY'];
        $this->userID = $_ENV['BORA_SMS_USERID'];
        $this->displayName = $_ENV['BORA_SMS_DISPLAYNAME'];
        $this->onSuccess = $_ENV['BORA_SMS_SUCCESS_URL'];
        $this->onFailure = $_ENV['BORA_SMS_FAILURE_URL'];

        if (empty($this->apiKey) || empty($this->userID) || empty($this->displayName)) {
            throw new \Exception('API key, User ID, and Display Name are required.');
        }
    }

    public function setApiVersion($apiVersion = 1.1)
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function setUserID($userID)
    {
        $this->userID = $userID;
        return $this;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function setOnSuccess($onSuccess = '')
    {
        $this->onSuccess = $onSuccess;
        return $this;
    }

    public function setOnFailure($onFailure = '')
    {
        $this->onFailure = $onFailure;
        return $this;
    }


    /**
     * Helper method to make HTTP POST requests.
     *
     * @param string $endpoint
     * @param array $payload
     * @return array
     */
    protected function makeRequest(string $endpoint, array $payload): array
    {
        
        $data_string = json_encode($payload);

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . base64_encode($this->userID . ":" . $this->apiKey)
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $endpoint);        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new \Exception("Curl Error: " . $error);
        }

        return json_decode($response, true) ?? [];
    }
    
}
