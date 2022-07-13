<?php
session_start();
if (isset($_SESSION['user_id'])) {

    include_once 'dashboard_header.php';
?>

    <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
        <div class="row m-2 action_bar">
            <div>
                <span class="mr-2">
                    <input type="checkbox" name="" title="select all" class="selectAll" id="selectAll" @change="selectall">
                </span>
                <span>
                    <button type="button" class="d-none read_status" @click="mark_as_read">Mark read</button>
                </span>
                <span>
                    <button type="button" class="d-none unread_status" @click="mark_as_unread">Mark unread</button>
                </span>
                <span>
                    <button type="button" class="d-none delete_status" @click="delete_message">Delete</button>
                </span>
                <span>
                    <button type="button" class="d-none restore_status" @click="restore_message">Restore</button>
                </span>
            </div>
        </div>
        <div class="row">
            <?php print_r($_SERVER); ?>
            <div class="col-12 mb-3">
                <div class="card">
                    <h5 class="card-header">{{ page_name }}</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="inbox_table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Sender Email</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="mail_content" v-for="(item,index) in inbox_contents" :class="item.is_read=='0'?'unread':'read'" :data_id="item.id">
                                        <td><input type="checkbox" name="action" class="checkboxs" :value="item.id" @change="single_select"></td>
                                        <td @click="navigate(item.id)" class="cursor-pointer">{{ user_records[item.sender_id] }}</td>
                                        <td @click="navigate(item.id)" class="cursor-pointer">{{ item.short_subject_msg }}</td>
                                        <td @click="navigate(item.id)" class="cursor-pointer">{{ item.delivered_date }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
                            <div class="error" id="message_error"></div>
                        </label>
                        <div class="attachments w-100 d-grid">
                            <template v-for="(item,index) in attch_list">
                                    <a :href="'images/mail_attachments/'+item.path">{{item.path}}</a>
                            </template>
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
    <script>
        var otable = "";
        var app = new Vue({
            el: "#main",
            data: function() {
                return {
                    inbox_contents: null,
                    user_records: null,
                    mail: "",
                    cc_mail: "",
                    bcc_mail: "",
                    subject: "",
                    message: "",
                    attachment: "",
                    inbox_id: null,
                    email_error: "",
                    cc_email_error: "",
                    bcc_email_error: "",
                    inbox_contents: null,
                    user_records: null,
                    page_name: "",
                    read_status: "",
                    selected: null,
                    attch_list:null
                }
            },
            methods: {
                navigate: function(id) {
                    if (this.page_name == 'Inbox') {
                        location.href = "mail_details.php?id=" + id
                    } else if (this.page_name == 'Draft') {
                        var data = {
                            'id': id,
                            'fetch_draft': true
                        }
                        axios.post('backend/compose_mail.php', data).then(res => {
                            this.inbox_id=res['data']['message_data'][0]['id']
                            this.cc_mail = res['data']['users'][res['data']['message_data'][0]['cc_receiver_id']]
                            this.bcc_mail = res['data']['users'][res['data']['message_data'][0]['bcc_receiver_id']]
                            this.mail = res['data']['users'][res['data']['message_data'][0]['receiver_id']]
                            this.subject = res['data']['message_data'][0]['short_subject_msg']
                            this.message = res['data']['message_data'][0]['full_message']
                            this.attch_list=res['data']['attachments']
                            $("#composeModal").modal('show')
                        })
                    }
                },
                send_email: function() {
                    var data = {
                        'inbox_id': this.inbox_id,
                        'main_mail': this.mail,
                        'to_message': this.message,
                        'send_mail': true
                    }
                    var id = "";
                    axios.post('backend/compose_mail.php', data).then(res => {
                        console.log(res['data'])
                        if (res['data'] == true) {
                            $('#composeModal').modal('hide');
                            alert('email_sent')
                        } else {
                            $('#message_error').html(res['data']['message']);
                        }

                        if (res['data']['inbox_id']) {
                            this.inbox_id = res['data']['inbox_id']
                        }
                        if (!res['data']['response']) {
                            res['data']['variable'] = res['data']['message']
                        }
                    })
                },
                make_draft: function() {

                    var data = {
                        'user_id': "<?php echo $_SESSION['id'] ?>",
                        'to_mail': this.mail,
                        'inbox_id': this.inbox_id,
                    }
                    var id = "";
                    axios.post('backend/compose_mail.php', data).then(res => {
                        if (res['data']['inbox_id']) {
                            this.inbox_id = res['data']['inbox_id']
                        }
                        if (!res['data']['response']) {
                            this.email_error = res['data']['message']
                        }
                    })
                },
                save_cc_mail: function(update_fields) {

                    var data = {
                        'user_id': "<?php echo $_SESSION['id'] ?>",
                        'cc_mail': this.cc_mail,
                        'inbox_id': this.inbox_id,
                    }
                    var id = "";
                    axios.post('backend/compose_mail.php', data).then(res => {
                        if (res['data']['inbox_id']) {
                            this.inbox_id = res['data']['inbox_id']
                        }
                        if (!res['data']['response']) {
                            this[res['data']['variable']] = res['data']['message']
                        }
                    })
                },
                save_bcc_mail: function(update_fields) {

                    var data = {
                        'user_id': "<?php echo $_SESSION['id'] ?>",
                        'bcc_mail': this.bcc_mail,
                        'inbox_id': this.inbox_id,
                    }
                    var id = "";
                    axios.post('backend/compose_mail.php', data).then(res => {
                        if (res['data']['inbox_id']) {
                            this.inbox_id = res['data']['inbox_id']
                        }
                        if (!res['data']['response']) {
                            this.email_error = res['data']['message']
                        }
                    })
                },
                save_subject: function(update_fields) {

                    var data = {
                        'user_id': "<?php echo $_SESSION['id'] ?>",
                        'subject': this.subject,
                        'inbox_id': this.inbox_id,
                    }
                    var id = "";
                    axios.post('backend/compose_mail.php', data).then(res => {
                        if (res['data']['inbox_id']) {
                            this.inbox_id = res['data']['inbox_id']
                        }
                        if (!res['data']['response']) {
                            this.email_error = res['data']['message']
                        }
                    })
                },
                save_message: function(update_fields) {

                    var data = {
                        'user_id': "<?php echo $_SESSION['id'] ?>",
                        'message': this.message,
                        'inbox_id': this.inbox_id,
                    }
                    var id = "";
                    axios.post('backend/compose_mail.php', data).then(res => {
                        if (res['data']['inbox_id']) {
                            this.inbox_id = res['data']['inbox_id']
                        }
                        if (!res['data']['response']) {
                            this.email_error = res['data']['message']
                        }
                    })
                },
                save_attachments: function(event) {
                    var formData = new FormData();
                    var imagefile = document.querySelector('#upload_attachments');
                    formData.append("files", imagefile.files[0]);
                    formData.append('inbox_id', this.inbox_id)
                    formData.append('user_id', '<?php echo $_SESSION['id']; ?>')
                    axios.post('backend/compose_mail.php', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(res => {
                        if (res['data']['inbox_id']) {
                            this.inbox_id = res['data']['inbox_id']
                            this.attch_list=res['data']['attachments']
                        }
                        if (!res['data']['response']) {
                            this.attch_list=res['data']['attahcments']
                            this.email_error = res['data']['message']
                        }
                    })
                },
                search: function(e) {
                    // alert($("#search").val())
                    $("#inbox_table").DataTable().search($("#search").val()).draw();
                },
                open_sent: function(event) {
                    this.page_name = "Sent"
                    $(".status").removeClass('disabled')
                    $(".status").removeClass('active')

                    event.target.classList.add('active');
                    event.target.classList.add('disabled');

                    var data = {
                        'page': 'sent'
                    }
                    this.inbox_contents = []

                    $('#inbox_table').DataTable().clear().destroy();
                    this.$nextTick(function() {
                        this.refresh(data)
                    })

                },
                open_inbox: function(event) {
                    this.page_name = "Inbox"

                    $(".status").removeClass('disabled')
                    $(".status").removeClass('active')

                    event.target.classList.add('active');
                    event.target.classList.add('disabled');
                    var data = {
                        'page': 'inbox'
                    }
                    this.inbox_contents = []


                    $('#inbox_table').DataTable().clear().destroy();

                    this.$nextTick(function() {
                        this.refresh(data)
                    })

                },
                open_draft: function(event) {
                    this.page_name = "Draft"

                    $(".status").removeClass('disabled')
                    $(".status").removeClass('active')

                    event.target.classList.add('active');
                    event.target.classList.add('disabled');
                    var data = {
                        'page': 'draft'
                    }
                    this.inbox_contents = []

                    $('#inbox_table').DataTable().clear().destroy();

                    this.$nextTick(function() {
                        this.refresh(data)
                    })

                },
                open_trash: function(event) {
                    this.page_name = "Trash"

                    $(".status").removeClass('disabled')
                    $(".status").removeClass('active')

                    event.target.classList.add('active');
                    event.target.classList.add('disabled');
                    var data = {
                        'page': 'trash'
                    }
                    this.inbox_contents = []
                    $('#inbox_table').DataTable().clear().destroy();
                    // this.$nextTick(function() {
                    this.refresh(data)
                    // })

                },
                refresh: function(data) {
                    axios.post('backend/inbox.php', data).then(res => {
                        this.inbox_contents = res['data']['data']
                        this.user_records = res['data']['user_records']
                        this.$nextTick(function() {
                            // $('#inbox_table').DataTable().clear();
                            $("#inbox_table").DataTable({
                                // retrieve: true,
                                // destroy: true,
                            });
                        })
                    })
                },
                selectall: function(event) {
                    // var page_name = $('.card-header').html()

                    if (this.page_name == 'Inbox') { // for inbox page

                        if ($("#selectAll").prop('checked') == true) {

                            var ind_check = $('.checkboxs').prop('checked', true)


                            var checked_all_array = [];

                            $("input[name=action]:checked").each(function() {
                                checked_all_array.push($(this).val());
                            });

                            // console.log(checked_all_array)

                            $('.read_status, .unread_status, .delete_status').removeClass('d-none');
                            this.selected = checked_all_array

                        } else {
                            $('.checkboxs').prop('checked', false)
                            var checked_all_array = [];

                            $("input[name=action]:checked").each(function() {
                                checked_all_array.push($(this).val());
                            });

                            $('.read_status, .unread_status, .delete_status').addClass('d-none');
                            this.selected = checked_all_array
                        }

                    } else if (this.page_name == 'Trash') { // for trash page
                        if ($("#selectAll").prop('checked') == true) {
                            $('.checkboxs').prop('checked', true)
                            $('.delete_status').removeClass('d-none');
                            $('.restore_status').removeClass('d-none');

                            var checked_all_array = [];

                            $("input[name=action]:checked").each(function() {
                                checked_all_array.push($(this).val());
                            });
                            this.selected = checked_all_array
                        } else {
                            $('.checkboxs').prop('checked', false)
                            $('.delete_status').addClass('d-none');
                            $('.restore_status').addClass('d-none');
                        }
                    } else {
                        if ($("#selectAll").prop('checked') == true) {
                            $('.checkboxs').prop('checked', true)
                            $('.delete_status').removeClass('d-none');
                            var checked_all_array = [];

                            $("input[name=action]:checked").each(function() {
                                checked_all_array.push($(this).val());
                            });
                            this.selected = checked_all_array
                        } else {
                            $('.checkboxs').prop('checked', false)
                            $('.delete_status').addClass('d-none');
                        }

                    }
                },
                single_select: function(event) {
                    var checked_all_array = [];

                    $("input[name=action]:checked").each(function() {
                        checked_all_array.push($(this).val());
                    });

                    if (this.page_name == 'Inbox') {
                        if (checked_all_array.length === 0) {
                            $('.read_status, .unread_status, .delete_status').addClass('d-none');
                        } else {
                            $('.read_status, .unread_status, .delete_status').removeClass('d-none');
                        }
                    } else if (this.page_name == 'Trash') {
                        if (checked_all_array.length === 0) {
                            $('.restore_status, .delete_status').addClass('d-none');
                        } else {
                            $('.restore_status, .delete_status').removeClass('d-none');
                        }
                    } else {
                        if (checked_all_array.length === 0) {
                            $('.delete_status').addClass('d-none');
                        } else {
                            $('.delete_status').removeClass('d-none');
                        }
                    }
                    this.selected = checked_all_array;
                },
                mark_as_read: function(event) {
                    var data = {
                        'selected': this.selected,
                        'mark_read': true,
                        'page_name': this.page_name
                    }
                    axios.post('backend/dashboard_actions.php', data).then(res => {
                        var data = {
                            'page': 'inbox'
                        }
                        this.inbox_contents = []
                        $('#inbox_table').DataTable().clear().destroy();
                        this.refresh(data)
                    })
                },
                mark_as_unread: function(event) {

                    var data = {
                        'selected': this.selected,
                        'mark_unread': true,
                        'page_name': this.page_name
                    }
                    axios.post('backend/dashboard_actions.php', data).then(res => {
                        var data = {
                            'page': 'inbox'
                        }
                        this.inbox_contents = []
                        $('#inbox_table').DataTable().clear().destroy();
                        this.refresh(data)
                    })
                },
                delete_message: function(event) {
                    if (this.page_name == 'Inbox') {
                        var data = {
                            'selected': this.selected,
                            'delete': true,
                            'delete_column': 'receiver_delete',
                            'page_name': this.page_name
                        }
                    } else {
                        var data = {
                            'selected': this.selected,
                            'delete': true,
                            'delete_column': 'sender_delete',
                            'page_name': this.page_name
                        }
                    }
                    axios.post('backend/dashboard_actions.php', data).then(res => {
                        var data = {
                            'page': (this.page_name).toLowerCase()
                        }
                        this.inbox_contents = []
                        $('#inbox_table').DataTable().clear().destroy();
                        this.refresh(data)
                    })
                },
                restore_message: function(event) {
                    if (this.page_name == 'Inbox') {
                        var data = {
                            'selected': this.selected,
                            'restore': true,
                            'delete_column': 'receiver_delete',
                            'page_name': this.page_name
                        }
                    } else {
                        var data = {
                            'selected': this.selected,
                            'restore': true,
                            'page_name': this.page_name,
                            'delete_column': 'sender_delete'
                        }
                    }
                    axios.post('backend/dashboard_actions.php', data).then(res => {
                        var data = {
                            'page': 'Trash'
                        }
                        this.inbox_contents = []
                        $('#inbox_table').DataTable().clear().destroy();
                        this.refresh(data)
                    })
                }
            },
            mounted() {
                var data = {
                    'page': 'inbox'
                }
                this.page_name = "Inbox"
                $('#inbox_table').DataTable().clear().destroy();
                this.$nextTick(function() {
                    this.refresh(data)
                })
            },
        })


        $(document).on("click", "td", function() {
            var message_id = $(this).closest('tr').attr('data_id');
        });
        $('#composeModal').on('hidden.bs.modal', function() {
            location.reload()
        });
    </script>

<?php
} else {
    header('location: index.php');
}

?>