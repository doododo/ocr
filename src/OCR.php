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

class OCR extends BaseConfig
{
    /**
     * 身份证识别.
     *
     * @param string $image 原始图片的base64编码数据（原图大小上限1MB，支持JPG、PNG、BMP格式）
     * @param int    $type  身份证图片类型，0-正面，1-反面
     *
     * @return array
     */
    public function idCard(string $image, int $type = 0)
    {
        $options['card_type'] = $type;

        $result = $this->request(self::OCR_URL_ID_CARD, $image, $options);

        return array_diff_key($result, ['frontimage' => '', 'backimage' => '']);
    }

    /**
     * 行驶证、驾驶证识别.
     *
     * @param int $type 识别类型，0-行驶证，1-驾驶证
     *
     * @return array
     */
    public function driverLicense(string $image, int $type = 0)
    {
        $options['type'] = $type;
        $result = $this->request(self::OCR_URL_DRIVER_LICENSE, $image, $options);
        $format = $type ? self::DRIVER_FIELDS : self::REGISTER_FIELDS;

        return $this->formatResponse($result['item_list'], $format);
    }

    /**
     * 通用识别.
     *
     * @return array
     */
    public function generic(string $image)
    {
        $result = $this->request(self::OCR_URL_GENERIC, $image);

        return $this->formatResponseGeneric($result['item_list']);
    }

    /**
     * 营业执照识别.
     *
     * @param array $format
     *
     * @return array
     */
    public function businessLicense(string $image, $format = self::BUSINESS_FIELDS)
    {
        $result = $this->request(self::OCR_URL_BUSINESS_LICENSE, $image);

        return $this->formatResponse($result['item_list'], $format);
    }

    /**
     * 银行卡识别.
     *
     * @param array $format
     *
     * @return array
     */
    public function creditCard(string $image, $format = self::CREDIT_FIELDS)
    {
        $result = $this->request(self::OCR_URL_CREDIT_CARD, $image);

        return $this->formatResponse($result['item_list'], $format);
    }

    /**
     * 手写体识别.
     *
     * @return array
     */
    public function handWrite(string $image)
    {
        $result = $this->request(self::OCR_URL_HAND_WRITE, $image);

        return $this->formatResponseGeneric($result['item_list']);
    }

    /**
     * 车牌识别.
     *
     * @param array $format
     *
     * @return array
     */
    public function plateLicense(string $image, $format = self::PLATE_FIELDS)
    {
        $result = $this->request(self::OCR_URL_CAR_PLATE, $image);

        return $this->formatResponse($result['item_list'], $format);
    }

    /**
     * 名片识别.
     *
     * @param array $format
     *
     * @return array
     */
    public function businessCard(string $image, $format = self::BUSINESS_CARD_FIELDS)
    {
        $result = $this->request(self::OCR_URL_BUSINESS_CARD, $image);

        return $this->formatResponse($result['item_list'], $format);
    }
}
