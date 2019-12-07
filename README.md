<h1 align="center">Tencent AI image OCR</h1>

# 支持

- [身份证识别](#id-card)
- [行驶证/驾驶证识别](#driver-license)
- [通用识别](#generic)
- [营业执照识别](#business-license)
- [银行卡识别](#credit-card)
- [手写体识别](#handwrite)
- [车牌识别](#plate-license)
- [名片识别](#business-card)

# 环境需求

 - PHP > 7.1
 - [composer](https://getcomposer.org/)

# 安装

```bash
composer require doododo/ocr
```

# 使用

> 注册 [腾讯 AI 控制台](https://ai.qq.com) 后，可得到[APP_ID 及 APP_KEY](https://ai.qq.com/console/home)

## 基本使用

**$image 参数支持**

- 文件路径（绝对路径）
- `SplFileInfo` 对象
- 在线图片地址

```php
$ocr = new \Doododo\OCR\OCR([
    'app_id' => 'xxxx',
    'app_key' => 'xxxx'
]);

$ocr->idCard($image); // 注意绝对路径或URL
```

## Laravel 使用

```php

# 1. 创建 `ocr.php` 文件

return [
    'app_id' => 'xxxx',
    'app_key' => 'xxxx'
];

# 2. 修改 `AppServiceProvider.php` 的 `boot()` 添加
use Doododo\OCR\OCR;

$this->app->singleton(OCR::class, function () {
    return new OCR(config('ocr'));
});

$this->app->alias(OCR::class, 'ocr');

# 3. 使用
$ocr = app('ocr');
$ocr->idCard($image); // 注意绝对路径或URL
```

# 文档

## 身份证识别
<a name="id-card"></a>

```php
// 身份证正面
$ocr->idCard($image);
// 身份证反面
$ocr->idCard($image, 1);
```

返回值（Array）

| 参数名称 | 描述 |
|-------|----|
| name | 姓名 |
| sex | 性别 |
| nation | 民族 |
| birth |出生日期 |
| address | 住址 |
| id | 身份证号码 |
| authority | 发证机关 |
| valid_date | 身份证有效期 |

eg.
```json
{
    "name": "艾米",
    "sex": "女",
    "nation": "汉",
    "birth": "1986/4/23",
    "address": "上海徐汇区田林路397号腾云大厦6F",
    "id": "310104198604230289",
    "authority": "",
    "valid_date": ""
}
```

## 行驶证/驾驶证识别
<a name="driver-license"></a>

```php
// 行驶证
$ocr->driverLicense($image);
// 驾驶证
$ocr->driverLicense($image, 1);
```

行驶证返回值（Array）

| 参数名称 | 描述 |
|-------|----|
| plate_no| 车牌号码|
| owner| 所有人|
| address| 住址|
| use_character| 使用性质|
| model| 品牌型号|
| vin| 识别代码|
| engine_no| 发动机号|
| register_date| 注册日期|
| issue_date| 发证日期|
| stamp| 红章|

eg.
```json
{
    "plate_no": "沪AA1234",
    "owner": "李明",
    "address": "上海市徐汇区田林路397号腾云大厦6F",
    "use_character": "非营运",
    "model": "大众汽车牌G4SVW71612RS",
    "vin": "ABCDEFGH123456789",
    "engine_no": "8B54321",
    "register_date": "2011-10-10",
    "issue_date": "2011-10-10",
    "stamp": "上海市公安局交通警察总队"
}
```

驾照返回值（Array）

| 参数名称 | 描述 |
|-------|----|
| driver_no| 证号|
| name| 姓名|
| sex| 性别|
| nationality| 国籍|
| address| 住址|
| brithday| 出生日期|
| issue_date| 领证日期|
| class| 准驾车型|
| start_date| 起始日期|
| end_date| 有效日期|
| stamp| 红章|

eg.
```json
{
    "driver_no": "610333199012213125",
    "name": "艾米",
    "sex": "女",
    "nationality": "中国",
    "address": "深圳市南山区高新科技园科技中一路腾讯大厦",
    "brithday": "1990-12-21",
    "issue_date": "2015-01-01",
    "class": "C1",
    "start_date": "2015-01-01",
    "end_date": "2021-01-01",
    "stamp": "广东省深圳市公安局交通警察支队"
}
```

## 通用识别
<a name="generic"></a>

```php
$ocr->generic('https://cdn.ai.qq.com/aiplat/static/ai-demo/large/o-1.jpg');
```

返回值（Array）

eg.
```json
[
    "夏天的飞鸟，飞到我窗前唱歌，又飞去了。",
    "秋天的黄叶，它们没有什么可唱，只叹息一声，飞落在那里。",
    "Stray birds of summer come to my window to sing and fly away.",
    "And yellow leaves of autumn, which have no songs, futter and fall there with a sign.",
    "飞鸟集",
    "STRAY",
    "BIRDS"
]
```

## 营业执照识别
<a name="business-license"></a>

```php
$ocr->businessLicense('https://cdn.ai.qq.com/aiplat/static/ai-demo/large/odemo-pic-5.jpg');
```

返回值（Array）

| 参数名称 | 描述 |
|-------|----|
| card_no | 注册号|
| card_type | 法定代表人|
| card_name | 公司名称|
| bank | 地址|
| card_time | 营业期限|

eg.
```json
{
    "business_license": "91440300708461136T",
    "legal_person": "马化腾",
    "company_code": "深圳市腾讯计算机系统有限公司",
    "company_address": "深圳市南山区深南大道10000号",
    "business_time": "1998年11月11日至长期"
}
```

## 银行卡识别
<a name="business-license"></a>

```php
$ocr->businessLicense('https://cdn.ai.qq.com/aiplat/static/ai-demo/large/odemo-pic-5.jpg');
```

返回值（Array）

| 参数名称 | 描述 |
|-------|----|
| card_no| 卡号|
| card_type| 卡类型|
| card_name| 卡名字|
| bank| 银行信息|
| card_time| 有效期|

eg.
```json
{
    "card_no": "6225760088888888",
    "card_type": "贷记卡",
    "card_name": "招商银行信用卡",
    "bank": "招商银行(03080000)",
    "card_time": "08/2022"
}
```

## 手写体识别
<a name="handwrite"></a>

```php
$ocr->handwrite('https://cdn.ai.qq.com/ai/assets/ai-demo/large/hd-5-lg.jpg');
```

返回值（Array）

eg.
```json
[
    "这个忧伤而明",
    "媚的三月，从我单",
    "薄的青春里打马",
    "而过，穿过紫堇穿",
    "过木棉。穿过时",
    "隐时现的悲喜和",
    "无常"
]
```

## 车牌识别
<a name="plate-license"></a>

```php
$ocr->plateLicense('https://cdn.ai.qq.com/ai/assets/ai-demo/large/plate-1-lg.jpg')
```

返回值（Array）

| 参数名称 | 描述 |
|-------|----|
| plate_no| 车牌号|

eg.
```json
{
    "plate_no": "京N0L9U8"
}
```

## 名片识别
<a name="business-card"></a>

```php
$ocr->businessCard('https://cdn.ai.qq.com/aiplat/static/ai-demo/large/odemo-pic-2.jpg');
```

返回值（Array）

| 参数名称 | 描述 |
|-------|----|
| name| 姓名|
| position| 职位|
| company| 公司|
| address| 地址|
| email| 邮箱|
| mobile| 手机|
| wechat| 微信|

eg.
```json
{
    "name": "李明",
    "position": "产品经理",
    "company": "Tencent腾讯",
    "address": "深圳市南山区深南大道10000号腾讯大厦",
    "email": "8888asss@tencent.com",
    "mobile": "13888882222",
    "wechat": "limig"
}
```

MIT
