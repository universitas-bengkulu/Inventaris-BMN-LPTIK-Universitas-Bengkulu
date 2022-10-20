<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\TransaksiKeluar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $transaksis = Peminjaman::orderBy('peminjamen.id','desc')
                            ->get();
        session()->forget('cart');
        return view('operator/peminjaman/index',compact('transaksis'));
    }

    public function add(){
        $barangs = Barang::all();
        $cart = session('cart');
        return view('operator/peminjaman.add',compact('barangs','cart'));
    }

    public function post(Request $request){
        $barang = Barang::where('id',$request->barang_id)->select('id','jumlah_barang')->first();
        $keluar = TransaksiKeluar::where('barang_id',$barang->id)->select(DB::raw('sum(jumlah_keluar) as jumlah_keluar'))->first();
        $peminjaman = Peminjaman::select(DB::raw('sum(jumlah_pinjam) as jumlah_pinjam'))
                                ->where('barang_id',$barang->id)
                                ->where('keterangan','sedang_dipinjam')
                                ->first();
        $tersedia = $barang->jumlah_barang - $keluar->jumlah_keluar - $peminjaman->jumlah_pinjam;
        if ($request->jumlah_pinjam > $tersedia) {
            return redirect()->back()->with(['error'    => 'Jumlah barang tidak mencukupi']);
        }
        $this->validate($request,[
            'barang_id'   =>  'required',
            'jumlah_pinjam'  =>  'required',
            'tanggal_pinjam' =>  'required',
            'tanggal_kembali'    =>  'required',
            'nama_peminjam'  =>  'required',
        ]);

        // DB::beginTransaction();
        // try {
            $barang = new Peminjaman;
            $barang->barang_id = $request->barang_id;
            $barang->jumlah_pinjam = $request->jumlah_pinjam;
            $barang->tanggal_pinjam = $request->tanggal_pinjam;
            $barang->tanggal_kembali = $request->tanggal_kembali;
            $barang->nama_peminjam = $request->nama_peminjam;
            $barang->keterangan = 'sedang_dipinjam';
            $barang->save();

            // DB::commit();
            return redirect()->route('barang.peminjaman')->with(['success' => 'Data transaksi peminjaman sudah ditambahkan !']);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect()->route('barang.peminjaman.add')->with(['error' => 'Data transaksi peminjaman gagal ditambahkan !']);
        // }


    }

    public function update(Request $request, $id){
        $this->validate($request,[
            'keterangan'  =>  'required',
        ]);

        Peminjaman::where('id',$id)->update([
            'keterangan'   =>  $request->keterangan,
        ]);

        return redirect()->route('barang.peminjaman')->with(['success' => 'Status Transaksi Peminjaman berhasil diubah !']);

    }
    public function delete($id){
        TransaksiKeluar::where('id',$id)->delete();
        return redirect()->route('barang.peminjaman')->with(['success' => 'Data Transaksi Peminjaman berhasil dihapus !']);
    }

    public function cariBarang(Request $request){
        $barang = Barang::where('kode_barang','like','%"'.$request->kode_barang.'"%')->first();
        return $barang;
    }

    public function tambahCart($id){
        $cart = session('cart');
        $barang = Barang::where('id',$id)->first();
        $cart[$id] = [
            'barang_id'     => $barang->id,
            'kode_barang'   => $barang->kode_barang,
            'nama_barang'   => $barang->nama_barang,
            'merk'   => $barang->merk,
            'jumlah' => 1,
        ];

        session(['cart' =>  $cart]);
        return redirect('/transaksi_peminjaman/add');
    }

    public function hapusCart($id){
        $cart = session('cart');
        unset($cart[$id]);
        session(['cart' => $cart]);
        return redirect('/transaksi_peminjaman/add');
    }

    public function pinjam(Request $request){
        $cart = session('cart');
        if (empty($cart)) {
            return redirect()->back()->with(['error'    => 'silahkan tambahkan barang ke keranjang']);
        }
        $this->validate($request,[
            'tanggal_pinjam' =>  'required',
            'tanggal_kembali'    =>  'required',
            'nama_peminjam'  =>  'required',
        ]);
        $peminjaman = Peminjaman::create([
            'tanggal_pinjam'    =>  $request->tanggal_pinjam,
            'tanggal_kembali'    =>  $request->tanggal_kembali,
            'nama_peminjam'    =>  $request->nama_peminjam,
        ]);

        foreach ($cart as $key => $a) {
            PeminjamanDetail::create([
                'peminjaman_id' =>$peminjaman->id,
                'barang_id' =>  $a['barang_id'],
                'jumlah' =>  $a['jumlah'],
            ]);
        }
        return redirect()->route('barang.peminjaman')->with(['success' => 'Transaksi peminjaman berhasil !']);
    }

    public function detail($id){
        $data = Peminjaman::where('id',$id)->first();
        return view('operator/peminjaman.detail',compact('data'));
    }
}
