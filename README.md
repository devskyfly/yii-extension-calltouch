# devskyfly\yii-extension-calltouch

## Config

```php
 [
	 'calltouch'=>[
	 	'class'=>'devskyfly\yiiExtensionCallTouch\CallTouch',
	 	'access'=>[
	 		'id'=>9999,
	 		'node'=>1
	 	]
	 ]
 ]
```
## Code sample

```php
$query=[
    'subject'=>'More test',
    'sessionId'=>0,
    'fio'=>'tester',
    'phoneNumber'=>'999999999',
    'email'=>'test@ya.ru',
    'comment'=>'text'
];
$calltouch=Yii::$app->calltouch;

$request=new Request();

$request->proxy_settings=[
    'http'=>'127.0.0.1:3128',
    'https'=>'127.0.0.1:3128'
];

return $calltouch->sendRequest($request,$query);
```