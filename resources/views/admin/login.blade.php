<x-layouts.site title="Administrator sign in">
    <section class="dji-container py-12 sm:py-20" aria-labelledby="login-heading">
        <div class="mx-auto w-full max-w-md rounded-2xl border border-neutral-200 bg-white p-6 sm:p-9">
            <p class="text-sm font-semibold tracking-wide text-neutral-600">DEAFJOBSINDIA / ADMIN</p>
            <h1 id="login-heading" class="mt-3 text-3xl font-bold tracking-tight">Administrator sign in</h1>
            <p class="mt-3 text-base leading-7 text-neutral-600">Sign in with your authorised team account.</p>

            @if($errors->any())
                <div role="alert" class="mt-6 rounded-lg border-2 border-neutral-900 bg-neutral-50 p-4 text-sm leading-6 text-neutral-900">
                    <p class="font-semibold">Unable to sign in</p>
                    <p class="mt-1">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('admin.login.store') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <div>
                    <label for="login-email" class="block text-sm font-semibold">Email address</label>
                    <input id="login-email" name="email" type="email" inputmode="email" autocomplete="username" required maxlength="255" value="{{ old('email') }}"
                        @if($errors->has('email')) aria-invalid="true" aria-describedby="login-email-error" @endif
                        class="mt-2 block min-h-12 w-full rounded-lg border border-neutral-500 bg-white px-4 py-3 text-base text-neutral-900 placeholder:text-neutral-500 focus:border-black focus:outline-2 focus:outline-offset-2 focus:outline-black" placeholder="name@example.com">
                    @error('email')
                        <p id="login-email-error" class="mt-2 text-sm font-medium text-neutral-900">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="login-password" class="block text-sm font-semibold">Password</label>
                    <div class="relative mt-2">
                        <input id="login-password" name="password" type="password" autocomplete="current-password" required
                            @if($errors->has('password')) aria-invalid="true" aria-describedby="login-password-error" @endif
                            class="block min-h-12 w-full rounded-lg border border-neutral-500 bg-white py-3 pl-4 pr-20 text-base text-neutral-900 focus:border-black focus:outline-2 focus:outline-offset-2 focus:outline-black">
                        <button type="button" data-password-toggle="login-password" aria-controls="login-password" aria-pressed="false" class="absolute inset-y-1 right-1 rounded-md px-3 text-sm font-semibold text-neutral-900 underline underline-offset-2 focus:outline-2 focus:outline-offset-2 focus:outline-black">Show</button>
                    </div>
                    @error('password')
                        <p id="login-password-error" class="mt-2 text-sm font-medium text-neutral-900">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="flex min-h-12 w-full items-center justify-center rounded-lg bg-neutral-950 px-5 py-3 text-base font-semibold text-white hover:bg-neutral-800 focus:outline-2 focus:outline-offset-2 focus:outline-black">Sign in</button>
            </form>
            <p class="mt-6 text-sm leading-6 text-neutral-600">Access is limited to authorised DeafJobsIndia administrators.</p>
        </div>
    </section>
</x-layouts.site>
