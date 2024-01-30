<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $req)
    {
        // $file = $req->file('driver_img');
        $req->validate([
            'lname'=>['required'],
            'fname'=>['required'],
            'sex'=>['required'],
            'contact'=>['required'],
            'license_id'=>['required'],
            'province'=>['required'],
            'barangay'=>['required'],
            'city'=>['required'],
            'street'=>['required'],
            'username'=>['required','unique:users'],
            'password'=>['required'],
            // 'customer_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            
        ]);

        // if ($file) {
        //     $filePath = $file->store('public/drivers'); //get path of the file
        //     $filePathArray = explode('/', $filePath); //split into array using /
        //     $file_location = $filePathArray[2];
        // }
        User::create([
            'username' => $req->username,
            'password' => $req->password,
            'role' => 'DRIVER'
        ]);

        $customerId = User::latest()->value('user_id');

        Driver::create([
            'user_id' => $customerId,
            'lname' => strtoupper($req->lname),
            'fname' => strtoupper($req->fname),
            'mname' => $req->mname,
            'suffix' => $req->suffix,
            'sex' => $req->sex,
            'contact_no' => $req->contact,
            'license_id' => $req->license_id,
            'province' => $req->province,
            'city' => $req->city,
            'barangay' => $req->barangay,
            'street' => strtoupper($req->street),
        ]);

        return response()->json([
            'status' => 'saved'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return DB::select('
        SELECT d.`user_id`,
        d.`lname`,
        d.`fname`,
        d.`mname`,
        d.`suffix`,
        d.`sex`,
        d.`contact_no`,
        d.`license_id`,
        d.`street`,
        d.`is_available`,
        p.`provDesc`,
        b.`brgyDesc`,
        cit.`citymunDesc`

        FROM drivers d
        INNER JOIN provinces p ON d.province = p.provCode
        LEFT JOIN barangays b ON d.barangay = b.brgyCode
        LEFT JOIN cities cit ON d.city = cit.citymunCode
       ;');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return $data = User::where('user_id',$id)->with('driver')->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
        $req->validate([
            'lname'=>['required'],
            'fname'=>['required'],
            'sex'=>['required'],
            'contact'=>['required'],
            'license_id'=>['required','unique:drivers,license_id,'.$req->user_id.',user_id'],
            'province'=>['required'],
            'barangay'=>['required'],
            'city'=>['required'],
            'street'=>['required'],
            'username'=>['required','unique:users,username,'.$req->user_id.',user_id'],
            'user_id' =>['required']   
        ]);

        $user = User::where('user_id',$req->user_id)
        ->update([
            'username' =>  $req->username,
        ]);


        $user = Driver::where('user_id',$req->user_id)
        ->update([
            'fname' =>  strtoupper($req->fname),
            'lname' =>  strtoupper($req->lname),
            'mname' =>  strtoupper($req->mname),
            'sex' =>  strtoupper($req->sex),
            'suffix' =>  strtoupper($req->suffix),
            'contact_no' =>  $req->contact,
            'license_id' =>  $req->license_id,
            'province' =>  $req->province,
            'city' =>  $req->city,
            'barangay' =>  $req->barangay,
            'street' =>  strtoupper($req->street),
        ]);
        return response()->json([
            'status' => 'saved'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
