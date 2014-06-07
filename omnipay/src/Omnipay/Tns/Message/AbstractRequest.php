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

/**
 * Tns Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
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
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
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

    public function send()

    {


        $httpRequest = $this->httpClient->post(

            $this->getEndpoint(),
            null,
            null
        );




        $httpRequest->setAuth('merchant.TESTWHIZBRMAA01',$this->getApiKey());
        $httpResponse=$httpRequest->send();
//echo'<pre>';
//print_r($httpResponse->getBody());exit;

        return $this->response = new Response($this, $httpResponse->json());


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
        $data['reference'] =66655;

        return $data;
    }
    protected function getTransactionData(){
        $this->getCard()->validate();
        $data = array();
        $data['amount'] = $this->getAmountInteger();
        $data['currency'] = 'EUR';
        $data['reference'] =66655;
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
        $data['year'] = $this->getCard()->getExpiryYear();
        return $data;

    }

}
