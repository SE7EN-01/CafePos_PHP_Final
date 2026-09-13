<x-app-layout>
    <div class="min-h-full bg-[#F9F6F0] p-4 sm:p-6 lg:p-8">
        <div class="mx-auto max-w-4xl space-y-6">
            <header class="bg-white rounded-3xl p-6 shadow-sm border border-stone-200/80">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Account Security</p>
                <h1 class="mt-1 text-2xl font-bold font-display tracking-tight text-stone-900">Profile &amp; Settings</h1>
                <p class="mt-0.5 text-xs text-stone-500">Manage your staff account information and credentials.</p>
            </header>

            <div class="space-y-6">
                <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-sm border border-stone-200/80">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-sm border border-stone-200/80 sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
