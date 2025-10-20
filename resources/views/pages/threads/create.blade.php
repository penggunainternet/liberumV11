<x-guest-layout>
    <main class="grid grid-cols-4 gap-8 mt-8 wrapper">

        <x-partials.sidenav />

        <section class="flex flex-col col-span-3 gap-y-4">

            {{-- breadcrumb --}}
            <div class="flex items-center pb-2 overflow-y-auto whitespace-nowrap">
                <a href="#" class="text-gray-600 dark:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                </a>

                <span class="mx-5 text-gray-500 dark:text-gray-300">
                    <x-heroicon-s-chevron-right class="w-5 h-5" />
                </span>

                <a href="#" class="text-yellow-500 dark:text-gray-200 ">
                    Buat Postingan
                </a>
            </div>

            <article class="p-5 bg-white shadow rounded-lg">
                <div class="w-full">

                    {{-- Create --}}
                    <div class="space-y-6">
                        <form method="POST" action="{{ route('threads.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-8">

                                {{-- Title --}}
                                <div>
                                    <x-jet-label for="title" value="{{ __('Judul') }}" />
                                    <x-jet-input id="title" class="block w-full mt-1" type="text" name="title" :value="old('title')" autofocus />
                                    @error('title')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Category --}}
                                <div>
                                    <x-jet-label for="category_id" value="{{ __('Kategori') }}" />
                                    <select name="category_id" id="category_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id() }}">{{ $category->name() }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Body --}}
                                <div>
                                    <x-jet-label for="body" value="{{ __('Deskripsi') }}" />
                                    <textarea
                                        id="body"
                                        name="body"
                                        rows="8"
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        placeholder="Tulis deskripsi thread Anda di sini...">{{ old('body') }}</textarea>
                                    @error('body')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Images Upload --}}
                                <div>
                                    <x-jet-label for="images" value="Gambar (Opsional)" />
                                    <div class="mt-1">
                                        <input type="file"
                                               name="images[]"
                                               id="images"
                                               multiple
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                               onchange="previewImages(this)">
                                        <p class="mt-1 text-xs text-gray-500">
                                            Maksimal 5 gambar, masing-masing maksimal 5MB. Total maksimal 15MB. Format: JPEG, PNG, JPG, GIF, WebP.
                                        </p>
                                        @error('images')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        @error('images.*')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Image Preview --}}
                                    <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4" style="display: none;"></div>
                                </div>

                                {{-- Button --}}
                                <x-buttons.primary>
                                    Buat Thread
                                </x-buttons.primary>
                            </div>
                        </form>
                    </div>
                </div>
            </article>
        </section>
    </main>

    {{-- Image Preview Script --}}
    <script>
        function previewImages(input) {
            const preview = document.getElementById('imagePreview');
            const files = input.files;

            // Clear previous previews
            preview.innerHTML = '';

            if (files.length === 0) {
                preview.style.display = 'none';
                return;
            }

            // Check file count limit
            if (files.length > 5) {
                alert('Maksimal 5 gambar yang dapat diupload.');
                input.value = '';
                return;
            }

            // Check total size limit (15MB)
            let totalSize = 0;
            for (let file of files) {
                totalSize += file.size;
            }

            if (totalSize > 15728640) { // 15MB in bytes
                alert('Total ukuran gambar tidak boleh lebih dari 15MB.');
                input.value = '';
                return;
            }

            preview.style.display = 'grid';

            // Show preview for each image
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}"
                                 class="w-full h-24 object-cover rounded-lg border"
                                 alt="Preview ${index + 1}">
                            <button type="button"
                                    onclick="removeImage(${index})"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center hover:bg-red-600">
                                ×
                            </button>
                            <p class="text-xs text-gray-500 mt-1 truncate">${file.name}</p>
                            <p class="text-xs text-gray-400">${formatFileSize(file.size)}</p>
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function removeImage(index) {
            const input = document.getElementById('images');
            const dt = new DataTransfer();

            for (let i = 0; i < input.files.length; i++) {
                if (i !== index) {
                    dt.items.add(input.files[i]);
                }
            }

            input.files = dt.files;
            previewImages(input);
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
</x-guest-layout>
