<?php
    class ProfilerDbLogger
    {
        /**
         * @var Db
         */
        private $db;

        private static $instance;

        public function __construct()
        {
            $this->db = new Db(PROFILER_DB_HOST, PROFILER_DB_USER, PROFILER_DB_PASSWORD, PROFILER_DB_NAME);
        }

        public function log(array $data)
        {
            $table_name = 'performance_log_'.date('Y_m_d');

            $sql = 'INSERT DELAYED INTO '.$table_name.'
                    SET ip = "'.mysql_real_escape_string($data['ip']).'",
                        page = "'.mysql_real_escape_string($data['page']).'",
                        controller = "'.mysql_real_escape_string($data['controller']).'",
                        action = "'.mysql_real_escape_string($data['action']).'",
                        params = "'.mysql_real_escape_string($data['params']).'",
                        utime = "'.(float)mysql_real_escape_string($data['utime']).'",
                        stime = "'.(float)mysql_real_escape_string($data['stime']).'",
                        mysql_time = "'.(float)mysql_real_escape_string($data['mysql_time']).'",
                        memcache_time = "'.(float)mysql_real_escape_string($data['memcache_time']).'",
                        mysql_count_queries = "'.(int)mysql_real_escape_string($data['mysql_count_queries']).'",
                        memcache_count_queries = "'.(int)mysql_real_escape_string($data['memcache_count_queries']).'",
                        mysql_queries = "'.mysql_real_escape_string($data['mysql_queries']).'",
                        total_time = "'.(float)mysql_real_escape_string($data['total_time']).'",
                        referer = "'.mysql_real_escape_string($data['referer']).'"
                        ';

            try {
                $this->db->query($sql);
            } catch (Exception $e) {
                if ($this->db->error_code() == 1146)
                {
                    $this->createLogTable($table_name);
                    $this->log($data);
                }
            }

        }

        private function createLogTable($table_name)
        {
            $sql = 'CREATE TABLE '.$table_name.'
                    LIKE performance_log_template';

            $this->db->query($sql);
        }

        public static function getInstance()
        {
            if (!self::$instance)
            {
                self::$instance = new ProfilerDbLogger();
            }

            return self::$instance;
        }
    }