<?php

    include 'dbconfig.php';
    include 'functions.php';
    
    $message = '';
    

    if(isset($_POST['delete'])){
        $id = mysqli_real_escape_string($con, $_POST['payment_id']);
        $delete = "DELETE FROM payments WHERE id='".$id."'";
        if(mysqli_query($con, $delete)){
            $message = "Record Deleted.";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        
        echo $message;
        header("Location: /admin/view_payments.php");
        die();
    }

    if(isset($_POST['pay'])){
        $message;
        $staff_id =  mysqli_real_escape_string($con, $_POST['staff_id']);
        $amount = mysqli_real_escape_string($con, $_POST['amount']);

        $insert = "INSERT INTO payments (staff_id, amount) VALUES ('$staff_id', '$amount');";

        if(mysqli_query($con, $insert)){
            $message = "Payment Information added";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        
        echo $message;
        header("Location: /admin/view_payments.php");
        die();
    }

    if(isset($_POST['update'])){
        $message;
        $id =  mysqli_real_escape_string($con, $_POST['payment_id']);
        $amount = mysqli_real_escape_string($con, $_POST['amount']);

        $insert = "UPDATE payments SET amount='".$amount."' WHERE id='".$id."';";
        
        if(mysqli_query($con, $insert)){
            $message = "Payment Information Updated";
        } else {
            $message = "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        
        echo $message;
        header("Location: /admin/view_payments.php");
        die();
        
    }
    
    $result = null;

    if(isset($_GET['update'])){
        $id = mysqli_real_escape_string($con, $_GET['update']);
        
        $sql = "SELECT * from payments where id = '".$id."'";
        
        $result = mysqli_query($con, $sql);
    }

?>    

<div class='d-flex align-center justify-content-center w-100'>
<form action="includes/payment_form.php" method="POST">
    <div class="form">
        <div class="form-group">
            <label for="inputPassword4">Pay Amount</label>
            <?php if($result){
                while($track = mysqli_fetch_assoc($result)){ ?>
                    <input type="number" value="<?php echo $track['amount']?>" class="form-control"  name="amount" required>
                <?php
                }
            }else{?>
                <input type="number" class="form-control"  name="amount" placeholder="1500" required>
            <?php } ?>
        </div>
        <input name="staff_id" value="<?php echo $_GET['pay']; ?>" style="visibility:hidden" />                    
        <input name="payment_id" value="<?php echo $_GET['update']; ?>" style="visibility:hidden" />                    
    </div>
    <?php if(isset($_GET['pay'])){?>
        <button name="pay" type="submit" class="btn btn-lg w-100 btn-success">Pay</button>
    <?php }?>
    <?php if(isset($_GET['update'])){?>
        <div class='row'>
            <div class='col'>
                <button name="update" type="submit" class="btn btn-lg w-100 btn-success">Update</button>
            </div>
            <div class='col'>
                <button name="delete" type="submit" class="btn btn-lg w-100 btn-danger">Delete</button>
            </div>
        </div>
    <?php }?>
</form>
</div>
