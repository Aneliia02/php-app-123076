<?php
session_unset(); //izliza ot sesiata 
session_destroy();//unishtojava dannite

header('Location: ../index.php');
exit;
?>