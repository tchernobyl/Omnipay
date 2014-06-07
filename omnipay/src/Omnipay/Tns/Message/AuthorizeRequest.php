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

/**
 * Tns Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = array();
        $data['apiOperation']='PAY';
        $data['sourceOfFunds']=$this->getSourceOfFundsData();
        $data['transaction']=$this->getTransactionData();
        $data['order']=$this->getOrderData();
//        $data['apiOperation']='PAY';
//        $data['transaction']= $this->getTransactionData();
//        $data['amount'] = $this->getAmountInteger();
//        $data['currency'] = strtolower($this->getCurrency());
//        $data['description'] = $this->getDescription();
//        $data['capture'] = 'false';
//        if ($this->getCardReference()) {
//            $data['customer'] = $this->getCardReference();
//        } elseif ($this->getToken()) {
//            $data['card'] = $this->getToken();
//        } elseif ($this->getCard()) {
//            $data['card'] = $this->getCardData();
//        } else {
//            // one of cardReference, token, or card is required
//            $this->validate('card');
//        }

        return $data;
    }

    public function getEndpoint()
    {
        $gatewayUrl = $this->endpoint. '/api/' .'rest' . '/version/' . $this->getApiVersion();

        $gatewayUrl .= '/merchant/TESTWHIZBRMAA01/order/66655/transaction/66655';



        return $gatewayUrl;
    }



}
