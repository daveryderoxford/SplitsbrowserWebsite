<?php

WILDCARD = "[any]";

function addWhereClause($quesy, $field, $clause)
{
    if ($clause != WILDCARD) {
       $query = $query ."  " . $field "='" . $clause "'";
    }
}

function addAndClause($quesy, $field, $clause)
{
    if ($clause != WILDCARD) {
       $query = $query ."  " . $field "='" . $clause "'";
    }
}

}
?>
