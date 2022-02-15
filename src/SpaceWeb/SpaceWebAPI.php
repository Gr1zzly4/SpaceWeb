<?php

namespace SpaceWeb;

require_once "ClassAPI.php";

/**
 *Class made special for work with SpaceWeb API
 */
class SpaceWebAPI extends ClassAPI
{
    /**
     * @var string
     */
    private string $login;
    /**
     * @var string
     */
    private string $password;
    /**
     * @var string version of application
     */
    private string $version;
    /**
     * @var string version of using SON-RPC
     */
    private string $jsonrpc;
    /**
     * @var array for init CURLOPT_HTTPHEADER
     */
    private array $header;
    /**
     * @var string url for request without authorized
     */
    private string $URL_NOT_AUTHORIZED;
    /**
     * @var string url for work with domains
     */
    private string $URL_DOMAINS;

    /**Constructor
     * @param $login
     *
     * @param $password
     *
     * @param $version
     */
    public function __construct($login, $password, $version)
    {
        /**Init param for work*/
        $this->login = $login;
        $this->password = $password;
        $this->version = $version;
        $this->jsonrpc = "2.0";
        $this->URL_NOT_AUTHORIZED = "https://api.sweb.ru/notAuthorized";
        $this->URL_DOMAINS = "https://api.sweb.ru/domains";
        $this->header = array(
            'Content-type: application/json; charset=utf-8',
            'Accept: application/json',
        );
    }

    /**Return token for authorization or false in error case
     *
     * @return false|mixed
     */
    public function getToken()
    {
        $header = array(
            'Content-type: application/json; charset=utf-8',
            'Accept: application/json'
        );
        $data = array("method" => "getToken", "params" => array("login" => $this->login, "password" => $this->password));
        $message = $this->initMessage ($data);

        $result = $this->sendRequest ($message, $this->URL_NOT_AUTHORIZED, $header, "POST");
        if ($result === false) {
            error_log ("Empty answer", 0);
            return false;
        }
        if (isset($result["result"])) {
            return $result["result"];
        } else {
            error_log ("Code:" . $result["error"]["code"] . "| Message:" . $result["error"]["message"], 0);
            return false;
        }
    }

    /**
     * Return json for request
     *
     * @param $data array for init json body
     *
     * @return false|string(json)
     */
    private function initMessage($data)
    {
        return json_encode (array("jsonrpc" => $this->jsonrpc, "version" => $this->version, "method" => $data["method"], "params" => $data["params"]));
    }

    /**Adds random domain to authorization using a user token
     * Domain in the range of 0 : 2147483647 in $dir directory
     *
     * @param $token token for authorization
     *
     * @param $dir directory for domen create.If NULL create in home directory
     *
     * @return bool
     */
    public function addRandomDomain($token, $dir = null)
    {
        $data = array("method" => "move", "params" => array("domain" => mt_rand () . ".ru", "prolongType" => "manual", $dir));
        $message = $this->initMessage ($data);
        $header = $this->header;
        $header[] = "Authorization: Bearer $token";
        $result = $this->sendRequest ($message, $this->URL_DOMAINS, $header, "POST");

        if ($result === false) {
            error_log ("Empty answer", 0);
            return false;
        }

        if (!isset($result["result"])) {
            error_log ("Code:" . $result["error"]["code"] . "| Message:" . $result["error"]["message"], 0);
            return false;
        }

        if ($result["result"] == 1) {
            return true;
        } else {
            error_log ("Code:" . $result["error"]["code"] . "| Message:" . $result["error"]["message"], 0);
            return false;
        }
    }
}

?>