<?php

require_once 'loader.php';
include_once "init.php";
router::handle(network::uri(), code::root_dir());

?>
