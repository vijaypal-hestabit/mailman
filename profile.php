<?php
session_start();
if (isset($_SESSION['user_id'])) {
    include_once 'dashboard_header.php';
?>
    <div class="profile col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
        <div class="container">
            <div class="grid mt-5">
                <div class="d-grid align-items-center">
                    <div class="profile_section">
                        <h2>Profile details</h2>
                        <div class="profile_content mt-2">
                            Name<input type="text" name="user_name" id="user_name" placeholder="User Name" readonly><br>
                            Email (Primary)<input type="text" name="email" class="password" id="email" placeholder="Email (Primary)" readonly>
                            Email (Backup)<input type="text" name="backup_email" class="password" id="backup_email" placeholder="Email (Secondary)" readonly>
                            User Name<input type="text" name="username" class="password" id="username" placeholder="Username" readonly>

                            <div class="d-flex justify-content-end mt-2">
                                <div class="float-right">
                                    <a href="edit_profile.php" class="mr-3">Edit Profile</a>
                                    <a href="change_password.php">Change Password</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-100">
                    <figure>
                        <img class="img-fluid" src="" id="profile_imgage" alt="mail image">
                    </figure>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'dashboard_footer.php'; ?>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: "post",
                url: "backend/profile.php",
                data: "",
                dataType: "json",
                success: function(response) {
                    $('#user_name').val(response.first_name + ' ' + response.last_name);
                    $('#email').val(response.email);
                    $('#backup_email').val(response.backup_mail);
                    $('#username').val(response.user_name);
                    if (response.profile_pic != null) {
                        $('#profile_imgage').attr('src', 'images/profile_pic/' + response.profile_pic);
                    } else {
                        $('#profile_imgage').attr('src', 'assets/avatar.png');
                    }
                }
            });
        });
    </script>
<?php
} else {
    header('location: index.php');
}

?>