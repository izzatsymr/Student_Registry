<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::paginate(10);
        return StudentResource::collection($students);
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return new StudentResource($student);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $students = Student::where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->paginate(10);

        return StudentResource::collection($students);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:students',
            'address' => 'required|string',
            'study_course' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $student = Student::create($request->all());
        return response()->json([
            'message' => 'Student created successfully',
            'data' => new StudentResource($student),
        ]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:students,email,' . $id,
            'address' => 'required|string',
            'study_course' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $student->update($request->all());
        return response()->json([
            'message' => 'Student updated successfully',
            'data' => new StudentResource($student),
        ]);
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx',
        ]);

        $file = $request->file('file');
        Excel::import(new StudentsImport, $file);

        return response()->json(['message' => 'Import successful'], 200);
    }
}
