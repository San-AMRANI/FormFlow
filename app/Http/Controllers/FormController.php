<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Form_m;
use App\Models\FormField;
use App\Models\FormResponse;
use App\Models\FormResponseAnswer;
use App\Models\User;

class FormController extends Controller
{
    // In your FormController or any controller that is responsible for the dashboard

    public function index()
    {
        // Fetch all forms created by the logged-in user
        $forms = Form_m::withCount('formResponses')->withCount('formFields')
                        ->where('created_by', Auth::id())  // Only get forms created by the logged-in user
                        ->get();

        return view('dashboard', compact('forms'));
    }

    public function create()
    {
        return view('forms.create');  // Return view for form creation
    }

    // Store the new form and its fields
    public function store(Request $request)
    {
        // dd($request);
        try {
            // Validate the incoming request
            $request->validate([
                'title' => 'required|string|max:255',
                'name' => 'required|string|max:255|unique:forms,name', // Ensure 'name' is unique
                'description' => 'nullable|string',
                'fields' => 'required|array', // Assuming fields come as an array
                'fields.*.label' => 'required|string|max:255',  // Validate each field's label
                'fields.*.field_type' => 'required|string',    // Validate each field type
            ]);

            // Create the form and associate it with the authenticated user
            $form = Form_m::create([
                'title' => $request->title,
                'name' => $request->name, // Store the name
                'description' => $request->description,
                'status' => 'active', // Default status is 'active'
                'created_by' => Auth::id(),
            ]);

            // Store each field associated with the form
            foreach ($request->fields as $field) {
                // Convert the 'required' checkbox value to 1 or 0
                $required = isset($field['required']) && $field['required'] === '1' ? 1 : 0;

                // Handle options (for select, checkbox, radio types) by sanitizing the input
                $options = isset($field['options']) ? json_encode(array_map('trim', explode(',', $field['options']))) : null;

                // Store the field data
                FormField::create([
                    'form_id' => $form->id,
                    'label' => $field['label'],
                    'field_type' => $field['field_type'],
                    'options' => $options,  // Store options if provided
                    'required' => $required,  // Convert checkbox to 1 or 0
                ]);
            }

            // Redirect to the form page after saving
            return redirect()->route('forms.show', [$form->name, $form->id])
                            ->with('success', 'Form created successfully!');

        } catch (\Exception $e) {
            // Log the exception message (optional)
            \Log::error('Error creating form: ' . $e->getMessage());

            // Redirect back with error message and previous input
            return back()->with('error', 'There was an error creating the form. Please try again.')
                        ->withInput(); // Keep the user inputs
        }
    }


    // Show the form with its fields
    public function show($name, $id)
    {
        // Retrieve the form using both name and id
        $form = Form_m::where('name', $name)->where('id', $id)->with('formFields')->firstOrFail();

        // Return the form details view
        return view('forms.show', compact('form'));
    }

    public function toggleStatus(Form_m $form)
    {
        // Toggle the status between active and suspended
        $form->status = $form->status == 'active' ? 'suspended' : 'active';
        $form->save();

        return redirect()->back()->with('status', 'Form status updated successfully!');
    }


    // Show the form edit page
    // public function edit($name, $id)
    // {
    //     // Retrieve the form using both name and id
    //     $form = Form_m::where('name', $name)->where('id', $id)->with('formFields')->firstOrFail();

    //     // Return the form edit view
    //     return view('forms.edit', compact('form'));
    // }

    // Update the form and its fields
    // public function update(Request $request, $name, $id)
    // {
    //     // Validate the incoming request
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'name' => 'required|string|max:255|unique:forms,name,' . $id, // Ensure 'name' is unique, excluding the current form
    //         'description' => 'nullable|string',
    //         'fields' => 'required|array',
    //         'fields.*.label' => 'required|string|max:255',
    //         'fields.*.field_type' => 'required|string',
    //     ]);

    //     // Retrieve the form to update
    //     $form = Form_m::where('name', $name)->where('id', $id)->firstOrFail();

    //     // Update the form details
    //     $form->update([
    //         'title' => $request->title,
    //         'name' => $request->name, // Update the name
    //         'description' => $request->description,
    //     ]);

    //     // Update the form fields (remove existing and add new ones)
    //     foreach ($form->formFields as $existingField) {
    //         $existingField->delete();
    //     }

    //     foreach ($request->fields as $field) {
    //         FormField::create([
    //             'form_id' => $form->id,
    //             'label' => $field['label'],
    //             'field_type' => $field['field_type'],
    //             'options' => isset($field['options']) ? json_encode($field['options']) : null,
    //             'required' => $field['required'] ?? false,
    //         ]);
    //     }

    //     // Redirect to the form page after updating
    //     return redirect()->route('forms.show', [$form->name, $form->id])->with('success', 'Form updated successfully!');
    // }

    // Delete the form and its related fields
    public function destroy($name, $id)
    {
        // Retrieve the form to delete using both name and id
        $form = Form_m::where('name', $name)->where('id', $id)->firstOrFail();

        // Delete related form fields
        foreach ($form->formFields as $field) {
            $field->delete();
        }

        // Delete the form itself
        $form->delete();

        // Redirect to the form listing page
        return redirect()->route('dashboard')->with('success', 'Form deleted successfully!');
    }

