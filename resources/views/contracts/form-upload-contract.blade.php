<div x-data="{}" x-init="@if (session('show_modal') === 'form-upload-contract') $nextTick(() => $flux.modal('upload-contract').show()) @endif">
    <flux:modal name="upload-contract" class="md:max-w-3xl">
        <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6"
            id="create-form">
            @csrf

            <div>
                <flux:heading size="lg">Upload Contract</flux:heading>
                <flux:text class="mt-2">
                    Contract upload accepts pdf, doc, and docx formats.
                </flux:text>
            </div>
            <flux:field class="md:col-span-2">
                <flux:label badge="Required">Select Profile (Shortname)</flux:label>
                <select name="shortname"
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required>
                    <option value="">— Select profile —</option>
                    @foreach ($profiles as $profile)
                        <option value="{{ $profile->short_name }}"
                            {{ old('shortname') == $profile->short_name ? 'selected' : '' }}>
                            {{ $profile->account_name }} ({{ $profile->short_name }})
                        </option>
                    @endforeach
                </select>
                @error('shortname')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </flux:field>

            {{-- two-column body --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                <flux:field>
                    <flux:label badge="Required">Contract Start Period</flux:label>
                    <flux:input name="contract_start" type="date" value="{{ old('contract_start') }}" />
                    @error('contract_start')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label badge="Required">Contract End Period</flux:label>
                    <flux:input name="contract_end" type="date" value="{{ old('contract_end') }}" />
                    @error('contract_end')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-2">
                    <flux:label badge="Required">Description</flux:label>
                    <flux:input name="description" value="{{ old('description') }}" placeholder="Enter description" />
                    @error('description')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </flux:field>

                <flux:field class="md:col-span-2">
                    <flux:input type="file" name="document" label="Document" badge="Required"
                        accept=".pdf,.doc,.docx" />
                </flux:field>
            </div>

            {{-- full-width footer with right-aligned button --}}
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <flux:button type="submit" variant="primary">
                    Upload Contract
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
