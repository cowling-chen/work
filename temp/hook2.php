<?php 
if (!defined("NIUYU"))
    die("不可以直接访问");

function hookexample($vars) {

    //  代码
	echo 'hookexample';

}

add_hook("hookexample",1,"hookexample");
echo 333;
////////////////////////////////////////////////////////////////////////////

//细心的读者会问了，add_hook怎么写呢？贴出代码来分析：


function add_hook($hook_name, $priority, $hook_function, $rollback_function = '')
{
    global $hooks; // 注册为全局的，保存牛域网所有用的hooks
    if( !is_array($hooks) )
    {
        $hooks = array(  );
    }
    if( !array_key_exists($hook_name, $hooks) )
    {
        $hooks[$hook_name] = array(  );
    }
    array_push($hooks[$hook_name], array( 'priority' => $priority, 'hook_function' => $hook_function, 'rollback_function' => $rollback_function ));
}


//每个hook能执行一系列函数，保存在$hooks[$hook_name] 中。


//run_hook

function run_hook($hook_name, $args)
{
    global $hooks;
    if( !is_array($hooks) )
    {
        $hooks = array(  );
    }

    if( !array_key_exists($hook_name, $hooks) )
    {
        return array(  );
    }
    unset($rollbacks);
    $rollbacks = array(  );
    reset($hooks[$hook_name]);
    $results = array(  );
    while( list($key, $hook) = each($hooks[$hook_name]) )
    {
        array_push($rollbacks, $hook['rollback_function']);
        if( function_exists($hook['hook_function']) )
        {

            $res = call_user_func($hook['hook_function'], $args);
            if( $res )
            {
                $results[] = $res;
                hook_log($hook_name, "Hook Completed - Returned True");
            }
            else
            {
                hook_log($hook_name, "Hook Completed - Returned False");
            }
        }
        else
        {
            hook_log($hook_name, "Hook Function %s Not Found", $hook['hook_function']);
        }
    }
    return $results;
}