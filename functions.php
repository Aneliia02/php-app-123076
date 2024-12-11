<?php


function debug($data,$exit=false){
    echo'<pre>';
print_r($data);
echo'</pre>';
if($exit){

    exit;
}
}

function is_admin(){
    return isset($_SESSION['is_admin']) and $_SESSION['is_admin'];
}

?>