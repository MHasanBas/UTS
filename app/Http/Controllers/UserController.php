<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\DataTables;
use App\Models\LevelModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    // JS 5
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user';

        // Ambil data level
        $level = LevelModel::all();

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        // Filter data user berdasarkan level_id jika ada
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn  = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                        '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all();
        $activeMenu = 'user';

        return view('user.create', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer'
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user';

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $user = UserModel::findOrFail($id);
        $levels = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit User',
            'nama' => $user->nama,
        ];

        $activeMenu = 'user';

        return view('user.edit', compact('breadcrumb', 'page', 'user', 'levels', 'activeMenu'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer'
        ]);

        $user = UserModel::findOrFail($id);

        $user->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        if (!$check) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (QueryException $e) {
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    // Jobsheet 6
    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.create_ajax')->with('level', $level);
    }

    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa ajax atau ingin JSON
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
            ];

            // Gunakan Validator dari Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }
            // Simpan data user
            UserModel::create($request->all());

            // Jika berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan',
            ]);
        }
        // Redirect jika bukan request Ajax
        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }

    public function show_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        return view('user.show_ajax', ['user' => $user, 'level' => $level]);
    }

    public function update_ajax(Request $request, string $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                'nama' => 'required|max:100',
                'password' => 'nullable|min:6|max:20'
            ];

            // Gunakan Validator dari Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            $check = UserModel::find($id);
            if ($check) {
                if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request
                    $request->request->remove('password');
                }
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);
        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function profile()
    {
        // Membuat objek breadcrumb yang berisi judul dan daftar breadcrumb untuk halaman profil.
        $breadcrumb = (object) [
            'title' => 'Profil Anda', // Judul yang akan ditampilkan di breadcrumb.
            'list'  => ['Home', 'Profile'] // Rangkaian breadcrumb: Home -> Profile.
        ];
    
        // Menentukan menu yang aktif pada sidebar (menu 'profile').
        $activeMenu = 'profile';
    
        // Mengembalikan view 'profile.index' dengan mengirim variabel breadcrumb dan activeMenu.
        return view('profile.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
    
    public function showChangePhotoForm()
    {
        // Mengembalikan view yang berisi form untuk mengubah foto profil pengguna.
        return view('profile.change_photo');
    }
    
    public function showManageProfileForm()
    {
        // Mengembalikan view yang berisi form untuk mengelola profil pengguna (username, nama, password).
        return view('profile.manage');
    }
    
    public function updatePhoto(Request $request)
    {
        // Mengecek apakah request yang diterima adalah melalui AJAX atau berformat JSON.
        if ($request->ajax() || $request->wantsJson()) {
            
            // Menentukan aturan validasi untuk file foto profil yang diupload.
            $rules = [
                'file_pfp' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
                // file_pfp harus berupa file gambar dengan format jpeg, png, jpg, atau gif, dan maksimal 2MB.
            ];
    
            // Melakukan validasi request berdasarkan aturan yang ditentukan.
            $validator = Validator::make($request->all(), $rules);
    
            // Jika validasi gagal:
            if ($validator->fails()) {
                // Mengembalikan respon JSON dengan status gagal dan pesan error untuk setiap field yang tidak valid.
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // Pesan kesalahan spesifik pada setiap field.
                ]);
            }
    
            // Mendapatkan file gambar dari request yang diupload oleh pengguna.
            $file = $request->file('file_pfp');
    
            // Membuat nama file unik berdasarkan timestamp dan nama asli file.
            $filename = time() . '_' . $file->getClientOriginalName();
    
            // Menentukan path tempat penyimpanan file gambar di server.
            $path = public_path('images/pfp');
    
            // Memindahkan file yang diupload ke folder tujuan.
            $file->move($path, $filename);
    
            // Mengambil data pengguna yang sedang login dari database.
            $user = UserModel::findOrFail(auth()->user()->user_id);
    
            // Mengupdate kolom avatar pengguna dengan nama file baru.
            $user->avatar = $filename;
    
            // Menyimpan perubahan data pengguna ke database.
            $user->save();
    
            // Mengembalikan respon JSON dengan status berhasil dan path gambar baru.
            return response()->json([
                'status' => true,
                'message' => 'Foto profil berhasil diupload',
                'newProfilePicturePath' => asset('images/pfp/' . $filename), // Path gambar baru untuk di-refresh di UI.
            ]);
        }
    }
    
    public function updateProfile(Request $request)
    {
        // Mengecek apakah request yang diterima adalah melalui AJAX atau berformat JSON.
        if ($request->ajax() || $request->wantsJson()) {
            
            // Menentukan aturan validasi untuk username, nama, dan password pengguna.
            $rules = [
                'username' => 'required|string|max:50|unique:m_user,username,' . auth()->user()->user_id . ',user_id',
                // Username harus unik di tabel m_user, kecuali untuk pengguna yang sedang login.
                'nama' => 'required|string|max:100', // Nama harus diisi, maksimal 100 karakter.
                'password' => 'nullable|string|min:6', // Password bersifat opsional, minimal 6 karakter jika diisi.
            ];
    
            // Melakukan validasi terhadap data yang diinput oleh pengguna.
            $validator = Validator::make($request->all(), $rules);
    
            // Jika validasi gagal:
            if ($validator->fails()) {
                // Mengembalikan respon JSON dengan status gagal dan pesan error.
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // Pesan kesalahan spesifik pada setiap field yang tidak valid.
                ]);
            }
    
            // Mengambil data pengguna yang sedang login dari database.
            $user = UserModel::findOrFail(auth()->user()->user_id);
    
            // Mengupdate kolom username dengan nilai yang baru dari request.
            $user->username = $request->username;
    
            // Mengupdate kolom nama dengan nilai yang baru dari request.
            $user->nama = $request->nama;
    
            // Jika pengguna menginput password baru, maka password akan di-hash menggunakan bcrypt.
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }
    
            // Menyimpan perubahan data pengguna ke database.
            $user->save();
    
            // Mengembalikan respon JSON dengan status berhasil dan pesan sukses.
            return response()->json([
                'status' => true,
                'message' => 'Profil berhasil diperbarui',
            ]);
        }
    }
    

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_user' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_user'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if (count($data) > 1) { // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $password = !empty($value['D']) ? bcrypt($value['D']) : null; // hash password jika ada
                        $insert[] = [
                            'level_id' => $value['A'],
                            'username' => $value['B'],
                            'nama' => $value['C'],
                            'password' => $password, // simpan password yang sudah dihash
                            'created_at' => now(),
                        ];
                    }
                }
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    UserModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }
    public function export_excel()
    {
        // ambil data level yang akan di export
        $user = UserModel::select('level_id', 'username', 'nama', 'password')
            ->orderBy('level_id')
            ->with('level')
            ->get();
        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Password');
        $sheet->setCellValue('E1', 'Level');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);    // bold header
        $no = 1;        // nomor data dimulai dari 1
        $baris = 2;     // baris data dimulai dari baris ke 2
        foreach ($user as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->username);
            $sheet->setCellValue('C' . $baris, $value->nama);
            $sheet->setCellValue('D' . $baris, $value->password);
            $sheet->setCellValue('E' . $baris, $value->level->level_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);   // set auto size untuk kolom
        }
        $sheet->setTitle('Data User'); // set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data User ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $user = UserModel::select('level_id', 'username', 'nama')
            ->get();

        $pdf = Pdf::loadView('user.export_pdf', ['user' => $user]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->stream('Data User' . date('Y-m-d H:i:s') . '.pdf');
    }
}





    // public function index()
    // {
    //     $user =UserModel::with('level')->get();
    //  return view('user', ['data' => $user]);
    // }

    // public function index()
    // {
    //     $users = UserModel::all();
    //     return view('user', ['data' => $users]);
    // }


    // js4
