<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-surface-900 dark:text-white">People</h1>
                <p class="text-sm text-surface-500 dark:text-ink-400 mt-0.5">Manage admins, providers, and clients</p>
            </div>
            <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-add-user'))"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-500 active:bg-brand-700 active:translate-y-px text-white text-sm font-semibold shadow-btn hover:shadow-btn-hover active:shadow-btn-active transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add Person
            </button>
        </div>
    </x-slot>

    <div x-data="{
            tab: '{{ request('tab', 'all') }}',
            deleteUserId: null,
            deleteUserName: '',
            deleteAction: '',
            editOpen: false,
            editUser: { id: null, name: '', email: '', role: 'client', phone: '', category_id: '' },
            editAction: '',
            openEdit(user) {
                this.editUser = { ...user };
                this.editAction = '/admin/users/' + user.id;
                this.editOpen = true;
            },
            get filtered() {
                const users = {{ Js::from($users->map(fn($u) => [
                    'id'          => $u->id,
                    'name'        => $u->name,
                    'email'       => $u->email,
                    'role'        => $u->role->value,
                    'phone'       => $u->phone ?? '',
                    'category_id' => $u->category_id ?? '',
                    'category_name' => $u->category?->name ?? '',
                    'photo'       => $u->profile_photo_path ? asset('storage/'.$u->profile_photo_path) : null,
                    'isSelf'      => $u->id === auth()->id(),
                ])) }};
                if (this.tab === 'all') return users;
                return users.filter(u => u.role === this.tab);
            }
         }">

        {{-- ── Tab bar ────────────────────────────────────────────────────── --}}
        <div class="flex items-center gap-1 mb-5 bg-surface-100/60 dark:bg-ink-800/60 rounded-xl p-1 w-fit">
            @foreach(['all' => 'All', 'provider' => 'Providers', 'client' => 'Clients', 'admin' => 'Admins'] as $key => $label)
            <button type="button"
                    @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}'
                        ? 'bg-white dark:bg-ink-900 text-surface-900 dark:text-white shadow-card dark:shadow-card-dark font-semibold'
                        : 'text-surface-500 dark:text-ink-400 hover:text-surface-700 dark:hover:text-surface-200'"
                    class="px-4 py-1.5 rounded-lg text-sm transition-all duration-150">
                {{ $label }}
                <span :class="tab === '{{ $key }}' ? 'bg-brand-100 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300' : 'bg-surface-200 dark:bg-ink-700 text-surface-500 dark:text-ink-400'"
                      class="ml-1.5 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full text-[10px] font-bold tabular-nums">
                    {{ $key === 'all' ? $users->count() : $users->where('role.value', $key)->count() }}
                </span>
            </button>
            @endforeach
        </div>

        {{-- ── Table ─────────────────────────────────────────────────────── --}}
        <x-card :padding="false">
            <template x-if="filtered.length > 0">
                <div class="overflow-x-auto">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Person</th>
                                <th>Contact</th>
                                <th>Role</th>
                                <th class="hidden md:table-cell">Category</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="user in filtered" :key="user.id">
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <template x-if="user.photo">
                                                <img :src="user.photo" :alt="user.name" class="h-9 w-9 rounded-xl object-cover shrink-0 shadow-sm">
                                            </template>
                                            <template x-if="!user.photo">
                                                <div class="h-9 w-9 rounded-xl flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-sm"
                                                     :style="user.role === 'admin' ? 'background:#7c3aed' : (user.role === 'provider' ? 'background:#0ea5e9' : 'background:#10b981')"
                                                     x-text="user.name.charAt(0).toUpperCase()">
                                                </div>
                                            </template>
                                            <span class="font-semibold text-surface-900 dark:text-white" x-text="user.name"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-surface-700 dark:text-surface-300" x-text="user.email"></p>
                                        <p class="text-xs text-surface-400 dark:text-ink-500 mt-0.5" x-text="user.phone || '—'"></p>
                                    </td>
                                    <td>
                                        <template x-if="user.role === 'admin'">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ring-1 bg-brand-50 dark:bg-brand-950/40 text-brand-700 dark:text-brand-300 ring-brand-200/60 dark:ring-brand-800/40">
                                                <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>Admin
                                            </span>
                                        </template>
                                        <template x-if="user.role === 'provider'">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ring-1 bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 ring-sky-200/60 dark:ring-sky-800/40">
                                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>Provider
                                            </span>
                                        </template>
                                        <template x-if="user.role === 'client'">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ring-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 ring-emerald-200/60 dark:ring-emerald-800/40">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Client
                                            </span>
                                        </template>
                                    </td>
                                    <td class="hidden md:table-cell text-surface-500 dark:text-ink-400">
                                        <template x-if="user.role === 'provider' && user.category_name">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-accent-50 dark:bg-amber-950/40 text-accent-700 dark:text-accent-300 ring-1 ring-accent-200/60 dark:ring-amber-800/40" x-text="user.category_name"></span>
                                        </template>
                                        <template x-if="!(user.role === 'provider' && user.category_name)">
                                            <span class="text-surface-300 dark:text-ink-600">—</span>
                                        </template>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                    @click="openEdit(user)"
                                                    class="px-3 py-1.5 text-xs font-semibold text-surface-600 dark:text-surface-300 bg-surface-100 dark:bg-ink-800 hover:bg-surface-200 dark:hover:bg-ink-700 rounded-lg transition-colors">
                                                Edit
                                            </button>
                                            <template x-if="!user.isSelf">
                                                <button type="button"
                                                        @click="deleteUserId = user.id; deleteUserName = user.name; deleteAction = '/admin/users/' + user.id"
                                                        class="px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition-colors">
                                                    Delete
                                                </button>
                                            </template>
                                            <template x-if="user.isSelf">
                                                <span class="text-surface-300 dark:text-ink-600 text-xs px-3 py-1.5">You</span>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </template>

            <template x-if="filtered.length === 0">
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-surface-100 dark:bg-ink-800 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-surface-400 dark:text-ink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                    </div>
                    <p class="font-semibold text-surface-700 dark:text-surface-300 mb-1">No people found</p>
                    <p class="text-sm text-surface-400 dark:text-ink-500">Use the Add Person button above to create the first account.</p>
                </div>
            </template>
        </x-card>

        {{-- ── Add User modal ───────────────────────────────────────────────── --}}
        <div x-data="{
                open: {{ $errors->hasAny(['name','email','role','phone','password','category_id']) ? 'true' : 'false' }},
                role: '{{ old('role', 'client') }}'
             }"
             x-show="open"
             x-cloak
             @open-add-user.window="open = true"
             class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-[2px]" @click="open = false"></div>

            <div class="relative bg-white dark:bg-ink-900 rounded-2xl shadow-[0_24px_64px_rgba(0,0,0,0.18),0_0_0_1px_rgba(0,0,0,0.06)] w-full max-w-md overflow-y-auto max-h-[90vh]"
                 x-transition:enter="transition ease-[cubic-bezier(0.16,1,0.3,1)] duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-[0.97]"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.stop>

                <div class="flex items-center justify-between px-6 pt-6 pb-5 border-b border-black/[0.06] dark:border-white/[0.05]">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-brand-50 dark:bg-brand-950/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.11z"/>
                            </svg>
                        </div>
                        <h2 class="font-display text-base font-bold text-surface-900 dark:text-white">Add Person</h2>
                    </div>
                    <button type="button" @click="open = false"
                            class="p-1.5 rounded-lg text-surface-400 hover:text-surface-600 dark:hover:text-surface-200 hover:bg-surface-100 dark:hover:bg-ink-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="add_name" :value="__('Full name')" />
                        <x-text-input id="add_name" name="name" type="text" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="add_email" :value="__('Email address')" />
                        <x-text-input id="add_email" name="email" type="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="add_role" :value="__('Role')" />
                        <select id="add_role" name="role" x-model="role"
                                class="block w-full rounded-xl border-0 bg-surface-50 dark:bg-ink-900/80 text-surface-900 dark:text-white text-sm px-3 py-2.5 shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark outline-none transition-shadow mt-1">
                            <option value="client">Client</option>
                            <option value="provider">Provider</option>
                            <option value="admin">Admin</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" />
                    </div>

                    {{-- Provider-specific fields --}}
                    <div x-show="role === 'provider'" x-cloak class="space-y-4 pt-1">
                        <div>
                            <x-input-label for="add_category" :value="__('Category')" />
                            <select id="add_category" name="category_id"
                                    class="block w-full rounded-xl border-0 bg-surface-50 dark:bg-ink-900/80 text-surface-900 dark:text-white text-sm px-3 py-2.5 shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark outline-none transition-shadow mt-1">
                                <option value="">— No category —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" />
                        </div>
                        <div>
                            <x-input-label for="add_bio" :value="__('Bio')" />
                            <x-textarea id="add_bio" name="bio" rows="3" placeholder="Short provider bio…">{{ old('bio') }}</x-textarea>
                            <x-input-error :messages="$errors->get('bio')" />
                        </div>
                        <div>
                            <x-input-label for="add_photo" :value="__('Profile photo')" />
                            <input id="add_photo" name="profile_photo" type="file" accept="image/*"
                                   class="mt-1 block w-full rounded-xl text-sm text-surface-600 dark:text-surface-300
                                          file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold
                                          file:bg-surface-100 file:text-surface-700 dark:file:bg-ink-800 dark:file:text-surface-300
                                          hover:file:bg-surface-200 dark:hover:file:bg-ink-700
                                          shadow-input dark:shadow-input-dark focus:outline-none">
                            <x-input-error :messages="$errors->get('profile_photo')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="add_phone" :value="__('Phone (optional)')" />
                        <x-text-input id="add_phone" name="phone" type="tel" :value="old('phone')" placeholder="+1 555 000 0000" />
                        <x-input-error :messages="$errors->get('phone')" />
                    </div>

                    <div>
                        <x-input-label for="add_password" :value="__('Password')" />
                        <x-text-input id="add_password" name="password" type="password" required />
                        <x-input-error :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="add_password_confirmation" :value="__('Confirm password')" />
                        <x-text-input id="add_password_confirmation" name="password_confirmation" type="password" required />
                    </div>

                    <div class="flex gap-3 pt-3 border-t border-black/[0.06] dark:border-white/[0.05]">
                        <button type="button" @click="open = false"
                                class="flex-1 px-4 py-2.5 bg-white dark:bg-ink-800 shadow-[0_1px_2px_rgba(0,0,0,0.08),0_0_0_1px_rgba(0,0,0,0.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,0.06)] text-surface-700 dark:text-surface-300 font-semibold text-sm rounded-xl hover:bg-surface-50 dark:hover:bg-ink-700 active:translate-y-px transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl bg-brand-600 hover:bg-brand-500 active:bg-brand-700 active:translate-y-px px-4 py-2.5 text-sm font-semibold text-white shadow-btn hover:shadow-btn-hover active:shadow-btn-active transition-all">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Edit User modal ─────────────────────────────────────────────── --}}
        <div x-show="editOpen"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-[2px]" @click="editOpen = false"></div>

            <div class="relative bg-white dark:bg-ink-900 rounded-2xl shadow-[0_24px_64px_rgba(0,0,0,0.18),0_0_0_1px_rgba(0,0,0,0.06)] w-full max-w-md overflow-y-auto max-h-[90vh]"
                 x-transition:enter="transition ease-[cubic-bezier(0.16,1,0.3,1)] duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-[0.97]"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.stop>

                <div class="flex items-center justify-between px-6 pt-6 pb-5 border-b border-black/[0.06] dark:border-white/[0.05]">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-accent-50 dark:bg-amber-950/40 flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                        </div>
                        <h2 class="font-display text-base font-bold text-surface-900 dark:text-white">Edit Person</h2>
                    </div>
                    <button type="button" @click="editOpen = false"
                            class="p-1.5 rounded-lg text-surface-400 hover:text-surface-600 dark:hover:text-surface-200 hover:bg-surface-100 dark:hover:bg-ink-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" :action="editAction" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label :value="__('Full name')" />
                        <input type="text" name="name" x-model="editUser.name" required
                               class="block w-full rounded-xl border-0 bg-surface-50 dark:bg-ink-900/80 text-surface-900 dark:text-white placeholder-surface-400 dark:placeholder-ink-500 px-4 py-2.5 text-sm shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark outline-none transition-shadow mt-1" />
                    </div>

                    <div>
                        <x-input-label :value="__('Email address')" />
                        <input type="email" name="email" x-model="editUser.email" required
                               class="block w-full rounded-xl border-0 bg-surface-50 dark:bg-ink-900/80 text-surface-900 dark:text-white placeholder-surface-400 dark:placeholder-ink-500 px-4 py-2.5 text-sm shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark outline-none transition-shadow mt-1" />
                    </div>

                    <div>
                        <x-input-label :value="__('Role')" />
                        <select name="role" x-model="editUser.role"
                                class="block w-full rounded-xl border-0 bg-surface-50 dark:bg-ink-900/80 text-surface-900 dark:text-white text-sm px-3 py-2.5 shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark outline-none transition-shadow mt-1">
                            <option value="client">Client</option>
                            <option value="provider">Provider</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    {{-- Category (provider only) --}}
                    <div x-show="editUser.role === 'provider'" x-cloak>
                        <x-input-label :value="__('Category')" />
                        <select name="category_id" x-model="editUser.category_id"
                                class="block w-full rounded-xl border-0 bg-surface-50 dark:bg-ink-900/80 text-surface-900 dark:text-white text-sm px-3 py-2.5 shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark outline-none transition-shadow mt-1">
                            <option value="">— No category —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label :value="__('Phone (optional)')" />
                        <input type="tel" name="phone" x-model="editUser.phone" placeholder="+1 555 000 0000"
                               class="block w-full rounded-xl border-0 bg-surface-50 dark:bg-ink-900/80 text-surface-900 dark:text-white placeholder-surface-400 dark:placeholder-ink-500 px-4 py-2.5 text-sm shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark outline-none transition-shadow mt-1" />
                    </div>

                    <div>
                        <x-input-label :value="__('New password')" />
                        <input type="password" name="password"
                               class="block w-full rounded-xl border-0 bg-surface-50 dark:bg-ink-900/80 text-surface-900 dark:text-white placeholder-surface-400 dark:placeholder-ink-500 px-4 py-2.5 text-sm shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark outline-none transition-shadow mt-1"
                               placeholder="Leave blank to keep current" />
                        <p class="text-xs text-surface-400 dark:text-ink-500 mt-1">Leave blank to keep the current password.</p>
                    </div>

                    <div>
                        <x-input-label :value="__('Confirm new password')" />
                        <input type="password" name="password_confirmation"
                               class="block w-full rounded-xl border-0 bg-surface-50 dark:bg-ink-900/80 text-surface-900 dark:text-white placeholder-surface-400 dark:placeholder-ink-500 px-4 py-2.5 text-sm shadow-input dark:shadow-input-dark focus:shadow-input-focus dark:focus:shadow-input-focus-dark outline-none transition-shadow mt-1" />
                    </div>

                    <div class="flex gap-3 pt-3 border-t border-black/[0.06] dark:border-white/[0.05]">
                        <button type="button" @click="editOpen = false"
                                class="flex-1 px-4 py-2.5 bg-white dark:bg-ink-800 shadow-[0_1px_2px_rgba(0,0,0,0.08),0_0_0_1px_rgba(0,0,0,0.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,0.06)] text-surface-700 dark:text-surface-300 font-semibold text-sm rounded-xl hover:bg-surface-50 dark:hover:bg-ink-700 active:translate-y-px transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 inline-flex justify-center items-center gap-2 rounded-xl bg-accent-500 hover:bg-accent-400 active:bg-accent-600 active:translate-y-px px-4 py-2.5 text-sm font-semibold text-white shadow-btn hover:shadow-btn-hover active:shadow-btn-active transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Delete confirmation modal ────────────────────────────────────── --}}
        <div x-show="deleteUserId !== null"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-[2px]" @click="deleteUserId = null"></div>

            <div class="relative bg-white dark:bg-ink-900 rounded-2xl shadow-[0_24px_64px_rgba(0,0,0,0.18),0_0_0_1px_rgba(0,0,0,0.06)] w-full max-w-sm p-6"
                 x-transition:enter="transition ease-[cubic-bezier(0.16,1,0.3,1)] duration-300"
                 x-transition:enter-start="opacity-0 scale-[0.97]"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 scale-95">

                <div class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-900/20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="font-display text-base font-bold text-surface-900 dark:text-white text-center mb-1">Delete Person</h3>
                <p class="text-sm text-surface-500 dark:text-ink-400 text-center mb-6">
                    Delete "<span class="font-semibold text-surface-800 dark:text-surface-200" x-text="deleteUserName"></span>"? This cannot be undone.
                </p>
                <div class="flex gap-3">
                    <button type="button" @click="deleteUserId = null"
                            class="flex-1 px-4 py-2.5 bg-white dark:bg-ink-800 shadow-[0_1px_2px_rgba(0,0,0,0.08),0_0_0_1px_rgba(0,0,0,0.08)] dark:shadow-[0_0_0_1px_rgba(255,255,255,0.06)] text-surface-700 dark:text-surface-300 font-semibold text-sm rounded-xl hover:bg-surface-50 dark:hover:bg-ink-700 active:translate-y-px transition-all">
                        Cancel
                    </button>
                    <form :action="deleteAction" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-500 active:bg-red-700 active:translate-y-px text-white font-semibold text-sm rounded-xl shadow-btn hover:shadow-btn-hover active:shadow-btn-active transition-all">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
