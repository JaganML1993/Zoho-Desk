<?php
  // Start the session
  ob_start();
  session_start();
  require_once('connection.php');

  if(isset($_POST['save_user'])){
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    // check user already exists
    $qry_user = mysqli_query($conn, "SELECT email FROM user WHERE email = '$user_email' AND password = '$user_password' ");
    $row_user = mysqli_fetch_assoc($qry_user);
    $count_user = mysqli_num_rows($qry_user);

    if($count_user > 0){
      $_SESSION['useremail'] = $row_user['email'];
      header('location:tickets.php');
    }else{
      echo "<script>alert('please enter valid email and password'); </script>";
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

    <title>Ticket Management</title>
    <style>
    .login-form {
        width: 400px;
        margin: 50px auto;
        font-size: 15px;
    }
    .login-form form {
        margin-bottom: 15px;
        background: #f7f7f7;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 50px;
    }
    .login-form h2 {
        margin: 0 0 15px;
    }
    .form-control, .btn {
        min-height: 38px;
        border-radius: 2px;
    }
    .btn {        
        font-size: 15px;
        font-weight: bold;
    }
    </style>
  </head>
  <body>

    <div class="login-form">
      <form method="post">
          <h4 class="text-center text-secondary">Ticket Management</h4>       
          <br>
          <div class="form-group">
              <input type="email" class="form-control" placeholder="Email" name="user_email" required="required">
          </div>
          <div class="form-group">
              <input type="password" class="form-control" placeholder="Password" name="user_password" required="required">
          </div>
          <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block" name="save_user">Sign in</button>
          </div>
      </form>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>