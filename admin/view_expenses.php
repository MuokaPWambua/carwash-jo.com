<!DOCTYPE html>
<html lang="en">
   <?php include 'includes/head.php';
   
        
    $sql = "SELECT * FROM expenses GROUP BY id ORDER BY id ASC LIMIT 1000";
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
                    <h1 class="h3 mb-3">View All Expenses</h1>
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
                                        <th>Expense Name</th>
                                        <th>Expense Cost</th>
                                        <th>Expense Description</th>
                                        <th>Created At</th>
                                        <th>Action</th>                          
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while($row = mysqli_fetch_assoc($result)) {    
                                        echo '<tr>
                                            <td>'.$row['id'].'</td>
                                            <td>'.$row['expense_name'].'</td>
                                            <td>'.$row['expense_cost'].'</td>
                                            <td>'.$row['expense_description'].'</td>
                                            <td>'.$row['created_at'].'</td>
                                            <td class="table-action">
                                                <a onclick="loadExpense('.$row['id'].')" data-id="'.$row['id'].'" type="button" class="btn" data-toggle="modal" data-target="#deleteModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
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
                                    <th>Expense Name</th>
                                    <th>Expense Cost</th>
                                    <th>Expense Description</th>
                                    <th>Created At</th>
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