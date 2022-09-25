<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $ruangans = Ruangan::join('users','users.id','ruangans.penanggung_jawab_id')->select('ruangans.id','nama_lengkap','nama_ruangan')
                            ->orderBy('id','desc')
                            ->get();
        return view('operator/ruangan/index',compact('ruangans'));
    }

    public function add(){
        $pjs = User::where('akses','pj')->get();
        return view('operator/ruangan.add',compact('pjs'));
    }

    public function post(Request $request){
        $this->validate($request,[
            'nama_ruangan'   =>  'required',
            'penanggung_jawab_id'  =>  'required',
        ]);
        $ruangan = new Ruangan;
        $ruangan->nama_ruangan = $request->nama_ruangan;
        $ruangan->penanggung_jawab_id = $request->penanggung_jawab_id;
        $ruangan->save();

        return redirect()->route('ruangan')->with(['success' => 'Data ruangan sudah ditambahkan !']);

    }
    public function edit($id){
        $ruangan = Ruangan::where('id', $id)->first();
        $pjs = User::where('akses','pj')->get();
        return view('operator/ruangan/.edit',compact('ruangan','pjs'));
    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'nama_ruangan'   =>  'required',
            'penanggung_jawab_id'  =>  'required',
        ]);

        Ruangan::where('id',$id)->update([
            'nama_ruangan'   =>  $request->nama_ruangan,
            'penanggung_jawab_id'  =>  $request->penanggung_jawab_id,
        ]);

        return redirect()->route('ruangan')->with(['success' => 'Data ruangan berhasil diubah !']);

    }
    public function delete($id){
        Ruangan::where('id',$id)->delete();
        return redirect()->route('ruangan')->with(['success' => 'Data ruangan berhasil dihapus !']);
    }
}
