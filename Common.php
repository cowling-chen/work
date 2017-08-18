<?php
/**
 * @file Marvel_Util_Common.php
 * @author gongwen(com@baidu.com)
 * @date 2016/06/23 16:03:18
 * @brief
 *
 **/
class Marvel_Util_Common {

    /**
     * 将输出数据展平
     *
     * @param mixed $data
     * @return string
     */
    public static function varExportFlatten($data) {
        return preg_replace('/[ \n\t]+/',"", var_export($data, true));
    }

    /**
     *生成唯一的reqid用于请求业务后台接口
     * @param int $uid 用户uid passport
     * @return md5 string
     */
    public static function makeReqid($arrInput) {
        $channel = $arrInput['channel'];
        $logintype = $arrInput['loginType'];
        $productid = isset($arrInput['pid']) ? $arrInput['pid'] : '';
        $ip = isset($arrInput['ip']) ? $arrInput['ip'] : $_SERVER['REMOTE_ADDR'];
        $bid = $arrInput['bid'];
        $time = Marvel_Util_Timer::microSecond();
        return md5($channel . $logintype . $productid . $ip . $bid . $time);
    }

    /**
     * 格式化通讯录
     *
     * @param string $strContact json格式
     * @return string 格式化后的通讯录 json格式
     */
    public static function formatContact($strContact) {
        $arrContact = json_decode($strContact, true);
        if (empty($arrContact[0])) {
            return '';
        }
        $i = 1;
        $arrList = array();
        foreach ($arrContact[0] as $value) {
            $tmpMobile = array();
            foreach ($value['list'] as $mobileValue) {
                $tmpMobile[] = $mobileValue['num'];
            }
            $arrList[] = array(
                'no' => $i,
                'name' => $value['name'],
                'mobile' => $tmpMobile,
            );
            $i++;
        }
        $intNum = count($arrList);
        //教育
        // $arrData = array(
        //     'count' => $intNum,
        //     'content' => array(
        //         'count' => $intNum,
        //         'total' => count($arrList),
        //         'data' => $arrList,
        //     ),
        // );
        //医美
        $arrData = array(
            'count' => $intNum,
            'total' => count($arrList),
            'data' => $arrList,
        );
        return json_encode($arrData);
    }

	/**
	 * @param $arrInput
	 * @param $name
	 * @param null $type
	 * @param string $default
	 * @return type
	 */
	public static function getParam($arrInput, $name, $type = null, $default = '')
	{
		if (isset($arrInput[$name])) {
			$param = $arrInput[$name];
		} else {
			$param = $default;
		}
		if ($type) {
			settype($param, $type);
		}
		return $param;
	}

    /**
     * 获取风控verifcations中的flashid
     * @param int $uid
     * @return string
     */
    public static function getFlashId($uid) {
        $time = time();
        $time = floor($time / 600);
        return md5($uid . '_' . $time);
    }

    /**
     * @return string
     */
    public static function getCookieStr() {
        $cookieStr = '';
        foreach ($_COOKIE as $key => $value) {
            $cookieStr .= $key . "=" . $value . ";";
        }
        return $cookieStr;
    }

    /**
     * 获取绑卡认证url
     * @return string
     */
    public static function getBindCardUrl() {
        $bakUrl = urlencode(str_replace('/index.php', '', $_SERVER['SCRIPT_URI'] . '?' . $_SERVER['QUERY_STRING']));
        return Marvel_Util_Conf::getConf('/wallet/qbBindCardUrl') . '&u=' . $bakUrl;
    }

    /**
     * 获取绑卡认证url
     * @return string
     */
    public static function getAuthUrl() {
        $bakUrl = urlencode(str_replace('/index.php', '', $_SERVER['SCRIPT_URI'] . '?' . $_SERVER['QUERY_STRING']));
        return Marvel_Util_Conf::getConf('/wallet/qbAuthUrl') . '&bak_url=' . $bakUrl . '&ru=' . $bakUrl;
    }


