<form class="form-horizontal mt-3" method="POST" action="{{ $isAdmin == 0 ? route('admin.register') : route('register') }}">
    @csrf

    <div class="form-group mb-3 row">
        <div class="col-12">
            <div class="row">
                <div class="col-md-4">
                    <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
                        name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name"
                        autofocus placeholder="Firstname">
                    @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror"
                        name="middle_name" value="{{ old('middle_name') }}" required autocomplete="middle_name"
                        autofocus placeholder="Middlename">
                    @error('middle_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror"
                        name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name"
                        autofocus placeholder="Lastname">
                    @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mb-3 row">
        <div class="col-12">
            <select name="office_division" id="office_division" required class="form-control">
                <option value="" selected disabled>Choose Office</option>
                <option value="records and archives unit">RECORDS and ARCHIVES Unit</option>
                <option value="bids and awards unit">BIDS and AWARDS Unit</option>
                <option value="payments unit">PAYMENTS Unit</option>
                <option value="procurement unit">PROCUREMENT Unit</option>
            </select>
            @error('office_division')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group mb-3 row">
        <div class="col-12">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    @if($isAdmin == 1)
    <div class="form-group mb-3 row">
        <div class="col-12">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="new-password" placeholder="Password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group mb-3 row">
        <div class="col-12">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                autocomplete="new-password" placeholder="Confirm Password">
        </div>
    </div>
    @else
        <input type="hidden" name="password" value="12345678">
        <input type="hidden" name="password_confirmation" value="12345678">
    @endif

    <input type="hidden" name="is_admin" value="{{ $isAdmin }}">

    <div class="form-group text-center row mt-3 pt-1">
        <div class="col-12">
            <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Register</button>
        </div>
    </div>
</form>
