<x-app-layout>
    @section('title', 'Form Answers - ' . $form->title)

    @section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold mb-4">{{ $form->title }} - Answers</h3>

                        <!-- Display Responses -->
                        @if ($responses->isEmpty())
                            <p>No responses available for this form yet.</p>
                        @else
                            <div class="space-y-6">
                                @foreach ($responses as $response)
                                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                                        <h4 class="text-lg font-semibold">Response from: {{ $response->email }}</h4>
                                        <ul class="list-disc pl-5">
                                            @foreach ($response->formResponseAnswers as $answer)
                                                <li>
                                                    <strong>{{ $answer->formField->label }}:</strong> {{ $answer->answer }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>
