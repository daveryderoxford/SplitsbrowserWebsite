<?php

$EVENT_TYPE_IOF = 4;
$EVENT_TYPE_INT = 3;
$EVENT_TYPE_NAT = 2;
$EVENT_TYPE_REGION = 1;
$EVENT_TYPE_LOCAL = 0;

function getEventTypeIds()
{
    $prvTypes = array(4,3,2,1,0);
    return($prvTypes);
}

function getEventTypeNames()
{
    $prvTypes = array("IOF/Elite", "International", "National", "Regional", "Local");
    return($prvTypes);
}
?>
