<!DOCTYPE html>
<html lang="en">
   <?php 
   
   include 'includes/head.php';
   
   include 'includes/functions.php';
   
    $staff_query = "SELECT * FROM staff";
    $service_typesql = "SELECT * FROM service_type";

    $staffs = mysqli_query($con, $staff_query);
    $service_type = mysqli_query($con, $service_typesql);
    
    if(isset($_POST['submit'])){
        $message;
        $owner_email = mysqli_real_escape_string($con, $_POST['owner_email']);
        $owner_name = mysqli_real_escape_string($con, $_POST['owner_name']);
        $owner_phone = mysqli_real_escape_string($con, $_POST['owner_phone']);
        $owner_address = mysqli_real_escape_string($con, $_POST['owner_address']);
        $staff = mysqli_real_escape_string($con, $_POST['service_provider']);
        $vehicle_number = mysqli_real_escape_string($con, $_POST['vehicle_number']);
        $service_type2 = mysqli_escape_string($con, $_POST['service_type']);
        $datum = new DateTime();
        $in_time = $datum->format('Y-m-d H:i:s');
        
        $insert = "INSERT INTO queue (owner_email, owner_name, owner_phone, owner_address, staff, vehicle_number, service_type, in_time) VALUES ('$owner_email', '$owner_name', '$owner_phone', '$owner_address', '$staff', '$vehicle_number', '$service_type2', '$in_time') ON DUPLICATE KEY UPDATE owner_email='$owner_email', staff='$staff', owner_name='$owner_name', owner_phone='$owner_phone', owner_address='$owner_address', service_type='$service_type2';";
        
        if(mysqli_query($con, $insert)){
            $message = "Vehicle Information Added.";
            try{
                $subject = "Car Wash  | Your Carwash Inititalized!";
                $id_get = mysqli_query($con, "SELECT * FROM status_type WHERE id='1' LIMIT 1");
                $id = mysqli_fetch_array($id_get);
                $description = "The status of your carwash is ".$id['name'];
                if(sendMail($owner_email, $subject, $owner_name, $description, $vehicle_number)){
                    $message = $message . " Tracking information sent to the customer's email.";
                }else{
                    $message = $message . " Failed to send tracking information to the customer.";
                }
            }catch(Exception $e){
                $message = $message + " Email sending failed.";
            }
         } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
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
                  <h1 class="h3 mb-3">Add New Vehicle</h1>
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-header">
                              <h5 class="card-title mb-0"><?php echo $message; ?></h5>
                           </div>
                           <div class="card-body">
                               	<form action="" method="POST">
										<div class="form-row">
											<div class="form-group col-md-4 col-sm-6 col-lg-4">
												<label for="inputEmail4">Owner's Name</label>
												<input type="text" name="owner_name" class="form-control" placeholder="Owner's Name">
											</div>
											<div class="form-group col-md-4 col-sm-6 col-lg-4">
												<label for="inputPassword4">Vehicle Number</label>
												<input type="text" class="form-control" name="vehicle_number" placeholder="Vehicle Number">
											</div>
											<div class="form-group col-md-4 col-sm-6 col-lg-4">
												<label for="inputState">Service Provider</label>
                        				<select name="service_provider" class="form-control" required>
                                       <option selected>Choose...</option>
                                       <?php
                                          if (mysqli_num_rows($staffs) > 0) {
                                                while($type = mysqli_fetch_assoc($staffs)) {
                                                   echo '<option value="'.$type["id"].'">'.$type["name"].'</option>'; 
                                                }      
                                          }
                                       ?>
                                    </select>
                        			</div>
										</div>
										
										<div class="form-row">
        	                            <div class="form-group col-md-4 col-sm-6 col-lg-4">
        												<label for="service_type">Service Type</label>
                                				<select name="service_type" class="form-control">
                                                <option selected>Choose...</option>
                                                <?php
                                                   if (mysqli_num_rows($service_type) > 0) {
                                                      while($servicetype = mysqli_fetch_assoc($service_type)) {
                                                         echo '<option value="'.$servicetype["id"].'">'.$servicetype["type"].'</option>'; 
                                                      }   
                                                   }
                                                ?>
                                          </select>
                        					</div>
                        											
                        											
											<div class="form-group col-md-4 col-sm-6 col-lg-4">
												<label for="owner_email">Email (To send status updates)</label>
										<input type="email" class="form-control" name="owner_email">
											</div>
											<div class="form-group col-md-4 col-sm-6 col-lg-4">
												<label for="owner_phone">Phone Number</label>
												<input type="text" class="form-control" name="owner_phone">
											</div>
										</div>
										
										
										<div class="form-group">
											<label for="owner_address">Address</label>
											<textarea type="text" class="form-control" name="owner_address" placeholder=""></textarea>
										</div>

										<button name="submit" type="submit" class="btn btn-primary">Add Vehicle</button>
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