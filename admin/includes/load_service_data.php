<?php

    include 'dbconfig.php';
    include 'functions.php';
    
    $message = '';

    $id =  mysqli_real_escape_string($con, $_POST['id']);

    if(isset($_POST['delete'])){
        // $id = mysqli_real_escape_string($con, $_POST['delete']);
        $delete = "DELETE FROM service_type WHERE id='".$id."'";
        if(mysqli_query($con, $delete)){
            $message = "Record Deleted.";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        
        echo $message;
        header("Location: /admin/view_services.php");
        die();
    }

    if(isset($_POST['update'])){
        $message;
        $service_name = mysqli_real_escape_string($con, $_POST['service_name']);
        $service_cost = mysqli_real_escape_string($con, $_POST['service_cost']);
        $service_commission = mysqli_real_escape_string($con, $_POST['service_commission']);

        $insert = "UPDATE service_type SET type='".$service_name."', service_cost='".$service_cost."', service_commission='".$service_commission."' WHERE id='".$id."';";
        
        if(mysqli_query($con, $insert)){
            $message = "Service Information Updated";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        
        echo $message;
        header("Location: /admin/view_services.php");
        die();
        
    }
    
    if(isset($_GET['info'])){
        $id = mysqli_real_escape_string($con, $_GET['info']);
        
        $sql = "SELECT * from service_type where id = '".$id."'";
        
        $result = mysqli_query($con, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            while($track = mysqli_fetch_assoc($result)) {
        ?> 
            <form action="includes/load_service_data.php" method="POST">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Service Name</label>
                        <input type="text" value="<?php echo $track['type']?>" name="service_name" class="form-control" placeholder="Buffing" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputPassword4">Service Cost</label>
                        <input type="number" value="<?php echo $track['service_cost']?>" class="form-control" name="service_cost" placeholder="3000" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputState">Service Commission</label>
                        <input type="number" value="<?php echo $track['service_commission']?>" class="form-control" name="service_commission" placeholder="20%" required>
                    </div>
                </div>

                <hr>
                
                <input name="id" value="<?php echo $track['id']; ?>" style="visibility:hidden" />                    
                <button name="update" type="submit" class="btn btn-success btn-lg col-4 float-right">Update</button>
                <button name="delete" type="submit" class="btn btn-danger">Delete</button>

            </form>  
        <?php
        }
        }
    }
?>