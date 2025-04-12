<form method="POST" action="{{ route('admin.login') }}">
    @csrf
    <div>
        <label for="email">Email</label>
        <input id="email" type="email" name="email" required autofocus />
    </div>
    <div>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required />
    </div>
    <div>
        <label for="remember_me">
            <input id="remember_me" type="checkbox" name="remember" />
            <span>Remember me</span>
        </label>
    </div>
    <div>
        <button type="submit">Log in</button>
    </div>
</form>