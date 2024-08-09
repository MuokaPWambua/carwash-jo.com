<!DOCTYPE html>
<html lang="en">
   <?php include 'includes/head.php';
   
   $sql = "SELECT 
        st.id AS service_id,
        st.type AS service_name,
        st.service_cost AS service_cost,
        st.service_commission AS service_commission,
        COALESCE(SUM(CASE WHEN q.status_type = 3 THEN st.service_cost ELSE 0 END), 0) AS total_revenue,
        COALESCE(SUM(CASE WHEN q.status_type = 3 THEN st.service_cost * st.service_commission / 100 ELSE 0 END), 0) AS total_commission    
    FROM 
        service_type st
    LEFT JOIN 
        queue q ON st.id = q.service_type
    GROUP BY 
        st.id
    ORDER BY 
        st.id
    ASC LIMIT 1000";
        
    $result = mysqli_query($con, $sql);
        
    $message="";   
    
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
                  <h1 class="h3 mb-3">View All Services</h1>
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
                                        <td>'.$row['service_cost'].'</td>
                                        <td>'.$row['service_commission'].'</td>
                                        <td>'.$row['total_revenue'].'</td>
                                        <td>'.$row['total_commission'].'</td>
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
									
      <?php include 'includes/scripts.php';?>
   </body>
</html>