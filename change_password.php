<?php include_once 'dashboard_header.php'; ?>
<div class="profile col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
    <div class="container">
        <div class="grid mt-5">
            <div class="d-grid align-items-center">
                <div class="profile_section">
                    <h2>Change Password</h2>
                    <div class="error"></div>
                    <div class="profile_content mt-2">
                        <input type="password" name="old_password" id="old_password" placeholder="Old password" autocomplete="off"><br>
                        <input type="password" name="new_password" class="l_name" id="new_password" placeholder="New password">
                        <input type="password" name="c_password" class="r_mail" id="c_password" placeholder="Confirm new password">
                        <div class="d-flex justify-content-end mt-2">
                            <div class="float-right">
                                <button class="btn btn-outline-dark edit_profile profile_shadow" id="edit_profile">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="edit_profile text-center">
                <figure>
                    <img class="w-100" src="images/mail.png" alt="mail image">
                </figure>
            </div>
        </div>
    </div>
</div>


<?php include_once 'dashboard_footer.php'; ?>
<script>
    $('#edit_profile').click(function(e) {
        var old_passowrd = $('#old_password').val();
        var new_password = $('#new_password').val();
        var c_password = $('#c_password').val();

        $.ajax({
            type: "post",
            url: "backend/change_password.php",
            data: {
                'old_password': old_passowrd,
                'new_password': new_password,
                'c_password': c_password
            },
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('.error').text(response.message);
                    $('.error').addClass('text-success');
                } else {
                    $('.error').text(response.message);
                }
            }
        });

    });
</script>