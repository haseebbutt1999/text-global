@component('mail::message')
# Introduction

Dear Customer, thanks for signing up and one of the team will contact you, to complete the setup.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
