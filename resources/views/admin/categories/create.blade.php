<x-app-layout>

    {{-- Header --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Kategori: Tambah Baru') }}
        </h2>
    </x-slot>

    <section class="mx-6">
        <div class="p-8">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="space-y-8">
                    {{-- Name --}}
                    <div>
                        <x-jet-label for="name" value="{{ __('Nama') }}" />
                        <x-jet-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus />
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Button --}}
                    <x-buttons.primary>
                        {{ __('Tambah') }}
                    </x-buttons.primary>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
