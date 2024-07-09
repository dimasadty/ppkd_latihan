<?php
session_start();
include '../config/config.php'; // Adjust the path as necessary
include('../fetch_user.php');

// Check if user is logged in
if (!isset($_SESSION['name'])) {
    header("location:/latihanujikom/login.php?error-access-failed");
    exit; // Ensure to exit after redirection
  }

// Query to get classofyears
$query = "SELECT * FROM classofyear ORDER BY id DESC";
$result = $db_latihanujikom->query($query);
if ($result) {
    $classofyears = $result->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Query failed: " . $db_latihanujikom->errorInfo()[2];
}

function customStatus($status)
{
    switch ($status) {
        case 1:
            $message = "Active";
            break;
            default:
            $message = "Non Active";
            break;
    }
    return $message;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PPKD Jakarta Pusat - Class of Year</title>
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
            <h1 class="h3 mb-4 text-gray-800">Class of Year</h1>
            <div align="right">
                <a href="/latihanujikom/pagesclassofyear/add.php" class="btn btn-primary mb-3">Add More</a>
            </div>
            <div class="table responsive">
                <table class="table table-bordered" id="">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Class of Year</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($classofyears as $dataClassofyear) { ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $dataClassofyear['classofyearname'] ?></td>
                            <td><?php echo customStatus($dataClassofyear['status']) ?></td>
                            <td>
                                <a href="/latihanujikom/pagesclassofyear/add.php?edit=<?php echo $dataClassofyear['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a onclick="return confirm('Are You Sure to Delete This Data?')" href="/latihanujikom/pagesclassofyear/add.php?delete=<?php echo $dataClassofyear['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
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
