<?php
require_once(MARKETSHARP_DIRECTORY . 'src/libraries/CurlController.class.php');
require_once(MARKETSHARP_DIRECTORY . 'src/exceptions/MarketSharpException.class.php');
require_once(MARKETSHARP_DIRECTORY . 'src/config/marketsharp-env.php');

/**
 * MarketSharp Api Class Wrapper
 *
 * @author Monique <monique.dingding@gmail.com>
 * created November 21, 2018
 */
class MarketSharpApi {
    private $database;
    private $apiUrl;
    private $envValues;
    private $hasEnv;
    private $testMode;

    public function __construct ($hasEnvFile = FALSE, $testMode = TRUE) {
        $this->hasEnv = $hasEnvFile;
        $this->testMode = $testMode;
    }

    /**
     * Setter method for API url
     *
     * @param string  $url
     */
    public function setApiUrl ($url = NULL) {
        if ($this->hasEnv) {
            $url = MARKETSHARP_API_URL;
        }

        $this->apiUrl = $url;
    }

    /**
     * SETS ENVIRONMENT PARAMETERS
     * If using an ENV file, create a `marketsharp-env.php` file to store in
     * your environment variables.
     * ALTERNATIVELY, you may integrate phpdotenv to be able to use .env
     *
     * @param int       $coy
     * @param string    $formId
     */
    public function setEnv ($coy = NULL, $formId = NULL, $leadCaptureName = NULL) {
        $testForm = $this->testMode;

        if ($this->hasEnv) {
            $coy = MARKETSHARP_API_COY;
            $formId = MARKETSHARP_API_FORMID;
            $testForm = MARKETSHARP_API_TESTMODE;
            $leadCaptureName = MARKETSHARP_API_LEADCAPTURENAME;
        }

        $this->envValues = [
            'coy' => $coy,
            'leadCaptureName' => $leadCaptureName,
            'formId' => $formId,
            'testForm' => $testForm,
        ];

    }

    /**
     * Getter method for api url
     * @return array  $apiUrl
     */
    public function getApiUrl () {
        if (!isset($this->apiUrl)) {
            throw new MarketSharpException('API URL not set.');
        }

        return $this->apiUrl;
    }

    /**
     * Getter method for environment variables
     * @return array  $envValues
     */
    public function getEnvValues () {
        if (!isset($this->envValues)) {
            throw new MarketSharpException('Environment variables not set.');
        }

        return $this->envValues;
    }

    /**
     * Submit a lead to MarketSharp CRM
     * @param  array $data
     * @return JSON
     */
    public function sendLead ($formData) {
        $apiUrl = $this->getApiUrl();
        $envData = $this->getEnvValues();
        
        $data = array_merge($envData, $formData);

        $curl = new CurlController($apiUrl);
        $result = $curl->post('', $data);

        return $result;
    }
}


?>
