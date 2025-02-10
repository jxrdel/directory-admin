@section('styles')
    <style>
        /* Variables */
        :root {
            --wizard-color-neutral: #ccc;
            --wizard-color-active: #6ba2eb;
            --wizard-color-complete: #87D37C;
            --wizard-step-width-height: 64px;
            --wizard-step-font-size: 24px;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f6f6f6;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            min-height: 560px;
            display: flex;
            flex-direction: column;
            /* Stack items vertically */
            justify-content: flex-start;
            /* Align to the top */
        }

        .step-wrapper {
            padding: 20px 0;
            display: none;
            flex-grow: 1;
            /* This makes it take up available space */
        }

        .step-wrapper.active {
            display: block;
        }

        .step-navigation {
            margin-bottom: 20px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            margin-left: 0;
            list-style: none;
            position: relative;
        }

        .step-indicator li {
            display: flex;
            justify-content: center;
            position: relative;
        }

        .step-indicator li::after {
            content: "";
            display: block;
            background-color: var(--wizard-color-neutral);
            position: absolute;
            top: calc(50% - 1px);
            left: 0;
            right: 0;
            width: 100%;
            height: 1px;
        }

        .step-indicator li:last-child::after {
            display: none;
        }

        .step-indicator li.active .step {
            border-color: var(--wizard-color-active);
            color: var(--wizard-color-active);
        }

        .step-indicator li.complete::after {
            background-color: var(--wizard-color-complete);
        }

        .step-indicator li.complete .step {
            border-color: var(--wizard-color-complete);
            color: var(--wizard-color-complete);
        }

        .step {
            background-color: #fff;
            border-radius: 50%;
            border: 2px solid var(--wizard-color-neutral);
            color: var(--wizard-color-neutral);
            font-size: var(--wizard-step-font-size);
            height: var(--wizard-step-width-height);
            line-height: var(--wizard-step-width-height);
            width: var(--wizard-step-width-height);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 1;
            cursor: pointer;
        }

        .step:hover {
            background-color: var(--wizard-color-active);
            color: #fff;
        }

        .caption {
            color: var(--wizard-color-neutral);
            padding: 11px 16px;
            font-size: 16px;
        }

        .step-controls {
            display: flex;
            justify-content: space-between;
            margin-top: auto;
            /* This will push the step controls to the bottom */
        }
    </style>
