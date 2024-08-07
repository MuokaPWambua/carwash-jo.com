<!DOCTYPE html>
<html lang="en">
   <?php include 'includes/head.php';
   
        
    $sql = "SELECT 
        e.id AS employee_id,
        e.name AS employee_name,
        e.employee_status AS status_type,
        e.employee_email AS employee_email,
        e.employee_contact AS employee_phone,
        e.employee_address AS employee_address,
        SUM(CASE WHEN q.status_type = 3 THEN st.service_cost ELSE 0 END) AS total_revenue,
        SUM(CASE WHEN q.status_type = 3 THEN st.service_cost * st.service_commission / 100 ELSE 0 END) AS total_commission
    FROM 
        staff e
    LEFT JOIN 
        queue q ON e.id = q.staff
    LEFT JOIN 
        service_type st ON q.service_type = st.id
    GROUP BY 
        e.id, e.name, e.employee_email, e.employee_contact, e.employee_address
    ORDER BY
        total_revenue
    ASC LIMIT 1000";
        
    $result = mysqli_query($con, $sql);
        
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
                  <h1 class="h3 mb-3">View All Employees</h1>
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
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Revenue</th>
                                        <th>Commission</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                              // output data of each row
                            while($row = mysqli_fetch_assoc($result)) {
                                $status = 'text-success';
                                
                                if($row["status_type"] == '2'){
                                    $status = 'text-warning';
                                }else if($row["status_type"] == '0'){
                                    $status = 'text-danger';
                                }else if($row["status_type"] == '1'){
                                    $status = 'text-primary';
                                }else if($row["status_type"] == '4'){
                                    $status = 'text-alert';
                                }else if($row["status_type"] == 'idle'){
                                    $status = 'text-success';
                                }
                                
                                
                                    $icon = 'fa-user';
                                
                                
                                echo '<tr>
                                    <td>'.$row['employee_id'].'</td>
                                    <td><i class="align-middle fa '.$icon.'"> </i> '.$row['employee_name'].'</td>
                                    <td>'.$row['employee_email'].'</td>
                                    <td>'.$row['employee_address'].'</td>
                                    <td><span class="'.$status.'">'.$row['status_type'].'</span></td>
                                    <td>'.$row['total_revenue'].'</td>
                                    <td>'.$row['total_commission'].'</td>
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
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Status</th>
                <th>Commission</th>
                <th>Revenue</th>
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
									
      <?php include 'includes/scripts.php';?>
   </body>
</html>