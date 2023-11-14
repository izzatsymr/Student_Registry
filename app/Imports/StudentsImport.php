<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $action = strtolower($row['action'] ?? 'create');

        switch ($action) {
            case 'create':
                return new Student([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'address' => $row['address'],
                    'study_course' => $row['study_course'],
                ]);

            case 'update':
                $existingStudent = Student::find($row['id']);

                if ($existingStudent) {
                    $existingStudent->update([
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'address' => $row['address'],
                        'study_course' => $row['study_course'],
                    ]);
                }

                return null; // Do not create a new model for updates

            case 'delete':
                $studentToDelete = Student::find($row['id']);

                if ($studentToDelete) {
                    $studentToDelete->delete();
                }

                return null; // Do not create a new model for deletions

            default:
                return null; // Invalid action, do nothing
        }
    }
}
