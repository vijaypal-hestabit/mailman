<?php include_once 'dashboard_header.php'; ?>
<div class="profile px-md-4 py-4">
    <div class="container">
        <div class="grid mt-5">
            <div class="d-grid align-items-center">
                <div class="profile_section">
                    <h2>Change Password</h2>
                    <div class="success_change text-success"></div>
                    <div class="profile_content mt-2">
                        <input type="password" name="old_password" id="old_password" placeholder="Old password" autocomplete="off">
                        <div class="error" id="old_pass_err"></div>
                        <input type="password" name="new_password" class="l_name" id="new_password" placeholder="New password">
                        <div class="error" id="new_pass_err"></div>
                        <input type="password" name="c_password" class="r_mail" id="c_password" placeholder="Confirm new password">
                        <div class="error" id="c_pass_err"></div>
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
                    $('.success_change').text(response.message);
                    setTimeout(function() {
                        $('.success_change').text('');
                    }, 2000);
                    $('#c_pass_err').html('')
                    $('#old_pass_err').html('')
                    $('#new_pass_err').html('')
                } else {

                    // set error message
                    if (response.message == 'Please enter old password' || response.message == 'Please enter correct old password') {
                        $('#old_pass_err').html(response.message)
                    } else {
                        $('#old_pass_err').html('')
                    }
                    if (response.message == 'Please enter new password') {
                        $('#new_pass_err').html(response.message)
                    } else {
                        $('#new_pass_err').html('')
                    }
                    if (response.message == "Please check, you've entered wrong confirm password!") {
                        $('#c_pass_err').html(response.message)
                    } else {
                        $('#c_pass_err').html('')
                    }

                    if (response.message == "Your Password Must contain one upper case character!") {
                        $('#c_pass_err').html(response.message)
                    } else {
                        $('#c_pass_err').html('')
                    }

                    if (response.message == "Your Password Must Contain At Least 6 Characters!") {
                        $('#c_pass_err').html(response.message)
                    } else {
                        $('#c_pass_err').html('')
                    }
                    if (response.message == "Your Password Must contain a number!") {
                        $('#c_pass_err').html(response.message)
                    } else {
                        $('#c_pass_err').html('')
                    }
                    if (response.message == "Your Password Must contain a special character!") {
                        $('#c_pass_err').html(response.message)
                    } else {
                        $('#c_pass_err').html('')
                    }

                }
            }
        });

    });
</script>