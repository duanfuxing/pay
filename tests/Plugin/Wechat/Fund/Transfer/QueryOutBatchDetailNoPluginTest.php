<?php

namespace duan617\Pay\Tests\Plugin\Wechat\Fund\Transfer;

use GuzzleHttp\Psr7\Uri;
use duan617\Pay\Exception\Exception;
use duan617\Pay\Exception\InvalidParamsException;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\Wechat\Fund\Transfer\QueryOutBatchDetailNoPlugin;
use duan617\Pay\Provider\Wechat;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class QueryOutBatchDetailNoPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\Wechat\Fund\Transfer\QueryOutBatchDetailNoPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryOutBatchDetailNoPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_batch_no' => '123', 'out_detail_no' => '456']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/transfer/batches/out-batch-no/123/details/out-detail-no/456'), $radar->getUri());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testNormalNoBatchId()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_detail_no' => '456']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalNoDetailId()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_batch_no' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['out_batch_no' => '123', 'out_detail_no' => '456']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/partner-transfer/batches/out-batch-no/123/details/out-detail-no/456'), $radar->getUri());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testPartnerNoBatchId()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['out_detail_no' => '456']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPartnerNoDetailId()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['out_batch_no' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }
}
