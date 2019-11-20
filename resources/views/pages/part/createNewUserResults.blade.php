<div class="panel panel-default">     
    <div class="panel-body">
        <form class="createuserform" method="POST" action="{{ route('create.user') }}">
            @csrf

            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name *') }}</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname *') }}</label>

                <div class="col-md-6">
                    <input id="surname" type="text" class="form-control" name="surname" value="{{ old('surname') }}" required autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address *') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="contacts[email]" value="{{ old('contacts[email]') }}" required>
                </div>
            </div>

            <div class="alternative_emails"></div>

            <div class="form-group row">
                <label for="add_fields_emails" class="col-md-4 col-form-label text-md-right"></label>
                <div class="col-md-6">
                    <a href="#" class="add_fields_emails btn btn-primary" id="add_fields_emails">Add More Fields</a>
                </div>
            </div>

            <div class="form-group row">
                <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number *') }}</label>

                <div class="col-md-6">
                    <input id="phone" type="phone" class="form-control" name="contacts[phone]" value="{{ old('contacts[phone]') }}" required>
                </div>
            </div>

            <div class="alternative_phones"></div>

            <div class="form-group row">
                <label for="add_fields_phones" class="col-md-4 col-form-label text-md-right"></label>
                <div class="col-md-6">
                    <a href="#" class="add_fields_phones btn btn-primary" id="add_fields_phones">Add More Fields</a>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Create User') }}
                    </button>
                </div>
            </div>

            <span><em><strong>* Compulsory</strong></em></span>
        </form><!-- createuserform -->
    </div><!-- panel-body nopadding -->
</div><!-- panel panel-default -->