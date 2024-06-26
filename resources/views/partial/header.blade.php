<header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap" />
                </svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li>My App</li>

            </ul>

            <div class="dropdown text-end">
                <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                </a>
                <ul class="dropdown-menu text-small">
                    <li>
                        <button type="button" class="dropdown-item" onclick="logout()">Sign out</button>

                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<script>
    async function logout() {
        await axios.post('/api/auth/logout', {
            headers: {
                'Authorization': $.cookie('token')
            }
        }).then((result) => {
            $.removeCookie('token', $.cookie('token'))
            window.location.href = '/'
        }).catch((err) => {
            console.log(err);
        });
    }
</script>