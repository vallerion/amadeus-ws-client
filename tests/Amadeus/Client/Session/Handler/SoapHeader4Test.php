<?php
/**
 * amadeus-ws-client
 *
 * Copyright 2015 Amadeus Benelux NV
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package Test\Amadeus
 * @license https://opensource.org/licenses/Apache-2.0 Apache 2.0
 */

namespace Test\Amadeus\Client\Session\Handler;

use Amadeus\Client;
use Amadeus\Client\Params\SessionHandlerParams;
use Amadeus\Client\Session\Handler\SoapHeader4;
use Psr\Log\NullLogger;
use Test\Amadeus\BaseTestCase;

/**
 * SoapHeader4Test
 *
 * @package Test\Amadeus\Client\Session\Handler
 */
class SoapHeader4Test extends BaseTestCase
{

    public function testCanCreateSoapHeaders()
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $meth = self::getMethod($sessionHandler, 'createSoapHeaders');

        /** @var \SoapHeader[] $result */
        $result = $meth->invoke(
            $sessionHandler,
            ['sessionId' => null, 'sequenceNumber' => null, 'securityToken' => null],
            $sessionHandlerParams,
            'PNR_Retrieve',
            []
        );


        $expectedSoapHeaders = [];

        $this->assertCount(5, $result);
        foreach($result as $tmp) {
            $this->assertInstanceOf('\SoapHeader', $tmp);
        }

        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\Security', $result[0]->data);
        $this->assertInstanceOf('\SoapVar', $result[0]->data->UsernameToken);
        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\UsernameToken', $result[0]->data->UsernameToken->enc_value);


        $this->assertInternalType('string', $result[1]->data);
        $this->assertEquals('https://dummy.webservices.endpoint.com/SOAPADDRESS', $result[2]->data);
        $this->assertEquals('http://webservices.amadeus.com/PNRRET_11_3_1A', $result[3]->data);
        $this->assertInstanceOf('Amadeus\Client\Struct\HeaderV4\SecurityHostedUser', $result[4]->data);


        //TODO further validate soap headers?

