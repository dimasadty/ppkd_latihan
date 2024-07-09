<?php
session_start();
include '../config/config.php'; // Adjust the path as necessary
include('../fetch_user.php');

// Check if user is logged in
if (!isset($_SESSION['name'])) {
    header("location:/latihanujikom/login.php?error-access-failed");
    exit; // Ensure to exit after redirection
  }

// Query to get majors
$query = "SELECT * FROM major ORDER BY id DESC";
$result = $db_latihanujikom->query($query);
if ($result) {
    $majors = $result->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Query failed: " . $db_latihanujikom->errorInfo()[2];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PPKD Jakarta Pusat - Major</title>
    <!-- plugins:css -->
    <?php include '../inc/css.php'; ?>
</head>

<body class="with-welcome-text">
    <!-- partial:partials/_navbar.html -->
    <?php include '../inc/navbar.php'; ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <?php include '../inc/sidebar.php'; ?>
        <!-- partial -->
        <!-- main panel -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Major</h1>
            <div align="right">
                <a href="/latihanujikom/pagesmajor/add.php" class="btn btn-primary mb-3">Add More</a>
            </div>
            <div class="table responsive">
                <table class="table table-bordered" id="">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Major Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($majors as $dataMajor) { ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $dataMajor['major_name'] ?></td>
                            <td>
                                <a href="/latihanujikom/pagesmajor/add.php?edit=<?php echo $dataMajor['id'] ?>"
                                    class="btn btn-primary btn-sm">Change Major</a>
                                <a onclick="return confirm('Are You Sure To Delete This Data?')"
                                    href="/latihanujikom/pagesmajor/add.php?delete=<?php echo $dataMajor['id'] ?>"
                                    class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- main-panel ends -->
    </div>
    <?php include '../inc/footer.php'; ?>
    <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <?php include '../inc/js.php'; ?>
    <?php include '../inc/modal-logout.php'; ?>
    <!-- End custom js for this page-->
</body>

</html>