<?php
	session_start();
	$link = mysqli_connect("shareddb-q.hosting.stackcp.net", "users-posts-3131376639", "Benryben2004", "users-posts-3131376639");
	$replyLink = mysqli_connect("shareddb-w.hosting.stackcp.net", "postComments-313439c8dd", "Benryben2004", "postComments-313439c8dd");


	function time_since($since) {
	    $chunks = array(
	        array(60 * 60 * 24 * 365 , 'yr'),
	        array(60 * 60 * 24 * 30 , 'mon'),
	        array(60 * 60 * 24 * 7, 'week'),
	        array(60 * 60 * 24 , 'day'),
	        array(60 * 60 , 'hr'),
	        array(60 , 'min'),
	        array(1 , 'sec')
	    );
	    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
	        $seconds = $chunks[$i][0];
	        $name = $chunks[$i][1];
	        if (($count = floor($since / $seconds)) != 0) {
	            break;
	        }
	    }
	    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
	    return $print;
	}


	function smartDisplayComments($id){
		$reply = "";
		global $replyLink;
		$getReplyQuery = "SELECT * FROM postReplies WHERE postID=".$id." ORDER BY replyTime DESC LIMIT 10";
		$replyResults = mysqli_query($replyLink, $getReplyQuery);
		if(mysqli_num_rows($replyResults) == 0){
			$reply.="<li class='media list-group-item p-a'><div class='media-body'>There is no replies to this post</div></li>";
		} else {
			while($row = mysqli_fetch_assoc($replyResults)){
				$reply.="<li class='media list-group-item p-a'><div class='media-body'><div class='media-heading'><small class='pull-right text-muted'>".time_since(time() - strtotime($row['replyTime']))." ago</small><small class='pull-left text-muted'>".$row['username']." replied</small><br></div><p>".$row['replyContent']."</p></div></li>";
			}
		}
		return $reply;
	}



	function displayPosts(){
		$commentModal = "";
		$post = "";
		global $link;
		$getPostsQuery = "SELECT * FROM userposts ORDER BY `postTime` DESC";
		$postResults = mysqli_query($link, $getPostsQuery);
		if(mysqli_num_rows($postResults) == 0){
			echo "There are no posts to display";
		} else {
			while($row = mysqli_fetch_assoc($postResults)){
				$post.="<li class='media list-group-item p-a'><div class='media-body'><div class='media-heading'><small class='pull-right text-muted'>".time_since(time() - strtotime($row['postTime']))." ago</small><small class='pull-left text-muted'>".$row['username']." posted</small><br><h5>".$row['postTitle']."</h5></div><p>".$row['postContent']."</p></div><svg data-toggle='modal' data-target='#".$row['id']."' width='1.2em' height='1.2em' viewBox='0 0 16 16' class='bi bi-chat' fill='gray' xmlns='http://www.w3.org/2000/svg' ><path fill-rule='evenodd' d='M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z'/></svg><form method='post'> <div class='modal fade' id='".$row['id']."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'><div class='modal-dialog' role='document'><div class='modal-content'><div class='modal-header'><h5 class='modal-title pull-left' id='exampleModalLabel'>Replies to ".$row['postTitle']."</h5><button type='button' class='close pull-right' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div><form method='post' style='padding: 15px; border-bottom: solid 0.5px #e3e3e3;'><div class='input-group'><input type='text' class='form-control' placeholder='Comment' name='comment' id='comment'><div class='input-group-btn'><button type='submit' class='btn btn-default' name='comment' id='".$row['id']."'>Comment</button></div></div></form><div class='modal-body' style='padding: 15px;''><div class='media-body'><ul class='list-group media-list media-list-stream' style='margin-top: 15px; margin-left: 0px !important; margin-right: 0px !important;'><li>".smartDisplayComments($row['id'])."<form method='post'></form></li></ul></div></div></div></div></div>";
			}
		}
		return $post;
	}



	function updateDatabase(){
		global $link;
		$updateQuery = "INSERT INTO userposts (username, postTime, postTitle, postContent) VALUES ('".$_SESSION['usernameActual']."', '".date("Y-m-d H:i:s")."', '".mysqli_real_escape_string($link, $_POST['post-title'])."', '".mysqli_real_escape_string($link, $_POST['post-body'])."')";
		if(mysqli_query($link, $updateQuery)){
			header("Location: http://thevirtualbubble-com.stackstaging.com/homepage.php");
		} else {
			echo "Something went wrong";
		}
	}	


	function updateComments($postID){
		global $replyLink;
		$insertQuery = "INSERT INTO postReplies (username, replyTime, replyContent, postID) VALUES (".$_SESSION['usernameActual'].", ".date("Y-m-d H:i:s").", ".mysqli_real_escape_string($replyLink, $_POST['comment']).", ".$postID.")";
		if(mysqli_query($replyLink, $insertQuery)){
			header("Location: http://thevirtualbubble-com.stackstaging.com/homepage.php");
		} else {
			echo "Something went wrong";	
		}
	}


	if(mysqli_connect_error()){
		die("Unable to connect to database");
	} 

	if(isset($_POST['post'])){
		updateDatabase();
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		unset($_POST);
		header("Location: http://thevirtualbubble-com.stackstaging.com/homepage.php");
	}

	if(isset($_GET['']))
