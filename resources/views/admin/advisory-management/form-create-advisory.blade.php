<div x-data="{
    preview: null,
    fileChosen(event) {
        const file = event.target.files[0];
        if (!file || !file.type.startsWith('image/')) {
            this.preview = null;
            return;
        }
        this.preview = URL.createObjectURL(file);
        this.$watch('preview', (newPreview, oldPreview) => {
            if (oldPreview) {
                URL.revokeObjectURL(oldPreview);
            }
        });
    },
    resetPreview() {
        if (this.preview) {
            URL.revokeObjectURL(this.preview);
        }
        this.preview = null;
    }
}" x-init="@if($errors->any())
$nextTick(() => $flux.modal('create-advisory').show())
@endif">
    <flux:modal name="create-advisory" class="w-full max-w-5xl" :dismissible="false" x-on:close="resetPreview()">

        <form action="{{ route('advisories.store') }}" method="POST" enctype="multipart/form-data" class="flex gap-6"
            id="create-form">
            @csrf

            <!-- Left side: Image upload -->
            <div class="w-2/5 flex flex-col">
                <flux:label class="mb-2">Attachment</flux:label>

                <input type="file" name="attachment" accept="image/*" @change="fileChosen($event)"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                <!-- Image preview or placeholder -->
                <div
                    class="w-full mt-4 aspect-square rounded-xl bg-gray-100 dark:bg-neutral-800 flex items-center justify-center relative overflow-hidden">
                    <!-- If a preview exists, show image -->
                    <template x-if="preview">
                        <img :src="preview" alt="Preview"
                            class="absolute inset-0 w-full h-full object-cover rounded-xl" />
                    </template>

                    <!-- If no preview, show placeholder -->
                    <template x-if="!preview">
                        <div class="flex flex-col items-center justify-center text-gray-400 dark:text-neutral-500">
                            <flux:icon name="image" class="w-14 h-14 mb-2" />
                            <span class="text-sm text-center px-4">No image selected</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right side: Form fields -->
            <div class="w-3/5 space-y-5">
                <flux:heading size="lg">
                    Create New Advisory
                </flux:heading>

                <flux:field>
                    <flux:label badge="Required">Headline</flux:label>
                    <flux:input name="headline" value="{{ old('headline') }}" placeholder="Enter headline" />
                    @error('headline')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea name="description" placeholder="Short description" rows="2">
                        {{ old('description') }}</flux:textarea>
                    @error('description')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Content</flux:label>
                    <flux:textarea name="content" placeholder="Full advisory content" rows="4">
                        {{ old('content') }}</flux:textarea>
                    @error('content')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </flux:field>
                <flux:field>
                    <flux:label>Link (optional)</flux:label>
                    <flux:input type="url" name="link" value="{{ old('link') }}"
                        placeholder="https://example.com/…" class="w-full" />
                    @error('link')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </flux:field>

                <div class="flex justify-end pt-2">
                    <flux:button type="button" id="create-button" variant="primary">
                        Create Advisory
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    <script>
        // Handling form submission and disabling the button
        document.getElementById('create-button').addEventListener('click', function() {
            const createBtn = this;
            const form = document.getElementById('create-form');

            createBtn.disabled = true;
            createBtn.innerText = 'Creating...';

            form.submit();
        });
    </script>
</div>
