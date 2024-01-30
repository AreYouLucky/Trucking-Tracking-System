<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;

class CustomerController extends Controller
{


    public function store(Request $req)
    {
        $file = $req->file('customer_img');
        $req->validate([
            'lname'=>['required'],
            'fname'=>['required'],
            'sex'=>['required'],
            'contact'=>['required'],
            'email'=>['required'],
            'province'=>['required'],
            'barangay'=>['required'],
            'city'=>['required'],
            'street'=>['required'],
            'username'=>['required','unique:users'],
            'password'=>['required'],
            // 'customer_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            
        ]);

        // if ($file) {
        //     $filePath = $file->store('public/images'); //get path of the file
        //     $filePathArray = explode('/', $filePath); //split into array using /
        //     $file_location = $filePathArray[2];
        // }
        User::create([
            'username' => $req->username,
            'password' => $req->password,
            'role' => 'CUSTOMER'
        ]);

        $customerId = User::latest()->value('user_id');

        Customer::create([
            'user_id' => $customerId,
            'lname' => strtoupper($req->lname),
            'fname' => strtoupper($req->fname),
            'mname' => $req->mname,
            'suffix' => $req->suffix,
            'sex' => $req->sex,
            'contact_no' => $req->contact,
            'email' => $req->email,
            'province' => $req->province,
            'city' => $req->city,
            'barangay' => $req->barangay,
            'street' => strtoupper($req->street),
            // 'pic_path' => $file_location
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
        SELECT c.`user_id`,
        c.`lname`,
        c.`fname`,
        c.`mname`,
        c.`suffix`,
        c.`sex`,
        c.`contact_no`,
        c.`email`,
        c.`street`,
        p.`provDesc`,
        b.`brgyDesc`,
        cit.`citymunDesc`

        FROM customers c
        INNER JOIN provinces p ON c.province = p.provCode
        LEFT JOIN barangays b ON c.barangay = b.brgyCode
        LEFT JOIN cities cit ON c.city = cit.citymunCode
       ;');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return $data = User::where('user_id',$id)->with('customer')->get();
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
            'email'=>['required'],
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

        $user = Customer::where('user_id',$req->user_id)
        ->update([
            'fname' =>  strtoupper($req->fname),
            'lname' =>  strtoupper($req->lname),
            'mname' =>  strtoupper($req->mname),
            'sex' =>  strtoupper($req->sex),
            'suffix' =>  strtoupper($req->suffix),
            'contact_no' =>  $req->contact,
            'email' =>  $req->email,
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
        // $data = Customer::where('user_id',$id)->get();
        // if(Storage::exists('public/images/' .$data[0]->pic_path)) {
        //     Storage::delete('public/images/' . $data[0]->pic_path);
        // }

        User::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
