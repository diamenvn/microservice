<?php
namespace Microservices;


class Hr
{
    protected $_url;
    public function __construct() {
        $this->_url = env('API_MICROSERVICE_URL').'/hr';
    }
    public function getSchedule($params)
    {
    	$params = array_merge(['date' => date('Y-m-d')],$params);
    	$whereArr = \Arr::only($params, ['date', 'employee_id','working_time']);
    	$filter = [];
        foreach($whereArr as $k => $v){
            if (is_null($v)) continue;
            switch ($k) {
            	case 'working_time':
            		$filter['start_time'] = ['lt' => $v];
            		$filter['end_time'] = ['gt' => $v];
            		break;
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
        //var_dump(['filter' => json_encode(['where' => $filter])]); die;
        $response = \Http::withToken(env('API_MICROSERVICE_TOKEN',''))->get($this->_url.'/schedules',['filter' => json_encode(['where' => $filter])]);
        if ($response->successful()) {
        	return $response->json();
        }
        \Log::error($response->body());
        return false;
    }
}