<!DOCTYPE html>
<html lang="en">
   <?php include 'includes/head.php';
   
   $sql = "SELECT 
        st.service_type_id AS service_id,
        st.type AS service_name,
        st.service_cost as service_cost,
        st.service_commission as service_commission,
        SUM(st.total_service_cost) AS total_service_cost,
        SUM(st.total_service_commission) AS total_service_commission,
        SUM(st.total_service_cost) AS total_revenue, 
        SUM(st.total_service_commission) AS total_commission 
    FROM 
        (
        SELECT 
            sa.service_type_id as service_type_id,
            sa.queue_id as queue_id,
            sts.type as type,
            sts.service_cost as service_cost,
            sts.service_commission as service_commission,
            SUM(sts.service_cost) AS total_service_cost,
            SUM(sts.service_cost * sts.service_commission / 100) AS total_service_commission 
        FROM
            service_assignment sa  
        JOIN
            service_type sts ON sts.id = sa.service_type_id
        GROUP BY 
            service_type_id, queue_id, type
        ) st
    JOIN 
        queue q ON q.id = st.queue_id
    GROUP BY 
        st.service_type_id, st.type
    ORDER BY 
        st.service_type_id";

        
    $result = mysqli_query($con, $sql);
    $message ="";
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
                  <h1 class="h3 mb-3 float-left">View All Services</h1>
                  <button class='btn btn-primary float-right' data-toggle="modal" data-target="#addService"> Add Service</button>
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
                                        <th>Service</th>
                                        <th>Service Cost</th>
                                        <th>Service Commission</th>
                                        <th>Total Revenue</th>
                                        <th>Total Commission</th>
                                        <th>Action</th>                          
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                                if (mysqli_num_rows($result) > 0) {
                                // output data of each row
                                while($row = mysqli_fetch_assoc($result)) {    
                                    echo '<tr>
                                        <td>'.$row['service_id'].'</td>
                                        <td>'.$row['service_name'].'</td>
                                        <td> KSH '.number_format($row['service_cost']).'</td>
                                        <td>'.$row['service_commission'].' % </td>
                                        <td> KSH '.number_format($row['total_revenue']).'</td>
                                        <td> KSH '.number_format($row['total_commission']).'</td>
                                        <td class="table-action">
                                            <a onclick="loadService('.$row['service_id'].')" data-id="'.$row['service_id'].'" type="button" class="btn" data-toggle="modal" data-target="#deleteModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
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
                <th>Service</th>
                <th>Service Cost</th>
                <th>Service Commission</th>
                <th>Total Revenue</th>
                <th>Total Commission</th>
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
                                                          <div class="modal fade deleteModal" id="addService" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Service</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body m-3">
                                    <form action="" method="POST">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Service Name</label>
                                            <input type="text" name="service_name" class="form-control" placeholder="Buffing" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="inputPassword4">Service Cost</label>
                                            <input type="number" class="form-control" name="service_cost" placeholder="3000" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="inputState">Service Commission</label>
                                            <input type="number" class="form-control" name="service_commission" placeholder="20%" required>
                                        </div>
                                    </div>
                                    
									<button name="submit" type="submit" class="btn btn-primary">Add Service</button>
								</form>             
                                                       </div>
                                    
                                </div>
                            </div>
                        </div>
									

      <?php include 'includes/scripts.php';?>
   </body>
</html>