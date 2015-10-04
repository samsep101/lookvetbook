<?php
    class JsonResponse
    {
        public static function result($data = true)
        {
            echo json_encode(array('status' => 0, 'result' => $data));
            $profiler = Profiler::getInstance();
            $profiler->stopTime('page');
            $profiler->logdata();
            exit(); 
        }
        
        public static function error($status_code, $data = array())
        {
            echo json_encode(array(
				'status' => $status_code,
				'data' => $data
			 ));
            $profiler = Profiler::getInstance();
            $profiler->stopTime('page');
            $profiler->logdata();
            exit();
        }
    }