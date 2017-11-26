<p class="popup-tip">Сброс пароля</p>
<form>
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-row">
        <input class="material" placeholder="e-mail" type="text" name="email">
    </div>
    <div class="form-row">
        <input class="material" placeholder="новый пароль" type="password" name="password">
    </div>
    <div class="form-row">
        <input class="material" placeholder="пароль еще раз" type="password" name="password_confirmation">
    </div>
    <div class="form-row form-submit">
        <input type="submit" class="skin-btn skin-btn-reg ajax-submit" data-href="{{ route('front.password.reset.post') }}" data-done="pwd" data-success="login" data-backdrop="hide" value="Сохранить">
    </div>
</form>
