<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $pjs = User::select('id','nama_lengkap','email')
                            ->where('akses','pj')
                            ->orderBy('id','desc')
                            ->get();
        return view('operator/pj/index',compact('pjs'));
    }

    public function add(){
        return view('operator/pj.add');
    }

    public function post(Request $request){
        $this->validate($request,[
            'nama_lengkap'   =>  'required',
            'email'  =>  'required',
            'password'  =>  'required',
        ]);
        $pj = new User;
        $pj->nama_lengkap = $request->nama_lengkap;
        $pj->email = $request->email;
        $pj->password = $request->password;
        $pj->akses = 'pj';
        $pj->save();

        return redirect()->route('pj')->with(['success' => 'Data penanggung jawab sudah ditambahkan !']);

    }
    public function edit($id){
        $pj = User::where('id',$id)->first();
        return view('operator/pj/.edit',compact('pj'));
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'nama_lengkap'   =>  'required',
            'email'  =>  'required',
        ]);

        User::where('id',$id)->update([
            'nama_lengkap'   =>  $request->nama_lengkap,
            'email'  =>  $request->email,
        ]);

        return redirect()->route('pj')->with(['success' => 'Data penanggung jawab berhasil diubah !']);

    }
    public function delete($id){
        User::where('id',$id)->delete();
        return redirect()->route('pj')->with(['success' => 'Data penanggung jawab berhasil diubah !']);
    }
}
