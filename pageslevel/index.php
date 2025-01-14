<?php
session_start();
include '../config/config.php'; // Adjust the path as necessary
include('../fetch_user.php');

// Check if user is logged in
if (!isset($_SESSION['name'])) {
    header("location:/latihanujikom/login.php?error-access-failed");
    exit; // Ensure to exit after redirection
  }

// Query to get levels
$query = "SELECT * FROM level ORDER BY id DESC";
$result = $db_latihanujikom->query($query);
if ($result) {
    $levels = $result->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Query failed: " . $db_latihanujikom->errorInfo()[2];
}

// If the form is submitted, handle the form data
if (isset($_POST['submit'])) {
    $levelname = $_POST['levelname'];

    $insertLevel = $db_latihanujikom->prepare("INSERT INTO level (levelname) VALUES (?)");
    if ($insertLevel->execute([$levelname])) {
        header("location:/latihanujikom/pageslevel/index.php?notif=success");
        exit;
    } else {
        echo "Error: " . $insertLevel->errorInfo()[2];
    }
}

// If the delete parameter is present, delete the user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = $db_latihanujikom->prepare("DELETE FROM level WHERE id=?");
    if ($delete->execute([$id])) {
        header("location:/latihanujikom/pageslevel/index.php?notif=delete-success");
        exit;
    } else {
        echo "Error: " . $delete->errorInfo()[2];
    }
}

// If the edit parameter is present, get the user data for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = "SELECT * FROM level WHERE id = ?";
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
    $levelname = $_POST['levelname'];

    $edit = $db_latihanujikom->prepare("UPDATE level SET levelname=? WHERE id=?");
    if ($edit->execute([$levelname, $id])) {
        header("location:/latihanujikom/pageslevel/index.php?notif=edit-success");
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
    <title>PPKD Jakarta Pusat - Level</title>
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
            <h1 class="h3 mb-4 text-gray-800">Level</h1>
            <div align="right">
                <a href="/latihanujikom/pageslevel/add.php" class="btn btn-primary mb-3">Add More</a>
            </div>
            <div class="table responsive">
                <table class="table table-bordered" id="">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($levels as $dataLevel) { ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $dataLevel['levelname']; ?></td>
                            <td>
                                <a href="/latihanujikom/pageslevel/index.php?edit=<?php echo $dataLevel['id']; ?>" class="btn btn-primary btn-sm">Change Level</a>
                                <a onclick="return confirm('Are You Sure To Delete This Data?')" href="/latihanujikom/pageslevel/index.php?delete=<?php echo $dataLevel['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php if (isset($_GET['edit'])) { ?>
                <div class="modal fade show" tabindex="-1" role="dialog" style="display:block; background: rgba(0,0,0,0.5);">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Level</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Level Name</label>
                                        <input type="text" class="form-control" id="name" name="levelname" value="<?php echo $dataEdit['levelname']; ?>" required>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $dataEdit['id']; ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- main-panel ends -->
    </div>
    <?php include '../inc/footer.php'; ?>
    <!-- page-body-wrapper ends -->
    <!-- container-scroller -->
    <!-- plugins:js -->
    <?php include '../inc/js.php'; ?>
    <?php include '../inc/modal-logout.php'; ?>
    <!-- End custom js for this page-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
