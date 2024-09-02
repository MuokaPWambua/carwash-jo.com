<?php

    include 'dbconfig.php';
    include 'functions.php';
    
    $message = '';


    if(isset($_POST['delete'])){
        $id = mysqli_real_escape_string($con, $_POST['id']);
        $delete = "DELETE FROM clients WHERE id='".$id."'";
        if(mysqli_query($con, $delete)){
            $message = "Record Deleted.";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        
        echo $message;
        header("Location: /admin/view_clients.php");
        die();
    }

    if(isset($_POST['update'])){
        $message;
        $client_email = mysqli_real_escape_string($con, $_POST['client_email']);
        $client_name = mysqli_real_escape_string($con, $_POST['client_name']);
        $client_contact = mysqli_real_escape_string($con, $_POST['client_contact']);
        $client_address = mysqli_real_escape_string($con, $_POST['client_address']);
        $id = mysqli_real_escape_string($con, $_POST['id']);

        $insert = "UPDATE clients SET email='".$client_email."', phone_number='".$client_contact."', address='".$client_address."', first_name='".$client_name."' WHERE id='".$id."';";
        
        if(mysqli_query($con, $insert)){
            $message = "Client Information Updated";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        
        echo $message;
        header("Location: /admin/view_clients.php");
        die();
        
    }
    
    if(isset($_GET['info'])){
        $id = mysqli_real_escape_string($con, $_GET['info']);
        
        $sql = "SELECT * from clients where id = '".$id."'";
        
        $result = mysqli_query($con, $sql);
        
        if (mysqli_num_rows($result) == 1) {
            while($track = mysqli_fetch_assoc($result)) {
        ?> 
                <form action="includes/load_client_data.php" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Client Name</label>
                            <input type="text" value=<?php echo $track['first_name']?> name="client_name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputPassword4">Client Phone</label>
                            <input type="number" class="form-control" value=<?php echo $track['phone_number']?> name="client_contact" placeholder="2547958567829" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputState">Client Email</label>
                            <input type="email" class="form-control" value=<?php echo $track['email']?> name="client_email" placeholder="johndoe@example.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="owner_address">Client Address</label>
                        <input type="text" class="form-control" value=<?php echo $track['address']?> name="client_address" placeholder="carwash, nairobi, kenya"/>
                    </div>
                    
                    <input name="id" value="<?php echo $track['id']; ?>" style="visibility:hidden" />                    
                    <button name="update" type="submit" class="btn btn-success btn-lg col-4 float-right">Update</button>
                    <button name="delete" type="submit" class="btn btn-danger">Delete</button>

                </form>  
        <?php
        }
        }
    }
?>