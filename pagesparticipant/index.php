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

// Fetch participants
$queryParticipant = $db_latihanujikom->query("SELECT participant.*, major.major_name AS major_name, classofyear.classofyearname AS classofyearname FROM participant LEFT JOIN major ON participant.id_major = major.id LEFT JOIN classofyear ON participant.id_classofyear = classofyear.id ORDER BY participant.id DESC");
$participants = $queryParticipant->fetchAll(PDO::FETCH_ASSOC);

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
    $stmt = $db_latihanujikom->prepare("DELETE FROM participant WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("location:/latihanujikom/pagesparticipant/index.php?notif=delete-success");
    exit;
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
    exit;
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
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Participant</h1>
            <div align="right">
                <a href="/latihanujikom/pagesparticipant/add.php" class="btn btn-primary mb-3">Add More</a>
            </div>
            <div class="table responsive">
                <table class="table table-bordered" id="">
                    <thead>
                        <tr class="justify-content-center">
                            <th>No</th>
                            <th>Major</th>
                            <th>Name</th>
                            <th>Personal Identity</th>
                            <th>Family Identity</th>
                            <th>Birth Place</th>
                            <th>Birth Date</th>
                            <th>Gender</th>
                            <th>E-mail</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Education Title</th>
                            <th>Education School</th>
                            <th>Class of Year</th>
                            <th>Activity Status</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
             foreach ($participants as $dataParticipant) { ?>
                        <tr>
                            <td>
                                <?php echo $no++ ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['major_name'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['name'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['identitycode'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['familycode'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['birthplace'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['birthdate'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['gender'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['email'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['phonenumber'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['address'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['educationtitle'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['educationschool'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['classofyearname'] ?>
                            </td>
                            <td>
                                <?php echo $dataParticipant['activitystatus'] ?>
                            </td>
                            <td>
                                <?php echo $dataclassofyear['classofyearname'] ?></br></br>
                            </td>
                            <td>
                            <a href="/latihanujikom/pagesparticipant/view.php?edit=<?php echo $dataParticipant['id']; ?>"
                            class="btn btn-secondary btn-sm">View</a>
                                <a href="/latihanujikom/pagesparticipant/add.php?edit=<?php echo $dataParticipant['id']; ?>"
                                    class="btn btn-primary btn-sm">Edit</a>
                                <a onclick="return confirm('Are You Sure to Delete This Data')"
                                    href="/latihanujikom/pagesparticipant/index.php?delete=<?php echo $dataParticipant['id'] ?>"
                                    class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        <?php include '../changestatus.php'; ?>
                        <?php } ?>
                    </tbody>
                </table>
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