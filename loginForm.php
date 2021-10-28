<?php    
    session_start();
    $error = "";
    $success = "";
    $userWarning = "";
    $passWarning = "";

    if(isset($_POST['submit'])){
        $link = mysqli_connect("shareddb-q.hosting.stackcp.net", "user-info-313137ea9c", "Benryben2004###", "user-info-313137ea9c");
        if(mysqli_connect_error()){
            $error.="Unable to connect to server at this moment.";
        }

        $getUsernameQuery = "SELECT id FROM users WHERE username='".mysqli_real_escape_string($link, $_POST['userIdentification'])."' OR email='".mysqli_real_escape_string($link, $_POST['userIdentification'])."'";
        $usernameResults = mysqli_query($link, $getUsernameQuery);

        if(mysqli_num_rows($usernameResults) > 0){
            $getPasswordQuery = "SELECT password FROM users WHERE username='".mysqli_real_escape_string($link, $_POST['userIdentification'])."' OR email='".mysqli_real_escape_string($link, $_POST['userIdentification'])."'";
            $passwordRow = mysqli_query($link, $getPasswordQuery);
            $arryRow = mysqli_fetch_array($passwordRow);

            $usernameIDArry = mysqli_fetch_array($usernameResults);
            $getUserActualQuery = "SELECT username FROM users WHERE id='".$usernameIDArry[0]."'";
            $userActualResult = mysqli_query($link, $getUserActualQuery);
            $userActualArry = mysqli_fetch_array($userActualResult);
            $_SESSION["usernameActual"] = $userActualArry[0];

            if(password_verify(mysqli_real_escape_string($link, $_POST['password']), $arryRow['password'])){ 
                header("Location: http://thevirtualbubble-com.stackstaging.com/homepage.php");
            } else {
                $passWarning.="<div class='alert alert-warning' role = 'alert'>You have entered the wrong password, please try again.</div>";
            }
        } else {
            $userWarning.="<div class='alert alert-warning' role = 'alert'>Your username / email input cannot be found, please try again.</div>";
        }
    }   
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style type="text/css">
        .custom-form {
            width: 400px;
            border-radius: 4px;
            padding: 20px 20px 30px 20px;
            background: rgb(0,0,0);
            background: linear-gradient(16deg, rgba(0,0,0,1) 0%, rgba(68,59,200,1) 38%, rgba(45,154,225,1) 64%, rgba(28,207,244,1) 84%);
        }

        .custom-container {
            background-color: #CAF0F8;
            border-bottom: 2px solid #e3e3e3;
            height: 70%;
        }

        .custom-container input{
            border: none !important;
            background-color: #CAF0F8 !important;
            border-radius: 0px !important;
            margin-bottom: 2px;
            height: 70%;
        }

        .custom-container small{
            padding: 5px 10px;
        }

        .custom-submit {
            background-color: #48CAE4;
            height: 40px;
            width: 100%;
            border: none;
        }

        input:focus, input.form-control:focus {
            outline:none !important;
            outline-width: 0 !important;
            box-shadow: none;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
        }

        .alert {
            border-radius: 0px !important;
            font-size: 80%;
        }

        .container {
            margin-top: 200px;
        }

        html { 
          background: url(Assets/Imgs/indexBG.jpeg) no-repeat center center fixed; 
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
        }

        body {
            background: none;
        }
    </style>

    <title>The Virtual Bubble</title>
  </head>
  <body>
        <div class="container custom-form">
            <form method="post">
              <h3 style="margin-bottom: 15px; color: #023E8A;">Log in</h3>
              <div> <?php echo $error.$success.$userWarning.$passWarning ?> </div>
              <div class="form-group custom-container usercon">
                <small id="userHelp" class="form-text">Username or email</small>
                <input type="text" class="form-control" id="userIdentification" aria-describedby="username" name="userIdentification" value="<?php echo $_POST["userIdentification"] ?>">
              </div>
              <div class="form-group custom-container passcon">
                <small id="passHelp" class="form-text">Password</small>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $_POST["password"] ?>">
              </div>
              <button type="submit" class="btn btn-primary custom-submit" name="submit">Login</button>
              <div style="text-align: center;">
                <small id="switcher" class="form-text" style="color: #0096C7; margin-top: 5px;">Don't have an account? <a href="index.php" style="color: #00B4D8">Sign up</a></small>
              </div>
            </form>
        </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script type="text/javascript">
        function clearStyles(p1, p2){
            $("."+p1).css("border-bottom", "");
            $("."+p1+" small").css("color", ""); 
            $("."+p2).css("border-bottom", "2px solid #48CAE4");
            $("."+p2+" small").css("color", "#0096C7");
        }
        $(".usercon input").focus(function(){
            clearStyles("passcon", "usercon");
        });
        $(".passcon input").focus(function(){
            clearStyles("usercon", "passcon");
        });

        function keepErrorStyles(elementClass){
            $("."+elementClass).css("border-bottom", "2px solid red");
            $("."+elementClass+" small").css("color", "red");
        }

        function errorStripStyles(elementClass){
            $("."+elementClass).css("border-bottom", "2px solid #48CAE4");
            $("."+elementClass+" small").css("color", "#0096C7");
        }

        function keepWarningStyles(elementClass){
            $("."+elementClass).css("border-bottom", "2px solid orange");
            $("."+elementClass+" small").css("color", "orange");
        }

        var errorNum = 0;
        var nameMiss = false;
        var passMiss = false;

        $("form").submit(function(e){
            if($("#userIdentification").val() == ""){
                nameMiss = true;
                keepErrorStyles("usercon");
                $("#userIdentification").focus(function(){
                    if(passMiss){
                        keepErrorStyles("passcon");
                    }
                    if(nameMiss){
                        keepErrorStyles("usercon");
                    }
                    $("#username").keyup(function(){
                        if($("#username").val() != ""){
                            errorStripStyles("usercon");
                            nameMiss = false;
                        } else {
                            keepErrorStyles("usercon");
                            nameMiss = true;
                        }
                    });
                });
                e.preventDefault();
            } 

            if($("#password").val() == ""){
                passMiss = true;
                keepErrorStyles("passcon");
                $("#password").focus(function(){
                    if(nameMiss){
                        keepErrorStyles("usercon");
                    }
                    if(passMiss){
                        keepErrorStyles("passcon");
                    }
                    $("#password").keyup(function(){
                        if($("#password").val() != ""){
                            errorStripStyles("passcon");
                            passMiss = false;
                        } else {
                            keepErrorStyles("passcon");
                            passMiss = true;
                        }
                    });
                });
                e.preventDefault();
            } 
        });
    </script>
  </body>
</html>