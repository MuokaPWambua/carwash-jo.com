<?php

    include 'dbconfig.php';
    include 'functions.php';

    if(isset($_POST['delete'])){
        $id = mysqli_real_escape_string($con, $_POST['id']);
        $delete = "DELETE FROM queue WHERE id='".$id."'";
        if(mysqli_query($con, $delete)){
            $message = "Record Deleted.";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        
        echo $message;
        header("Location: /admin/view.php");
        die();
    }

    if(isset($_POST['update'])){
        $message;
        $id =  mysqli_real_escape_string($con, $_POST['id']);
        $owner_name = mysqli_real_escape_string($con, $_POST['owner_name']);

        $vehicle_type2 = mysqli_real_escape_string($con, $_POST['vehicle_type']);
        $status_type2 = mysqli_real_escape_string($con, $_POST['status_type']);
        $payment_method = mysqli_real_escape_string($con, $_POST['payment_method']);
        $amount_paid = mysqli_real_escape_string($con, $_POST['amount_paid']);
        $vehicle_number = mysqli_real_escape_string($con, $_POST['vehicle_number']);
        $service_type2 = $_POST['service_type'];
        $datum = new DateTime();
        $in_time = $datum->format('Y-m-d H:i:s');
        
        $queue_query = mysqli_query($con, "SELECT * FROM queue WHERE id='$id' LIMIT 1");
        $queue = mysqli_fetch_array($queue_query);
        $amount_paid = $queue['amount_paid'] + $amount_paid;

        $insert = "UPDATE queue SET status_type='".$status_type2."',  payment_method='".$payment_method."', amount_paid='".$amount_paid."', client_id='".$owner_name."', staff='".$vehicle_type2."', vehicle_number='".$vehicle_number."' WHERE id='".$id."';";
        
        if(mysqli_query($con, $insert)){
            $queue_id = mysqli_insert_id($conn);
            if (isset($_POST['service_type'])) {
                foreach($service_type2 as $service_type_id){
                   $service_assignment = "INSERT INTO service_assignment (service_type_id, staff_id, client_id, queue_id) VALUE ('$service_type_id','$staff','$owner_name', '$queue_id');";
                   mysqli_query($conn, $service_assignment); 
                }
            }
            $message = "Sale Information Added.";

            $id_get = mysqli_query($con, "SELECT * FROM status_type WHERE id='".$status_type2."' LIMIT 1");
            $id = mysqli_fetch_array($id_get);
            $clients = mysqli_query($con, "SELECT * FROM clients WHERE id='$owner_name' LIMIT 1");
            $client = mysqli_fetch_array($clients);
            $description = "The status of your carwash is ".$id['name'];
    
            if(sendMail($client['email'], $subject, $client['first_name'], $description, $vehicle_number)){
                    $message = $message . " Tracking information sent to the customer's email.";
                }else{
                    $message = $message . " Failed to send tracking information to the customer.";
                }
            } else {
                $message = "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        echo $message;
        header("Location: /admin/sale_reports.php");
        die();
        
    }
    
if(isset($_GET['info'])){
            $id = mysqli_real_escape_string($con, $_GET['info']);
            
            $sql = "SELECT q.last_update, q.service_type, q.id, q.staff, q.amount_paid as amount_paid, q.payment_method as payment_method, q.status_type as 'status_type', st.name as 'status', q.client_id, q.vehicle_number from queue q, status_type st where st.id = q.status_type AND q.id = '".$id."'";
            
            $vehicle_typeSQL = "SELECT * FROM staff";
            $vehicle_type = mysqli_query($con, $vehicle_typeSQL);
            $service_typeSQL = "SELECT * FROM service_type";
            $service_type = mysqli_query($con, $service_typeSQL);
            $status_typeSQL = "SELECT * FROM status_type";
            $status_type = mysqli_query($con, $status_typeSQL);
            $clients_sql = "SELECT * FROM clients";
            $clients = mysqli_query($con, $clients_sql);
            $result = mysqli_query($con, $sql);
            
            if (mysqli_num_rows($result) == 1) {
                while($track = mysqli_fetch_assoc($result)) {
                    ?>              
                    
                    <form action="includes/loadVehicleData.php" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-4 col-sm-6 col-lg-4">
                                <label for="inputEmail4">Client</label>
                                <select name="owner_name" class="form-control">
                        <?php
                            if (mysqli_num_rows($clients) > 0) {
                                while($type = mysqli_fetch_assoc($clients )) {
                                    echo '<option'; ?>
                    
                        <?php if($track['client_id'] == $type['id']){ echo 'selected';
                        }; ?>
                                    
                                    <?php echo ' value="'.$type["id"].'">'.$type["first_name"].'</option>'; 
                                }
                                
                            }
                                ?>
                        </select>                
                    </div>
                    <div class="form-group col-md-4 col-sm-6 col-lg-4">
                        <label for="inputPassword4">Client Vehicle Number</label>
                        <input value="<?php echo $track['vehicle_number']; ?>" type="text" class="form-control" name="vehicle_number" placeholder="Vehicle Number">
                    </div>
                    
                    <div class="form-group col-md-4 col-sm-6 col-lg-4">
                        <label for="inputState">Staff</label>
                        <select name="vehicle_type" class="form-control">
                        <?php
                            if (mysqli_num_rows($vehicle_type) > 0) {
                                while($type = mysqli_fetch_assoc($vehicle_type)) {
                                    echo '<option'; ?>
                    
                            <?php if($track['staff'] == $type['id']){ echo 'selected';
                                }; ?>
                            <?php echo ' value="'.$type["id"].'">'.$type["name"].'</option>'; 
                            }                    
                            }
                        ?>
                        </select>
                    </div>
                </div>
                        
                    <div class="form-row">
                        <div class="form-group col-md-4 col-sm-6 col-lg-4 col-sm-6">
                            <label for="service_type">Service Type</label>
                            <select name="service_type[]" multiple class="form-control">
                                <?php
                                    if (mysqli_num_rows($service_type) > 0) {
                                        while($servicetype = mysqli_fetch_assoc($service_type)) {
                                            echo '<option'; ?>     
                                            <?php if($track['service_type'] == $servicetype['id']){ echo 'selected';
                                    }; ?>
                                
                                    <?php echo ' value="'.$servicetype["id"].'">'.$servicetype["type"].'</option>'; 
                                    }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4 col-sm-6 col-lg-4">
                            <label for="service_type">Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option <?php echo $track['payment_method']==''?   "selected disabled" : "" ; ?>>Choose...</option>
                                <option <?php echo $track['payment_method']=='mpesa'?   "selected" : "" ; ?> value='mpesa'>Mpesa</option>
                                <option <?php echo $track['payment_method']=='cash'?   "selected" : "" ; ?> value='cash'>Cash</option>
                                <option <?php echo $track['payment_method']=='card'?   "selected" : "" ; ?> value='card'>Card</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 col-sm-6 col-lg-4">
                            <label for="service_type">Amount Paid</label>
                            <input type='number' name='amount_paid' value=<?php echo $track['amount_paid']?> class="form-control" placeholder='1000'/>
                        </div>
                        </div>										
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-check form-check-inline">
                                                                <?php
                                    if (mysqli_num_rows($status_type) > 0) {
                                        while($statustype2 = mysqli_fetch_assoc($status_type)) {
                                            echo '<label class="custom-control custom-radio col-3"><input '; ?>
                                    
                        <?php if($track['status_type'] == $statustype2['id']){ echo 'checked';
                        }; ?>
                                    
                                    <?php echo ' name="status_type" value="'.$statustype2['id'].'" type="radio" class="custom-control-input">
    <span class="custom-control-label">'.$statustype2['name'].'  </span>
    </label>'; 
                                }
                                
                            }
                                ?>
                                    </div>
                                </div>
                            </div>
                        
                        <hr>
                        
                        <button name="update" type="submit" class="btn btn-success btn-lg col-4 float-right">Update</button>
                        <button name="delete" type="submit" class="btn btn-danger clearfix">Delete</button>

                        <input name="id" value="<?php echo $track['id']; ?>" style="visibility:hidden" />

                    </form> 
                    
                    
                    
                    <?php
                }
    }
}
?>