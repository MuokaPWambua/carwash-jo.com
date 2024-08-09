<!DOCTYPE html>
<html lang="en">
<?php 

    include 'includes/head.php';
    include 'includes/functions.php';

    $staff_query = "SELECT * FROM staff";
    $staffs = mysqli_query($con, $staff_query);

    $service_query = "SELECT * FROM service_type";
    $service = mysqli_query($con, $service_query);

    $status_query = "SELECT * FROM status_type";
    $status = mysqli_query($con, $status_query);

?>
    <body>
        <div class="wrapper">
         <?php include 'includes/nav.php';?>
         <div class="main">
            <?php include 'includes/navtop.php';?>
            <main class="content">
            <div class="container-fluid p-0">
                <h1 class="h3 mb-2">Sales Reports</h1>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><?php echo $message; ?></h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-row">
                                        <div class="form-group col-4">
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
                                        <div class="form-group col-4">
                                            <label for="inputState">Service</label>
                                            <select name="service_provider" class="form-control" required>
                                                <option selected>Choose...</option>
                                                <?php
                                                    if (mysqli_num_rows($service) > 0) {
                                                            while($type = mysqli_fetch_assoc($service)) {
                                                            echo '<option value="'.$type["id"].'">'.$type["type"].'</option>'; 
                                                            }      
                                                    }
                                                ?>
                                            </select>            
                                        </div> 
                                        <div class="form-group col-4">
                                            <label for="inputState">Status Type</label>
                                            <select name="service_provider" class="form-control" required>
                                                <option selected>Choose...</option>
                                                <?php
                                                    if (mysqli_num_rows($status) > 0) {
                                                            while($type = mysqli_fetch_assoc($status)) {
                                                            echo '<option value="'.$type["id"].'">'.$type["name"].'</option>'; 
                                                            }      
                                                    }
                                                ?>
                                            </select>            
                                        </div>                                       
                                        <div class="form-group col-4">
                                            <label for="inputEmail4">Start Date</label>
                                            <input type="date" name="start_date" class="form-control">
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="inputPassword4">End Date</label>
                                            <input type="date" class="form-control" name="end_date">
                                        </div>

                                        <div class=" col-4 " style="padding-top:1.8rem;">
                                            <button name="submit" type="submit" class="btn btn-primary btn-fluid w-100">Filter</button>
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
                                    <h2>TOTAL SALES</h2>
                                    <p class='lead'>KSH 300,000</p>
                                </div>
                                <div class='col-4'>
                                    <h2>TOTAL COMMISSION</h2>
                                    <p class='lead'>KSH 330,000</p>
                                </div>
                                <div class='col-4'>
                                    <h2>PROFIT/LOSE </h2>
                                    <p class='text-mute'>(sales - (commission + expense))
                                    <p class='lead'>KSH 30,000</p>
                                </div>
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