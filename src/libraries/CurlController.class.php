<?php

/**
 * cURL Wrapper for all your RESTful API needs.
 *
 * @author Monique <monique.dingding@gmail.com>
 * created August 7, 2018
 */

class CurlController {

    private $apiUrl;
    private $username;
    private $password;
    private $headers;

    public function __construct($url) {
        $this->apiUrl = $url;
    }

    /**
     * Convert array of parameters to send into query string
     * @param  array    $fields
     * @return string
     */
    private function buildQueryString($fields) {
        $queryString = '';
        $count = 0;

        foreach ($fields as $key => $value) {
            $count++;

            $query .= $key . '=' . urlencode($value);

            if ($count < count($fields)) {
                $query .= '&';
            }
        }

        return $queryString;
    }

    /**
     * Set basic authentication parameters which are normally just
     * the username and password
     *
     * @param string $username
     * @param string $password
     */
    public function setAuthentication($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Set HTTP headers to include in calling a resource
     * @param array $headers
     */
    public function setHeaders($headers = []) {
        $this->headers = $headers;
    }


    /**
    * Call an HTTP Request using cURL.
    *
    * @param  string    $url
    * @param  array     $header
    * @param  string    $requestType
    * @param  string    $fields
    *
    * @return json
    */
    public function curl($url, $requestType = "GET", $fields = NULL) {
        $ch = curl_init();

        $data = [
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FRESH_CONNECT => TRUE,
            CURLOPT_URL => $apiUrl . $url
        ];

        if (isset($this->headers)) {
            $data[CURLOPT_HTTPHEADER] = $this->headers;
        }

        if (isset($this->username) && isset($this->password)) {
            curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
        }

        if (isset($requestType) && $requestType === "POST") {
            $data[CURLOPT_POST] = TRUE;
            $data[CURLOPT_POSTFIELDS] = $fields;
        }

        curl_setopt_array($ch, $data);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Calls a GET resource.
     * @param  string   $url
     * @return json
     */
    public function get($url = '') {
        $ch = curl_init();

        $data = [
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FRESH_CONNECT => TRUE,
            CURLOPT_URL => $this->apiUrl  . '/' . $url
        ];

        if (isset($this->headers)) {
            $data[CURLOPT_HTTPHEADER] = $this->headers;
        }

        if (isset($this->username) && isset($this->password)) {
            curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
        }

        curl_setopt_array($ch, $data);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    /**
     * Call a method in POST
     * @param  string   $segment
     * @param  array    $postData
     * @param  string   $encodeBy
     * @return string
     */
    public function post($segment = '', $postData = [], $encodeBy = 'http') {
        $ch = curl_init();

        $url = (empty($segment))? $this->apiUrl: $this->apiUrl . '/' . $segment;

        $data = [
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FRESH_CONNECT => TRUE,
            CURLOPT_URL => $url
        ];

        if (isset($this->headers)) {
            $data[CURLOPT_HTTPHEADER] = $this->headers;
        }

        if (isset($this->username) && isset($this->password)) {
            curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
        }

        $postData = ($encodeBy === 'http')? http_build_query($postData): json_encode($postData);

        $data[CURLOPT_POST] = TRUE;
        $data[CURLOPT_POSTFIELDS] = $postData;

        curl_setopt_array($ch, $data);

        $response = curl_exec($ch);

        if ($response === FALSE) {
            die("Curl failed: " . curL_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}
?>
