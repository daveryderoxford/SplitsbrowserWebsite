<?php
#if (!defined ("_STDCTRL_LIB_")):

// =================================== 
//      Standart HTML controls 
//         generator library 
//        Copyleft Softerra LLC 
//          www.softerra.com 
//      Author: bobd@softerra.com 
// =================================== 
//           Version 1.1 
// =================================== 
//  -------------------------------- 
//  ------ Declared functions ------ 
//  -------------------------------- 
// 
//  makeOptionsFromSimpleArray ($array, $selectedItems=false) 
//  makeOptionsFromArray ($array, $valueField, $textField, $selectedItems=false) 
//  makeOptionsFromHashArray ($array, $selectedItems=false, $reverse=false) 
//  makeRadioFromArray ($ctrlName, $array, $checkedIndex=false) 
//  makeCheckboxesFromArray ($ctrlName, $array, $checkedItems=false) 
// 
//  -------------------------------- 
// ================================== 

#define (_STDCTRL_LIB_, "stdctrl.lib.php");

// String too denote the selection of all items
// used in queries
$WILDCARD ="[any]";

function in_values ($val, $vals) { 
    if (is_array ($vals)) 
        return in_array ($val, $vals); 
    else 
        return $val == $vals; 
} 

function outOption ($value, $text, $selected) { 
    $s = sprintf ("<option value=\"%s\" %s>%s", $value, $selected ? "selected":"", $text);
    return($s);
} 

function outRadio ($name, $value, $text, $checked) { 
    $s = sprintf ("<input type=radio name=\"%s\" value=\"%s\" %s>%s <BR>", $name, $value, $checked ? "checked":"", $text);
    return($s);
} 

function outLinkedRadio ($name, $value, $text, $link, $checked) {
    $s = sprintf ("<input type=radio name=\"%s\" value=\"%s\" %s>%s &nbsp&nbsp<a href=\"%s\">info</a> <BR>",
               $name, $value, $checked ? "checked":"", $text, $link);
    return($s);
}

function outCheckbox ($name, $value, $text, $checked) {
    $s = sprintf("<input type=checkbox name=\"%s\" value=\"%s\" %s>%s <BR>", $name, $value, $checked ? "checked":"", $text);
    return($s);
} 

function makeOptionsFromSimpleArray ($array, $selectedItems=false) {
    $s="";
     for ($i=0; $i < count ($array); $i++) {
        $s .= outOption ($array[$i], $array[$i], in_values ($array[$i], $selectedItems));
    }
    return($s);
} 

function makeOptionsFromArray ($array, $textarray, $selectedItems=false) {
    $s="";
    for ($i=0; $i < count ($array); $i++) {
        $value = $array[$i];
        $text = $textarray[$i];
        $s .= outOption ($value, $text, in_values ($value, $selectedItems));
    } 
    return($s);
} 

function makeOptionsFromHashArray ($array, $selectedItems=false, $reverse=false) { 
    if ($reverse) { 
        $key = "value"; 
        $val = "text"; 
    } else { 
        $key = "text"; 
        $val = "value"; 
    } 
    $s="";
    while (list (${$key}, ${$val}) = each ($array)) {
        $s .= outOption ($value, $text, in_values ($value, $selectedItems));
    }
    return($s);
} 

function makeRadioFromArray ($ctrlName, $array, $textarray,  $checkedIndex=false) { 
    $s="";
    for ($i=0; $i<count($array); $i++) {
        $s .= outRadio($ctrlName, $array[$i], $textarray[i], $array[$i]==$checkedIndex);
    }
    return($s);
} 

function makeLinkedRadioFromArray ($ctrlName, $array, $textarray, $linksarray, $checkedValue=false) {
    $s="";
    for ($i=0; $i<count($array); $i++) {
        $s .= outLinkedRadio($ctrlName, $array[$i], $textarray[i], $linksarray[$i],  $array[$i]==$checkedValue);
    }
    return($s);
}

function makeCheckboxesFromArray ($ctrlName, $array, $checkedItems=false) {
    $s="";
    for($i=0; $i<count ($array); $i++) {
        $s .= outCheckbox ($ctrlName . $i, $i, $array[$i], in_values ($i, $checkedItems));
    }
    return($s);
} 

#endif
?>
