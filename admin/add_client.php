<!DOCTYPE html>
<html lang="en">
   <?php 
   
   include 'includes/head.php';
   
   include 'includes/functions.php';
   $message="";
   
   if(isset($_POST['submit'])){
      $message;
      $client_email = mysqli_real_escape_string($con, $_POST['client_email']);
      $client_name = mysqli_real_escape_string($con, $_POST['client_name']);
      $client_contact = mysqli_real_escape_string($con, $_POST['client_contact']);
      $client_address = mysqli_real_escape_string($con, $_POST['client_address']);

      $insert = "INSERT INTO clients (email, first_name, phone_number, address) VALUES ('$client_email', '$client_name', '$client_contact', '$client_address') ON DUPLICATE KEY UPDATE email='$client_email', first_name='$client_name', phone_number='$client_contact', address='$client_address';";
      
      if(mysqli_query($con, $insert)){
         $message = "Client Information Added.";
      } else {
         $message = "Error: " . mysqli_error($conn);
      }
      
   }
   ?>
   <body>
      <div class="wrapper">
         <?php include 'includes/nav.php';?>
         <div class="main">
            <?php include 'includes/navtop.php';?>
            <main class="content">
               <div class="container-fluid p-0">
                  <h1 class="h3 mb-3">Add Client</h1>
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-header">
                              <h5 class="card-title mb-0"><?php echo $message; ?></h5>
                           </div>
                           <div class="card-body">
                           <form action="" method="POST">
										<div class="form-row">
											<div class="form-group col-md-4">
												<label for="inputEmail4">Client Name</label>
												<input type="text" name="client_name" class="form-control" placeholder="Client Name" required>
											</div>
											<div class="form-group col-md-4">
												<label for="inputPassword4">Client Phone</label>
												<input type="number" class="form-control" name="client_contact" placeholder="2547958567829" required>
											</div>
											<div class="form-group col-md-4">
												<label for="inputState">Client Email</label>
												<input type="email" class="form-control" name="client_email" placeholder="email@carwash.co.ke" required>
        									</div>
										</div>
										<div class="form-group">
											<label for="owner_address">Client Address</label>
											<input type="text" class="form-control" name="client_address" placeholder="carwash, nairobi, kenya"/>
										</div>

										<button name="submit" type="submit" class="btn btn-primary">Add Client</button>
									</form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            <?php include 'includes/footer.php';?>
         </div>
      </div>
      <?php include 'includes/scripts.php';?>
   </body>
</html>