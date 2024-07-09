<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['name'])) {
    header("location:/latihanujikom/login.php?error-access-failed");
    exit; // Ensure to exit after redirection
}

// Include configuration file
include '../config/config.php';
include('../fetch_user.php');

// Check if the connection is established
$query = "SELECT * FROM participant ORDER BY id DESC";
$result = $db_latihanujikom->query($query);
if ($result) {
    $participants = $result->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Query failed: " . $db_latihanujikom->errorInfo()[2];
}


// Fetch participants
$queryParticipant = $db_latihanujikom->query("SELECT * FROM Participant ORDER BY id DESC");

// Insert participant
if (isset($_POST['submit'])) {
    $identitycode = $_POST['identitycode'];
    $familycode = $_POST['familycode'];
    $birthplace = $_POST['birthplace'];
    $birthdate = $_POST['birthdate'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $educationtitle = $_POST['educationtitle'];
    $educationschool = $_POST['educationschool'];
    $id_major = $_POST['id_major'];
    $activitystatus = $_POST['activitystatus'];
    $id_classofyear = $_POST['id_classofyear'];

    $stmt = $db_latihanujikom->prepare("INSERT INTO participant (identitycode, familycode, birthplace, birthdate, name, email, phonenumber, gender, address, educationtitle, educationschool, id_major, activitystatus, id_classofyear) VALUES (:identitycode, :familycode, :birthplace, :birthdate, :name, :email, :phonenumber, :gender, :address, :educationtitle, :educationschool, :id_major, :activitystatus, :id_classofyear)");
    $stmt->execute([
        ':identitycode' => $identitycode,
        ':familycode' => $familycode,
        ':birthplace' => $birthplace,
        ':birthdate' => $birthdate,
        ':name' => $name,
        ':email' => $email,
        ':phonenumber' => $phonenumber,
        ':gender' => $gender,
        ':address' => $address,
        ':educationtitle' => $educationtitle,
        ':educationschool' => $educationschool,
        ':id_major' => $id_major,
        ':activitystatus' => $activitystatus,
        ':id_classofyear' => $id_classofyear,
    ]);

    header("location:/latihanujikom/pagesparticipant/index.php?notif=success");
    exit;
}

// Delete participant
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db_latihanujikom->prepare("DELETE FROM Participant WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("location:/latihanujikom/pagesparticipant/index.php?notif=delete-success");
}

// Edit participant
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $db_latihanujikom->prepare("SELECT * FROM participant WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $dataEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['edit'])) {
    $identitycode = $_POST['identitycode'];
    $familycode = $_POST['familycode'];
    $birthplace = $_POST['birthplace'];
    $birthdate = $_POST['birthdate'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $educationtitle = $_POST['educationtitle'];
    $educationschool = $_POST['educationschool'];
    $id_major = $_POST['id_major'];
    $activitystatus = $_POST['activitystatus'];
    $id_classofyear = $_POST['id_classofyear'];
    $id = $_GET['edit'];

    $stmt = $db_latihanujikom->prepare("UPDATE participant SET identitycode = :identitycode, familycode = :familycode, birthplace = :birthplace, birthdate = :birthdate, name = :name, email = :email, phonenumber = :phonenumber, gender = :gender, address = :address, educationtitle = :educationtitle, educationschool = :educationschool, id_major = :id_major, activitystatus = :activitystatus, id_classofyear = :id_classofyear WHERE id = :id");
    $stmt->execute([
        ':identitycode' => $identitycode,
        ':familycode' => $familycode,
        ':birthplace' => $birthplace,
        ':birthdate' => $birthdate,
        ':name' => $name,
        ':email' => $email,
        ':phonenumber' => $phonenumber,
        ':gender' => $gender,
        ':address' => $address,
        ':educationtitle' => $educationtitle,
        ':educationschool' => $educationschool,
        ':id_major' => $id_major,
        ':activitystatus' => $activitystatus,
        ':id_classofyear' => $id_classofyear,
        ':id' => $id,
    ]);

    header("location:/latihanujikom/pagesparticipant/index.php?notif=edit-success");
}

// Fetch classofyear
$stmt = $db_latihanujikom->query("SELECT * FROM classofyear WHERE status = 1 ORDER BY id DESC");
$dataclassofyear = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dataclassofyear) {
    echo "Error: No active classofyear found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PPKD Jakarta Pusat - Participant</title>
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

        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <?php if (isset($_GET['edit'])) { ?>

                                            <div class="text-center">
                                                <h1 class="h4 text-gray-900 mb-4">Pendaftaran Pelatihan - PPKD Jakarta Pusat
                                                </h1>
                                            </div>
                                            <form class="user" method="post">
                                                <!-- input form -->
                                                <div class="form-group">
                                                    <input name="name" type="text" class="form-control form-control-user" value="<?php echo $dataEdit['name']; ?>" placeholder="Name...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="familycode" type="text" class="form-control form-control-user" value="<?php echo $dataEdit['familycode']; ?>" placeholder="Family Identity Code...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="identitycode" type="text" class="form-control form-control-user" value="<?php echo $dataEdit['identitycode']; ?>" placeholder="Personal Identity Code...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="birthplace" type="text" class="form-control form-control-user" value="<?php echo $dataEdit['birthplace']; ?>" placeholder="Birth Place...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="birthdate" type="date" class="form-control form-control-user" value="<?php echo $dataEdit['birthdate']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <input name="email" class="form-control form-control-user" value="<?php echo $dataEdit['email']; ?>" type="email" placeholder="Email...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="phonenumber" type="tel" class="form-control form-control-user" value="<?php echo $dataEdit['phonenumber']; ?>" placeholder="Phone Number...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="gender" type="radio" id="gender" value="Men" <?php if ($dataEdit['gender'] == 'Men') echo 'checked'; ?>> Men
                                                    <input name="gender" type="radio" id="gender" value="Women" <?php if ($dataEdit['gender'] == 'Women') echo 'checked'; ?>> Women
                                                </div>
                                                <div class="form-group">
                                                    <input name="address" type="text" class="form-control form-control-user" value="<?php echo $dataEdit['address']; ?>" placeholder="Address...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="educationtitle" type="text" class="form-control form-control-user" value="<?php echo $dataEdit['educationtitle']; ?>" placeholder="Education Title...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="educationschool" type="text" class="form-control form-control-user" value="<?php echo $dataEdit['educationschool']; ?>" placeholder="Education School...">
                                                </div>
                                                <div class="form-group">
                                                    <select name="id_major" id="id_major" class="form-control">
                                                        <option value="" disabled selected>--Select Major--</option>
                                                        <?php
                                                        $stmt = $db_latihanujikom->query("SELECT * FROM major ORDER BY id ASC");
                                                        while ($dataMajor = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            if (isset($dataEdit) && $dataEdit['id_major'] == $dataMajor['id']) {
                                                                echo "<option value='{$dataMajor['id']}' selected>{$dataMajor['major_name']}</option>";
                                                            } else {
                                                                echo "<option value='{$dataMajor['id']}'>{$dataMajor['major_name']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input name="activitystatus" type="text" class="form-control form-control-user" value="<?php echo $dataEdit['activitystatus']; ?>" placeholder="Activity Status...">
                                                </div>
                                                <div class="form-group">
                                                    <select name="id_classofyear" id="id_classofyear" class="form-control">
                                                        <option value="" disabled selected>--Select Class of Year--</option>
                                                        <?php
                                                        $stmt = $db_latihanujikom->query("SELECT * FROM classofyear WHERE status = 1 ORDER BY id ASC");
                                                        while ($dataClassOfYear = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            if (isset($dataEdit) && $dataEdit['id_classofyear'] == $dataClassOfYear['id']) {
                                                                echo "<option value='{$dataClassOfYear['id']}' selected>{$dataClassOfYear['classofyearname']}</option>";
                                                            } else {
                                                                echo "<option value='{$dataClassOfYear['id']}'>{$dataClassOfYear['classofyearname']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <button type="submit" name="edit" class="btn btn-primary btn-user btn-block">
                                                    Edit Data
                                                </button>
                                                <hr>
                                            </form>
                                        <?php } else { ?>
                                            <div class="text-center">
                                                <h1 class="h4 text-gray-900 mb-4">Pendaftaran Pelatihan - PPKD Jakarta Pusat
                                                </h1>
                                            </div>
                                            <form class="user" method="post">
                                                <!-- input form -->
                                                <div class="form-group">
                                                    <input name="name" type="text" class="form-control form-control-user" placeholder="Name...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="familycode" type="text" class="form-control form-control-user" placeholder="Family Identity Code...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="identitycode" type="text" class="form-control form-control-user" placeholder="Personal Identity Code...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="birthplace" type="text" class="form-control form-control-user" placeholder="Birth Place...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="birthdate" type="date" class="form-control form-control-user">
                                                </div>
                                                <div class="form-group">
                                                    <input name="email" class="form-control form-control-user" type="email" placeholder="Email...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="phonenumber" type="tel" class="form-control form-control-user" placeholder="Phone Number...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="gender" type="radio" id="gender" value="Men"> Men
                                                    <input name="gender" type="radio" id="gender" value="Women"> Women
                                                </div>
                                                <div class="form-group">
                                                    <input name="address" type="text" class="form-control form-control-user" placeholder="Address...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="educationtitle" type="text" class="form-control form-control-user" placeholder="Education Title...">
                                                </div>
                                                <div class="form-group">
                                                    <input name="educationschool" type="text" class="form-control form-control-user" placeholder="Education School...">
                                                </div>
                                                <div class="form-group">
                                                    <select name="id_major" id="id_major" class="form-control">
                                                        <option value="" disabled selected>--Select Major--</option>
                                                        <?php
                                                        $stmt = $db_latihanujikom->query("SELECT * FROM major ORDER BY id ASC");
                                                        while ($dataMajor = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value='{$dataMajor['id']}'>{$dataMajor['major_name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input name="activitystatus" type="text" class="form-control form-control-user" placeholder="Activity Status...">
                                                </div>
                                                <select name="id_classofyear" id="id_classofyear" class="form-control">
                                                        <option value="" disabled selected>--Select Class of Year--</option>
                                                        <?php
                                                        $stmt = $db_latihanujikom->query("SELECT * FROM classofyear WHERE status = 1 ORDER BY id ASC");
                                                        while ($dataClassOfYear = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            if (isset($dataEdit) && $dataEdit['id_classofyear'] == $dataClassOfYear['id']) {
                                                                echo "<option value='{$dataClassOfYear['id']}' selected>{$dataClassOfYear['classofyearname']}</option>";
                                                            } else {
                                                                echo "<option value='{$dataClassOfYear['id']}'>{$dataClassOfYear['classofyearname']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                                    Submit Data
                                                </button>
                                                <hr>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- main panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</body>

</html>