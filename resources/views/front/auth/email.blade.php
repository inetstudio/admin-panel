@php
    \Session::reflash();
@endphp

<form>
    <div class="form-row">
        <input placeholder="e-mail" type="email" name="email">
    </div>
    <div class="form-row form-submit">
        <input type="submit" data-href="{{ route('front.oauth.email.approve') }}" value="Продолжить">
    </div>
</form>
