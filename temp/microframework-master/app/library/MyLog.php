<?php


/**
 * Class MyLog
 * 自定义日志类
 */
class Log
{

    /**
     * 普通日志写入
     * @param string $file
     * @param $log
     */
    public static function writeMessageLog($file = '', $log)
    {
        $logPath = ".";//日志路径
        $file = $logPath . '/' . $file . '.' . date('Y.m.d') . '.log';//日志文件

        $fp = fopen($file, 'a');
        if (strlen($log) <= 1024) {
            fwrite($fp, $log.PHP_EOL);
        } else if (flock($fp, LOCK_EX)) {
            fwrite($fp, $log.PHP_EOL);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

    /**
     * 小文本写入
     * @param string $file
     * @param $log
     */
    public static function writeSimpleLog($file = '', $log)
    {
        $logPath = ".";//日志路径
        $file = $logPath . '/' . $file . '.' . date('Y.m.d') . '.log';//日志文件
        file_put_contents($file, $log.PHP_EOL, FILE_APPEND);
    }

}

//自定义日志
$dtime=date('Y-m-d H:i:s',time());
$logContent="<?xml version='1.0' encoding='UTF-8'?><root>......</root>";
MyLog::writeMessageLog('Message',$dtime."\t".json_encode($logContent,JSON_UNESCAPED_UNICODE));


$logContent=[
    "request-time"=>$dtime,
    "request-url"=>'www.xxxx.com',
    "request-data"=>['order_sn'=>'1000000000001'],
    "response-time"=>$dtime,
    "response-url"=>'www.xxxx.com',
    "response-data"=>[
        'name'=>'','age'=>25,'address'=>'beijing'
    ],
];

/*
 * 日志格式:
 * {"request":{"time":"2016-05-30 17:58:00","url":"www.xxxx.com","data":{"order_sn":"1000000000001"}},"response":{"time":"2016-05-30 17:58:00","url":"www.xxxx.com","data":{"name":"","age":25,"address":"beijing"}}}
 * */

MyLog::writeSimpleLog('Message',$dtime."\t".json_encode($logContent,JSON_UNESCAPED_UNICODE));
