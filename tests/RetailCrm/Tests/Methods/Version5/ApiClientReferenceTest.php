<?php

/**
 * PHP version 5.4
 *
 * API client references test class
 *
 * @category RetailCrm
 * @package  RetailCrm
 */

namespace RetailCrm\Tests\Methods\Version5;

use RetailCrm\Test\TestCase;

/**
 * Class ApiClientReferenceTest
 *
 * @category RetailCrm
 * @package  RetailCrm
 */
class ApiClientReferenceTest extends TestCase
{
    /**
     * @group reference_v5
     * @dataProvider getListDictionaries
     * @param $name
     */
    public function testList($name)
    {

        $client = static::getApiClient();

        $method = $name . 'List';
        echo $method;
        $response = $client->request->$method();

        /* @var \RetailCrm\Response\ApiResponse $response */

        static::assertInstanceOf('RetailCrm\Response\ApiResponse', $response);
        static::assertTrue($response->isSuccessful());
        static::assertTrue(isset($response[$name]));
        static::assertTrue(is_array($response[$name]));
    }

    /**
     * @group reference_v5
     * @dataProvider getEditDictionaries
     * @expectedException \InvalidArgumentException
     *
     * @param $name
     */
    public function testEditingException($name)
    {

        $client = static::getApiClient();

        $method = $name . 'Edit';
        $client->request->$method([]);
    }

    /**
     * @group reference_v5
     * @dataProvider getEditDictionaries
     *
     * @param $name
     */
    public function testEditing($name)
    {

        $client = static::getApiClient();

        $code = 'dict-' . strtolower($name) . '-' . time();
        $method = $name . 'Edit';
        $params = [
            'code' => $code,
            'name' => 'Aaa' . $code,
            'active' => false
        ];
        if ($name == 'statuses') {
            $params['group'] = 'new';
        }

        $response = $client->request->$method($params);
        /* @var \RetailCrm\Response\ApiResponse $response */

        static::assertTrue(in_array($response->getStatusCode(), [200, 201]));

        $response = $client->request->$method([
            'code' => $code,
            'name' => 'Bbb' . $code,
            'active' => false
        ]);

        static::assertTrue(in_array($response->getStatusCode(), [200, 201]));
    }

    /**
     * @group reference_v5
     * @group site
     */
    public function testSiteEditing()
    {
        $name = 'sites';

        $client = static::getApiClient();

        $code = 'dict-' . strtolower($name) . '-' . time();
        $method = $name . 'Edit';
        $params = [
            'code' => $code,
            'name' => 'Aaa',
            'active' => false
        ];

        $response = $client->request->$method($params);
        /* @var \RetailCrm\Response\ApiResponse $response */

        static::assertEquals(400, $response->getStatusCode());

        if ($code == $client->request->getSite()) {
            $method = $name . 'Edit';
            $params = [
                'code' => $code,
                'name' => 'Aaa' . time(),
                'active' => false
            ];

            $response = $client->request->$method($params);
            static::assertEquals(200, $response->getStatusCode());
        }
    }

    /**
     * @group reference_v5
     */
    public function testUnitsEditing()
    {
        $client = static::getApiClient();

        $unit = [
            'code' => 'test',
            'name' => 'Test',
            'sym' => 'tst'
        ];

        $response = $client->request->unitsEdit($unit);

        static::assertTrue(in_array($response->getStatusCode(), [200, 201]));
    }

    /**
     * @group reference_v5
     * @expectedException \InvalidArgumentException
     */
    public function testUnitsEditingFail()
    {
        $client = static::getApiClient();

        $unit = [
            'name' => 'Test',
            'sym' => 'tst'
        ];

        $client->request->unitsEdit($unit);
    }

    /**
     * @return array
     */
    public function getListDictionaries()
    {
        return [
            ['deliveryServices'],
            ['deliveryTypes'],
            ['orderMethods'],
            ['orderTypes'],
            ['paymentStatuses'],
            ['paymentTypes'],
            ['productStatuses'],
            ['statusGroups'],
            ['statuses'],
            ['sites'],
            ['stores'],
            ['couriers'],
            ['costs'],
            ['units']
        ];
    }

    /**
     * @return array
     */
    public function getEditDictionaries()
    {
        return [
            ['deliveryServices'],
            ['deliveryTypes'],
            ['orderMethods'],
            ['orderTypes'],
            ['paymentStatuses'],
            ['paymentTypes'],
            ['productStatuses'],
            ['statuses'],
        ];
    }
}
