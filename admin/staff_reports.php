<!DOCTYPE html>
<html lang="en">
<?php 

    include 'includes/head.php';
    include 'includes/functions.php';

    $staff_query = "SELECT * FROM staff";
    $staffs = mysqli_query($con, $staff_query);
        
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
    if(isset($_POST['submit'])){
        $message;
        $service_name = mysqli_real_escape_string($con, $_POST['service_name']);
        $service_cost = mysqli_real_escape_string($con, $_POST['service_cost']);
        $service_commission = mysqli_real_escape_string($con, $_POST['service_commission']);

        $insert = "INSERT INTO service_type (type, service_cost, service_commission) VALUES ('$service_name', '$service_cost', '$service_commission') ON DUPLICATE KEY UPDATE type='$service_name', service_cost='$service_cost', service_commission='$service_commission';";
        
        if(mysqli_query($con, $insert)){
            $message = "Service Information Added.";
        } else {
            $message = "Error: " . "<br>" . mysqli_error($conn);
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
                  <h1 class="h3 mb-2">Staff Reports</h1>
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                           <div class="card-header">
                            <h5 class="card-title mb-0"><?php echo $message; ?></h5>
                           </div>
                           <div class="card-body">
                               	<form action="" method="POST">
                                    <div class="form-row">
                                        <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                            <label for="inputEmail4">Start Date</label>
                                            <input type="date" name="start_date" class="form-control">
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                            <label for="inputPassword4">End Date</label>
                                            <input type="date" class="form-control" name="end_date">
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                            <label for="inputState">Staff</label>
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
                                        <div class="col-md-3 col-sm-6 col-lg-3 " style="padding-top:1.8rem;">
                                            <button name="submit" type="submit" class="btn btn-primary w-100">Filter</button>
                                        </div>
                                    </div>
                                    
								</form>
                            </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                            <div class="card-body row text-center pt-5">
                                <div class='col-4'>
                                    <h2>TOTAL PAYMENT</h2>
                                    <p class='lead'>KSH 300,000</p>
                                </div>
                                <div class='col-4'>
                                    <h2>TOTAL COMMISSION</h2>
                                    <p class='lead'>KSH 330,000</p>
                                </div>
                                <div class='col-4'>
                                    <h2>TOTAL DUE</h2>
                                    <p class='lead'>KSH 30,000</p>
                                </div>
                            </div>
                        </div>
                     </div>

                  </div>
                  <div class="row">
                     <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                            <table id="example" class="table table-striped table-responsive table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
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
                                    <td>'.$row['employee_phone'].'</td>
                                    <td>'.$row['employee_address'].'</td>
                                    <td><span class="'.$status.'">'.$row['status_type'].'</span></td>
                                    <td>'.$row['total_revenue'].'</td>
                                    <td>'.$row['total_commission'].'</td>
                                    <td class="table-action">
										<a onclick="loadStaff('.$row['employee_id'].')" data-id="'.$row['employee_id'].'" type="button" class="btn" data-toggle="modal" data-target="#deleteModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
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
      <?php include 'includes/scripts.php';?>
   </body>
</html>