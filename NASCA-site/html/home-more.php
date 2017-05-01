<?php
	//type and id args
	if(isset($_GET['type'])) {
    //if type isn't interview, images, video, then there's a problem as well
		echo $_GET['type'];
	} else {
    die('there was a problem');
  }
	if(isset($_GET['id'])) {
		echo $_GET['id'];
	} else {
      die('there was a problem');
  }
?>