    public function showAnswers($formId)
    {
        // Retrieve the form and ensure it exists
        $form = Form_m::findOrFail($formId);
    
        // Retrieve the form responses along with their answers
        $responses = FormResponse::where('form_id', $formId)
            ->with(['formResponseAnswers.formField'])
            ->get();
    
        // Pass the form and responses to the view
        return view('forms.answers', compact('form', 'responses'));
    }
    

    public function submitForm(Request $request)
    {
        $formId = $request->input('form_id'); // Get the form ID from the request
        $fields = $request->input('fields'); // Get the form fields and their answers
    
        try {
            // Log incoming request data for debugging
            \Log::info('Form Data:', $request->all());
    
            // Fetch the form fields with their validation requirements from the database
            $formFields = FormField::where('form_id', $formId)->get();

                    // Check if the session token and IP address already exist for this form
            $existingResponse = FormResponse::where('form_id', $formId)
            ->where('session_token', session()->getId())
            ->where('ip_address', $request->ip())
            ->first();

            if ($existingResponse) {
                return response()->json([
                    'error' => 'Vous avez déjà soumis ce formulaire.',
                ], 400);
            }
            
            // Initialize an array to store the validation rules
            $validationRules = [];
    
            // Check if all answers are empty
            $allEmpty = collect($fields)->every(fn($field) => empty($field['answer']));
    
            if ($allEmpty) {
                // If all answers are empty, return an error
                return response()->json(['error' => 'Tous les champs sont vides. Veuillez fournir au moins une réponse.'], 400);
            }
    
            // Loop through each form field and define the validation rules dynamically
            foreach ($formFields as $field) {
                // Initialize the validation rules
                $rules = [];
    
                // Check if the field is required and ensure its answer is provided
                $fieldKey = collect($fields)->firstWhere('form_field_id', $field->id);
    
                if ($field->required == 1 && empty($fieldKey['answer'])) {
                    $validationRules["fields.{$field->id}.answer"] = 'required';
                }
    
                // Add specific validation rules based on the field type
                switch ($field->type) {
                    case 'text':
                        $rules[] = 'string|max:255'; // Text fields are strings with a max length
                        break;
    
                    case 'textarea':
                        $rules[] = 'string'; // Textarea fields are strings
                        break;
    
                    case 'select':
                        $rules[] = 'required|in:' . implode(',', $field->options); // Dropdown options are limited by the choices
                        break;
    
                    case 'radio':
                        $rules[] = 'required|in:' . implode(',', $field->options); // Radio buttons are selected from options
                        break;
    
                    case 'checkbox':
                        $rules[] = 'nullable|array'; // Checkboxes can be multiple values (array) or empty
                        break;
    
                    case 'dateinput':
                        $rules[] = 'required|date'; // Date input fields must be valid dates
                        break;
    
                    case 'email':
                        $rules[] = 'required|email|max:255'; // Email field must be a valid email address
                        break;
    
                    case 'phone':
                        $rules[] = 'nullable|regex:/^\+?[0-9]{1,4}?[-.\s\(\)]?(\d{1,3})[-.\s\(\)]?(\d{1,4})[-.\s\(\)]?(\d{1,4})$/'; // Phone field validation (example regex)
                        break;
    
                    case 'number':
                        $rules[] = 'required|numeric'; // Numeric field must be a valid number
                        break;
    
                    default:
                        $rules[] = 'nullable|string'; // Default rule for unspecified types (e.g., empty text fields)
                }
    
                // Apply additional validation rules to each field's answer (only if the answer is provided)
                if (!empty($fieldKey['answer'])) {
                    $validationRules["fields.{$field->id}.answer"] = implode('|', $rules);
                }
            }
    
            // Log validation rules for debugging
            \Log::info('Validation Rules:', $validationRules);
    
            // Validate the incoming request data using the dynamically generated rules
            $validated = $request->validate($validationRules);
    
            // Create a new FormResponse with system-generated values
            $formResponse = FormResponse::create([
                'form_id' => $formId,
                'email' => $request->input('email', null),
                'session_token' => session()->getId(),
                'ip_address' => $request->ip(),
            ]);
    
            // Save each field's answer
            foreach ($fields as $field) {
                // Only save the answer if it is not empty
                if (!empty($field['answer'])) {
                    FormResponseAnswer::create([
                        'form_response_id' => $formResponse->id,
                        'form_field_id' => $field['form_field_id'],
                        'answer' => $field['answer'],
                    ]);
                }
            }
    
            return response()->json(['message' => 'Formulaire soumis avec succès !'], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('La soumission du formulaire a échoué : ' . $e->getMessage());
            \Log::error('Trace de la pile : ' . $e->getTraceAsString());
    
            return response()->json(['error' => 'Échec de la soumission du formulaire.', 'details' => $e->getMessage()], 500);
        }
    }
    



}
