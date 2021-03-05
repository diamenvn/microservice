<?php
namespace Microservices;


class Lms
{
    protected $_url;
    public function __construct() {
        $this->_url = env('API_MICROSERVICE_URL').'/edu';
    }
    public function getCoursePrices($params)
    {
        $whereArr = \Arr::only($params, ['course_id']);
        $filter = [];
        foreach($whereArr as $k => $v){
            if (is_null($v)) continue;
            switch ($k) {
                default:
                    if (is_array($v)) {
                        $filter[$k] = ['inq' => $v];
                    }
                    else {
                        $filter[$k] = ['eq' => $v];
                    }
                    break;
            }
        }
        $filter = array_merge($filter, ['status' => 'active','from_date' => ['lte' => date("Y-m-d")],'to_date' => ['gte' => date('Y-m-d')]]);
        $response = \Http::withToken(env('API_MICROSERVICE_TOKEN',''))->get($this->_url.'/course-prices',['filter' => json_encode([
            'where' => $filter,
            'fields' => ['course_id','amount','price_id','validation']
            ])]);
        if ($response->successful()) {
            return $response->json();
        }
        \Log::error($response->body());
        return false;
    }
}