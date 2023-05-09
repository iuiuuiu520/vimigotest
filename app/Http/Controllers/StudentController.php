<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use App\Http\Resources\StudentsResource;
use App\Imports\StudentsImport;
use Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studentList = Student::paginate(10,['name','email']);

        return response()->json($studentList,200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|max:255',
            'address' => 'required',
            'studycourse' => 'required'
        ]);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'study course' => $request->studycourse
        ]);

        return response([
            'name' => $student->name,
            'email' => $student->email,
            'Message'=> 'Successfully Created!'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        
        return '123';
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $student->update([
            'name' => $request->name
        ]);

        return response([
            'name' => $student->name,
            'email' => $student->email,
            'Message'=> 'Successfully Updated!'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return response([
            'Message'=> 'Successfully Deleted!'
        ],200);
    }

    public function findbycond(Request $request)
    {

        $searchcond = $request->searchcond;
        
        $searchcond = trim($searchcond);

        $student = Student::where('name',$searchcond)->first(['name','email']);

        
        if (!is_null($student)){
            return new StudentsResource($student);
        }
        else{
            $student = Student::where('email',$searchcond)->first(['name','email']); 
            return new StudentsResource($student);
        }

        

        // if (count($student->get(['name','email'])) === 0){
        //     $data = [
        //         'message' => "The name or email that you entered is not existed in database!"
        //     ];

        //     return json_encode($data);
        // }
        // else{
        //     return $student->get(['name','email']);
        // }
    }

    public function uploadStudents(Request $request){
        
        Excel::import(new StudentsImport, $request->file);

        return response([
            'Message' => 'Students uploaded successfully!'
        ],200);
    }

    public function updateStudents(Request $request){
        
        $array = Excel::toArray(new StudentsImport, $request->file);

        foreach($array as $key){
            foreach($key as $row){
                $student = Student::where('email',$row[1]);
                
                if($student){
                    $student->update([
                        'name' => $row[0],
                        'address' => $row[2],
                        'study course' => $row[3]
                    ]);
                }
                else{
                    continue;
                }
            }
        }

        return response([
            'Message' => 'Updated students successfully!'
        ],200);
    }

    public function deleteStudents(Request $request){
        $array = Excel::toArray(new StudentsImport, $request->file);

        foreach($array as $key){
            foreach($key as $row){
                $student = Student::where('email',$row[1]);

                if($student){
                    $student->delete();
                }
                else{
                    continue;
                }
            }
        }
        
        return response([
            'Message' => 'Delete students successfully!'
        ],200);
    }

}
