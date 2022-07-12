<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mailman</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.css" />
    

</head>

<body>
    <div id="main">
        <nav class="navbar navbar-light bg-light p-3">
            <div class="d-flex justify-content-between col-12 col-md-3 col-lg-3 mb-2 mb-lg-0 flex-wrap flex-md-nowrap">
                <a class="navbar-brand" href="dashboard.php">
                    <figure>
                        <img src="assets/logo.svg" alt="mailman">
                    </figure>
                </a>
                <button class="navbar-toggler d-md-none collapsed mb-3" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="col-8 col-md-6 col-lg-6">
                <input class="form-control form-control-dark" name="search" id="search" type="text" placeholder="Search ..." @keyup="search">
            </div>
            <div class="col-4 col-md-2 col-lg-2 d-flex align-items-center justify-content-end mt-md-0">
                <div class="mr-3 mt-1 show_user_name">
                    <?php echo $_SESSION['user_name']; ?>
                </div>
                <div class="dropdown">
                    <div class="profile_image" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        if ($_SESSION['profile_pic']) {
                        ?>
                            <img src="images/profile_pic/<?php echo $_SESSION['profile_pic'] ?>" alt="profile image">
                        <?php
                        } else {
                        ?>
                            <img src="assets/avatar.png" alt="profile image">
                        <?php
                        }
                        ?>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                </div>

            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky">
                        <ul class="nav flex-column">
                            <li class="compose">
                                <button class="btn btn-hb" data-bs-toggle="modal" data-bs-target="#composeModal">+ Compose</button>
                            </li>
                            <li class="nav-item mt-2">
                                <a class="nav-link status active" aria-current="page" id="inbox" href="dashboard.php" @click="open_inbox">Inbox
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link status" id="sent" href="#" @click="open_sent">Sent</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link status" id="draft" href="#" @click="open_draft">
                                    Draft
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link status" id="trash" href="#" @click="open_trash">
                                    Trash
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>