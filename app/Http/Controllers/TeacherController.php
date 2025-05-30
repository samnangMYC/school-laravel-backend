<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;



class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all teachers with their department data
        $teachers = Teacher::with('department')->paginate(10);

        // Format each teacher's data including department name
        $formatted = $teachers->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'fname' => $teacher->fname,
                'lname' => $teacher->lname,
                'department_name' => $teacher->department->name ?? null,
                'phone' => $teacher->phone,
                'dob' => $teacher->dob,
                'gender' => $teacher->gender,
                'email' => $teacher->email,
                'join_date' => $teacher->join_date,
                'education' => $teacher->education,
                'description' => $teacher->description,
                'status' => $teacher->status,
                'image' => $teacher->image,
            ];
        });

        return response()->json($formatted,200);

    }
    /**
     * Display a listing of the resource by specific id.
     */
    public function show($id)
    {
        // Find teacher by ID
        $teacher = Teacher::find($id);

        // Check if teacher exists
        if ($teacher) {
            return response()->json($teacher);
        }

        // If teacher not found, return an error response
        return response()->json(['message' => 'Teacher not found'], 404);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        Log::info($request->all());

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'fname'         => 'required|string|max:255',
            'lname'         => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'dob'           => 'required|date',
            'gender'        => 'required|in:male,female,other',
            'email'         => 'required|email|unique:teachers,email',
            'join_date'     => 'required|date',
            'education'     => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')->store('uploads/images/', 'public');

//            Log::info('Uploaded image filename: ' . basename($imagePath));

        }

        // Create the teacher record
        $teacher = Teacher::create([
            'fname'         => $request->fname,
            'lname'         => $request->lname,
            'phone'         => $request->phone,
            'dob'           => $request->dob,
            'gender'        => $request->gender,
            'email'         => $request->email,
            'join_date'     => $request->join_date,
            'education'     => $request->education,
            'description'   => $request->description,
            'image'         => $imagePath,
            'department_id' => $request->department_id,
        ]);


        return response()->json([
            'message' => 'Teacher created successfully.',
            'teacher' => $teacher,
        ], 201);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
//        Log::info("Updating teacher ID: {$id}");
//        Log::info(json_encode($request->all()));

        // Basic optional validation
        $validated = $request->validate([
            'email' => 'nullable|email',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Find the teacher
        $teacher = Teacher::findOrFail($id);

        // Merge remaining request data
        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/images/', 'public');
            $data['image'] = $imagePath;
        }

        // Update teacher with merged data
        $teacher->fill($data);
        $teacher->save();

        return response()->json([
            'message' => 'Teacher updated successfully',
            'teacher' => $teacher
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found.'], 404);
        }

        // Delete the image if it exists
        if ($teacher->image && Storage::disk('public')->exists($teacher->image)) {
            Storage::disk('public')->delete($teacher->image);
        }

        $teacher->delete();

        return response()->json([
            'message' => 'Teacher deleted successfully.',
            'teacherId' => $id,
        ]);
    }
}
