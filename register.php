<?php
require_once "config.php";

$username = $password = $confirm_password = $email = $gender = $city = $hobbies= "";
$username_err = $password_err = $confirm_password_err = $err = $gender_err = $city_err = $hobbies_err= "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Checking if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "*Username cannot be blank";
        
    }
    else{
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = trim($_POST['username']);

            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "*This username is already taken"; 
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong";
            }
        } mysqli_stmt_close($stmt);
    }

       


// Check for password
if(empty(trim($_POST['password']))){
    $password_err = "*Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err = "*Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

// Check for confirm password field
if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
    $confirm_password_err = "*Passwords should match";
}
// Check for email
if(empty(trim($_POST['email']))){
  $err = "*Email cannot be blank";
}
elseif(isset($_POST['email']) && !empty($_POST['email'])){
  if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)== false){
    $err="*Email is not valid";
  }

else{
  $sql = "SELECT id FROM users WHERE email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  if($stmt)
  {
      mysqli_stmt_bind_param($stmt, "s", $param_email);

      
      $param_email = trim($_POST['email']);

      
      if(mysqli_stmt_execute($stmt)){
          mysqli_stmt_store_result($stmt);
          if(mysqli_stmt_num_rows($stmt) == 1)
          {
              $err = "*This email is already taken"; 
          }
          else{
              $email = trim($_POST['email']);
          }
      }
      else{
          echo "Something went wrong";
      }
  } mysqli_stmt_close($stmt);
}
}
//check for gender
if(empty(trim($_POST['gender']))){
  $gender_err = "*Please enter gender";
}
else{
  $gender = trim($_POST['gender']);
}
//check for city
if(trim($_POST['city']) == 'Choose...'){
  $city_err = "*Please select city";
}
else{
  $city = trim($_POST['city']);
}
//check for gender
if(empty($_POST['hobbies'])){
  $hobbies_err = "*Please select hobbies";
}
else{
  $hobbies = implode(",",$_POST['hobbies']);
  
  
}


if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($err) && empty($gender_err) && empty($city_err) && empty($hobbies_err))
{
    $sql = "INSERT INTO users (username, password, email, gender, city, hobbies) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
      
        mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_email, $param_gender, $param_city, $param_hobbies);

        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);
        $param_email = $email;
        $param_gender = $gender;
        $param_city = $city;
        $param_hobbies = $hobbies;

     
        if (mysqli_stmt_execute($stmt))
        {
            header("location:login.php");
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }mysqli_stmt_close($stmt);
    
}


mysqli_close($conn);
}
?>




<!doctype html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Login system!</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Php Login System</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
  <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="register.php">Register</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="login.php">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>

      
     
    </ul>
  </div>
</nav>

<div class="container mt-4">
<h3>Please Register Here:</h3>
<hr>
<form action="" method="post">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="username">Username</label>
      <input type="text" class="form-control" name="username" id="username" placeholder="Username">
      <p style="color:red";><?php if(isset($username_err)) echo $username_err;?></p>
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Password</label>
      <input type="password" class="form-control" name ="password" id="inputPassword4" placeholder="Password">
      <p style="color:red";><?php if(isset($password_err)) echo $password_err;?></p>
    </div>
  </div>
  <div class="form-group">
      <label for="inputPassword">Confirm Password</label>
      <input type="password" class="form-control" name ="confirm_password" id="inputPassword" placeholder="Confirm Password">
      <p style="color:red";><?php if(isset($confirm_password_err)) echo $confirm_password_err;?></p>
  </div>
    
  <div class="form-group">
    <label for="email">Email</label>
    <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email">
    <p style="color:red";><?php if(isset($err)) echo $err;?></p>
  </div>

  <div class="form-group">
    Gender
    <div class="form-check">
      <input class="form-check-input" type="radio" name="gender" id="male" value="male">
      <label class="form-check-label" for="male">
      Male
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="gender" id="female" value="female">
      <label class="form-check-label" for="female">
      Female
      </label>
    </div>
    <p style="color:red";><?php if(isset($gender_err)) echo $gender_err;?></p>
  </div>
  <div class="form-group">
    <label for="city">City</label>
    <select id="city" name="city" class="form-control">
      <option>Choose...</option>
      <option>Ambala</option>
      <option>Karnal</option>
      <option>Sonipat</option>
      <option>panipat</option>
      <option>Kurukshetra</option>
    </select>
    <p style="color:red";><?php if(isset($city_err)) echo $city_err;?></p>
  </div>
  <div class="form-group">
     Hobbies
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="hobbies[]" id="reading" value="Reading novels">
        <label class="form-check-label" for="reading">
          Reading novels
        </label>
      </div>
      <div class="form-check">
      <input class="form-check-input" type="checkbox" name="hobbies[]" id="playing" value="Playing badminton">
        <label class="form-check-label" for="playing">
          Playing badminton
        </label>
      </div>
      <div class="form-check">
      <input class="form-check-input" type="checkbox" name="hobbies[]" id="music" value="Listening music">
        <label class="form-check-label" for="music">
          Listening Music
        </label>
      </div>
      <p style="color:red";><?php if(isset($hobbies_err)) echo $hobbies_err;?></p>
    </div>
  
 
  
  <button type="submit" class="btn btn-primary">Sign in</button>
</form>
</div>

   
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
