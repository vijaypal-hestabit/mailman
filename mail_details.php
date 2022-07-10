<?php
session_start();
if (isset($_SESSION['user_id'])) {

    include_once 'dashboard_header.php';
?>

    <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
        <div id="show_mail">
            <h2>Subject :- <span id="subject"></span></h2>
            <div class="row">
                <div class="col-sm-6">
                    <a class="link-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Participants <span class="dropdown-toggle"></span></a>
                </div>
                <div class="col-sm-6 text-right">
                    <h4 class="show_date">dd/mm/yyyy</h4>
                </div>
            </div>
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    <div>From</div><span class="from_mail"></span>
                    <div>To</div><span class="to_mail"></span>
                    <div>CC</div><span class="cc_mail"></span>
                    <div>BCC</div><span class="bcc_mail"></span>
                </div>
            </div>
            <div id="mail_content" class="mt-4 border p-3 rounded"></div>

            <div class="mt-4" id="attachments">


            </div>
            <div class="mt-4">
                <button class="btn btn-hb mr-2">Reply</button>
                <button class="btn btn-hb">Reply all</button>
            </div>
        </div>
    </main>

    </div>
    </div>



    <div class="modal fade" id="composeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Compose</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="compose_email">
                    <fieldset class="form-group float-right w-100 border p-2">
                        <!-- <legend>Compose</legend> -->
                        <label class="form-control border-0" for="mail_id">
                            <input type="email" class="form-control" name="" id="mail_id" placeholder="To" v-model="mail" @change="make_draft">
                        </label>
                        <div class="error" id="email_err">{{ email_error }}</div>
                        <label class="form-control border-0" for="cc">
                            <input type="email" class="form-control" name="" id="cc" placeholder="CC" v-model="cc_mail" @change="save_cc_mail">
                        </label>
                        <div class="error" id="email_err">{{ cc_email_error }}</div>

                        <label class="form-control border-0" for="bcc">
                            <input type="email" class="form-control" name="" id="bcc" placeholder="BCC" v-model="bcc_mail" @change="save_bcc_mail">
                        </label>
                        <label class="form-control border-0" for="subject">
                            <input type="text" class="form-control" name="" id="subject" placeholder="Subject" v-model="subject" @change="save_subject">
                        </label>
                        <label for="msg_body" class="form-control border-0  ">
                            <textarea class="form-control" name="" id="msg_body" cols="30" rows="10" placeholder="Message Body" v-model="message" @change="save_message"></textarea>
                        </label>
                        <div>

                        </div>
                        <div class="row">
                            <div class="col-8">
                                <label class="btn btn-link" for="upload_attachments">+ Add Attachments</label>
                                <input type="file" name="" id="upload_attachments" hidden v-model="attachment" @change="save_attachments">
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-6 text-right">
                                        <button class="btn btn-hb" data-bs-dismiss="modal">Close</button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-hb" @click="send_email">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'dashboard_footer.php'; ?>
<?php
} else {
    header('location: index.php');
}

?>


<script>
    $(document).ready(function() {
        var mail_id = "<?php echo $_GET['id']; ?>"
        var data = {
            'mail_id': mail_id
        };
        axios.post('backend/mail_details.php', data).then(res => {
            console.log(res.data.attachments)

            $("#subject").html(res.data.message_data[0]['short_subject_msg'])
            $(".show_date").html(res.data.message_data[0]['delivered_date'])
            $("#mail_content").html(res.data.message_data[0]['full_message'])

            $('.from_mail').html(res.data.users[res.data.message_data[0]['sender_id']])
            $('.to_mail').html(res.data.users[res.data.message_data[0]['receiver_id']])
            $('.cc_mail').html(res.data.users[res.data.message_data[0]['cc_receiver_id']])
            $('.bcc_mail').html(res.data.users[res.data.message_data[0]['bcc_receiver_id']])
            var html='<h5 >Attachments</h5>' ;
            $.each(res.data.attachments, function(indexInArray, valueOfElement) {
                var attach_name = valueOfElement['path'];
                console.log(attach_name);
                html=html+'<a class="d-block" href="../images/mail_attachments/' + attach_name + '">' + attach_name + '</a>'    

            })
            $("#attachments").html(html)




            // mail participentes details

        })
    });
</script>