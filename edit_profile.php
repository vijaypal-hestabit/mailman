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
                        <h2>Edit Profile details</h2>
                        <div class="profile_content mt-2">
                            <div id="edit_success" class="d-none">
                                <h4 class="text-success">Profile chage successfully.</h4>
                            </div>
                            <input type="text" name="edit_f_name" id="edit_f_name" placeholder="First Name">
                            <div class="error" id="fname_err"></div>
                            <input type="text" name="edit_l_name" class="l_name" id="edit_l_name" placeholder="Last Name">
                            <div class="error" id="lname_err"></div>
                            <input type="email" name="edit_r_mail" class="r_mail" id="edit_r_mail" placeholder="Recovery Email">
                            <div class="error" id="recovery_email_err"></div>
                            <div class="d-flex justify-content-end mt-2">
                                <div class="float-right updating_btn">
                                    <button class="btn btn-outline-dark edit_profile profile_shadow" id="edit_profile">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="edit_profile text-center">
                    <figure>
                        <img class="w-100" src="#" alt="mail image" id="show_profile_pic">
                    </figure>
                    <label class="mt-3 btn-link" for="edit_prifile_pic">Edit Profile</label>
                    <input type="file" name="edit_prifile_pic" id="edit_prifile_pic" hidden>
                    <div class="error" id="profile_err"></div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'dashboard_footer.php'; ?>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: "post",
                url: "backend/edit_profile.php",
                data: {
                    'show_details': true
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    var f_name = $("#edit_f_name").val(response['first_name']);
                    var l_name = $("#edit_l_name").val(response['last_name']);
                    var r_mail = $("#edit_r_mail").val(response['backup_mail']);
                    var profile_location = '';
                    if (response['profile_pic']) {
                        profile_location = 'images/profile_pic/' + response['profile_pic'] + '';
                    } else {
                        profile_location = "assets/avatar.png";
                    }
                    console.log(profile_location)
                    var profile_pic = $('#show_profile_pic').attr("src", profile_location)

                }
            });
        });
        $('#edit_prifile_pic').change(function (e) { 
            $('#profile_err').html('')
        });
        $(document).on('click','#edit_profile',function(e) {
            var f_name = $("#edit_f_name").val();
            var l_name = $("#edit_l_name").val();
            var r_mail = $("#edit_r_mail").val();
            var user_id = "<?php echo ($_SESSION['user_id']) ?>"

            var profile_picture = document.getElementById('edit_prifile_pic')
            var formData = new FormData();
            formData.append('f_name', f_name);
            formData.append('l_name', l_name);
            formData.append('r_mail', r_mail);
            formData.append('user_id', user_id);
            formData.append('profile_pic', profile_picture.files[0]);

            $.ajax({
                url: "backend/edit_profile.php",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                type: 'POST',
                beforeSend: function() {
                    var html = '<div class="spinner-grow text-primary" role="status">' +
                        '<span class="visually-hidden">Loading...</span>' +
                        '</div>';
                        $('.updating_btn').html(html);

                },
                success: function(response) {
                    // register successfully
                    if (response.edit_profile == true) {
                        $("#edit_success").removeClass('d-none');
                    } else {
                        if (response.fname.fname_error) {
                            $('#fname_err').html(response.fname.fname_error)
                        } else {
                            $('#fname_err').html('')
                        }

                        // last name
                        if (response.lname.lname_error) {
                            $('#lname_err').html(response.lname.lname_error)
                        } else {
                            $('#lname_err').html('')
                        }

                        // recover email
                        if (response.recoveryemail.recoveryemail_error) {
                            $('#recovery_email_err').html(response.recoveryemail.recoveryemail_error)
                        } else {
                            $('#recovery_email_err').html('')
                        }

                        // profile pic
                        if (response.profile_img.response) {

                        } else {
                            $('#profile_err').html(response.profile_img.message)
                        }
                    }
                },
                complete:function(){
                    $('.updating_btn').html('<button class="btn btn-outline-dark edit_profile profile_shadow" id="edit_profile">Submit</button>')
                }
            })

        });
    </script>
<?php
} else {
    header('location: index.php');
}
?>