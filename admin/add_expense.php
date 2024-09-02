<!DOCTYPE html>
<html lang="en">
<?php 

    include 'includes/head.php';
    include 'includes/functions.php';
            
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
    $message="";   

    if(isset($_POST['submit'])){
        $message;
        $expense_name = mysqli_real_escape_string($con, $_POST['expense_name']);
        $expense_cost = mysqli_real_escape_string($con, $_POST['expense_cost']);
        $expense_description = mysqli_real_escape_string($con, $_POST['expense_description']);

        $insert = "INSERT INTO expenses (expense_name, expense_cost, expense_description) VALUES ('$expense_name', '$expense_cost', '$expense_description');";
        
        if(mysqli_query($con, $insert)){
            $message = "Expense Information Added.";
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
                <h1 class="h3 mb-3">Add New Expense</h1>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><?php echo $message; ?></h5>
                            </div>
                            <div class="card-body">
                               	<form action="" method="POST">
                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <label for="inputEmail4">Expense Name</label>
                                            <input type="text" name="expense_name" class="form-control" placeholder="Soap" required>
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="inputPassword4">Expense Cost</label>
                                            <input type="number" class="form-control" name="expense_cost" placeholder="1500" required>
                                        </div>
                                        <div class="form-group col-12">
                                            <label for="inputState">Expense Description</label>
                                            <textarea type="text" class="form-control" name="expense_description" placeholder="car washing soap 3l" required></textarea>
                                        </div>
                                    </div>
                                    
									<button name="submit" type="submit" class="btn btn-primary">Add Expense</button>
								</form>
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