        /*
         * Array
            (
                [0] => SoapHeader Object
                    (
                        [namespace] => http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd
                        [name] => Security
                        [data] => Amadeus\Client\Struct\HeaderV4\Security Object
                            (
                                [UsernameToken] => SoapVar Object
                                    (
                                        [enc_type] => 301
                                        [enc_value] => Amadeus\Client\Struct\HeaderV4\UsernameToken Object
                                            (
                                                [Username] => SoapVar Object
                                                    (
                                                        [enc_type] => 101
                                                        [enc_value] => DUMMYORIG
                                                        [enc_name] => Username
                                                        [enc_namens] => http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd
                                                    )

                                                [Password] => SoapVar Object
                                                    (
                                                        [enc_type] => 147
                                                        [enc_value] => <ns2:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wssusername-token-profile-1.0#PasswordDigest">CGBrO+mV+zgTb+A8YHrdOG1MOtg=</ns2:Password>
                                                        [enc_name] => Password
                                                    )

                                                [Nonce] => SoapVar Object
                                                    (
                                                        [enc_type] => 101
                                                        [enc_value] => D7vbVfxRu3UA+erbh5Mx/k4LW8s=
                                                        [enc_name] => Nonce
                                                        [enc_namens] => http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd
                                                    )

                                                [Created] => SoapVar Object
                                                    (
                                                        [enc_type] => 101
                                                        [enc_value] => 2016-01-15T10:22:05:298Z
                                                        [enc_name] => Created
                                                        [enc_namens] => http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd
                                                    )

                                            )

                                        [enc_name] => UsernameToken
                                        [enc_namens] => http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd
                                    )

                            )

                        [mustUnderstand] =>
                    )

                [1] => SoapHeader Object
                    (
                        [namespace] => http://www.w3.org/2005/08/addressing
                        [name] => MessageID
                        [data] => 1C02E9C5-2E87-9D87-CAB6-6494D341ABED
                        [mustUnderstand] =>
                    )

                [2] => SoapHeader Object
                    (
                        [namespace] => http://www.w3.org/2005/08/addressing
                        [name] => To
                        [data] => https://dummy.webservices.endpoint.com/SOAPADDRESS
                        [mustUnderstand] =>
                    )

                [3] => SoapHeader Object
                    (
                        [namespace] => http://www.w3.org/2005/08/addressing
                        [name] => Action
                        [data] => http://webservices.amadeus.com/PNRRET_11_3_1A
                        [mustUnderstand] =>
                    )

                [4] => SoapHeader Object
                    (
                        [namespace] => http://xml.amadeus.com/2010/06/Security_v1
                        [name] => AMA_SecurityHostedUser
                        [data] => Amadeus\Client\Struct\HeaderV4\SecurityHostedUser Object
                            (
                                [UserID] => Amadeus\Client\Struct\HeaderV4\UserId Object
                                    (
                                        [RequestorType] => U
                                        [PseudoCityCode] => BRUXX0000
                                        [POS_Type] => 1
                                        [AgentDutyCode] =>
                                    )

                            )

                        [mustUnderstand] =>
                    )

            )
*/

    }


    public function dataProviderGenerateDigest()
    {
        return [
            ["WBSPassword", "2013-01-11T09:41:03Z", base64_decode("PZgFvh5439plJpKpIyf5ucmXhNU="), "ic3AOJElVpvkz9ZBKd105Siry28="],
            ["AMADEUS", "2015-09-30T14:12:15Z", "secretnonce10111", "+LzcaRc+ndGAcZIXmq/N7xGes+k="],
            ["AMADEUS", "2015-09-30T14:15:11Z", base64_decode("NjPfanrqSdmXuFWgPoQlAsHOUbjOBg=="), "k/upHztkhZzrsqAKsOBUa45+j1w="],
            [base64_decode('VnA3ZjN1T0k='), "2016-01-15T10:16:41:553Z", base64_decode("ZjViYjdiZGJmOTMwY2FhYzQ5Zjk2NTEzMjhmYTdjMjUzN2NlMzI2ZQ=="), "CELeKeKpVxMV3xvxhfVvvl/ayIA="],
            [base64_decode('VnA3ZjN1T0k='), "2016-01-15T11:06:30:321Z", base64_decode("oD07B/1XeFLCsuKhB2NXdwtvMJY="), "hbgT0fpvlBCRF+J/EV/4XwCRxdw="],
        ];
    }


    /**
     * @dataProvider dataProviderGenerateDigest
     */
    public function testCanGenerateCorrectPasswordDigest($password, $creationString, $plainNonce, $expectedResult)
    {
        $sessionHandlerParams = $this->makeSessionHandlerParams();
        $sessionHandler = new SoapHeader4($sessionHandlerParams);

        $meth = self::getMethod($sessionHandler, 'generatePasswordDigest');

        $result = $meth->invoke(
            $sessionHandler,
            $password,
            $creationString,
            $plainNonce
        );

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return SessionHandlerParams
     */
    protected function makeSessionHandlerParams()
    {
        $wsdlpath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testfiles' . DIRECTORY_SEPARATOR . 'testwsdl.wsdl';

        $par = new SessionHandlerParams([
            'wsdl' => realpath($wsdlpath),
            'stateful' => false,
            'soapHeaderVersion' => Client::HEADER_V4,
            'receivedFrom' => 'unittests',
            'logger' => new NullLogger(),
            'authParams' => [
                'officeId' => 'BRUXX0000',
                'originatorTypeCode' => 'U',
                'originator' => 'DUMMYORIG',
                'organizationId' => 'DUMMYORG',
                'passwordLength' => 12,
                'passwordData' => 'dGhlIHBhc3N3b3Jk'
            ]
        ]);

        return $par;
    }
}