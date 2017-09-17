<?php

function is_valid_email ($address) { 

    return (preg_match( 
        '/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+'.   // the user name 
        '@'.                                     // the ubiquitous at-sign 
        '([-0-9A-Z]+\.)+' .                      // host, sub-, and domain names 
        '([0-9A-Z]){2,4}$/i',                    // top-level domain (TLD) 
        trim($address))); 
} 

function is_valid_email_eregi ($address) { 

    return (eregi( 
        '^[-!#$%&\'*+\\./0-9=?A-Z^_`{|}~]+'.      // the user name 
        '@'.                                      // the ubiquitous at-sign 
        '([-0-9A-Z]+\.)+' .                       // host, sub-, and domain names 
        '([0-9A-Z]){2,4}$',                       // top-level domain (TLD) 
        trim($address))); 
} 

function validateURL($url) 
{     return eregi("^((ht|f)tp://)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))((/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$", $url); 
} 

/* Alternative function 
function validateEmail($email) 
{     return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email); 
} 
*/
function printPostVars() {
    global $HTTP_POST_VARS;
    
    foreach ($HTTP_POST_VARS as $value) {
        print($value."<br>\n");
    }
}

?> 
