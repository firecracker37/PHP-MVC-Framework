<?php

class Router {

    public static function route($url) {
        
        //controller
        $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]) : DEFAULT_CONTROLLER;
        $controller_name = $controller;
        array_shift($url);

        //controller
        $action = (isset($url[0]) && $url[0] != '') ? $url[0] . 'Action' : 'indexAction';
        $action_name = (isset($url[0]) && $url[0] !='') ? $url[0] : 'index';
        array_shift($url);

        // ACL Check
        $grantAccess = self::hasAccess($controller_name, $action_name);

        if(!$grantAccess) {
            $controller_name = $controller = ACCESS_RESTRICTED;
            $action = 'indexAction';
        }
        
        //params
        $queryParams = $url;

        $dispatch = new $controller($controller_name, $action);
        
        if(method_exists($controller, $action)) {
            call_user_func_array([$dispatch, $action], $queryParams);
        } else {
            die('That method does not exist in the controller \"' . $controller_name . '\"');
        }

    }

    public static function redirect($location) {
        if(!headers_sent()) {
            header('Location: ' . PROOT . $location);
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . PROOT . $location . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
            echo '</noscript>';
            exit();
        }
    }

    public static function hasAccess($controller_name, $action_name='index') {
        $acl_file = file_get_contents(ROOT . DS . 'app' . DS . 'acl.json');
        $acl = json_decode($acl_file, TRUE);
        $current_user_acls = ["Guest"];
        $grantAccess = false;

        if(Session::exists(CURRENT_USER_SESSION_NAME)) {
            $current_user_acls[] = "LoggedIn";
            foreach(currentUser()->acls() as $a) {
                $current_user_acls[] = $a;
            }
        }

        foreach($current_user_acls as $level) {
            if(array_key_exists($level, $acl) && array_key_exists($controller_name, $acl[$level])) {
                if(in_array($action_name, $acl[$level][$controller_name]) || in_array("*", $acl[$level][$controller_name])) {} 
                    if($controller_name === "Register" && $action_name ==='logout' && !(Session::exists(CURRENT_USER_SESSION_NAME))) {
                        $grantAccess = false;
                        break;
                    }
                    $grantAccess = true;
                    break;
            }
        }

        //Check for Denied
        foreach($current_user_acls as $level) {
            $denied = $acl[$level]['denied'];
            if(!empty($denied) && 
            array_key_exists($controller_name, $denied) && 
            in_array($action_name, $denied[$controller_name])) {
                
                $grantAccess = false;
                break;
            }
        }
        return $grantAccess;
    }

    public static function getMenu($menu) {
        $menuAry = [];
        $menuFile = file_get_contents(ROOT . DS . 'app' . DS . $menu . '.json');
        $acl = json_decode($menuFile, true);
        foreach($acl as $key => $val) {
            if(is_array($val)) {
                $sub=[];
                foreach($val as $k => $v) {
                    if($k == 'separator' && !empty($sub)) {
                        $sub[$k] = '';
                        continue;
                    }else if($finalVal = self::get_link($v)) {
                        $sub[$k] = $finalVal;
                    }
                }
                if(!empty($sub)) {
                    $menuAry[$key] = $sub;
                }
            } else {
                if($finalVal = self::get_link($val)) {
                    $menuAry[$key] = $finalVal;
                }
            }
        }
        /*echo "<h2>ACL</h2>";
        echo "<pre>";
        echo var_dump($acl);
        echo "</pre><hr>";
        echo "<h2>MenuAry</h2>";
        echo "<pre>";
        echo var_dump($menuAry);
        echo "</pre>";
        die();*/
        return $menuAry;
    }

    private static function get_link($val) {
        //check if external link
        //echo 'Testing the value of $val: ' . $val . ' ';
        if(preg_match('/https?:\/\//', $val) == 1) {
            return $val;
        } else {
            $uAry = explode('/', $val);
            $controller_name = ucwords($uAry[0]);
            $action_name = (isset($uAry[1]))? $uAry[1] : '';
            if(self::hasAccess($controller_name, $action_name)) {
                //echo "Access granted for " . $controller_name . " - " . $action_name . "<br />";
                return PROOT . $val;
            } else {
                //echo "Access denied for " . $controller_name . " = " . $action_name . "<br />";
            }
            return false;
        }
    }
}