//     public function tambah()
//     {
//         return view('user_tambah');
//     }

//     public function ubah($id)
//     {
//         $user = UserModel::find($id);
//         return view('user_ubah', ['data' => $user]);
//     }


//     public function tambah_simpan(Request $request)
//     {
//         UserModel::create([
//             'username' => $request->username,
//             'nama' => $request->nama,
//             'password' => Hash::make($request->password),
//             'level_id' => $request->level_id
//         ]);

//         return redirect('/user');
//     }
    
//     public function ubah_simpan($id, Request $request)
// {
//     $user = UserModel::find($id);

//     $user->username = $request->username;
//     $user->nama = $request->nama;

//     if (!empty($request->password)) {
//         $user->password = Hash::make($request->password);
//     }

//     $user->level_id = $request->level_id;

//     $user->save();

//     return redirect('/user');
// }

// public function hapus($id)
// {
//     $user = UserModel::find($id);
//     $user->delete();
//     return redirect('/user');
// }







// jobsheet 3

    // public function index()
    // {
    //     // Membuat user baru dengan metode create
    //     $user = UserModel::create([
    //         'username' => 'manager11',
    //         'nama' => 'Manager11',
    //         'password' => Hash::make('12345'),
    //         'level_id' => 2,
    //     ]);

    //     // Mengubah username
    //     $user->username = 'manager12';

    //     // Menyimpan perubahan ke database
    //     $user->save();

    //     // Mengecek apakah ada perubahan setelah disimpan
    //     $userWasChanged = $user->wasChanged(); // true
    //     $usernameWasChanged = $user->wasChanged('username'); // true
    //     $usernameAndLevelIdChanged = $user->wasChanged(['username', 'level_id']); // true
    //     $namaWasChanged = $user->wasChanged('nama'); // false

    //     // Debugging untuk melihat hasil wasChanged setelah perubahan
    //     dd($user->wasChanged(['nama', 'username'])); // true
    // }
