<?php

// create a new session
session_start();

function getNationality() {
  if (!session_is_registered("sessionNationality")) :
    session_register("sessionNationality");
    $sessionNationality=$WILDCARD;
  };
  return($sessionNationality);
}

?>

