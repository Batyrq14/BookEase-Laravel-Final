<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Categories</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Manage service categories used to group and filter bookable services</p>
            </div>
        </div>
    </x-slot>

    <div class="grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
        {{-- Category list --}}
        <x-card :padding="false">
            @if($categories->count())
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Services</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700" x-data="{ editingId: null, editName: '' }">
                    @foreach ($categories as $category)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div x-show="editingId !== {{ $category->id }}">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $category->name }}</p>
                            </div>
                            <div x-show="editingId === {{ $category->id }}" x-cloak>
                                <form method="POST" action="{{ route('categories.update', $category) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" x-model="editName"
                                           class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/30"
                                           required />
                                    <button type="submit"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-slate-900 hover:bg-slate-800 rounded-lg transition-colors">
                                        Save
                                    </button>
                                    <button type="button" @click="editingId = null"
                                            class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                            {{ $category->services_count }}
                            <span class="text-slate-400 dark:text-slate-500 text-xs">{{ Str::plural('service', $category->services_count) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button"
                                        @click="editingId = {{ $category->id }}; editName = '{{ addslashes($category->name) }}'"
                                        class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                    Edit
                                </button>
                                @if($category->services_count === 0)
                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Delete category \'{{ addslashes($category->name) }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                        Delete
                                    </button>
                                </form>
                                @else
                                <span class="px-3 py-1.5 text-xs text-slate-300">Has services</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <p class="text-slate-700 dark:text-slate-300 font-semibold mb-1">No categories yet</p>
                <p class="text-slate-400 dark:text-slate-500 text-sm">Add your first category to start organising services.</p>
            </div>
            @endif
        </x-card>

        {{-- Add category form --}}
        <x-card>
            <div class="mb-5">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">New Category</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Add a category to group related services.</p>
            </div>

            <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Category name')" />
                    <x-text-input id="name" name="name" type="text" :value="old('name')"
                                  placeholder="e.g. Hair & Styling" required autofocus />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <x-primary-button>Add Category</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
