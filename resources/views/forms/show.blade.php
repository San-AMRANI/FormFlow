<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }}</title>
    @vite(['resources/css/app.css', 'resources/css/fireForms.css', 'resources/js/formSubmit.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="min-h-screen flex items-center justify-center p-6">

    <div class="fire-card p-6 rounded-xl max-w-lg w-full">
        <!-- Title and Description -->
        <div class="form-header text-center mb-6">
            <h1 class="glow-text">🔥 Club Fire 🔥</h1>
        </div>

        <div class="form-description text-center mb-6">
            <h3>{{ $form->title }}</h3>
            <p>{{ $form->description }}</p>
        </div>

        <!-- Form Start -->
        @if ($form->status == "active")

            <form class="space-y-6" id="form" data-form-id="{{ $form->id }}">
                @foreach($form->formFields as $field)
                    <div class="form-field-group">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="field_{{ $field->id }}">
                            {{ $field->label }}
                            @if($field->required)
                                <span class="required-field">*</span>
                            @endif
                        </label>

                        @switch($field->field_type)
                            @case('text')
                                <input type="text" data-field-id="{{ $field->id }}" placeholder="Entrez {{ $field->label }}"
                                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 input-focus"
                                    @if($field->required) required @endif>
                                <p id="error-{{ $field->id }}" class="text-red-600 text-sm mt-1" style="display: none;"></p>
                                @break

                            @case('email')
                                <input type="email" data-field-id="{{ $field->id }}" placeholder="Entrez {{ $field->label }}"
                                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 input-focus"
                                    @if($field->required) required @endif>
                                <p id="error-{{ $field->id }}" class="text-red-600 text-sm mt-1" style="display: none;"></p>
                                @break

                            @case('textarea')
                                <textarea data-field-id="{{ $field->id }}" rows="4" placeholder="Entrez {{ $field->label }}"
                                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 input-focus"
                                    @if($field->required) required @endif></textarea>
                                <p id="error-{{ $field->id }}" class="text-red-600 text-sm mt-1" style="display: none;"></p>
                                @break

                            @case('select')
                                <select data-field-id="{{ $field->id }}"
                                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 input-focus"
                                    @if($field->required) required @endif>
                                    @foreach(json_decode($field->options) as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                                <p id="error-{{ $field->id }}" class="text-red-600 text-sm mt-1" style="display: none;"></p>
                                @break

                            @case('radio')
                                @foreach(json_decode($field->options) as $option)
                                    <div class="flex items-center">
                                        <input type="radio" name="{{ $field->label }}" value="{{ $option }}" class="mr-2"
                                            data-field-id="{{ $field->id }}" @if($field->required) required @endif>
                                        <label class="text-gray-700">{{ $option }}</label>
                                    </div>
                                @endforeach
                                <p id="error-{{ $field->id }}" class="text-red-600 text-sm mt-1" style="display: none;"></p>
                                @break

                            @case('checkbox')
                                @foreach(json_decode($field->options) as $option)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="{{ $field->label }}[]" value="{{ $option }}" class="mr-2"
                                            data-field-id="{{ $field->id }}" @if($field->required) required @endif>
                                        <label class="text-gray-700">{{ $option }}</label>
                                    </div>
                                @endforeach
                                <p id="error-{{ $field->id }}" class="text-red-600 text-sm mt-1" style="display: none;"></p>
                                @break

                            @case('number')
                                <input type="number" data-field-id="{{ $field->id }}" placeholder="Entrez {{ $field->label }}"
                                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 input-focus"
                                    @if($field->required) required @endif>
                                <p id="error-{{ $field->id }}" class="text-red-600 text-sm mt-1" style="display: none;"></p>
                                @break

                            @case('phone')
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 text-gray-700 bg-gray-200 border border-gray-300 rounded-l-md">+212</span>
                                    <input type="tel" data-field-id="{{ $field->id }}" placeholder="612345678"
                                        class="w-full p-3 rounded-r-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 input-focus"
                                        @if($field->required) required @endif>
                                </div>
                                <p id="error-{{ $field->id }}" class="text-red-600 text-sm mt-1" style="display: none;"></p>
                                @break

                            @case('dateinput')
                                <input type="date" data-field-id="{{ $field->id }}"
                                    class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 input-focus"
                                    @if($field->required) required @endif>
                                <p id="error-{{ $field->id }}" class="text-red-600 text-sm mt-1" style="display: none;"></p>
                                @break
                        @endswitch
                    </div>
                @endforeach

                <!-- Submit Button -->
                <button type="submit"
                    class="submit-form w-full py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white text-lg font-semibold rounded-lg hover:from-red-600 hover:to-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-300 transition hover:shadow-glow">
                    Soumettre
                </button>

            </form>

        @else
            <div class="text-center">
                <p class="text-red-600 text-lg font-semibold">Ce formulaire est actuellement suspendu ou fermé et ne peut pas être soumis.</p>
                <p class="text-gray-700 mt-4 mb-2">Veuillez contacter l'administrateur pour plus de détails.</p>
                <a href="https://cv.amranihassan.site/contact" target="_blank"
                    class="px-3 py-2 bg-gradient-to-r from-orange-500 to-red-600 text-white text-lg font-semibold rounded-lg hover:from-red-600 hover:to-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-300 transition">Contactez-nous</a>
            </div>
        @endif

        <!-- Spinner and Response Message -->
        <div class="spinner-container">
            <div id="spinner" class="text-center" style="display: none;">
                <div class="cube">
                    <div class="face front"></div>
                    <div class="face back"></div>
                    <div class="face left"></div>
                    <div class="face right"></div>
                    <div class="face top"></div>
                    <div class="face bottom"></div>
                </div>
            </div>
        </div>
        <div id="responseMessage" class="mt-4 text-center" style="display: none;"></div>
    </div>
    <!-- Modal for Review -->
    <div id="reviewModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 fire-card p-8 rounded-xl max-w-lg w-full">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Révisez vos réponses</h2>
            </div>
            <div class="p-4">
                <ul id="reviewList" class="list-disc list-inside text-gray-700">
                    <!-- Answers will be dynamically populated here -->
                </ul>
            </div>
            <div class="flex justify-end p-4 border-t">
                <button id="editButton" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Edit</button>
                <button id="confirmButton" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Submit</button>
            </div>
        </div>
    </div>
    <footer class="absolute bottom-0 w-full text-white py-4 text-center">
        <div class="">
            <p>&copy; {{ date('Y') }} Fire Club. All rights reserved.</p>
            <p>Created by <a href="https://cv.amranihassan.site" target="_blank" class="text-white"><u><b>AMRANI Hassan</b></u></a></p>
        </div>
    </footer>
        
</body>

</html>
