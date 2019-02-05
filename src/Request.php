<?php
namespace devskyfly\yiiExtensionCalltouch;

use GuzzleHttp\Client;
use devskyfly\php56\types\Arr;
use devskyfly\php56\types\Nmbr;
use devskyfly\php56\types\Obj;
use devskyfly\php56\types\Str;
use devskyfly\php56\types\Vrbl;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use GuzzleHttp\Exception\RequestException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Request extends BaseObject
{
    public $url_tempalte="https://api-node%s.calltouch.ru/calls-service/RestAPI/requests/%s/register/";
    
    /**
     * 
     * @var array
     */
    public $proxy_settings=[];
    
    /**
     * 
     * @var array
     */
    public $query=[];
    
    protected $access=null;
    
    protected $client=null;
    
    public function init()
    {
        parent::init();
        $this->client=new Client();
    }
    
    /**
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function check()
    {
        if(!Arr::isArray($this->access)){
            throw new \InvalidArgumentException('Property $accessis not array type.');
        }
        $this->checkQuery();
    }
    
    
    
    /**
     * 
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function checkQuery()
    {
        $required=['subject','requestNumber','requestDate','sessionId','fio','phoneNumber','email'];
        foreach ($required as $item){
            if(!Arr::keyExists($this->query, $item)){
                throw new \RuntimeException("Key '{$item}' does not exist.");
            }
            
            if((!Str::isString($item))
                &&(!Nmbr::isNumeric($item))){
                throw \InvalidArgumentException("Item '{$item} is not string or number type.'");
            }
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getUrl()
    {
        return $this->url_tempalte=sprintf($this->url_tempalte,$this->access['node'],$this->access['id']);
    }
    
    /**
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws RequestException
     * @return string
     */
    public function send()
    {
        $this->initQuery();
        $this->check();
        $query=ArrayHelper::merge($this->access,$this->query);
        
        $client=new Client();
        
        $url=$this->getUrl();
        $response=$client->request('GET', $url,
            [
                'query'=>$query,
                'proxy'=>$this->proxy_settings
                
            ]);
        
        $data=$response->getBody()->getContents();
        
        
        return $data;
    }
    
    
    public function setQuery($query)
    {
        $this->query=$query;
        return $this;
    }
    
    public function setAccess($access)
    {
        $this->access=$access;
        return $this;
    }
    
    /**
     * @throws UnsatisfiedDependencyException
     */
    protected function initQuery()
    {
        $required=['requestNumber','requestDate','sessionId','fio','phoneNumber','email'];
        
        if((!isset($this->query['requestDate']))
            ||Vrbl::isEmpty($this->query['requestDate'])){
                $this->query['requestDate']=(new \DateTime())->format('d.m.Y H:i:s');
        }
        
        if((!isset($this->query['requestNumber']))
            ||Vrbl::isEmpty($this->query['requestNumber'])){
                $uuid1 = Uuid::uuid1();
                $this->query['requestNumber']=$uuid1->toString();
        }
    }
}