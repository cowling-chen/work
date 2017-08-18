<?php
$arr1 = array(
	'key1'=>'val1',
	'key2'=>'val2',
	'key3'=>array(
        '11'=>'11',
        '22'=>'22',
        '33'=>'33',
    ),
	'key4'=>'val4',
	'key5'=>'val5',
);

$arr2 = array(
    'key1'=>'val1',
    'key2'=>'val22',
    'key3'=>array(
        '11'=>'11',
        '22'=>'22',
        '33'=>'33',
    ),
    'key4'=>'val4',
//    'key5'=>'val5',
    'key6'=>'val6',
);
$ss = array_diff($arr1,$arr2);
var_dump($ss);exit;

function ss(){
    echo 'ssssssssssss';
}

call_user_func('ss');