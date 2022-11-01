<?php
show_source(__FILE__);
$url=$_GET['url'];
echo file_get_contents($url);