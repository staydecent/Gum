<?php 

// so safe
$q = $_SERVER['QUERY_STRING'];
$s = file_get_contents($q);

function filter_html_tokens($a)
{
    return (is_array($a) && $a[0] == T_INLINE_HTML) ? $a[1] : '';
}

$s2 = implode('', array_map('filter_html_tokens', token_get_all($s)));

echo $s2;