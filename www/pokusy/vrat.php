<?php
if(isset($_GET["q"]) && !empty($_GET["q"])){
    if($_GET["q"] === "mates")
        echo 1;
    else
        echo 0;
}
else
    echo -1;
?>