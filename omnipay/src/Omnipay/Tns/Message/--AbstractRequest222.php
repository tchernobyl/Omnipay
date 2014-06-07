<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Tns\Message;

use Omnipay\AuthorizeNet\Message\AIMResponseTest;
use Omnipay\Tns\tns\Client;
use Omnipay\Tns\tns\Connection;
/**
 * Tns Abstract Request
 */
abstract class AbstractRequest2 extends \Omnipay\Common\Message\AbstractRequest
{
    protected $curlObj;
    protected $endpoint = 'https://secure.ap.tnspayments.com';

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }
    public function getApiVersion()
    {
        return $this->getParameter('apiVersion');
    }

    public function setApiVersion($value)
    {
        return $this->setParameter('apiVersion', $value);
    }
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }
    /**
     * @deprecated
     */
    public function getCardToken()
    {
        return $this->getParameter('token');
    }

    /**
     * @deprecated
     */
    public function setCardToken($value)
    {
        return $this->setParameter('token', $value);
    }

    abstract public function getEndpoint();

    public function getHttpMethod()
    {
        return 'POST';
    }
    protected function ConfigureCurlProxy($merchantObj) {
        // If proxy server is defined, set cURL options
        if ($merchantObj->GetProxyServer() != "") {
            curl_setopt($this->curlObj, CURLOPT_PROXY, $merchantObj->GetProxyServer());
            curl_setopt($this->curlObj, $merchantObj->GetProxyCurlOption(), $merchantObj->GetProxyCurlValue());
        }
        // If proxy authentication is defined, set cURL option
        if ($merchantObj->GetProxyAuth() != "")
            curl_setopt($this->curlObj, CURLOPT_PROXYUSERPWD, $merchantObj->GetProxyAuth());
    }

    protected function ConfigureCurlCerts($merchantObj) {

        if ($merchantObj->GetCertificatePath() != "")
            curl_setopt($this->curlObj, CURLOPT_CAINFO, $merchantObj->GetCertificatePath());

        curl_setopt($this->curlObj, CURLOPT_SSL_VERIFYPEER, $merchantObj->GetCertificateVerifyPeer());
        curl_setopt($this->curlObj, CURLOPT_SSL_VERIFYHOST, $merchantObj->GetCertificateVerifyHost());

    }
    public function send()

    {

        $configArray = array();

        $configArray["certificateVerifyPeer"] = TRUE;

        $configArray["certificateVerifyHost"] = 2;

        $configArray["gatewayUrl"] = "https://secure.ap.tnspayments.com/api/rest/version/15/merchant/TESTWHIZBRMAA01/order/200/transaction/115330221220";

        $configArray["merchantId"] = "TESTWHIZBRMAA01";

        $configArray["apiUsername"] = "merchant.TESTWHIZBRMAA01";

        $configArray["password"] = "ea5aba403160d2921137578f327eee7b";

        $configArray["debug"] = TRUE;

        $configArray["version"] = "15";

        $merchantObj = new Client($configArray);

        $this->curlObj = curl_init();

        // configure cURL proxy options by calling this function
        $this->ConfigureCurlProxy($merchantObj);

        // configure cURL certificate verification settings by calling this function
        $this->ConfigureCurlCerts($merchantObj);

        curl_setopt($this->curlObj, CURLOPT_POST, 1);

        curl_setopt($this->curlObj, CURLOPT_POSTFIELDS, json_encode($this->getData()));

        curl_setopt($this->curlObj, CURLOPT_HTTPHEADER, array("Content-Length: " . strlen(json_encode($this->getData()))));
        curl_setopt($this->curlObj, CURLOPT_HTTPHEADER, array("Content-Type: Application/json;charset=UTF-8"));
        curl_setopt($this->curlObj, CURLOPT_URL, $merchantObj->GetGatewayUrl());
        curl_setopt($this->curlObj, CURLOPT_USERPWD, $merchantObj->GetApiUsername() . ":" . $merchantObj->GetPassword());


        curl_setopt($this->curlObj, CURLOPT_RETURNTRANSFER, TRUE);

        // this is used for debugging only. This would not be used in your integration, as DEBUG should be set to FALSE
        if ($merchantObj->GetDebug()) {
            curl_setopt($this->curlObj, CURLOPT_HEADER, TRUE);
            curl_setopt($this->curlObj, CURLINFO_HEADER_OUT, TRUE);
        }

        $response = curl_exec($this->curlObj);
        echo"<h3>responssdfse </h3><pre>";


        if (curl_error($this->curlObj))
            $response = "cURL Error: " . curl_errno($this->curlObj) . " - " . curl_error($this->curlObj);
        echo"<h3>request </h3><pre>";

        print_r($response);exit;
//        return $response;
//
//        return $this->response = new Response($this, $response->json());


//        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData())->send();
//        $this->response = new AIMResponseTest($this, $httpResponse->getBody()->json());
    }

    protected function getCardData()
    {
        $this->getCard()->validate();

        $data = array();
        $data['number'] = $this->getCard()->getNumber();
        $data['exp_month'] = $this->getCard()->getExpiryMonth();
        $data['exp_year'] = $this->getCard()->getExpiryYear();
        $data['cvc'] = $this->getCard()->getCvv();
        $data['name'] = $this->getCard()->getName();
        $data['address_line1'] = $this->getCard()->getAddress1();
        $data['address_line2'] = $this->getCard()->getAddress2();
        $data['address_city'] = $this->getCard()->getCity();
        $data['address_zip'] = $this->getCard()->getPostcode();
        $data['address_state'] = $this->getCard()->getState();
        $data['address_country'] = $this->getCard()->getCountry();


        return $data;
    }
    protected function getOrderData(){

        $data = array();
        $data['reference'] =8585;

        return $data;
    }
    protected function getTransactionData(){
        $this->getCard()->validate();
        $data = array();
        $data['amount'] = $this->getAmountInteger();
        $data['currency'] = 'AUD';
        $data['reference'] =8585;
        return $data;
    }
    protected function getSourceOfFundsData(){

        $data = array();
        $data['type'] = "CARD";
        $data['provided'] = $this->getAchMaJa();

        return $data;
    }
    protected function getAchMaJa(){

        $data=array();
        $data['card']=$this->getCardProviderData();
        return $data;

}
    protected function getCardProviderData(){
        $this->getCard()->validate();
        $data = array();
        $data['number'] = $this->getCard()->getNumber();
        $data['expiry'] = $this->getCardExpiryData();
        $data['securityCode'] = 120;
        return $data;

    }
    protected function getCardExpiryData(){
        $this->getCard()->validate();
        $data = array();
        $data['month'] = $this->getCard()->getExpiryMonth();
        $data['year'] = 17;
        return $data;

    }

}
