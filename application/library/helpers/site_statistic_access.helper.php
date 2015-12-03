<?php

class SiteStatisticAccessHelper {

    public static function checkAccess()
    {
        if (!Acc::isSystemAccess())
            RedirectManager::redirect('/');

        $user_ip = $_SERVER['REMOTE_ADDR'];

        if ($user_ip and $user_ip!='127.0.0.1'){

            $system_access_ip_manager = new SystemAccessIpManager();
            $system_access_ips = $system_access_ip_manager->getActiveList();

            if (count($system_access_ips))
            {
                $access_ip_lists = array();
                foreach ($system_access_ips as $system_access_ip)
                {
                    $access_ip_lists[] = $system_access_ip->ip;
                }

                if (!in_array($user_ip, $access_ip_lists))
                    RedirectManager::redirect('/');
            }
        }
    }
}