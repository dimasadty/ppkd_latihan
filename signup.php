<?php
session_start();
include 'config/config.php'; // Adjust the path as necessary

if (!isset($_SESSION['name'])) {
    header("location:../latihanujikom/login.php?error-access-failed");
    exit;
}

$query = "SELECT user.*, level.levelname FROM user LEFT JOIN level ON level.id = user.id_level ORDER BY user.id DESC";
$result = $db_latihanujikom->query($query);
if ($result) {
    $users = $result->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Query failed: " . $db_latihanujikom->errorInfo()[2];
}

// If the form is submitted, handle the form data
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $level = $_POST['id_level'];
    $password = $_POST['password'];

    $insertUser = $db_latihanujikom->prepare("INSERT INTO user (name, email, id_level, password) VALUES (?, ?, ?, ?)");
    if ($insertUser->execute([$name, $email, $level, $password])) {
        header("location:/latihanujikom/pagesuser/index.php?notif=success");
        exit;
    } else {
        echo "Error: " . $insertUser->errorInfo()[2];
    }
}

// If the delete parameter is present, delete the user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = $db_latihanujikom->prepare("DELETE FROM user WHERE id=?");
    if ($delete->execute([$id])) {
        header("location:user.php?notif=delete-success");
        exit;
    } else {
        echo "Error: " . $delete->errorInfo()[2];
    }
}

// If the edit parameter is present, get the user data for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = "SELECT * FROM user WHERE id = ?";
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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $level = $_POST['id_level'];
    $password = $_POST['password'];

    $edit = $db_latihanujikom->prepare("UPDATE user SET name=?, email=?, id_level=?, password=? WHERE id=?");
    if ($edit->execute([$name, $email, $level, $password, $id])) {
        header("location:/latihanujikom/pagesuser/index.php?notif=edit-success");
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
    <title>PPKD Jakarta Pusat - Register</title>
    <!-- plugins:css -->
    <?php include 'inc/css.php'; ?>
    <!-- endinject -->
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="/latihanujikom/assets/admin/images/jakarta.png"  alt="logo">
                            </div>
                            <?php if (isset($_GET['edit'])) { ?>
                                <h4>Edit User</h4>
                                <h6 class="fw-light">Update user information</h6>
                                <form class="pt-3" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $dataEdit['id']; ?>">
                                    <div class="form-group">
                                        <input name="name" type="text" class="form-control form-control-lg" id="name" value="<?php echo $dataEdit['name']; ?>" placeholder="Name...">
                                    </div>
                                    <div class="form-group">
                                        <input name="email" type="email" class="form-control form-control-lg" id="exampleInputEmail" value="<?php echo $dataEdit['email']; ?>" placeholder="E-mail...">
                                    </div>
                                    <div class="form-group">
                                        <select class="form-select form-select-lg" name="id_level" id="id_level" class="form-control">
                                            <option value="">Select Level</option>
                                            <?php
                                            $queryLevel = $db_latihanujikom->query("SELECT * FROM level");
                                            while ($dataLevel = $queryLevel->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $dataLevel['id']; ?>" <?php if ($dataEdit['id_level'] == $dataLevel['id']) echo 'selected'; ?>>
                                                    <?php echo $dataLevel['levelname']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input name="password" type="password" class="form-control form-control-lg" id="password" placeholder="Password...">
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <label class="form-check-label text-muted">
                                                <input type="checkbox" class="form-check-input"> I agree to all Terms & Conditions
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mt-3 d-grid gap-2">
                                        <button name="edit" type="submit" class="btn btn-primary">Edit</button>
                                        <a href="../latihanujikom/login.php" class="btn btn-danger">Cancel</a>
                                    </div>
                                </form>
                            <?php } else { ?>
                                <h4>New here?</h4>
                                <h6 class="fw-light">Signing up is easy. It only takes a few steps</h6>
                                <form class="pt-3" method="POST">
                                    <div class="form-group">
                                        <input name="name" type="text" class="form-control form-control-lg" id="name" placeholder="Name...">
                                    </div>
                                    <div class="form-group">
                                        <input name="email" type="email" class="form-control form-control-lg" id="exampleInputEmail" placeholder="E-mail...">
                                    </div>
                                    <div class="form-group">
                                        <select class="form-select form-select-lg" name="id_level" id="id_level" class="form-control">
                                            <option value="">Select Level</option>
                                            <?php
                                            $queryLevel = $db_latihanujikom->query("SELECT * FROM level");
                                            while ($dataLevel = $queryLevel->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $dataLevel['id']; ?>">
                                                    <?php echo $dataLevel['levelname']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input name="password" type="password" class="form-control form-control-lg" id="password" placeholder="Password...">
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <label class="form-check-label text-muted">
                                                <input type="checkbox" class="form-check-input"> I agree to all Terms & Conditions
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mt-3 d-grid gap-2">
                                        <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                                        <a href="../latihanujikom/login.php" class="btn btn-danger">Cancel</a>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.php'; ?>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <?php include 'inc/js.php'; ?>
    <!-- endinject -->
</body>

</html>
