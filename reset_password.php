<?php include_once 'header.php'; ?>

<div class="container">
    <form class="grid reset_pass_form">
        <div class="d-grid align-items-center">
            <div class="forgot_section">
                <h2>Create new password</h2>
                <h4 class="error"></h4>
                <div class="forgot_content mt-2">
                    <input type="password" name="create_password" id="create_password" placeholder="New password" required>
                    <input type="password" name="create_cpassword" id="create_cpassword" placeholder="Confirm New password" required>
                    <div class="d-flex justify-content-between mt-2">
                        <div>
                            <!-- Back to <a href="index.php">Login</a> -->
                        </div>
                        <div>
                            <button class="btn btn-outline-dark sign_in forgot_shadow" id="change_pass">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="password_expiry_section d-none">
                <h1>Reset Link Invalid Or Expired!. Please generate link again</h1>
            </div>
        </div>
        <div class="">
            <figure>
                <img class="w-100" src="images/mail.png" alt="mail image">
            </figure>
        </div>
    </form>
</div>

<?php include_once 'footer.php'; ?>
<script>
    $(document).ready(function() {
        var unique_id = "<?php echo $_GET['unique_id']; ?>";
        var reset_code = "<?php echo $_GET['reset_code']; ?>";
        $.ajax({
            type: "post",
            url: "backend/forget.php",
            data: {
                "verify_reset_code": true,
                'unique_id': unique_id,
                'reset_code': reset_code,
            },
            dataType: "JSON",
            success: function(response) {
                if (response['result']) {
                    $(".password_expiry_section").remove()
                } else {
                    $(".forgot_section").remove()
                    $(".password_expiry_section").removeClass('d-none')
                }
            }
        });
        $("#change_pass").click(function(e) {
            e.preventDefault();
            var password = $("#create_password").val();
            var cpassword = $("#create_cpassword").val();
            $.post("backend/forget.php", {
                    'password': password,
                    'confirm_password': cpassword,
                    'unique_id': unique_id,
                    'update_password': true
                },
                function(data, textStatus, jqXHR) {
                    if (data['result']) {
                        $('.error').html('Password change successfully')
                        $('.error').addClass('text-success');
                        setTimeout(function() {
                            window.location.replace('index.php');
                        }, 2000);

                    } else {
                        $('.error').html(data['message']);
                    }
                },
                "json"
            );
        });
    });
</script>