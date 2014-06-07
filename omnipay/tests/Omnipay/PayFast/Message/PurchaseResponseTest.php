<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayFast\Message;

use Omnipay\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testConstruct()
    {
        $data = array('test' => '123');
        $response = new PurchaseResponse($this->getMockRequest(), $data, 'https://example.com/');

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertSame($data, $response->getData());

        $this->assertSame('https://example.com/', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertSame($data, $response->getRedirectData());
    }
}
