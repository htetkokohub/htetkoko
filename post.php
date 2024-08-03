<?php
  header("Location: index.php");
  if(isset($_POST['send'])){
    $message = $_POST['message'];
    $filename = fopen("message.txt", "a") or die ("Error!");
    fwrite($filename, "Message: \n");
    fwrite($filename, $message."\n \n");
    fclose($filename);
 }
?>