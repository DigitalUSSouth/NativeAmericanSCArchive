<?php
  //if there are http args called 'type' and 'id', then load a view more page
  //corresponding to that id and type. Usually this will be
  //type = images
  //id = some cdm image id
	if(isset($_GET['type']) && isset($_GET['id'])) {
    //be sure id is a number like it should be
    $id = $_GET['id'];
    if(is_numeric($id)) {
      $idint = intval($id);
      //if type isn't interview, images, video, then there's a problem as well 
      switch($_GET['type']) {
        case 'interviews':
          echo 'This is an interview';
          echo 'The id is ' . $id;
          break;
        case 'images':
          echo 'This is an image';
          echo 'The id is ' . $id;
          break;
        case 'video':
          echo 'This is a video';
          echo 'The id is ' . $id;
          break;
        default:
          errorMessage();   
      }
    } else {
      errorMessage();
    }
    
	}
  //if there aren't any arguments, load a default page that goes in the view
  //more box
  else {
    //default page
    printDefault();
  }
  
  function errorMessage() {
    echo 'I\'m afraid you have encountered a bug.';
    echo '"What should I do about this?"';
    echo 'Please let the silly developers know that there\'s a problem in a file called \'home-more.php\'';
  }
  
  function printDefault() {
    echo '<h1>Welcome to the Native American South Carolina Archive!</h1>';
    echo '<p>Click any of the cards on the left to get more information in this window about featured pictures, interviews, and videos in our archive.</p>';
    echo '<p><i>OR</i></p>';
    echo '<p>Click the tabs in the nav bar to see all the images, interviews, etcetera, in one place. You\'re also encouraged to visually learn about local Native American history under the Video, Map, and Timeline tabs!</p>';
  }
?>