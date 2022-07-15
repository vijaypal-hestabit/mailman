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