<x-app-layout>
    @section('title', 'Create New Form')
    @push('vite')
        @vite(['resources/js/newForm.js'])
    @endpush
    @section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold mb-4">Create New Form</h3>

                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="mb-4">
                                <ul class="list-disc pl-5 text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form to create new form -->
                        <form method="POST" action="{{ route('forms.store') }}">
                            @csrf

                            <!-- Form Title -->
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Form Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                            </div>

                            <!-- Form Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Form Name (Unique)</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                            </div>

                            <!-- Form Description -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                            </div>

                            <!-- Dynamic Fields -->
                            <div class="mb-4">
                                <h4 class="text-lg font-semibold">Form Fields</h4>
                                <div id="fields-container">
                                    <!-- Field Template (Initial field) -->
                                    <div class="field-entry field-entry-fix mb-4 p-4 border rounded-lg">
                                        <div class="flex space-x-4">
                                            <div class="flex-1">
                                                <label for="field_label" class="block text-sm font-medium text-gray-700">Field Label</label>
                                                <input type="text" name="fields[0][label]" value="{{ old('fields.0.label', 'email') }}" 
                                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                                    required>
                                            </div>
                                            <div class="flex-1">
                                                <label for="field_type" class="block text-sm font-medium text-gray-700">Field Type</label>
                                                <select name="fields[0][field_type]" 
                                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                    <option value="text" {{ old('fields.0.field_type') == 'text' ? 'selected' : '' }}>Text</option>
                                                    <option value="textarea" {{ old('fields.0.field_type') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                                    <option value="select" {{ old('fields.0.field_type') == 'select' ? 'selected' : '' }}>Select</option>
                                                    <option value="checkbox" {{ old('fields.0.field_type') == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                                    <option value="radio" {{ old('fields.0.field_type') == 'radio' ? 'selected' : '' }}>Radio</option>
                                                    <option selected value="email" {{ old('fields.0.field_type') == 'email' ? 'selected' : '' }}>Email</option> <!-- Added email option -->
                                                    <option value="phone" {{ old('fields.0.field_type') == 'phone' ? 'selected' : '' }}>Phone</option> <!-- Added phone option -->
                                                    <option value="number" {{ old('fields.0.field_type') == 'number' ? 'selected' : '' }}>Number</option> <!-- Added number option -->
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <label for="options" class="block text-sm font-medium text-gray-700">Options (comma separated)</label>
                                            <input type="text" name="fields[0][options]" value="{{ old('fields.0.options') }}" 
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <small class="text-gray-500">Only required for select, checkbox, or radio fields.</small>
                                        </div>                                        

                                        <div class="mt-4">
                                            <label for="required" class="inline-flex items-center">
                                                <input type="checkbox" checked name="fields[0][required]" value="1" {{ old('fields.0.required') == '1' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm">Required Field</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-field" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-700">
                                    Add Field
                                </button>
                                
                                <button type="button" id="remove-field" class="mt-4 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-700">
                                    Remove Last Field
                                </button>                                

                            </div>

                            <!-- Submit Button -->
                            <div class="mb-4">
                                <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    Create Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>
