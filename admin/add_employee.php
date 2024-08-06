<!DOCTYPE html>
<html lang="en">
   <?php 
   
   include 'includes/head.php';
   
   include 'includes/functions.php';
   $message="";
   
   if(isset($_POST['submit'])){
      $message;
      $employee_email = mysqli_real_escape_string($con, $_POST['employee_email']);
      $employee_name = mysqli_real_escape_string($con, $_POST['employee_name']);
      $employee_contact = mysqli_real_escape_string($con, $_POST['employee_contact']);
      $employee_address = mysqli_real_escape_string($con, $_POST['employee_address']);

      $insert = "INSERT INTO staff (employee_email, name, employee_contact, employee_address) VALUES ('$employee_email', '$employee_name', '$employee_contact', '$employee_address') ON DUPLICATE KEY UPDATE employee_email='$employee_email', name='$employee_name', employee_contact='$employee_contact', employee_address='$employee__address';";
      
      if(mysqli_query($con, $insert)){
         $message = "Employee Information Added.";
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
                  <h1 class="h3 mb-3">Add New Employee</h1>
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-header">
                              <h5 class="card-title mb-0"><?php echo $message; ?></h5>
                           </div>
                           <div class="card-body">
                           <form action="" method="POST">
										<div class="form-row">
											<div class="form-group col-md-6">
												<label for="inputEmail4">Employee Name</label>
												<input type="text" name="employee_name" class="form-control" placeholder="Employee's Name" required>
											</div>
											<div class="form-group col-md-3">
												<label for="inputPassword4">Employee Phone</label>
												<input type="number" class="form-control" name="employee_contact" placeholder="2547958567829" required>
											</div>
											<div class="form-group col-md-3">
												<label for="inputState">Employee Email</label>
												<input type="email" class="form-control" name="employee_email" placeholder="email@carwash.co.ke" required>
        									</div>
										</div>
										<div class="form-group">
											<label for="owner_address">Employee Address</label>
											<input type="text" class="form-control" name="employee_address" placeholder="carwash, nairobi, kenya"/>
										</div>

										<button name="submit" type="submit" class="btn btn-primary">Add Employee</button>
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