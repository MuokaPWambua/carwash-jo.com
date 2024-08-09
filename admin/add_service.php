<!DOCTYPE html>
<html lang="en">
<?php 

    include 'includes/head.php';
    include 'includes/functions.php';

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
                  <h1 class="h3 mb-3">Add New Service</h1>
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
               </div>
            </main>
            <?php include 'includes/footer.php';?>
         </div>
      </div>
      <?php include 'includes/scripts.php';?>
   </body>
</html>