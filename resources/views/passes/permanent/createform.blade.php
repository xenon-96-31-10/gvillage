<create-pass-component role="{{auth()->user()->roles()->first()->slug}}" type="permanent" applicant="{{auth()->user()->profile->id}}"></create-pass-component>