@endsection
<div class="container" x-data="{
    isImported: $wire.entangle('isImported'),
    importOption: $wire.entangle('importOption'),
    currentStep: 1,
    steps: [
        { id: 1, title: 'Import Type', icon_class: 'fa-regular fa-hand-pointer' },
        { id: 2, title: 'Upload', icon_class: 'fa-solid fa-upload' },
        { id: 3, title: 'Finish', icon_class: 'fa-solid fa-circle-check' }
    ],
    nextStep() { if (this.currentStep < 3) this.currentStep++ },
    prevStep() { if (this.currentStep > 1) this.currentStep-- }
}">

    <h2 style="text-align: center;"><i class="fa-solid fa-file-import"></i> &nbsp; Import Wizard</h2>
    <!-- Step Navigation -->
    <div wire:ignore class="step-navigation">
        <ol class="step-indicator">
            <template x-for="step in steps" :key="step.id">
                <li
                    :class="{
                        active: step.id === currentStep,
                        complete: step.id < currentStep
                    }">
                    <div class="step">
                        <i :class="step.icon_class"></i>
                    </div>
                    <div class="caption fw-bold" :class="{ 'text-black': currentStep === step.id }">
                        Step <span x-text="step.id"></span>: <span x-text="step.title"></span>
                    </div>
                </li>
            </template>
        </ol>
    </div>

    <!-- Step 1 -->
    <div x-show="currentStep === 1" class="step-wrapper" :class="{ 'active': currentStep === 1 }" x-transition>
        <h2>Select the Type of Import</h2>
        <div class="form-check form-check mt-4">
            <input class="form-check-input" type="radio" name="import-option" id="importRecords"
                wire:model="importOption" value="import-records">
            <label class="form-check-label" for="importRecords">Import New Records (Add new records to current
                directory)</label>
        </div>
        <div class="form-check form-check">
            <input class="form-check-input" type="radio" name="import-option" id="bulkOverwrite"
                wire:model="importOption" value="bulk-overwrite">
            <label class="form-check-label" for="bulkOverwrite">Bulk Overwrite (Completely overwrite the existing
                database)</label>
        </div>
        <div class="row mt-4">
            <div class="alert alert-warning" role="alert">
                <ul>
                    <li>A copy of the current directory should be downloaded before importing records. <a
                            href="{{ route('download') }}" class="fw-bold" target="_blank">Click here</a> to download a
                        copy
                        of the
                        current database.</li>
                    <li>Ensure that the imported file is in the correct format. The file must be a CSV and the
                        headers <span class="fw-bold text-decoration-underline">must</span>
                        be in the following order: <span class="fw-bold">Floor, Department, Group Name, Employee,
                            Extension Name, Extension Number.</span></li>
                </ul>

            </div>
        </div>
    </div>

    <!-- Step 2 -->
    <div x-show="currentStep === 2" class="step-wrapper" :class="{ 'active': currentStep === 2 }" x-transition>
        <h2>Upload File</h2>

        <form wire:submit.prevent="import">
            <div class="d-flex">
                <!-- File Input and Button -->
                <div class="d-flex align-items-center gap-3">
                    <label for="file" class="form-label mb-0">Choose file to import:</label>
                    <input type="file" class="form-control" wire:model="file" id="file" required
                        style="max-width: 300px;">
                    <button @disabled(!$this->file) wire:loading.attr="disabled" wire:target="file"
                        class="btn btn-primary">Import
                        Records</button>
                </div>

                <!-- Error Message -->
                @error('file')
                    <span class="error" style="color: red;">{{ $message }}</span>
                @enderror

                <!-- Loading Spinner -->
                <div class="mx-2 d-none" wire:loading.class.remove="d-none" wire:target="file">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span>Uploading...</span>
                </div>
            </div>
        </form>

        <div class="row">

            <div class="d-none" wire:loading.class.remove="d-none" wire:target="import">
                <div class="d-flex justify-content-center align-items-center mt-5">
                    <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                    <span class="ms-2">Processing...</span>
                </div>
            </div>


            <div class="row mt-5" x-show="isImported" x-transition>
                @if ($count)
                    <p> {{ $this->count }} records loaded successfully</p>
                @endif

                @if ($this->fileDuplicates)

                    @if (count($this->duplicateExtensionsInFile) > 0)
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Duplicate Extensions in Imported File <i
                                    class="fa-solid fa-triangle-exclamation"></i>
                            </h4>
                            <p>The following extensions occur more than once in the imported file.</p>
                            <ul>
                                @foreach ($this->duplicateExtensionsInFile as $extension)
                                    <li>{{ $extension }}</li>
                                @endforeach
                            </ul>
                        </div>

                    @endif


                    @if (count($this->duplicateEmployeesInFile) > 0)
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Reoccurring Employee Names in Imported File <i
                                    class="fa-solid fa-circle-exclamation"></i>
                            </h4>
                            <p>The following employee names occur more than once in the file. Please ensure that these
                                are not dublicates before proceeding</p>
                            <ul>
                                @foreach ($this->duplicateEmployeesInFile as $employee)
                                    <li>{{ $employee }}</li>
                                @endforeach
                            </ul>
                        </div>

                    @endif
                @endif

                @if (count($this->duplicateExtensions) > 0)
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Duplicate Extensions <i class="fa-solid fa-triangle-exclamation"></i>
                        </h4>
                        <p>The following extensions already exist in the database. Please remove them from the file
                            and
                            try again.</p>
                        <ul>
                            @foreach ($this->duplicateExtensions as $extension)
                                <li>{{ $extension }}</li>
                            @endforeach
                        </ul>
                    </div>

                @endif


                @if (count($this->duplicateEmployees) > 0)
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Reoccurring Employee Names <i
                                class="fa-solid fa-circle-exclamation"></i>
                        </h4>
                        <p>The following employees already exist in the database. Please ensure that this is not a
                            duplicate employee.</p>
                        <ul>
                            @foreach ($this->duplicateEmployees as $employee)
                                <li>{{ $employee }}</li>
                            @endforeach
                        </ul>
                    </div>

                @endif
            </div>

        </div>
    </div>

    <!-- Step 3 -->
    <div x-show="currentStep === 3" class="step-wrapper" :class="{ 'active': currentStep === 3 }" x-transition>
        <h1>Summary</h1>
        <ul>
            <li> Import Option: <span class="fw-bold" x-show="importOption === 'bulk-overwrite'">Bulk Overwrite</span>
                <span class="fw-bold" x-show="importOption === 'import-records'">Import New Records</span>
            </li>
            <li> Records to be imported: <span class="fw-bold">{{ $this->count }}</span></li>
        </ul>
        <p class="text-danger fw-bold h4" x-show="importOption === 'bulk-overwrite'">All existing records will be
            overwritten. This action cannot be undone.</p>
    </div>

    <!-- Step Controls -->
    <div class="step-controls">
        <div x-show="currentStep === 1">
            <button type="button" class="btn btn-primary" @click="nextStep">Next</button>
        </div>

        <div x-show="currentStep === 2">
            <button type="button" class="btn btn-secondary" @click="prevStep">Back</button>
            <button @disabled($this->nextStepDisabled) type="button" class="btn btn-primary"
                @click="nextStep">Next</button>
        </div>

        <div x-show="currentStep === 3" wire:loading.class="d-none" wire.target="confirm">
            <button type="button" class="btn btn-secondary" @click="prevStep">Back</button>
            <button type="button" class="btn btn-success"
                wire:confirm="Are you sure you want to import these records?\n\nThis action cannot be undone"
                wire:click="confirm">Finish</button>
        </div>

        <div class="d-none" wire:loading.class.remove="d-none" wire:target="confirm">
            <div class="mt-5">
                <span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
                <span class="ms-2">Processing...</span>
            </div>
        </div>
    </div>
</div>



@script
    <script>
        $(document).ready(function() {
            $('#importlink').css('color', 'white');


            window.addEventListener('show-error', event => {

                toastr.options = {
                    "progressBar": true,
                    "closeButton": true,
                }
                toastr.error(event.detail.message, '', {
                    timeOut: 3000
                });
            })
        });
    </script>
@endscript
