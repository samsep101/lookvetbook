<?php
    class Redis_TestController extends BaseController
    {
        /**
         * @var Rediska
         */
        private $redis;

        private $attempts = 10000;

        public function __construct()
        {
            $this->redis = Register::get('redis');
        }

        public function setValue()
        {
            $start = microtime(true);

            for ($i = 1; $i <= $this->attempts; $i++)
            {
                $this->redis->set('t'.$i, $i);
            }
            $end = microtime(true);

            $total = $end-$start;

            echo $total.'<br />';
            echo $total/$this->attempts;
            exit();
        }

        public function getValue()
        {
            $start = microtime(true);

            for ($i = 1; $i <= $this->attempts; $i++)
            {
                $this->redis->get('t'.$i);
            }
            $end = microtime(true);

            $total = $end-$start;

            echo $total.'<br />';
            echo $total/$this->attempts;
            exit();
        }
    }