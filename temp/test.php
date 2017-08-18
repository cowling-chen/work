<?php

function ss(){
	$s = 5;
	$s = pow($s,1/2); var_dump($s);
}

call_user_func('ss');