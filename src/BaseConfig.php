<?php

/*
 * This file is part of the doododo/ocr.
 *
 * (c) doododo <saybye720@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doododo\OCR;

use GuzzleHttp\Client;
use Doododo\OCR\Support\Base64;
use Doododo\OCR\Support\Config;
use Doododo\OCR\Exception\Exception;

class BaseConfig
{
    const OCR_URL_ID_CARD = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_idcardocr';
    const OCR_URL_DRIVER_LICENSE = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_driverlicenseocr';
    const OCR_URL_GENERIC = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_generalocr';
    const OCR_URL_BUSINESS_LICENSE = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_bizlicenseocr';
    const OCR_URL_CREDIT_CARD = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_creditcardocr';
    const OCR_URL_HAND_WRITE = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_handwritingocr';
    const OCR_URL_CAR_PLATE = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_plateocr';
    const OCR_URL_BUSINESS_CARD = 'https://api.ai.qq.com/fcgi-bin/ocr/ocr_bcocr';

    // 营业执照
    const BUSINESS_FIELDS = [
        'business_license' => '注册号',
        'legal_person' => '法定代表人',
        'company_code' => '公司名称',
        'company_address' => '地址',
        'business_time' => '营业期限',
    ];

    // 银行卡
    const CREDIT_FIELDS = [
        'card_no' => '卡号',
        'card_type' => '卡类型',
        'card_name' => '卡名字',
        'bank' => '银行信息',
        'card_time' => '有效期',
    ];

    // 行驶证
    const REGISTER_FIELDS = [
        'plate_no' => '车牌号码',
        'owner' => '所有人',
        'address' => '住址',
        'use_character' => '使用性质',
        'model' => '品牌型号',
        'vin' => '识别代码',
        'engine_no' => '发动机号',
        'register_date' => '注册日期',
        'issue_date' => '发证日期',
        'stamp' => '红章',
    ];

    // 驾照
    const DRIVER_FIELDS = [
        'driver_no' => '证号',
        'name' => '姓名',
        'sex' => '性别',
        'nationality' => '国籍',
        'address' => '住址',
        'brithday' => '出生日期',
        'issue_date' => '领证日期',
        'class' => '准驾车型',
        'start_date' => '起始日期',
        'end_date' => '有效日期',
        'stamp' => '红章',
    ];

    // 车牌
    const PLATE_FIELDS = [
        'plate_no' => '车牌',
    ];

    // 名片
    const BUSINESS_CARD_FIELDS = [
        'name' => '姓名',
        'position' => '职位',
        'company' => '公司',
        'address' => '地址',
        'email' => '邮箱',
        'mobile' => '手机',
        'wechat' => '微信',
    ];

    protected $appId;
    protected $appKey;

    public function __construct(array $config)
    {
        $config = new Config($config);
        $this->appId = $config->get('app_id');
        $this->appKey = $config->get('app_key');
    }

    /**
     * 发送请求
     *
     * @param string $url     请求地址
     * @param string $image   图片
     * @param array  $options 参数
     *
     * @return mixed
     */
    protected function request(string $url, string $image, array $options = [])
    {
        $params = array_merge($options, [
            'image' => Base64::base64($image),
        ]);
        $params = $this->sign($params);

        $response = (new Client())->post($url, [
            'form_params' => $params,
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        if ($result['ret'] !== 0) {
            throw new Exception(sprintf('识别失败：%s', $result['msg']));
        }

        return $result['data'];
    }

    /**
     * 签名.
     *
     * @return array
     */
    protected function sign(array $params = [])
    {
        $params = array_merge($params, [
            'app_id' => $this->appId,
            'time_stamp' => time(),
            'nonce_str' => uniqid(),
        ]);
        ksort($params);

        $params['sign'] = strtoupper(md5(http_build_query(array_merge($params, [
            'app_key' => $this->appKey,
        ]))));

        return $params;
    }

    /**
     * 格式化输出.
     *
     * @param array $list   要格式化的数组
     * @param array $format 格式化数组模板
     *
     * @return array
     */
    protected function formatResponse(array $list, array $format)
    {
        $formatFlip = array_flip($format);
        $result = [];

        foreach ($list as $value) {
            if (!isset($formatFlip[$value['item']])) {
                continue;
            }
            $result[$formatFlip[$value['item']]] = $value['itemstring'];
        }

        return $result;
    }

    /**
     * 通用格式化输出.
     *
     * @param array $list 要格式化的数组
     *
     * @return array
     */
    protected function formatResponseGeneric(array $list)
    {
        $result = [];
        foreach ($list as $value) {
            $result[] = $value['itemstring'];
        }

        return $result;
    }
}
