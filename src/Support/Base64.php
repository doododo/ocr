<?php

/*
 * This file is part of the doododo/ocr.
 *
 * (c) doododo <saybye720@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doododo\OCR\Support;

use Exception;
use SplFileInfo;
use GuzzleHttp\Client;
use InvalidArgumentException;

class Base64
{
    /**
     * 图片转为base64格式
     *
     * @param string $image
     *
     * @return string
     */
    protected function base64(string $image): string
    {
        if (empty($image)) {
            throw new InvalidArgumentException('图片不能为空');
        }

        return base64_encode($this->getContent($image));
    }

    /**
     * 获取图片内容
     *
     * @param string $image
     *
     * @return mixed
     */
    private function getContent(string $image)
    {
        // URL
        if (filter_var($image, FILTER_VALIDATE_URL) !== false) {
            try {
                return (new Client)->get($image)->getBody()->getContents();
            } catch (Exception $e) {
                throw new InvalidArgumentException('无效的URL');
            }
        }

        // SplFileInfo
        if ($image instanceof SplFileInfo) {
            return file_get_contents($image);
        }

        try {
            return file_get_contents($image);
        } catch (\Exception $e) {
            throw new InvalidArgumentException('无效的图片');
        }
    }

    public static function __callStatic($func, $arguments)
    {
        return (new static)->$func(...$arguments);
    }
}
