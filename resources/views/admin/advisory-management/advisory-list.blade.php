<x-layouts.app>
    <div class="p-6 bg-white rounded-xl shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Advisories</h2>

            <!-- Create Advisory button (unchanged) -->
            <flux:modal.trigger name="create-advisory">
                <flux:button variant="primary">
                    Create Advisory
                </flux:button>
            </flux:modal.trigger>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-left">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Headline</th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($advisories as $advisory)
                        <tr class="cursor-pointer hover:bg-gray-100 transition flux-btn-info {{ $advisory->is_archive ? 'bg-red-100' : '' }}"
                            data-id="{{ $advisory->id }}" data-headline="{{ $advisory->headline }}"
                            data-description="{{ $advisory->description }}" data-content="{{ $advisory->content }}"
                            data-attachment-url="{{ $advisory->attachment_url ?? '' }}"
                            data-date="{{ $advisory->created_at->format('M d, Y') }}"
                            data-created-by="{{ $advisory->user->name }}"
                            data-is-archive="{{ $advisory->is_archive ? '1' : '0' }}" data-link="{{ $advisory->link }}"
                            onclick="document.getElementById('open-view-modal').click()">
                            <td>{{ $advisory->id }}</td>
                            <td>{{ $advisory->headline }}</td>
                            <td>{{ $advisory->user->name }}</td>
                            <td>{{ $advisory->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $advisories->links() }}
        </div>

        <!-- Hidden button that actually opens the “Edit Advisory” modal -->
        <flux:modal.trigger name="advisory-show-modal">
            <button id="open-view-modal" class="hidden"></button>
        </flux:modal.trigger>
    </div>

    @include('admin.advisory-management.advisory-show-modal')
    @include('admin.advisory-management.form-create-advisory')


    <script>
        document.querySelectorAll('tr.flux-btn-info').forEach(row => {
            row.addEventListener('click', function() {
                const advisoryId = this.getAttribute('data-id');
                const headline = this.getAttribute('data-headline');
                const description = this.getAttribute('data-description');
                const content = this.getAttribute('data-content');
                const attachmentUrl = this.getAttribute('data-attachment');
                const isArchive = this.getAttribute('data-is-archive') === '1';

                // 1) Grab the Alpine component wrapping the “Edit Advisory” modal
                const alpineComponent = document.getElementById('advisory-modal-root');
                if (!alpineComponent) {
                    console.warn('Cannot find #advisory-modal-root');
                    return;
                }
                const alpineData = Alpine.$data(alpineComponent);

                // 2) Update Alpine’s preview with the existing image URL (if any)
                alpineData.setExistingPreview(attachmentUrl);

                // 3) Populate the form’s action + hidden advisory_id + text fields
                const form = document.getElementById('edit-advisory-form');
                const baseAction = form.getAttribute('data-base-action');
                form.action = baseAction.replace(':advisory_id', advisoryId);

                // Hidden ID field
                form.querySelector('input[name="advisory_id"]').value = advisoryId;

                // Headline / Description / Content inputs
                form.querySelector('input[name="edit_headline"]').value = headline;
                form.querySelector('textarea[name="edit_description"]').value = description;
                form.querySelector('textarea[name="edit_content"]').value = content;

                // 4) Set archive checkbox
                const archiveCheckbox = form.querySelector('input[name="is_archive"]');
                if (archiveCheckbox) {
                    archiveCheckbox.checked = isArchive;
                }

                // 5) Finally, show the modal
                $flux.modal('advisory-show-modal').show();
            });
        });
    </script>

</x-layouts.app>
