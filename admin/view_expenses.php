<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php';

    $current_date = date("Y-m-d", strtotime("-1 month"));
    $start_date = $current_date . " 00:00:00";
    $ed = date("Y-m-d");
    $end_date =  $ed. " 23:59:59";
    $filter ='';

    if (isset($_POST['filter'])) {
        // If the form is submitted, use the provided dates
        $start_date = $_POST['start_date'] . " 00:00:00";
        $end_date = $_POST['end_date'] . " 23:59:59";
        $filter = "WHERE created_at BETWEEN '$start_date' AND '$end_date'";
    }

    // SQL query with date filter
    $expense_query = "SELECT * FROM expenses $filter ORDER BY id ASC LIMIT 1000";
    $expense_result = mysqli_query($con, $expense_query);

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
        <?php include 'includes/nav.php'; ?>
        <div class="main">
            <?php include 'includes/navtop.php'; ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3">View All Expenses</h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="form-row">
                                            <div class="form-group col-md-4 col-sm-6 col-lg-4">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" name="start_date" class="form-control" value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : $current_date; ?>">
                                            </div>
                                            <div class="form-group col-md-4 col-sm-6 col-lg-4">
                                                <label for="end_date">End Date</label>
                                                <input type="date" class="form-control" name="end_date" value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : $ed; ?>">
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-lg-4" style="padding-top:1.8rem;">
                                                <button name="filter" type="submit" class="btn btn-primary w-100">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class='col-12'>
                        <button class='btn btn-primary float-right mb-4' data-toggle="modal" data-target="#addExpense"> Add Expense</button>
                        <div class='clearfix'></div>
                        </div>

                        <div class="col-12">
                            <div class="card">
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
                                        if (mysqli_num_rows($expense_result) > 0) {
                                            // Output data of each row
                                            while ($row = mysqli_fetch_assoc($expense_result)) {
                                                echo '<tr>
                                                    <td>' . $row['id'] . '</td>
                                                    <td>' . $row['expense_name'] . '</td>
                                                    <td>' . $row['expense_cost'] . '</td>
                                                    <td>' . $row['expense_description'] . '</td>
                                                    <td>' . $row['created_at'] . '</td>
                                                    <td class="table-action">
                                                        <a onclick="loadExpense(' . $row['id'] . ')" data-id="' . $row['id'] . '" type="button" class="btn" data-toggle="modal" data-target="#deleteModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
                                                    </td>
                                                </tr>';
                                            }
                                        } else {
                                            echo '<tr>
                                                <td colspan="6">No Data</td>
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
            <?php include 'includes/footer.php'; ?>
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
      				<!-- BEGIN delete modal -->
                      <div class="modal fade deleteModal" id="addExpense" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Expense</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body m-3">
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
    <?php include 'includes/scripts.php'; ?>
</body>
</html>
