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
      $employee_status = mysqli_real_escape_string($con, $_POST['employee_status']);

      $insert = "INSERT INTO staff (employee_email, name, employee_status, employee_contact, employee_address) VALUES ('$employee_email', '$employee_name', '$employee_status', '$employee_contact', '$employee_address') ON DUPLICATE KEY UPDATE employee_email='$employee_email', name='$employee_name', employee_contact='$employee_contact', employee_address='$employee_address', employee_status='$employee_status';";
      
      if(mysqli_query($con, $insert)){
         $message = "Staff Information Added.";
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
                  <h1 class="h3 mb-3">Add New Staff</h1>
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-header">
                              <h5 class="card-title mb-0"><?php echo $message; ?></h5>
                           </div>
                           <div class="card-body">
                           <form action="" method="POST">
										<div class="form-row">
											<div class="form-group col-6">
												<label for="inputEmail4">Staff Name</label>
												<input type="text" name="employee_name" class="form-control" placeholder="Staff's Name" required>
											</div>
											<div class="form-group col-6">
												<label for="inputPassword4">Staff Phone</label>
												<input type="number" class="form-control" name="employee_contact" placeholder="2547958567829" required>
											</div>
                                 <div class="form-group col-12">
                                    <label for="owner_address">Staff Address</label>
                                    <input type="text" class="form-control" name="employee_address" placeholder="carwash, nairobi, kenya"/>
                                 </div>
                                 <div class="form-group col-6">
												<label for="inputState">Staff Email</label>
												<input type="email" class="form-control" name="employee_email" placeholder="email@carwash.co.ke" required>
        									</div>

                                 <div class="form-group col-6">
                                    <label for="owner_address">Status</label>
                                    <select name='employee_status' class="form-control">                            
                                       <option selected value=''>Select</option>
                                       <option value='active'>Active</option>
                                       <option value='unavailable'>Unavailable</option>
                                       <option value='idle'>Idle</option>
                                    </select>
                                 </div>

										</div>

										<button name="submit" type="submit" class="btn btn-primary">Add Staff</button>
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