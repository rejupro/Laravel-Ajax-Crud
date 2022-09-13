<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class MainController extends Controller
{
    public function getstudent(){
        $data = Student::orderBy('id', 'DESC')->get();
        return response()->json(array(
            'data' => $data,
        ));

    }

    public function storestudent(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'hometown' => 'required',
        ]);
        Student::Insert([
            'name'       => $request->name,
            'email'      => $request->email,
            'hometown'   => $request->hometown
        ]);
        return response()->json(['success' => 'Successfully Added on Database']);
    }

    public function editstudent($id){
        $data = Student::findOrFail($id);
        return response()->json($data);
    }

    public function updatedata(Request $request, $id){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'hometown' => 'required',
        ]);
        // Student::findOrFail($id)->update([
        //     'name'       => $request->name,
        //     'email'      => $request->email,
        //     'hometown'   => $request->hometown
        // ]);
        $data = Student::findOrFail($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->hometown = $request->hometown;
        $data->save();
        return response()->json(['success' => 'Successfully Updated on Database']);
    }

    public function deletestudent($id){
        Student::findOrFail($id)->delete();
        return response()->json(['success' => 'Successfully Deleted successfully']);
    }

}
