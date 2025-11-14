<div id="advisory-modal-root" x-data="{
    preview: null,

    fileChosen(event) {
        const file = event.target.files[0];
        if (!file || !file.type.startsWith('image/')) {
            this.preview = null;
            return;
        }
        this.preview = URL.createObjectURL(file);
    },

    resetPreview() {
        if (this.preview && this.preview.startsWith('blob:')) {
            URL.revokeObjectURL(this.preview);
        }
        this.preview = null;
    },

    setExistingPreview(url) {
        this.resetPreview();
        if (url) {
            this.preview = url;
        }
    }
}" x-init="@if ($errors->any()) $nextTick(() => $flux.modal('advisory-show-modal').show()) @endif">
    <flux:modal name="advisory-show-modal" class="w-full max-w-5xl" :dismissible="false" x-on:close="resetPreview()">
        <form action="{{ route('advisories.update', ['advisory' => ':advisory_id']) }}"
            data-base-action="{{ route('advisories.update', ['advisory' => ':advisory_id']) }}" method="POST"
            enctype="multipart/form-data" class="flex gap-8" id="edit-advisory-form">
            @csrf
            @method('PUT')

            <input type="hidden" name="advisory_id" value="">

            <!-- Left: Image upload & preview -->
            <div class="w-2/5 flex flex-col">
                <flux:label class="mb-2">Attachment</flux:label>

                <input type="file" name="edit_attachment" accept="image/*" @change="fileChosen($event)"
                    class="w-full text-sm text-gray-500
                           file:mr-4 file:py-2 file:px-4 file:border-0
                           file:bg-blue-50 file:text-blue-700
                           hover:file:bg-blue-100">

                <div
                    class="w-full mt-4 aspect-square rounded-xl
                            bg-gray-100 dark:bg-neutral-800
                            flex items-center justify-center
                            relative overflow-hidden">
                    <!-- If preview is set (either existing URL or new blob), show it -->
                    <template x-if="preview">
                        <img :src="preview" alt="Preview"
                            class="absolute inset-0 w-full h-full object-cover rounded-xl" />
                    </template>

                    <!-- Otherwise, display the “No image selected” placeholder -->
                    <template x-if="!preview">
                        <div class="flex flex-col items-center justify-center text-gray-400 dark:text-neutral-500">
                            <flux:icon name="image" class="w-14 h-14 mb-2" />
                            <span class="text-sm text-center px-4">No image selected</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right: Text fields -->
            <div class="w-3/5 space-y-5">
                <flux:heading size="lg">Edit Advisory</flux:heading>

                <flux:field>
                    <flux:label badge="Required">Headline</flux:label>
                    <flux:input name="edit_headline" placeholder="Enter headline" />
                    @error('edit_headline')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea name="edit_description" placeholder="Short description" rows="2">
                    </flux:textarea>
                    @error('edit_description')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </flux:field>
                <flux:field>
                    <flux:label>Link (optional)</flux:label>
                    <flux:input type="url" name="edit_link" placeholder="https://example.com/…" class="w-full" />
                    @error('edit_link')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </flux:field>
                <flux:field>
                    <flux:label>Content</flux:label>
                    <flux:textarea name="edit_content" placeholder="Full advisory content" rows="4">
                    </flux:textarea>
                    @error('edit_content')
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Archive Status</flux:label>
                    <div class="flex items-center gap-3 mt-1">
                        <flux:switch id="archive-status-switch" />
                        <span id="archive-status-label" class="text-sm font-medium text-gray-700">Loading…</span>
                    </div>
                    <input type="hidden" name="is_archive" id="archive-value" value="">
                    <flux:error name="is_archive" />
                </flux:field>




                <div class="flex justify-end pt-2 gap-3">
                    <flux:button type="button" variant="primary" @click="$flux.modal('advisory-show-modal').hide()">
                        Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save Changes</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('tr.flux-btn-info').forEach(row => {
            row.addEventListener('click', function() {
                // Read current archive state
                let isArchived = this.dataset.isArchive === '1';

                // Grab modal elements
                const label = document.getElementById('archive-status-label');
                const hidden = document.getElementById('archive-value');
                const toggle = document.getElementById('archive-status-switch');

                // Initialize UI
                label.textContent = isArchived ? 'Unarchive' : 'Archive';
                hidden.value = isArchived ? '1' : '0';

                // Clear old listener, bind new one
                const newToggle = toggle.cloneNode(true);
                toggle.parentNode.replaceChild(newToggle, toggle);
                newToggle.addEventListener('click', () => {
                    isArchived = !isArchived;
                    label.textContent = isArchived ? 'Unarchive' : 'Archive';
                    hidden.value = isArchived ? '1' : '0';
                });

                // Populate the rest of the form
                const form = document.getElementById('edit-advisory-form');
                form.action = form.dataset.baseAction.replace(':advisory_id', this.dataset.id);
                form.querySelector('input[name="advisory_id"]').value = this.dataset.id;
                form.querySelector('input[name="edit_headline"]').value = this.dataset.headline;
                form.querySelector('textarea[name="edit_description"]').value = this.dataset
                    .description;
                form.querySelector('textarea[name="edit_content"]').value = this.dataset
                .content;
                const linkInput = form.querySelector('input[name="edit_link"]');
                linkInput.value = this.dataset.link || '';

                Alpine.$data(document.getElementById('advisory-modal-root'))
                    .setExistingPreview(this.dataset.attachmentUrl || null);

                // Show it
                $flux.modal('advisory-show-modal').show();
            });
        });
    });
</script>
