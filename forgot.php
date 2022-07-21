<?php include_once 'header.php'; ?>

<div class="container">
    <form class="grid" name="" style="grid-template-columns: auto auto;" method="post" id="forgotForm">
        <div class="d-grid align-items-center">
            <!-- <figure class="forgot_logo">
                <img src="assets/logo.svg" alt="logo">
            </figure> -->
            <div class="forgot_section">
                <h2>Enter your Username or Mailman Id</h2>
                <?php
                if (isset($_GET['error'])) {
                    echo "<h4 class='error'>" . $_GET['error'] . "</h4>";
                }
                ?>
                <div class="forgot_content mt-2">
                    <input type="text" name="forgot_user_name" id="forgot_user_name" placeholder="username/abc@mailman.com">
                    <div id="mail_error"></div>
                    <div class="d-flex justify-content-between mt-2">
                        <div>
                            Back to <a href="index.php">Login</a>
                        </div>
                        <div>
                            <button class="btn btn-outline-dark sign_in forgot_shadow" id="sign_in">Submit</button>
                        </div>
                    </div>
                </div>
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

        $('#sign_in').click(function(e) {
            e.preventDefault()
            FormData = {
                'forgot_user_name': $("#forgot_user_name").val()
            }
            $.ajax({
                type: "post",
                url: "backend/forget.php",
                data: FormData,
                dataType: "json",
                beforeSend: function() {
                    $('#mail_error').addClass('text-warning');
                    $('#mail_error').removeClass('text-success');
                    $('#mail_error').removeClass('text-danger');
                    $('#mail_error').html("Please wait ...");
                },
                success: function(response) {

                    if (response['status']) {
                        $('#mail_error').addClass('text-success');
                        $('#mail_error').removeClass('text-danger');
                        $('#mail_error').removeClass('text-warning');
                        $('#mail_error').html("Link generated successfully. Please check your registered backup email address.</h2>");
                    } else {
                        $('#mail_error').removeClass('text-success');
                        $('#mail_error').removeClass('text-warning');
                        $('#mail_error').addClass('text-danger');
                        $('#mail_error').html("Link generating failed. Please check your username or mailman address.");
                        
                    }

                    // echo "<h2></h2>";

                }
            });
        })
    });
</script>