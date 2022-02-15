<?php

namespace SpaceWeb;
/** This class made for work with API, send request and get answer in json format
 */
class ClassAPI
{
    /**Send request and return associative array or fasle in error case
     *
     * @param $data string(json) message POST | PUT | GET request
     *
     * @param $url string url where to send the request
     *
     * @param $header array for init CURLOPT_HTTPHEADER
     *
     * @param $method string POST | PUT | GET set method type, if set something else, stop and return false
     *
     * @return false|array
     */
    protected function sendRequest($data, $url, $header, $method)
    {

        try {
            json_encode ($data);
            if (json_last_error ()) {
                throw new Exception("json encode error->" . json_last_error_msg ());
            }
        } catch (Exception $e) {
            echo 'Caught exception->', $e->getMessage (), "\n";
            return false;
        }

        try {
            if (!is_array ($header)) {
                throw new Exception("header init error");
            }
        } catch (Exception $e) {
            echo 'Caught exception->', $e->getMessage (), "\n";
            return false;
        }

        $curl = curl_init ();

        switch ($method) {
            case "POST":
                curl_setopt ($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt ($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt ($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    curl_close ($curl);
                return false;
        }

        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        try {
            $result = curl_exec ($curl);
            if ($result === false) {
                throw new Exception('Curl error: ' . curl_error ($curl));
            } else {
                $result = json_decode ($result, true);
                if (json_last_error ()) {
                    throw new Exception("json encode error->" . json_last_error_msg ());
                }
            }

        } catch (Exception $e) {
            echo 'Caught exception->', $e->getMessage (), "\n";
            $result = false;

        }
        curl_close ($curl);

        return $result;
    }
}