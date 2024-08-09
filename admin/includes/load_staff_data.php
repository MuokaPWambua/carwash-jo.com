<?php

    include 'dbconfig.php';
    include 'functions.php';
    
    $message = '';

    $id =  mysqli_real_escape_string($con, $_POST['id']);

    if(isset($_POST['delete'])){
        // $id = mysqli_real_escape_string($con, $_POST['delete']);
        $delete = "DELETE FROM staff WHERE id='".$id."'";
        if(mysqli_query($con, $delete)){
            $message = "Record Deleted.";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        
        echo $message;
        header("Location: /admin/view_employees.php");
        die();
    }

    if(isset($_POST['update'])){
        $message;
        $employee_email = mysqli_real_escape_string($con, $_POST['employee_email']);
        $employee_name = mysqli_real_escape_string($con, $_POST['employee_name']);
        $employee_contact = mysqli_real_escape_string($con, $_POST['employee_contact']);
        $employee_address = mysqli_real_escape_string($con, $_POST['employee_address']);

        $insert = "UPDATE staff SET employee_email='".$employee_email."', employee_contact='".$employee_contact."', employee_address='".$employee_address."', name='".$employee_name."' WHERE id='".$id."';";
        
        if(mysqli_query($con, $insert)){
            $message = "Staff Information Updated";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        
        echo $message;
        header("Location: /admin/view_employees.php");
        die();
        
    }
    
    if(isset($_GET['info'])){
        $id = mysqli_real_escape_string($con, $_GET['info']);
        
        $sql = "SELECT * from staff where id = '".$id."'";
        
        $result = mysqli_query($con, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            while($track = mysqli_fetch_assoc($result)) {
        ?> 
                <form action="includes/load_staff_data.php" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Employee Name</label>
                            <input type="text" value=<?php echo $track['name']?> name="employee_name" class="form-control" placeholder="Employee's Name" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4">Employee Phone</label>
                            <input type="number" class="form-control" value=<?php echo $track['employee_contact']?> name="employee_contact" placeholder="2547958567829" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputState">Employee Email</label>
                            <input type="email" class="form-control" value=<?php echo $track['employee_email']?> name="employee_email" placeholder="email@carwash.co.ke" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="owner_address">Employee Address</label>
                        <input type="text" class="form-control" value=<?php echo $track['employee_address']?> name="employee_address" placeholder="carwash, nairobi, kenya"/>
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