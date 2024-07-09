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

// If the form is submitted, handle the form data
if (isset($_POST['submit'])) {
    $classofyearname = $_POST['classofyearname'];
    $status = $_POST['status'];

    $insertClassofyear = $db_latihanujikom->prepare("INSERT INTO classofyear (classofyearname, status) VALUES (?, ?)");
    if ($insertclassofyear->execute([$classofyearname, $status])) {
        header("location:/latihanujikom/pagesclassofyear/index.php?notif=success");
        exit;
    } else {
        echo "Error: " . $insertclassofyear->errorInfo()[2];
    }
}

// If the delete parameter is present, delete the user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = $db_latihanujikom->prepare("DELETE FROM classofyear WHERE id=?");
    if ($delete->execute([$id])) {
        header("location:/latihanujikom/pagesclassofyear/index.php?notif=delete-success");
        exit;
    } else {
        echo "Error: " . $delete->errorInfo()[2];
    }
}

// If the edit parameter is present, get the user data for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = "SELECT * FROM classofyear WHERE id = ?";
    $stmt = $db_latihanujikom->prepare($editQuery);
    if ($stmt->execute([$id])) {
        $dataEdit = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Fetch failed: " . $db_latihanujikom->errorInfo()[2];
    }
}

// If the edit form is submitted, update the user data
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $classofyearname = $_POST['classofyearname'];
    $status = $_POST['status'];

    $edit = $db_latihanujikom->prepare("UPDATE classofyear SET classofyearname=?,  status=? WHERE id=?");

    if ($edit->execute([$classofyearname, $id])) {
        header("location:/latihanujikom/pagesclassofyear/index.php?notif=edit-success");
        exit;
    } else {
        echo "Error: " . $edit->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PPKD Jakarta Pusat - Class of Year </title>
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
            <?php if (isset($_GET['edit'])) { ?>
            <h1 class="h3 mb-4 text-gray-800">Class of Year</h1>
            <div class="card">
                <div class="card-header">Edit</div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="nama">Class of Year</label>
                            <input value="<?php echo $dataEdit['classofyearname'] ?>" type="text" class="form-control"
                                name="classofyearname" placeholder="Your class of year">
                        </div>
                        <div class="mb-3">
                            <label for="">Active</label>
                            <select name="status" id="" class="form-control">
                                <option value="">--Choose Status-</option>
                                <option <?php echo ($dataEdit['status']==1) ? 'selected' : "" ?>value="1">Active</option>
                                <option <?php echo ($dataEdit['status']==0) ? 'selected' : "" ?>value="0">Inactive
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input name="edit" type="submit" class="btn btn-primary" value="edit">
                            <a href="/latihanujikom/pagesclassofyear/index.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
                <?php } else { ?>
                <h1 class="h3 mb-4 text-gray-800">Class of Year</h1>
                <div class="card-body">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="classofyearname">Class Of Year</label>
                                <input type="text" class="form-control" name="classofyearname"
                                    placeholder="Choose your Class of Year">
                            </div>
                            <div class="mb-3">
                                <label for="name">Status</label>
                                <select name="status" id="" class="form-control">
                                    <option value="">--Choose Status-</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input name="submit" type="submit" class="btn btn-primary" value="submit">
                                <a href="/latihanujikom/pagesclassofyear/index.php" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                    <?php } ?>
                </div>
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