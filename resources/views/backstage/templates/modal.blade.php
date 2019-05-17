<button type="button" class="btn btn-info" data-toggle="modal" data-target="#login-modal">Form in Modal</button>

<div id="login-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <a href="index.html" class="text-success">
                        <span><img src="assets/images/logo-dark.png" alt="" height="18"></span>
                    </a>
                </div>

                <form action="#" class="pl-3 pr-3">
                    <div class="form-group">
                        <label for="emailaddress1">Email address</label>
                        <input class="form-control" type="email" id="emailaddress1" required="" placeholder="john@deo.com">
                    </div>

                    <div class="form-group">
                        <label for="password1">Password</label>
                        <input class="form-control" type="password" required="" id="password1" placeholder="Enter your password">
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck2">
                            <label class="custom-control-label" for="customCheck2">Remember me</label>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button class="btn btn-rounded btn-primary" type="submit">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>