<!DOCTYPE html>
<html lang="en">
   <?php include 'includes/head.php'; 
    // Fetching payments data
    // Default current date
    $current_date = date("Y-m-d", strtotime("-1 month"));
    $start_date = $current_date . " 00:00:00";
    $ed = date("Y-m-d");
    $end_date = $ed . " 23:59:59";

    $staff_payment_condition = "";

    if (isset($_POST['filter_payments'])) {
        // If the form is submitted, use the provided dates
        $start_date = $_POST['start_date'] . " 00:00:00";
        $end_date = $_POST['end_date'] . " 23:59:59";
        $staff_id = $_POST['staff_id'];

        $staff_payment_condition = $staff_id ? "AND p.staff_id = '$staff_id'" : "";
    }

    // Fetch payment records within the selected or default time range
    $payment_query = "SELECT 
        p.id AS payment_id,
        e.name AS employee_name,
        p.amount AS payment_amount,
        p.updated_at AS payment_date
    FROM 
        payments p
    JOIN 
        staff e ON p.staff_id = e.id
    WHERE 
        p.updated_at BETWEEN '$start_date' AND '$end_date'
    $staff_payment_condition
    ORDER BY 
        p.updated_at DESC, p.amount DESC";

    $payment_result = mysqli_query($con, $payment_query);
    // Fetch all staff members for the dropdown
    $staff_query = "SELECT * FROM staff";
    $staffs = mysqli_query($con, $staff_query);
    ?>
    <body>
        <div class="wrapper">
            <?php include 'includes/nav.php'; ?>
            <div class="main">
                <?php include 'includes/navtop.php'; ?>
                <main class="content">
                    <div class="container-fluid p-0">
                        <h1 class="h3 mb-3">View All Payments</h1>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                    <form action="" method="POST">
                                        <div class="form-row">
                                            <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                                <label for="start_date">Start Date</label>
                                                <input type="date" name="start_date" class="form-control" value="<?php echo $current_date; ?>">
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                                <label for="end_date">End Date</label>
                                                <input type="date" class="form-control" name="end_date" value="<?php echo $ed; ?>">
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                                <label for="staff_id">Staff</label>
                                                <select name="staff_id" class="form-control">
                                                    <option value="" selected>All Staff</option>
                                                    <?php
                                                    if (mysqli_num_rows($staffs) > 0) {
                                                        while($type = mysqli_fetch_assoc($staffs)) {
                                                            echo '<option value="'.$type["id"].'">'.$type["name"].'</option>'; 
                                                        }      
                                                    }
                                                    ?>
                                                </select>            
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-lg-3 " style="padding-top:1.8rem;">
                                                <button name="filter_payments" type="submit" class="btn btn-primary w-100">Filter</button>
                                            </div>
                                        </div>
                                    </form> 
                                    </div>
                                </div>
                            <div>   

                            </div>
                            <div class='row'>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                    <table id="example" class="table table-striped w-100 table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Staff ID</th>
                                                    <th>Amount</th>
                                                    <th>Created At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($payment_result) > 0) {
                                                    // Output data of each row
                                                    while($row = mysqli_fetch_assoc($payment_result)) {
                                                        echo '<tr>
                                                            <td>'.$row['payment_id'].'</td>
                                                            <td>'.$row['employee_name'].'</td>
                                                            <td> KSH '.number_format($row['payment_amount'], 2).'</td>
                                                            <td>'.date("Y-m-d", strtotime($row['payment_date'])).'</td>
                                                
                                                            <td class="table-action">
                                                                <a onclick="loadPayment('.$row['payment_id'].', pay=false)" data-id="'.$row['payment_id'].'" type="button" class="btn" data-toggle="modal" data-target="#updateModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
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
                                                    <th>Staff </th>
                                                    <th>Amount</th>
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
                    </div>
                </main>
                <?php include 'includes/footer.php'; ?>
            </div>
        </div>

        <!-- BEGIN update modal -->
        <div class="modal fade updateModal" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment Records</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-3" id="formData">
                        <!-- Form data will be loaded here by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END update modal -->

        <?php include 'includes/scripts.php'; ?>
    </body>
</html>