?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <title>
      
        The Virtual Bubble
      
    </title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
    <link href="assets/css/toolkit.css" rel="stylesheet">
    
    <link href="assets/css/application.css" rel="stylesheet">

    <style>
      /* note: this is a hack for ios iframe for bootstrap themes shopify page */
      /* this chunk of css is not part of the toolkit :) */
      body {
        width: 1px;
        min-width: 100%;
        *width: 100%;
      }

      .media-list{
      	margin-left: 20%;
      	margin-right: 20%;
      }

      .navbar-inverse {
      	background: rgb(94,24,168) !important; 
		background: linear-gradient(16deg, rgba(94,24,168,1) 11%, rgba(68,188,255,1) 49%, rgba(4,120,144,1) 83%) !important;
      }
    </style>

  </head>


<body class="with-top-navbar" scrolling="no">
	<nav class="navbar navbar-inverse navbar-fixed-top app-navbar">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-main">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="index.html">
	        The Virtual Bubble
	      </a>
	    </div>
	    <div class="navbar-collapse collapse" id="navbar-collapse-main">

	        <ul class="nav navbar-nav hidden-xs" style="padding-top: 3px; padding-bottom: 0px; height: 90%;">
	          <li>
	            <a href="#">
	            	<svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-house" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					  <path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
					  <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
					</svg>
	            </a>
	          </li>
	          <li>
	            <a href="#">
 					<svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-person-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					  <path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z"/>
					  <path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
					  <path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"/>
					</svg>
	            </a>
	          </li>
	          <li>
	            <a href="#">
					<svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-bell" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					  <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
					  <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
					</svg>
	            </a>
	          </li>
	        </ul>
	        <form class="navbar-form navbar-right app-search" role="search">
	          <div class="form-group">
	            <input type="text" class="form-control" data-action="grow" placeholder="Search">
	          </div>
	        </form>

	        <ul class="nav navbar-nav hidden-sm hidden-md hidden-lg">
	          <li><a href="">Home</a></li>
	          <li><a href="">Profile</a></li>
	          <li><a href="">Notifications</a></li>
	          <li><a href="">Logout</a></li>
	        </ul>

	        <ul class="nav navbar-nav hidden">
	          <li><a href="login/index.html">Logout</a></li>
	        </ul>
	      </div>
	  </div>
	</nav>

	<div class="container custom">
	      <ul class="list-group media-list media-list-stream" style="margin-top: 15px;">
	      	<li class='media list-group-item p-a'>
	      		<div class='media-body'>
					<form method="post" class="postForm">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Disucssion title (Required)" name="post-title" id="post-title">
				            <div class="input-group-btn">
				              <button type="submit" class="btn btn-default" name="post" id="post">
				              	Post
				              </button>
				            </div>
						</div>
						<textarea class="form-control" style="resize: none; margin-top: 15px;" placeholder="Discussion body (Optional)" name="post-body"></textarea>
					</form>	      			
	      		</div>
	      	</li>
	      	<br>
	      	<?php 
	      		echo displayPosts();
	      	?>
	      </ul>
	</div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/chart.js"></script>
    <script src="assets/js/toolkit.js"></script>
    <script src="assets/js/application.js"></script>
    <script>
      // execute/clear BS loaders for docs
      $(function(){
        if (window.BS&&window.BS.loader&&window.BS.loader.length) {
          while(BS.loader.length){(BS.loader.pop())()}
        }
      })

      function keepErrorStyles(elementID, secondID){
          $("#"+elementID).css("border", "0.5px solid red");
          $("#"+secondID).css("border-left", "0.5px solid red");
      }

        function errorStripStyles(elementID, secondID){
            $("#"+elementID).css("border", "");
            $("#"+secondID).css("border-left", "");
        }

      $('.postForm').submit(function(e){
      	if($("#post-title").val() == ""){
      		var missingTitle = true;
            keepErrorStyles("post-title", "post");
            $("#post-title").focus(function(){
                if(missingTitle){
                        keepErrorStyles("post-title", "post");
                    }
                    $("#post-title").keyup(function(){
                        if($("#post-title").val() != ""){
                            errorStripStyles("post-title", "post");
                            missingTitle = false;
                        } else {
                            keepErrorStyles("post-title", "post");
                            missingTitle = true;
                        }
                    });
                });
                e.preventDefault();
      	}
      });
    </script>
  </body>
</html>

 