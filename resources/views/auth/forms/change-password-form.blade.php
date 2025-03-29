<form id="changePasswordForm" class="form-horizontal mt-3" method="POST" action="{{ route('change-password') }}">
    @csrf
    @method('POST')

    <div id="response-message"></div>

    <div class="form-group mb-3 row">
        <div class="col-12">
            <input id="current-password" type="password" class="form-control"
                name="current-password" required  placeholder="Current Password">
        </div>
    </div>

    <div class="form-group mb-3 row">
        <div class="col-12">
            <input id="new-password" type="password" class="form-control"
                name="new-password" required  placeholder="New Password">
        </div>
    </div>

    <div class="form-group mb-3 row">
        <div class="col-12">
            <input id="new-password_confirmation" type="password" class="form-control" name="new-password_confirmation" placeholder="Confirm Password" required>
        </div>
    </div>

    <div class="form-group text-center row mt-3 pt-1">
        <div class="col-12">
            <button class="btn btn-info w-100 waves-effect waves-light" id="changeSubmit" type="button">Change Password</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#changeSubmit").on("click", function (e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('change-password') }}",
                method: "POST",
                data: $("#changePasswordForm").serialize(),
                success: function (response) {
                    $("#response-message").html(
                        `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Password changed successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                    );

                    $("#changePasswordForm")[0].reset();
                    
                    setTimeout(function () {
                        $("#changePasswordModal").modal("hide");
                    }, 1100);
                },
                error: function (xhr) {
                    let errorMessage = "Failed to change password.";

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    $("#response-message").html(
                        `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${errorMessage}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                    );
                }
            });
        });
    });
</script>
