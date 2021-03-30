<!DOCTYPE html>
<html>
<head>
  <title>IT490</title>
  <link rel="stylesheet" type="text/css" href="style.css? <?php echo time(); ?>" />
</head>
<body>
  <div class="header">
        <h2>Register</h2>
  </div>

  <form method="POST" action="connection.php">

     <?php if(!empty($_GET['error']) && $_GET['error']=='true'){ ?>
        <h2 style="color:red;">Passwords do not match!</h2>
	<?php } ?> 

     <input type="hidden" value="register" name="option"><!--e-->
        <div class="input-group">
          <label>Username</label>
          <input required type="text" name="username" autocomplete="off">
        </div>

        <div class="input-group">
          <label>Password</label>
          <input required type="password" name="password_1" autocomplete="new-password">
        </div>
        <div class="input-group">
          <label>Confirm password</label>
          <input required type="password" name="password_2" autocomplete="new-password">
        </div>
        <div class="input-group">
          <button type="submit" class="btn" name="reg_user">Register</button>
        </div>
        <p>
                Already a member? <a href="index.html">Sign in</a>
        </p>
  </form>
</body>
</html>
