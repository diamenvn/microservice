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
    	$params = array_merge(['date' => date('Y-m-d')],$params);
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
        $filter['status'] = ['eq' => 'active'];
        $filter['from_date'] = ['gte' => 'Y-m-d'];
        $filter['to_date'] = ['lte' => 'Y-m-d'];
        //var_dump(['filter' => json_encode(['where' => $filter])]); die;
        $response = \Http::withToken(env('API_MICROSERVICE_TOKEN',''))->get($this->_url.'/course-prices/'.$params['course_id'],['filter' => json_encode(['where' => $filter,'order' => 'ordering'])]);
        if ($response->successful()) {
        	return $response->json();
        }
        \Log::error($response->body());
        return false;
    }
}