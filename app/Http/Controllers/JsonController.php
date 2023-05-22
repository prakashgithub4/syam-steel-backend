<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\FileUploader;
use Validator;
use Illuminate\Support\Str;


class JsonController extends Controller
{
    use FileUploader;
    private $file;
    function __construct()
    {
        $this->file = file_get_contents(public_path('crud.json'));
    }
    public function index()
    {
        $data = json_decode($this->file,true);
        return response()->json(["data" => $data]);
        //return view('list',compact('data'));
    }
    public function add()
    {
        return view('add');
    }
    public function save(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'gender' => ['required'],
            'file' => ['mimes:jpeg,jpg,png,gif']
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
                'data' => [],

            ], 400);
        }
        if($request->has('file')){

            $data = ['id' => Str::uuid(), 'name' => $request->name, 'gender' => $request->gender, 'address' => $request->address, 'avatar' =>$this->uploader($request->file) ];
        }else{
            $data = ['id' => Str::uuid(), 'name' => $request->name, 'gender' => $request->gender, 'address' => $request->address, 'avatar' =>null];
        }

        $file_data = json_decode($this->file, true);
        $file_data['records'] = array_values($file_data['records']);

        array_push($file_data['records'], $data);
        file_put_contents(public_path('crud.json'), json_encode($file_data));
        return response()->json(['status' => true, "data" => $data]);
        //    return redirect('/')->with('success','Item has been added successfully');

    }
    public function remove($index)
    {

        $file_data = json_decode($this->file, true);
        $all_records = $file_data['records'];
        $records = $all_records[$index];
        if ($records) {
            unset($file_data['records'][$index]);
            $file_data['records'] = array_values($file_data['records']);
            file_put_contents(public_path('crud.json'), json_encode($file_data));
            return response()->json(['status' => true, 'message' => 'Record has been deleted successfully']);
            // return redirect('/')->with('success', 'Item has been removed successfully');;
        }
    }
    public function details($index)
    {
        $file_data = json_decode($this->file, true);
        $file_data['records'] = $file_data['records'][$index];
      // echo"<pre>"; print_r($file_data); exit;
        $data = $file_data['records'];
        return response()->json(['status' => true, 'data' => $data]);
    }
    public function edit($id)
    {
        $file_data = json_decode($this->file, true);
        $file_data['records'] = $file_data['records'][$id];
        $data = json_decode($file_data['records']);
        return response()->json(['status' => true, 'data' => $data]);
    }
    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'gender' => ['required'],
            'file' => ['mimes:jpeg,jpg,png,gif']
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
                'data' => [],

            ], 400);
        }
        $file_data = json_decode($this->file, true);
        $file_data['records'] = array_values($file_data['records']);
        $jsonFile = json_decode(json_encode($file_data['records'][$id]),true);


        $data = [
            'id' => $jsonFile['id'],
            'name' => !isset($request->name) ? $jsonFile['name'] : $request->name,
            'gender' => !isset($request->gender) ? $jsonFile['gender'] : $request->gender,
            'avatar' => !isset($request->file) ? $jsonFile['avatar'] : $this->uploader($request->file),
            'address' => !isset($request->address) ? $jsonFile['address'] : $request->address
        ];
        if ($jsonFile) {
            unset($file_data['records'][$id]);
            $file_data['records'][$id] = $data;
            $file_data['records'] = array_values($file_data['records']);
            file_put_contents(public_path('crud.json'), json_encode($file_data));
        }
        return response()->json(['status' => true, 'message' => "data has been updated", 'data' => $data]);
    }
    public function search(Request $request)
    {
        $all = json_decode($this->file, true);

        $string = $request->query('search');
    }
}
