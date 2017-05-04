{{-- resources/views/emails/custom-password.blade.php --}}

<?php
$request = app(\Illuminate\Http\Request::class);
$resetUrl = $request->get('reset_url');
$url = url(str_replace('{token}', $token, $resetUrl));
?>

Click here to reset your password: <a href="{{ $url }}">{{ $url }}</a>