// }
//     public function index()
//     {
//         // Membuat user baru dengan metode create
//         $user = UserModel::create([
//             'username' => 'manager55',
//             'nama' => 'Manager55',
//             'password' => Hash::make('12345'),
//             'level_id' => 2,
//         ]);

//         // Mengubah username setelah pembuatan
//         $user->username = 'manager56';

//         // Mengecek apakah properti 'username' sudah berubah
//         $isDirtyUsername = $user->isDirty('username'); // true
//         $isDirtyNama = $user->isDirty('nama'); // false
//         $isDirtyBoth = $user->isDirty(['nama', 'username']); // true

//         // Mengecek apakah properti 'username' dan 'nama' bersih (tidak berubah)
//         $isCleanUsername = $user->isClean('username'); // false
//         $isCleanNama = $user->isClean('nama'); // true
//         $isCleanBoth = $user->isClean(['nama', 'username']); // false

//         // Menyimpan perubahan ke database
//         $user->save();

//         // Setelah save, semua perubahan tersimpan
//         $isDirtyAfterSave = $user->isDirty(); // false
//         $isCleanAfterSave = $user->isClean(); // true

//         // Debugging untuk melihat hasil isDirty setelah disimpan
//         dd($isDirtyAfterSave);
//     }
// }

    // public function index()
    // {
    //     $user = UserModel::firstOrCreate(
    //         [
    //         'username' => 'manager33', 
    //         'nama' => 'Manager Tiga Tiga',
    //         'password' =>Hash::make('12345'),
    //         'level_id' => 2
    //     ],
            // [
            //     'nama' => 'Manager Dua Dua',
            //     'password' => Hash::make('12345'),
            //     'level_id' => 2,
            // ]
        // );

        // Mengirim data ke view
        // $user->save();
        // return view('user', ['data' => $user]);

        // $user = UserModel::find(1);
        // $user = UserModel::where('level_id', 1)->first();
        // $user = UserModel::firstWhere('level_id', 1);
        // $user = UserModel::findOr(20,['username','nama'], function () {
        //     abort(404);
        // });
        // $user = UserModel::findOrFail(1);
        // $user = UserModel::where('username', 'manager9')->firstOrFail();
        // $userCount = UserModel::where('level_id', 2)->count();
        // dd($user);
        // return view('user', ['userCount' => $userCount]);
    // }

// {
//     public function index()
//     {
//         $data =[
//             'level_id' => 2,
//             'username' => 'manager_tiga',
//             'nama' => 'Manager 3',
//             'password' => Hash::make('12345'),
//         ];
//         UserModel::create($data);


        // $data = [
        //     'username' => 'customer-1',
        //     'nama' => 'Pelanggan ',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 4
        // ];
        // UserModel::insert($data);
        
        // tambah data user dengan Eloquent Model
        // $data = [
        //     'nama' => 'Pelanggan Pertana',
        // ];
        // UserModel::where('username', 'customer-1')->update($data); // update data user
        
        // coba akses model UserModel
        // $user = UserModel::all(); // ambil semua data dari tabel m_user
//         return view('user', ['data' => $user]);
//     }   
// }    