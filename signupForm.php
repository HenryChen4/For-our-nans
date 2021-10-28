<?php
    $error = "";
    $success = "";
    $userWarning = "";
    $mailWarning = "";

if(isset($_POST['submit'])){
    $link = mysqli_connect("shareddb-q.hosting.stackcp.net", "user-info-313137ea9c", "Benryben2004###", "user-info-313137ea9c");
    if(mysqli_connect_error()){
        $error.="Unable to connect to server at this moment.";
    }
    $emailQuery = "SELECT id FROM users WHERE email='".mysqli_real_escape_string($link, $_POST['email'])."'";
    $emailResults = mysqli_query($link, $emailQuery);
    $usernameQuery = "SELECT id FROM users WHERE username='".mysqli_real_escape_string($link, $_POST['username'])."'";
    $usernameResults = mysqli_query($link, $usernameQuery);   

    if(mysqli_num_rows($emailResults) == 0 && mysqli_num_rows($usernameResults) == 0){
        $insertQuery = "INSERT INTO users (username, email, password) VALUES ('".mysqli_real_escape_string($link, $_POST['username'])."', '".mysqli_real_escape_string($link, $_POST['email'])."', '".password_hash($_POST['password'], PASSWORD_DEFAULT)."')";
        if(mysqli_query($link, $insertQuery)){
            header("http://thevirtualbubble-com.stackstaging.com/loginForm.php");
        } else {
            $error.="<div class='alert alert-danger' role = 'alert'>Your account cannot be submitted at this time.</div>";
        }
    } else {
        if(mysqli_num_rows($emailResults) > 0){
            $mailWarning.="<div class='alert alert-warning' role = 'alert'>Your email has already been used, please choose another.</div>";
        }
        if(mysqli_num_rows($usernameResults) > 0){
            $userWarning.="<div class='alert alert-warning' role = 'alert'>Your username has already been used, please choose another.</div>";
        }
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
            border: none;
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
              <h3 style="margin-bottom: 15px; color: #023E8A;">Create an account</h3>
              <div> <?php echo $error.$success.$userWarning.$mailWarning ?> </div>
              <div class="form-group custom-container usercon">
                <small id="userHelp" class="form-text">Username</small>
                <input type="text" class="form-control" id="username" aria-describedby="username" name="username" value="<?php echo $_POST["username"] ?>">
              </div>
              <div class="form-group custom-container emailcon">
                <small id="emailHelp" class="form-text">Email</small>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" value="<?php echo $_POST["email"] ?>">
              </div>
              <div class="form-group custom-container passcon">
                <small id="passHelp" class="form-text">Password</small>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $_POST["password"] ?>">
              </div>
              <button type="submit" class="btn btn-primary custom-submit" name="submit">Sign up</button>
              <div style="text-align: center;">
                <small id="switcher" class="form-text" style="color: #0096C7; margin-top: 5px;">Have an existing account? <a href="loginForm.php" style="color: #00B4D8">Log in</a></small>
              </div>
            </form>
        </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script type="text/javascript">
        function clearStyles(p1, p2, p3){
            $("."+p1).css("border-bottom", "");
            $("."+p1+" small").css("color", "");
            $("."+p2).css("border-bottom", "");
            $("."+p2+" small").css("color", ""); 
            $("."+p3).css("border-bottom", "2px solid #48CAE4");
            $("."+p3+" small").css("color", "#0096C7");
        }
        $(".usercon input").focus(function(){
            clearStyles("emailcon", "passcon", "usercon");
        });
        $(".emailcon input").focus(function(){
            clearStyles("usercon", "passcon", "emailcon");
        });
        $(".passcon input").focus(function(){
            clearStyles("emailcon", "usercon", "passcon");
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
        var emailMiss = false;
        var passMiss = false;

        $("form").submit(function(e){
            if($("#username").val() == ""){
                nameMiss = true;
                keepErrorStyles("usercon");
                $("#username").focus(function(){
                    if(emailMiss){
                        keepErrorStyles("emailcon");
                    }
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

            if($("#email").val() == ""){
                emailMiss = true;
                keepErrorStyles("emailcon");
                $("#email").focus(function(){
                    if(nameMiss){
                        keepErrorStyles("usercon");
                    }
                    if(passMiss){
                        keepErrorStyles("passcon");
                    }
                    if(emailMiss){
                        keepErrorStyles("emailcon");
                    }
                    $("#email").keyup(function(){
                        if($("#email").val() != ""){
                            errorStripStyles("emailcon");
                            emailMiss = false;
                        } else {
                            keepErrorStyles("emailcon");
                            emailMiss = true;
                        }
                    });
                });
                e.preventDefault();
            }

            if($("#password").val() == ""){
                passMiss = true;
                keepErrorStyles("passcon");
                $("#password").focus(function(){
                    if(emailMiss){
                        keepErrorStyles("emailcon");
                    }
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