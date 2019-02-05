<?php
namespace devskyfly\yiiExtensionCalltouch;

use devskyfly\php56\types\Arr;
use devskyfly\php56\types\Obj;
use yii\base\BaseObject;
use GuzzleHttp\Exception\RequestException;

class Calltouch extends BaseObject
{
    public $access=null;
    
    public function init()
    {
        parent::init();
        if(!Arr::isArray($this->access)){
            throw new \InvalidArgumentException('Property $accessis not array type.');
        }
    }
    
    /**
     * 
     * @param Request $request
     * @param [] $query
     * @throws \InvalidArgumentException
     * @throws RequestException
     * @return string
     */
    public function sendRequest($request,$query)
    {
        if(!Obj::isA($request,Request::class)){
            throw new \InvalidArgumentException('Paramter $request is not '.Request::class.' type.');
        }
        if(!Arr::isArray($query)){
            throw new \InvalidArgumentException('Paramter $query is not array type.');
        }

        $request->setQuery($query);
        $request->setAccess($this->access);
        return $request->send();
    }
}