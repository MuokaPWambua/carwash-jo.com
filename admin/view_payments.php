<!DOCTYPE html>
<html lang="en">
   <?php include 'includes/head.php'; ?>

   <?php
    // Fetching payments data
    $sql = "SELECT * FROM payments ORDER BY id ASC LIMIT 1000";
    $result = mysqli_query($con, $sql);

    $message = "";

    // Handle form submission for adding a new payment
    if (isset($_POST['submit'])) {
        $staff_id = mysqli_real_escape_string($con, $_POST['staff_id']);
        $amount = mysqli_real_escape_string($con, $_POST['amount']);

        $insert = "INSERT INTO payments (staff_id, amount) VALUES ('$staff_id', '$amount')";

        if (mysqli_query($con, $insert)) {
            $message = "Payment Information Added.";
        } else {
            $message = "Error: " . "<br>" . mysqli_error($con);
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
                        <h1 class="h3 mb-3">View All Payments</h1>
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
                                                    <th>Staff ID</th>
                                                    <th>Amount</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result) > 0) {
                                                    // Output data of each row
                                                    while($row = mysqli_fetch_assoc($result)) {
                                                        echo '<tr>
                                                            <td>'.$row['id'].'</td>
                                                            <td>'.$row['staff_id'].'</td>
                                                            <td>'.$row['amount'].'</td>
                                                            <td>'.$row['created_at'].'</td>
                                                            <td>'.$row['updated_at'].'</td>
                                                            <td class="table-action">
                                                                <a onclick="loadPayment('.$row['id'].', pay=false)" data-id="'.$row['id'].'" type="button" class="btn" data-toggle="modal" data-target="#updateModal"><i class="align-middle" data-feather="edit"></i> UPDATE</a>
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
                                                    <th>Staff ID</th>
                                                    <th>Amount</th>
                                                    <th>Created At</th>
                                                    <th>Updated At</th>
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
