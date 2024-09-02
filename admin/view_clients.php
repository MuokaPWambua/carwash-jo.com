<!DOCTYPE html>
<html lang="en">
   <?php include 'includes/head.php';
      
   $sql = "SELECT * FROM clients"; 

   $result = mysqli_query($con, $sql);  
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
                  <h1 class="h3 mb-3 float-left">View Client</h1>
                  <button class='btn btn-primary float-right' data-toggle="modal" data-target="#addClient"> Add Client</button>
                  <div class='clearfix'></div>
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-header">
                              <h5 class="card-title mb-0"><?php echo $message; ?></h5>
                           </div>
                           <div class="card-body">
                               	<table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                              // output data of each row
                            while($row = mysqli_fetch_assoc($result)) {
                                
                                echo '<tr>
                                    <td>'.$row['id'].'</td>
                                    <td>'.$row['first_name'].'</td>
                                    <td>'.$row['email'].'</td>
                                    <td>'.$row['phone_number'].'</td>
                                    <td>'.$row['address'].'</td>

                                    <td class="table-action">
										<a onclick="loadClient('.$row['id'].')" data-id="'.$row['id'].'" type="button" class="btn col" data-toggle="modal" data-target="#deleteModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
									</td>
                                </tr>';
                                ?> 
                            <?php
                            }
                            } else {
                                echo '<tr>
                                        <td colspan="5">No Data</td>
                                    </tr>';
                            }
                        ?> 
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
    
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </main>
            <?php include 'includes/footer.php';?>
         </div>
      </div>
      
      
      									<!-- BEGIN delete modal -->
									<div class="modal fade deleteModal" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">Update Records</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
												</div>
												<div class="modal-body m-3" id="formData">

												</div>
												
											</div>
										</div>
									</div>
									<!-- END delete modal -->
      				<!-- BEGIN delete modal -->
                  <div class="modal fade deleteModal" id="addClient" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Client</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body m-3">
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
									</form>                                    </div>
                                    
                                </div>
                            </div>
                        </div>
									
      <?php include 'includes/scripts.php';?>
   </body>
</html>