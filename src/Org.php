<?php
namespace Microservices;

class Org
{
    protected $_url;
    public function __construct() {
        $this->_url = env('API_MICROSERVICE_URL').'/org';
    }
    public function getBranchDetail($id)
    {
        //var_dump(['filter' => json_encode(['where' => $filter])]); die;
        $response = \Http::withToken(env('API_MICROSERVICE_TOKEN',''))->get($this->_url.'/brand-branches/'.$id);
        if ($response->successful()) {
        	return $response->json();
        }
        \Log::error($response->body());
        return false;
    }
    public function getBrandsByBranch($id) {
        //var_dump(['filter' => json_encode(['where' => $filter])]); die;
        $response = \Http::withToken(env('API_MICROSERVICE_TOKEN',''))->get($this->_url.'/brand-branches/'.$id.'/brands/');
        if ($response->successful()) {
            return $response->json();
        }
        \Log::error($response->body());
        return false;
    }
}