    /**
     * 检测用户是否登录
     * @param $bduss
     * @return string
     */
    public static function checkUserLogin($bduss=null,$ptoken=null,$stoken=null) {
        if ($bduss === null) {
            if (isset($_COOKIE['BDUSS'])) {
            	$bduss = $_COOKIE['BDUSS'];
                $stoken = $_COOKIE['FBUYMSTOKEN'];
            } else {
                return false;
            }
        }
        if (Marvel_Util_Conf::getAppConf('loginauth/switch') == 1) {
			$arrLoginInfo = Bd_Passport::authGetData($bduss, $stoken);
		} else {
			$arrLoginInfo = Bd_Passport::getData($bduss);
		}
        return $arrLoginInfo;
    }


    /**
     * 生成签名
     *
     * @param array $arrSignParam
     * @param string $strSignKey
     * @return string | false
     */
    public static function createSign($arrSignParam, $strSignKey){
        if (!is_array($arrSignParam)){
            return false;
        }
        ksort($arrSignParam);

        $signStr = '';
        foreach ($arrSignParam as $k=>$v) {
            $signStr .= $k.'='.$v.'&';
        }
        $signStr .= 'key='.$strSignKey;

        return md5($signStr);
    }

    /**
     * 获取城市首字母
     * @param string $str
     * @return string|NULL
     */
    public static function getFirstCharter($str) {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) {
            return strtoupper($str{0});
        }
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        $specialCharacter = array(
            '10536' => 'C',
            '9767' => 'B',
            '7182' => 'L',
            '6745' => 'P',
            '3372' => 'J',
            '7703' => 'Q',
        );
        if (isset($specialCharacter[abs($asc)])) {
            return $specialCharacter[abs($asc)];
        }
        if ($asc >= -20319 && $asc <= -20284) {
            return 'A';
        }
        if ($asc >= -20283 && $asc <= -19776) {
            return 'B';
        }
        if ($asc >= -19775 && $asc <= -19219) {
            return 'C';
        }
        if ($asc >= -19218 && $asc <= -18711) {
            return 'D';
        }
        if ($asc >= -18710 && $asc <= -18527) {
            return 'E';
        }
        if ($asc >= -18526 && $asc <= -18240) {
            return 'F';
        }
        if ($asc >= -18239 && $asc <= -17923) {
            return 'G';
        }
        if ($asc >= -17922 && $asc <= -17418) {
            return 'H';
        }
        if ($asc >= -17417 && $asc <= -16475) {
            return 'J';
        }
        if ($asc >= -16474 && $asc <= -16213) {
            return 'K';
        }
        if ($asc >= -16212 && $asc <= -15641) {
            return 'L';
        }
        if ($asc >= -15640 && $asc <= -15166) {
            return 'M';
        }
        if ($asc >= -15165 && $asc <= -14923) {
            return 'N';
        }
        if ($asc >= -14922 && $asc <= -14915) {
            return 'O';
        }
        if ($asc >= -14914 && $asc <= -14631) {
            return 'P';
        }
        if ($asc >= -14630 && $asc <= -14150) {
            return 'Q';
        }
        if ($asc >= -14149 && $asc <= -14091) {
            return 'R';
        }
        if ($asc >= -14090 && $asc <= -13319) {
            return 'S';
        }
        if ($asc >= -13318 && $asc <= -12839) {
            return 'T';
        }
        if ($asc >= -12838 && $asc <= -12557) {
            return 'W';
        }
        if ($asc >= -12556 && $asc <= -11848) {
            return 'X';
        }
        if ($asc >= -11847 && $asc <= -11056) {
            return 'Y';
        }
        if ($asc >= -11055 && $asc <= -10247) {
            return 'Z';
        }
        return null;
    }

    /**
     * 创建唯一字符串
     * @param string $namespace 前缀
     * @return STRING
     */
    public static function createUniqueString($preFix = '') {
        $arr = gettimeofday();
        $uniqId = $preFix . date('Ymd') . ((($arr['sec'] * 100000 + $arr['usec'] / 10) & 0x7FFFFFFF) | 0x80000000) . rand(1000000, 9999999);
        return $uniqId;
    }

    /**
     * 多维数组排序
     * @param mix
     * @return array
     */
    public static function arrayOrderBy() {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    /**
     * decryptStr
     * 解密
     * @param mixed $str
     * @param mixed $key
     * @param mixed $toBase64
     * @static
     * @access public
     * @return string
     **/
    public static function decryptStr($str, $key, $toBase64 = true) {
        if (true == $toBase64) {
            $str = base64_decode($str);
        }
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }

    /**
     * encryptStr
     * 加密
     * @param $str
     * @param $key
     * @param bool $toBase64
     * @return string
     */
    public static function encryptStr($str, $key, $toBase64 = true) {
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $enc_str = mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        return (true == $toBase64) ? base64_encode($enc_str) : $enc_str;
    }

    /**
     * @return string
     */
    public static function getToken() {
        //todo 暂时先注释 后期升级以后再放开
        //$string = Marvel_Code_Constants::TOKEN_SALT;
        //$dst = '';
        //$src =  $_COOKIE['BDUSS'] ? $_COOKIE['BDUSS'] : $_COOKIE['BAIDUID'];
        //Bd_Passport::getBdsToken($src, $string, null, $dst);
        //return $dst;
        //return !empty($string) ? md5($string) : '';
        $userInfo = Bd_Passport::checkUserLogin();
        $string = $userInfo ? $userInfo['uid'] . Marvel_Code_Constants::TOKEN_SALT : '';
        return !empty($string) ? md5($string) : '';

    }

    /**
     * 生成唯一ID值
     * @return string
     */
    public static function getUniqueId() {
        $userInfo = Bd_Passport::checkUserLogin();
        $string = $userInfo ? $userInfo['uid'] . Marvel_Code_Constants::TOKEN_SALT : '';
        return !empty($string) ? md5($string) : '';
    }

    /**
     * @param $arr
     * @param $column
     * @return array
     */
    public static function fetchColumns($arr, $column) {
        $res = array();
        if (!function_exists('array_columns')) {
            foreach ($arr as $k => $v) {
                if (isset($v[$column])) {
                    $res[] = $v[$column];
                }
            }
        } else {
            $res = array_column($arr, $column);
        }
        return $res;
    }

    /**
     * 节点获取
     * @param  $object
     * @return mixed
     */
    public static function bizNode($object) {

        $className = get_class($object);

        $classNameData = explode('_', $className);

        return strtolower(end($classNameData));

    }

    /**
     * getCallbackName
     * @param arrInput
     * @return  callback name
     */
    public static function getCallbackName($arrInput) {
        if (isset($arrInput['callback']) && preg_match("/^[A-Za-z0-9_]+\$/", $arrInput['callback'])) {
            $strCallback = $arrInput['callback'];
        } else {
            $strCallback = '';
        }
        return $strCallback;
    }
    /**
     * retJson
     *
     * @param array $data
     * @param mixed $callBack
     *
     * @static
     * @return string
     */
    public static function retJson($data = array(), $callBack ="") {
        if ($callBack != '') {
            $strRet = json_encode($data);
            $strRet = '/**/' . $callBack . '(' . $strRet . ')';
            header('Content-Type: text/javascript; charset=UTF-8');
        } else {
            $strRet = json_encode($data);
            header('Content-Type: text/json; charset=UTF-8');
        }
        return $strRet;
    }
    /**
     * replaceStar
     * 替换字符串*
     * @param mixed $string   替换前字段串
     * @param int $front      保留前几位
     * @param int $back       保留后几位
     * @access public
     * @return string $result 替换前字段串
     */
    public static function replaceStar($string, $front = 0, $back = 0) {
        $count = mb_strlen($string, 'UTF-8');

        //此处传入编码，建议使用utf-8。此处编码要与下面mb_substr()所使用的一致
        if (!$count) {
            return $string;
        }
        if ($back == 0) {
            $back = $count;
        }

        $i = 0;
        $result = '';
        while ($i < $count) {
            $tmpString = mb_substr($string, $i, 1, 'UTF-8');

            // 与mb_strlen编码一致
            if ( $i >= $front && $i < ($count - $back)) {
                $result .= '*';
            } else {
                $result .= $tmpString;
            }
            $i++;
        }
        return $result;
    }
    
    /**
     * 加 * 替换
     * @param $string
     * @param int $start
     * @param int $end
     * @return string
     */
    public static function replaceStartFilter($string, $start = 0, $end = 0) {
        $count = mb_strlen($string, 'UTF-8');
        
        //此处传入编码，建议使用utf-8。此处编码要与下面mb_substr()所使用的一致
        if (!$count) {
            return $string;
        }
        if ($end == 0) {
            $end = $count;
        }
        
        $i = 0;
        $returnString = '';
        while ($i < $count) {
            $tmpString = mb_substr($string, $i, 1, 'UTF-8');
            
            // 与mb_strlen编码一致
            if ($start <= $i && $i < $end) {
                $returnString .= '*';
            } else {
                $returnString .= $tmpString;
            }
            $i++;
        }
        return $returnString;
    }

    /**
     * 金额格式化
     * @param int $credit  金额
     * @param bool $isSmall   是否除以100 默认除100
     *
     * @return string $result
     * @author ningyandong(com@baidu.com)
     */
    public static function numberFormat($credit , $isSmall=true){
        if($isSmall){
            $credit = $credit/100;
        }
        return  number_format($credit , 2 , '.' , ',');
    }



    /**
     * smartyDisplay
     * 封装samrty的渲染模板
     * @param mixed $data
     * @param mixed $tpl
     * @static
     * @access public
     * @return void
     */
    public static function smartyDisplay($tpl, $data = array()) {
        $appDir = Bd_AppEnv::getCurrApp() . DIRECTORY_SEPARATOR;
        $baseDir = !empty($data['data']['frontComponentVersion']) ? $appDir .  $data['data']['frontComponentVersion'] . '/': $appDir;
        define('FBU_SMARTY_PROJECT_BASIC_DIR', $baseDir);
        $tplObj = Bd_TplFactory::getInstance();
        if ($data) {
            foreach ($data as $k => $v) {
                $tplObj->assign($k, $v);
            }
        }
        $tplObj->display($baseDir . $tpl);
    }

    /**
     * base64Decode
     * @param mixed $data
     * @static
     * @access public
     * @return void
     */
    public static function base64Decode($data) {
        return base64_decode($data);
    }

    /**
     * checkParams
     * @param mixed $data
     * @static
     * @access public
     * @return void
     */
    public static function checkParams($method,$pattern='/^[a-zA-Z0-9_]+$/') {
        if( 1 === preg_match($pattern,$method)){
            return true;
        }
        return false;
    }

    /**
     * 发送信息
     * @param $url
     * @param $params
     * @param array $headers
     * @param array $cookie
     * @return bool|mixed
     * http://man.baidu.com/inf/orp/#服务介绍_fetchurl服务
     */
    public static function sendFetchUrl($url, $params, $headers = array(), $cookie = array())
    {

        if(!class_exists('Orp_FetchUrl')){
            Bd_Log::warning(__METHOD__ . ':notice_FetchUrl_is_not_exists');
            return false;
        }

        Bd_Log::debug(__METHOD__ . ':notice_FetchUrl_request|curl:' . $url . '|params:' . json_encode($params));
        $httpproxy = Orp_FetchUrl::getInstance(array('timeout' => 3000, 'conn_timeout' => 1000, 'max_response_size' => 1024000));
        $res = $httpproxy->post($url, $params, $headers, $cookie);
        $curl_msg = $httpproxy->errmsg();
        $curl_code = $httpproxy->errno();
        $http_code = $httpproxy->http_code();

        Bd_Log::debug(__METHOD__ . ':notice_FetchUrl_response|curl_code:' . $curl_code . '|curl_msg:' . $curl_msg . '|' . $res);
        if ($curl_code !== 0) {
            Bd_Log::warning(__METHOD__ . ':notice_FetchUrl_failed|http_code: '. $http_code.'|curl_code:' . $curl_code . '|curl_msg:' . $curl_msg . '|' . $res);
            return false;
        }
        return $res;
    }
    
	/**
     * isHttps
     * @param 
     * @static
     * @access public
     * @return bool
     */
    public static function isHttps() {
    	// 直接访问https,hsts
		if ($_SERVER['HTTPS']) {
			return true;
		}
    	// 透过bfe访问
		if ($_SERVER['HTTP_X_SSL_HEADER'] == 1) {
			return true;
		}   
		// 透过加速卡访问
		if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
			return true;
		}    
        return false;
    }
}
