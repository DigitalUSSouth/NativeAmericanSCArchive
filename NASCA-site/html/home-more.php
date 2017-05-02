<?php
	//type and id args
	if(isset($_GET['type']) && isset($_GET['id'])) {
    //if type isn't interview, images, video, then there's a problem as well 
		echo $_GET['type'] . $_GET['id'];
	} else {
    //default page
    echo 'default';
  }
?>