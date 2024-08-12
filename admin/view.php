<!DOCTYPE html>
<html lang="en">
    <?php include 'includes/head.php';
        
            // Initialize variables for filters
    $staff_filter = "";
    $service_filter = "";
    $status_filter = "";
    $date_filter = "";
    $payment_filter = "";
    $where_conditions = [];
    
    $current_date = date("Y-m-d", strtotime("-1 month"));
    $start_date = $current_date . " 00:00:00";
    $ed = date("Y-m-d");
    $end_date = $ed . " 23:59:59";
    
    // Check if form is submitted
    if (isset($_POST['submit'])) {
        $staff_id = $_POST['staff_id'] ?? '';
        $service_id = $_POST['service_id'] ?? '';
        $status_id = $_POST['status_id'] ?? '';
        $start_date = $_POST['start_date'] ?? $current_date;
        $end_date = $_POST['end_date'] ?? $ed;
    
        // Apply staff filter
        if (!empty($staff_id)) {
            $where_conditions[] = "q.staff = '$staff_id'";
            $payment_filter = "AND staff_id = '$staff_id'";
        }
    
        // Apply service filter
        if (!empty($service_id)) {
            $where_conditions[] = "q.service_type = '$service_id'";
        }
    
        // Apply status filter
        if (!empty($status_id)) {
            $where_conditions[] = "q.status_type = '$status_id'";
        }
    
        // Apply date filter
        if (!empty($start_date) && !empty($end_date)) {
            $where_conditions[] = "q.in_time BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
        }
    }
    
    // Build the WHERE clause if there are any conditions
    $where_clause = "";
    if (!empty($where_conditions)) {
        $where_clause = "WHERE " . implode(" AND ", $where_conditions);
    }
    
    $sales_query = "SELECT 
            q.id, 
            q.last_update,
            q.staff, 
            e.name as staff_name,
            q.in_time,
            q.out_time,
            st.type as 'service_type',
            q.status_type as 'status_type',
            s.name as 'status',
            c.first_name as owner_name,
            q.vehicle_number,
            st.id AS service_id,
            st.type AS service_name,
            st.service_cost AS service_cost,
            st.service_commission AS service_commission,
            COALESCE(SUM(st.service_cost), 0) AS total_revenue,
            COALESCE(SUM(st.service_cost * st.service_commission / 100), 0) AS total_commission    
        FROM 
            service_type st
        LEFT JOIN 
            queue q ON st.id = q.service_type
        LEFT JOIN 
            status_type s ON s.id = q.status_type 
        LEFT JOIN 
            staff e ON e.id = q.staff 
        LEFT JOIN 
            clients c ON c.id = q.client_id 
        $where_clause
        GROUP BY 
            q.id, 
            q.last_update,
            q.staff, 
            staff_name,
            q.in_time,
            q.out_time,
            st.type,
            q.status_type,
            s.name,
            owner_name,
            q.vehicle_number,
            st.id,
            st.type,
            st.service_cost,
            st.service_commission
        ORDER BY 
            q.out_time ASC";
    
    $result = mysqli_query($con, $sales_query);
    
    $message=""; 

    $staff_query = "SELECT * FROM staff";
    $staff_results = mysqli_query($con, $staff_query);
    $staffs =[];

    while($type = mysqli_fetch_assoc($staff_results)) {
        $staffs[] = $type;
    }      

    $service_query = "SELECT * FROM service_type";
    $service_results = mysqli_query($con, $service_query);
    $services =[];

    while($type = mysqli_fetch_assoc($service_results)) {
        $services[] = $type;
    }      

    $status_query = "SELECT * FROM status_type";
    $status = mysqli_query($con, $status_query);   

    $clients_sql = "SELECT * FROM clients";
    $client_results = mysqli_query($con, $clients_sql);    
    $clients = [];

    while($type = mysqli_fetch_assoc($client_results)) {
        $clients[] = $type;
    }      


    if(isset($_POST['submit'])){
        $message;
        
        $owner_name = mysqli_real_escape_string($con, $_POST['owner_name']);
        $staff = mysqli_real_escape_string($con, $_POST['service_provider']);
        $vehicle_number = mysqli_real_escape_string($con, $_POST['vehicle_number']);
        $service_type2 = mysqli_escape_string($con, $_POST['service_type']);
        $datum = new DateTime();
        $in_time = $datum->format('Y-m-d H:i:s');
        
        $insert = "INSERT INTO queue (client_id, staff, vehicle_number, service_type, in_time) VALUES ('$owner_name', '$staff', '$vehicle_number', '$service_type2', '$in_time') ON DUPLICATE KEY UPDATE staff='$staff', client_id='$owner_name', vehicle_number='$vehicle_number', service_type='$service_type2';";
        
        if(mysqli_query($con, $insert)){
            $message = "Vehicle Information Added.";
            try{
                $subject = "Car Wash  | Your Carwash Initialized!";
                $id_get = mysqli_query($con, "SELECT * FROM status_type WHERE id='1' LIMIT 1");
                $id = mysqli_fetch_array($id_get);
                $clients = mysqli_query($con, "SELECT * FROM clients WHERE id='$owner_name' LIMIT 1");
                $client = mysqli_fetch_array($clients);
                $description = "The status of your carwash is ".$id['name'];
                if(sendMail($client['email'], $subject, $client['name'], $description, $vehicle_number)){
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
                  <h1 class="h3 mb-3">View Sales</h1>
                  <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <div class="form-row">
                                                <div class="form-group col-4">
                                                    <label for="inputState">Staff</label>
                                                    <select name="staff_id" class="form-control">
                                                        <option selected value="">Choose...</option>
                                                        <?php
                                                            foreach($staffs as $type) {
                                                                echo '<option value="' . $type["id"] . '">' . $type["name"] . '</option>';
                                                            }
                                                        ?>
                                                    </select>            
                                                </div>
                                                <div class="form-group col-4">
                                                    <label for="inputState">Service</label>
                                                    <select name="service_id" class="form-control">
                                                        <option selected value="">Choose...</option>
                                                        <?php
                                                            foreach ($services as $type) {
                                                                echo '<option value="' . $type["id"] . '">' . $type["type"] . '</option>';
                                                        }
                                                        ?>
                                                    </select>            
                                                </div> 
                                                <div class="form-group col-4">
                                                    <label for="inputState">Status Type</label>
                                                    <select name="status_id" class="form-control">
                                                        <option selected value="">Choose...</option>
                                                        <?php
                                                        if (mysqli_num_rows($status) > 0) {
                                                            while ($type = mysqli_fetch_assoc($status)) {
                                                                echo '<option value="' . $type["id"] . '">' . $type["name"] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>            
                                                </div>                                       
                                                <div class="form-group col-4">
                                                    <label for="inputEmail4">Start Date</label>
                                                    <input type="date" name="start_date" value="<?php echo $current_date ?>" class="form-control">
                                                </div>
                                                <div class="form-group col-4">
                                                    <label for="inputPassword4">End Date</label>
                                                    <input type="date" class="form-control" name="end_date" value="<?php echo $ed ?>">
                                                </div>

                                                <div class="col-4" style="padding-top:1.8rem;">
                                                    <button name="submit" type="submit" class="btn btn-primary btn-fluid w-100">Filter</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <button class='btn btn-primary float-right mb-4' data-toggle="modal" data-target="#addSale"> Record Sale</button>
                    <div class='clearfix mt-3'></div>
                    <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-header">
                                <h5 class="card-title mb-0"><?php echo $message; ?></h5>
                           </div>
                            <div class="card-body">
                               	<table id="example" class="table table-striped table-bordered table-responsive" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vehicle Number</th>
                                        <th>Client</th>
                                        <th>Staff</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Commission</th>
                                        <th>Status</th>
                                        <th>Time In</th>
                                        <th>Last Update</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                              // output data of each row
                              while($row = mysqli_fetch_assoc($result)) {
                                $icon = 'fa-car';
                                $status = 'text-success';
                                
                                if($row["status_type"] == '2'){
                                    $status = 'text-warning';
                                }else if($row["status_type"] == '0'){
                                    $status = 'text-danger';
                                }else if($row["status_type"] == '1'){
                                    $status = 'text-primary';
                                }else if($row["status_type"] == '4'){
                                    $status = 'text-alert';
                                }else if($row["status_type"] == '3'){
                                    $status = 'text-success';
                                }
                                
                                
                                $icon = 'fa-car';
                                
                                
                                echo '<tr>
                                    <td>'.$row['id'].'</td>
                                    <td><i class="align-middle fa '.$icon.'"> </i> '.$row['vehicle_number'].'</td>
                                    <td>'.$row['owner_name'].'</td>
                                    <td>'.$row['staff_name'].'</td>
                                    <td>'.$row['service_type'].'</td>
                                    <td> KSH '.number_format($row['service_cost'], 2).'</td>
                                    <td> '.$row['service_commission'].' %</td>
                                    <td><span class="'.$status.'">'.$row['status'].'</span></td>
                                    <td>'.date('Y M j,  h:i A', strtotime($row['in_time'])).'</td>
                                    <td>'.($row['last_update'] != '' ? date('Y M j,  h:i A', strtotime($row['out_time'])) : null).'</td>
                                    <td class="table-action">
												<a onclick="loadData('.$row['id'].')" data-id="'.$row['id'].'" type="button" class="btn" data-toggle="modal" data-target="#deleteModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
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
                <th>Vehicle Number</th>
                <th>Client</th>
                <th>Staff</th>
                <th>Service</th>
                <th>Amount</th>
                <th>Commission</th>
                <th>Status</th>
                <th>In Time</th>
                <th>Last Update</th>
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
                          <div class="modal fade deleteModal" id="addSale" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Record Sale</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body m-3">
                                    <form action="" method="POST">
										<div class="form-row">
											<div class="form-group col-md-4 col-sm-6 col-lg-4">
												<label for="inputEmail4">Client Name</label>
                        				<select name="owner_name"" class="form-control" required>
                                       <option selected>Choose...</option>
                                       <?php
                                    
                                        foreach($clients as $type ) {
                                            echo '<option value="'.$type["id"].'">'.$type["first_name"].'</option>'; 
                                        }      
                
                                       ?>
                                    </select>
                                    
											</div>
											<div class="form-group col-md-4 col-sm-6 col-lg-4">
												<label for="inputPassword4">Client Vehicle Number</label>
												<input type="text" class="form-control" name="vehicle_number" placeholder="Vehicle Number">
											</div>
											<div class="form-group col-md-4 col-sm-6 col-lg-4">
												<label for="inputState">Staff</label>
                        				<select name="service_provider" class="form-control" required>
                                       <option selected>Choose...</option>
                                       <?php
                                            foreach($staffs as $type) {
                                                echo '<option value="'.$type["id"].'">'.$type["name"].'</option>'; 
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
                                                    foreach($services as $service) {
                                                        echo '<option value="'.$service["id"].'">'.$service["type"].'</option>'; 
                                                    }   
                                                ?>
                                          </select>
                        					</div>
                        											
                        											
											
										</div>
										
							
										<button name="submit" type="submit" class="btn btn-primary">Add Sale</button>
									</form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
						<!-- END delete modal -->
								
      <?php include 'includes/scripts.php';?>
   </body>
</html>