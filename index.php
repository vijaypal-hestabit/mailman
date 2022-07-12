<?php

session_start();
// var_dump($_SESSION);
if (isset($_SESSION['user_id'])) {
    header('location: dashboard.php');
} else {
    include_once 'header.php'
?>
    <!-- login section -->
    <div class="main">
        <div class="container">
            <div class="grid">
                <div class="">
                    <figure>
                        <img class="w-100" src="images/mail.png" alt="mail image">
                    </figure>
                </div>
                <div class="d-grid align-items-center">
                    <div class="login_section">
                        <h2>Login to your account.</h2>
                        <div id="signup_success" class="d-none">
                            <h4>Login successuflly.</h4>
                        </div>
                        <div class="error" id="user_err"></div>
                        <div class="login_content mt-2">
                            <input type="text" name="log_user_name" id="log_user_name" placeholder="Email/username" required>
                            <div class="error" id="password_err"></div>
                            <input type="password" name="log_password" class="password" id="log_password" placeholder="Password" required>
                            <div class="d-flex justify-content-between mt-2">
                                <div>
                                    <a href="forgot.php">Forgot password?</a>
                                </div>
                                <div>
                                    <button class="btn btn-outline-dark sign_in login_shadow" id="log_in">Sing-in</button>
                                </div>
                            </div>
                            <h3 class="mt-2">Don't have an account yet?</h3>
                            <a href="register.php"><button class="btn btn-outline-primary sign_in create_shadow">Create One</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_once 'footer.php';
}

?>
<script>
    $('#log_in').click(function() {
        var log_user_name = $('#log_user_name').val();
        var password = $('#log_password').val();
        var data = {'user_name': log_user_name,'password': password};
        $.ajax({
            type: "POST",
            url: "backend/login.php",
            data: data,
            dataType: "json",
            success: function(response) {
                console.log(response);
                if (response.login == true) {
                    $("#login_success").removeClass('d-none');
                    window.location.replace('dashboard.php');
                } else if (response.login == false) {
                    $('#user_err').html('Invalid Cadential.');
                } else {

                    // user name 
                    if (response.username.username_error) {
                        $('#user_err').html(response.username.username_error)
                    } else {
                        $('#user_err').html('')
                    }

                    // password
                    if (response.password.password_error) {
                        $('#password_err').html(response.password.password_error)
                    } else {
                        $('#password_err').html('')
                    }
                }
            }
        });
    });
</script>