<?php

function smarty_modifier_minute_offset($string=0)
{
    return strtotime("+".$string." minute");
}

?>
