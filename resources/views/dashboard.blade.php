<x-app-layout>
    @push('vite')
        @vite(['resources/js/dashboard.js'])
    @endpush
    @section('title', 'Dashboard')
    @section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold mb-4">Your Forms</h3>

                        <!-- Card layout for forms -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($forms as $form)
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-4">
                                        <!-- Form Title -->
                                        <h4 class="text-xl font-semibold mb-2">{{ $form->title }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Created at: {{ $form->created_at->format('M d, Y') }}</p>
                                                
                                        <!-- Form Description -->
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">{{ $form->description }}</p>

                                        <!-- Form Name -->
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-4"><strong>Form Name:</strong> {{ $form->name }}</p>

                                        <!-- Number of Fields -->
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-4"><strong>Number of Fields:</strong> {{ $form->form_fields_count }}</p>

                                        <!-- Form Status -->
                                        <span class="inline-block px-3 py-1 rounded-full text-sm 
                                            {{ $form->status == 'suspended' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800' }} mb-4">
                                            {{ ucfirst($form->status) }}
                                        </span>

                                        <!-- Form Responses -->
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">Responses: {{ $form->form_responses_count }}</p>

                                        <!-- Action buttons -->
                                        <div class="flex space-x-5">
                                            <!-- Delete Icon with Tooltip -->
                                            <div class="tooltip-wrapper flex w-full h-full">
                                                <form action="{{ route('forms.destroy', [$form->name, $form->id]) }}" method="POST" class="inline relative w-full h-full">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 w-full h-full flex items-center justify-center">
                                                        <i class="fa fa-trash"></i> <!-- White icon -->
                                                        <span class="tooltip">Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <!-- View Icon with Tooltip -->
                                            <div class="tooltip-wrapper flex w-full h-full">
                                                <a target="_blank" href="{{ url('forms/' . $form->name . '_' . $form->id) }}" class="relative bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 w-full h-full flex items-center justify-center">
                                                    <i class="fa fa-eye"></i> <!-- White icon -->
                                                    <span class="tooltip">View</span>
                                                </a>
                                            </div>
                                                

                                            <!-- Copy Link Icon with Tooltip -->
                                            <div class="tooltip-wrapper flex w-full h-full">
                                                <button type="button" class="copy-link-btn bg-gray-600 text-white p-2 rounded-full hover:bg-gray-700 w-full h-full flex items-center justify-center" data-link="{{ url('forms/' . $form->name . '_' . $form->id) }}">
                                                    <i class="fa fa-copy"></i> <!-- White icon -->
                                                    <span class="tooltip tooltipCopy">Copy Link</span>
                                                </button>
                                            </div>

                                            <!-- Toggle Status Button -->
                                            <div class="tooltip-wrapper flex w-full h-full">
                                                <form action="{{ route('forms.toggleStatus', $form->id) }}" method="POST" class="inline relative w-full h-full">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if ($form->status == 'active')
                                                        <button type="submit" class="bg-yellow-600 text-white p-2 rounded-full hover:bg-yellow-700 w-full h-full flex items-center justify-center">
                                                            <i class="fa fa-ban"></i> <!-- Icon for suspend -->
                                                            <span class="tooltip">Suspend</span>
                                                        </button>
                                                        @else
                                                        <button type="submit" class="bg-green-600 text-white p-2 rounded-full hover:bg-yellow-700 w-full h-full flex items-center justify-center">
                                                            <i class="fa fa-check"></i> <!-- Icon for activate -->
                                                            <span class="tooltip">Activate</span>
                                                        </button>
                                                        @endif
                                                </form>
                                            </div>
                                            <!-- View Answers Icon with Tooltip -->
                                            <div class="tooltip-wrapper flex w-full h-full">
                                                <a href="{{ route('forms.answers', $form->id) }}" class="relative bg-orange-600 text-white p-2 rounded-full hover:bg-blue-700 w-full h-full flex items-center justify-center">
                                                    <i class="fa fa-file"></i>
                                                    <span class="tooltip z-100">View Answers</span>
                                                </a>
                                            </div>

                                            
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>
