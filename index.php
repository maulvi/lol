<?php

error_reporting(0);
session_start();

include "system/function.php";
$title = config('site.tag')." - ".config('site.title');
include "header.php";
include "system/menu/root.php";
include "footer.php";