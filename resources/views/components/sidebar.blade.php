<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
    type="button"
    class="text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base ms-3 mt-3 text-sm p-2 focus:outline-none inline-flex sm:hidden">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
        viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
    </svg>
</button>

<aside id="default-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-6 py-8 overflow-y-auto bg-neutral-primary-soft border-e border-default">
        <div class="flex items-center gap-2 mb-8">
            <img src="/images/kota-kendari-logo.png" alt="Logo Kota Kendari" class="size-12">
            <h1 class="text-2xl leading-[90%] font-semibold">DP3A Kota Kendari</h1>
        </div>
        <ul class="space-y-2 font-medium">

            {{-- Dashboard --}}
            <x-ui.nav-link href="/admin" :active="request()->is('admin')">
                <x-slot:icon>
                    <x-ui.nav-icon xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
                    </x-ui.nav-icon>
                </x-slot:icon>
                Dashboard
            </x-ui.nav-link>

            {{-- Pegawai --}}
            <x-ui.nav-link href="/admin/pegawai" :active="request()->is('admin/pegawai')">
                <x-slot:icon>
                    <x-ui.nav-icon fill="currentColor" width="24" height="24" viewBox="0 0 32 32"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M23.313 26.102l-6.296-3.488c2.34-1.841 2.976-5.459 2.976-7.488v-4.223c0-2.796-3.715-5.91-7.447-5.91-3.73 0-7.544 3.114-7.544 5.91v4.223c0 1.845 0.78 5.576 3.144 7.472l-6.458 3.503s-1.688 0.752-1.688 1.689v2.534c0 0.933 0.757 1.689 1.688 1.689h21.625c0.931 0 1.688-0.757 1.688-1.689v-2.534c0-0.994-1.689-1.689-1.689-1.689zM23.001 30.015h-21.001v-1.788c0.143-0.105 0.344-0.226 0.502-0.298 0.047-0.021 0.094-0.044 0.139-0.070l6.459-3.503c0.589-0.32 0.979-0.912 1.039-1.579s-0.219-1.32-0.741-1.739c-1.677-1.345-2.396-4.322-2.396-5.911v-4.223c0-1.437 2.708-3.91 5.544-3.91 2.889 0 5.447 2.44 5.447 3.91v4.223c0 1.566-0.486 4.557-2.212 5.915-0.528 0.416-0.813 1.070-0.757 1.739s0.446 1.267 1.035 1.589l6.296 3.488c0.055 0.030 0.126 0.063 0.184 0.089 0.148 0.063 0.329 0.167 0.462 0.259v1.809zM30.312 21.123l-6.39-3.488c2.34-1.841 3.070-5.459 3.070-7.488v-4.223c0-2.796-3.808-5.941-7.54-5.941-2.425 0-4.904 1.319-6.347 3.007 0.823 0.051 1.73 0.052 2.514 0.302 1.054-0.821 2.386-1.308 3.833-1.308 2.889 0 5.54 2.47 5.54 3.941v4.223c0 1.566-0.58 4.557-2.305 5.915-0.529 0.416-0.813 1.070-0.757 1.739 0.056 0.67 0.445 1.267 1.035 1.589l6.39 3.488c0.055 0.030 0.126 0.063 0.184 0.089 0.148 0.063 0.329 0.167 0.462 0.259v1.779h-4.037c0.61 0.46 0.794 1.118 1.031 2h3.319c0.931 0 1.688-0.757 1.688-1.689v-2.503c-0.001-0.995-1.689-1.691-1.689-1.691z" />
                    </x-ui.nav-icon>
                </x-slot:icon>
                Pegawai
            </x-ui.nav-link>

            {{-- Cuti --}}
            <x-ui.nav-link href="/admin/cuti" :active="request()->is('admin/cuti')">
                <x-slot:icon>
                    <x-ui.nav-icon width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7 3V6M17 3V6M7.10002 20C7.56329 17.7178 9.58104 16 12 16C14.419 16 16.4367 17.7178 16.9 20M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21ZM14 11C14 12.1046 13.1046 13 12 13C10.8954 13 10 12.1046 10 11C10 9.89543 10.8954 9 12 9C13.1046 9 14 9.89543 14 11Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </x-ui.nav-icon>
                </x-slot:icon>
                Cuti
            </x-ui.nav-link>

            {{-- KGB --}}
            <x-ui.nav-link href="/admin/kgb" :active="request()->is('admin/kgb*')">
                <x-slot:icon>
                    <x-ui.nav-icon width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.5858,2 C14.1162,2 14.6249,2.21071 15,2.58579 L15,2.58579 L19.4142,7 C19.7893,7.37507 20,7.88378 20,8.41421 L20,8.41421 L20,20 C20,21.1046 19.1046,22 18,22 L18,22 L6,22 C4.89543,22 4,21.1046 4,20 L4,20 L4,4 C4,2.89543 4.89543,2 6,2 L6,2 Z M12,4 L6,4 L6,20 L18,20 L18,10 L13.5,10 C12.6716,10 12,9.32843 12,8.5 L12,4 Z M15,14 C15.5523,14 16,14.4477 16,15 C16,15.5523 15.5523,16 15,16 L9,16 C8.44772,16 8,15.5523 8,15 C8,14.4477 8.44772,14 9,14 L15,14 Z M10,10 C10.5523,10 11,10.4477 11,11 C11,11.5523 10.5523,12 10,12 L10,12 L9,12 C8.44772,12 8,11.5523 8,11 C8,10.4477 8.44772,10 9,10 L9,10 Z M14,4.41421 L14,8 L17.5858,8 L14,4.41421 Z" />
                    </x-ui.nav-icon>
                </x-slot:icon>
                KGB
            </x-ui.nav-link>
        </ul>
    </div>
</aside>
