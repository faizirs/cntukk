->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})

// admin controller------------------
public function index()
    {
        return view('admin.dashboard');
    }

//auth.login---------------------------
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SPP</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <h2 class="card-title text-center">LOGIN</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" class="form-control" required placeholder="Masukkan Username">
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" class="form-control" required placeholder="Masukkan Password">
                    </div>

                    <button type="submit" class="btn btn-info btn-block">LOGIN</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


//AuthController -------------------------
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.index');
            } elseif ($user->role === 'siswa') {
                return redirect()->route('siswa.index');
            }

            Auth::logout();
            return back()->withErrors(['login' => 'Role tidak dikenali.']);
        }

        return back()->withErrors(['login' => 'Username atau Password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}

//KelasController -------------------------------
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with('kompetensiKeahlian')->get();
        $kompetensi = KompetensiKeahlian::all();
    return view('admin.kelas', compact('kelas', 'kompetensi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not needed as we're using modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|max:10',
            'kompetensi_keahlian_id' => 'required|exists:kompetensi_keahlian,id_kompetensi_keahlian',
        ]);
    
        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'kompetensi_keahlian_id' => $request->kompetensi_keahlian_id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not needed for this implementation
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not needed as we're using modal
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kelas' => 'required|max:10',
            'kompetensi_keahlian_id' => 'required|exists:kompetensi_keahlian,id_kompetensi_keahlian',
        ]);
    
        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'kompetensi_keahlian_id' => $request->kompetensi_keahlian_id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
}


//KelolaSiswaController ------------------------------------------
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Spp;

class KelolaSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::with(['user', 'kelas', 'spp'])->get();
        $users = User::all(); // untuk dropdown user di modal
        $kelas = Kelas::all();
        $spp = Spp::all();
        
        return view('admin.kelola-siswa', compact('siswa', 'users', 'kelas', 'spp'));
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
            'nisn' => 'required|unique:siswa,nisn',
            'nis' => 'required',
            'nama' => 'required',
            'id_kelas' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'id_spp' => 'required',
            'id_user' => 'nullable|exists:users,id',
        ]);
        Siswa::create($request->all());
        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nisn' => 'required|unique:siswa,nisn,' . $id . ',nisn',
            'nis' => 'required',
            'nama' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'alamat' => 'required',
            'no_telp' => 'required',
            'id_spp' => 'required|exists:spp,id_spp',
            'id_user' => 'nullable|exists:users,id',
        ]);

        $siswa->update($validated);

        return redirect()->back()->with('success', 'Siswa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->back()->with('success', 'Siswa berhasil dihapus.');
    }
}


//KompetensiKeahlianController ------------------------------------------------------------
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KompetensiKeahlian;

class KompetensiKeahlianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = KompetensiKeahlian::all();
        return view('admin.kompetensi-keahlian', compact('data'));
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
            'nama_kompetensi_keahlian' => 'required|max:255'
        ]);

        KompetensiKeahlian::create($request->all());
        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kompetensi_keahlian' => 'required|max:255'
        ]);

        KompetensiKeahlian::where('id_kompetensi_keahlian', $id)->update([
            'nama_kompetensi_keahlian' => $request->nama_kompetensi_keahlian
        ]);
        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        KompetensiKeahlian::where('id_kompetensi_keahlian', $id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}


//ManagementController ---------------------------------------------------------
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.management', compact('users'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,siswa',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|unique:users,username,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,siswa',
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->role = $validated['role'];

        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}

//SiswaController ---------------------------------------------------------------------------
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

    // Ambil data pembayaran berdasarkan id_user dari user yang login
    $pembayaran = Pembayaran::with(['siswa', 'spp'])
        ->where('id_user', $user->id)
        ->get();
        return view('siswa.riwayat-pembayaran', compact('pembayaran'));
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
        'nisn' => 'required|unique:siswa,nisn',
        'nis' => 'required',
        'nama' => 'required',
        'id_kelas' => 'required',
        'alamat' => 'required',
        'no_telp' => 'required',
        'id_spp' => 'required',
        'id_user' => 'nullable|exists:users,id',
    ]);

    Siswa::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

//PembayaranController ---------------------------------------------------------------
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Spp;
use App\Exports\PembayaranExport;
use Maatwebsite\Excel\Facades\Excel;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayaran = Pembayaran::with(['siswa', 'spp'])->get();
        $siswa = Siswa::all();
        $spp = Spp::all();
        return view('admin.pembayaran', compact('pembayaran', 'siswa', 'spp'));
    }

    public function exportExcel()
    {
        return Excel::download(new PembayaranExport, 'data_pembayaran.xlsx');

    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'nisn' => 'required',
            'tgl_bayar' => 'required|date',
            'bulan_bayar' => 'required',
            'tahun_bayar' => 'required',
            'id_spp' => 'required',
            'jumlah_bayar' => 'required|numeric',
        ]);

        Pembayaran::create($request->all());
        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update($request->all());
        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Pembayaran::destroy($id);
        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }
}

//SppController ------------------------------------------------------------
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spp;

class SppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $spp = Spp::all();
        return view('admin.spp', compact('spp'));
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
            'tahun' => 'required|integer',
            'nominal' => 'required|integer',
        ]);

        Spp::create($request->all());

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'nominal' => 'required|integer',
        ]);

        $spp = Spp::findOrFail($id);
        $spp->update($request->all());

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $spp = Spp::findOrFail($id);
        $spp->delete();

        return redirect()->route('spp.index')->with('success', 'Data berhasil dihapus!');
    }
}


//RoleMiddleware.php ---------------------------------------------------------
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login/siswa'); // default jika belum login
        }

        if (Auth::user()->role !== $role) {
            return redirect('/');
        }

        return $next($request);
    }
}

<-----Model------>

//Kelas --------------------------------------------------------------------
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    protected $fillable = ['nama_kelas', 'kompetensi_keahlian_id'];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }
    public function kompetensiKeahlian(){
        return $this->belongsTo(KompetensiKeahlian::class, 'kompetensi_keahlian_id', 'id_kompetensi_keahlian');
    }
}


//KompetensiKeahlian ---------------------------------------------------------------------------
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KompetensiKeahlian extends Model
{
    protected $table = 'kompetensi_keahlian';
    protected $primaryKey = 'id_kompetensi_keahlian';
    protected $fillable = ['nama_kompetensi_keahlian'];

    public function kelas(){
        return $this->hasMany(Kelas::class, 'id_kompetensi_keahlian', 'id_kompetensi_keahlian');
    }
}

//Pembayaran -------------------------------------------------------------------------------------
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_user', 'nisn', 'tgl_bayar', 'bulan_bayar', 'tahun_bayar', 'id_spp', 'jumlah_bayar'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function spp()
    {
        return $this->belongsTo(Spp::class, 'id_spp');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

// Siswa -----------------------------------------------------------------
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    
    protected $table = 'siswa';
    
    protected $primaryKey = 'nisn';
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    public $timestamps = false;
    
    protected $fillable = [
        'nisn',
        'id_user',
        'nis',
        'nama',
        'id_kelas',
        'alamat',
        'no_telp',
        'id_spp',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function spp()
    {
        return $this->belongsTo(SPP::class, 'id_spp', 'id_spp');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function pembayaran()
{
    return $this->hasMany(Pembayaran::class, 'nisn', 'nisn');
}
}


//Spp --------------------------------------------------------------------------
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spp extends Model
{
    use HasFactory;
    protected $table = 'spp';
    
    protected $primaryKey = 'id_spp';
    
    protected $fillable = ['tahun', 'nominal'];

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_spp', 'id_spp');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_spp', 'id_spp');
    }
}

//User -------------------------------------------------------------------
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_user');
    }
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user');
    }

}

<-------Migration------>

//users --------------------------------------------------------------
Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('username')->unique(); // untuk admin
            $table->string('password');
            $table->string('role'); // admin atau siswa
            $table->timestamps();
        });

//Kompetensi Keahlian -------------------------------------
Schema::create('kompetensi_keahlian', function (Blueprint $table) {
            $table->id('id_kompetensi_keahlian');
            $table->string('nama_kompetensi_keahlian', 255);
            $table->timestamps();
        });

//Kelas ---------------------------------------------------------------

        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 255);
            $table->unsignedBigInteger('kompetensi_keahlian_id');
            $table->timestamps();

            $table->foreign('kompetensi_keahlian_id')->references('id_kompetensi_keahlian')->on('kompetensi_keahlian')->onDelete('cascade');

            });

//spp ------------------------------------------
Schema::create('spp', function (Blueprint $table) {
            $table->id('id_spp');
            $table->integer('tahun');
            $table->integer('nominal');
            $table->timestamps();
        });

//Siswa -----------------------------------------------------

        Schema::create('siswa', function (Blueprint $table) {
            $table->string('nisn', 10)->primary();
            $table->unsignedBigInteger('id_user');
            $table->string('nis', 8);
            $table->string('nama', 255);
            $table->unsignedBigInteger('id_kelas');
            $table->text('alamat');
            $table->string('no_telp', 13);
            $table->unsignedBigInteger('id_spp');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
            $table->foreign('id_spp')->references('id_spp')->on('spp')->onDelete('cascade');
        });


//pembayaran ----------------------------------------------------------
Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_user');
            $table->string('nisn', 10);
            $table->date('tgl_bayar');
            $table->string('bulan_bayar', 10);
            $table->string('tahun_bayar', 4);
            $table->unsignedBigInteger('id_spp');
            $table->integer('jumlah_bayar');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            $table->foreign('id_spp')->references('id_spp')->on('spp')->onDelete('cascade');
        });

<------------------web.php--------------------------->
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelolaSiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SppController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KompetensiKeahlianController;
use App\Http\Controllers\ManagementController;

Route::get('/', [AuthController::class, 'formLogin']);
Route::get('/login', [AuthController::class, 'formLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('admin', AdminController::class); 
    Route::resource('kompetensi-keahlian', KompetensiKeahlianController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('spp', SppController::class);
    Route::resource('pembayaran', PembayaranController::class)->except(['show']);
    Route::get('/pembayaran/export', [PembayaranController::class, 'exportExcel'])->name('pembayaran.export');
    Route::resource('kelola-siswa', KelolaSiswaController::class);
    Route::resource('management', ManagementController::class);
});

Route::middleware(['auth', 'role:siswa'])->group(function () {
    
    Route::resource('siswa', SiswaController::class);
});


<------------view------------>
//layouts.app------------------------------------------------------
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Aplikasi SPP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->

    <style>
        body {
            background-color: #383851;
            color: #ffffff;
        }

        .navbar {
            background-color: #1e1e2f;
        }

        .navbar-brand, .nav-link, .dropdown-item {
            color: #ddd !important;
        }

        .nav-link:hover, .dropdown-item:hover {
            color: #fff !important;
        }

        .active-link {
            color: #fff !important;
            font-weight: bold;
        }

        .card {
            background-color: #1f1f2e;
            color: white;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container-content {
            padding: 30px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Aplikasi SPP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                @if (auth()->user()->role == "admin")
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.index') ? 'active-link' : '' }}" href="{{ route('admin.index') }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('pembayaran*') ? 'active-link' : '' }}" href="{{ route('pembayaran.index') }}">
                            Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kompetensi-keahlian*') ? 'active-link' : '' }}" href="{{ route('kompetensi-keahlian.index') }}">
                            Kompetensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kelas*') ? 'active-link' : '' }}" href="{{ route('kelas.index') }}">
                            Kelas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('spp*') ? 'active-link' : '' }}" href="{{ route('spp.index') }}">
                            SPP
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kelola-siswa*') ? 'active-link' : '' }}" href="{{ route('kelola-siswa.index') }}">
                            Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('management*') ? 'active-link' : '' }}" href="{{ route('management.index') }}">
                            Management
                        </a>
                    </li>
                @elseif(auth()->user()->role == "siswa")
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('siswa*') ? 'active-link' : '' }}" href="{{ route('siswa.index') }}">
                            Riwayat Pembayaran
                            </a>
                    </li>
                @endif
            </ul>

            <form action="{{ route('logout') }}" method="POST" class="d-flex">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="container-content">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


<---admin--->
//dashboard--------------------------------------------------------------
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-dark text-white shadow-lg rounded-4">
                <div class="card-body text-center">
                    <h1 class="display-5 fw-bold mb-3">Selamat Datang di Aplikasi Pembayaran SPP</h1>
                    <hr class="my-4 border-light">
                    <p class="mb-4">
                        Silakan gunakan menu navigasi di atas untuk mengelola data SPP, siswa, pembayaran, dan lainnya.
                    </p>
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-cash-coin me-1"></i> Lihat Data Pembayaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

//kelas ----------------------------------------------------------
@extends('layouts.app')

@section('content')
<h2 class="mb-4">Data Kelas</h2>
<hr>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">
    <i class="bi bi-plus-circle mr-1"></i> Tambah Kelas
</button>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Kelas</th>
                    <th>Kompetensi Keahlian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelas as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->nama_kelas }}</td>
                    <td>{{ $item->kompetensiKeahlian->nama_kompetensi_keahlian }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn"
                            data-id="{{ $item->id_kelas }}"
                            data-nama="{{ $item->nama_kelas }}"
                            data-kompetensi="{{ $item->kompetensi_keahlian_id }}"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('kelas.destroy', $item->id_kelas) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <form method="POST" action="{{ route('kelas.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" class="form-control" required>
                    <label class="mt-3">Kompetensi Keahlian</label>
                    <select name="kompetensi_keahlian_id" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($kompetensi as $k)
                        <option value="{{ $k->id_kompetensi_keahlian }}">{{ $k->nama_kompetensi_keahlian }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kelas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="edit_nama" class="form-control" required>
                    <label class="mt-3">Kompetensi Keahlian</label>
                    <select name="kompetensi_keahlian_id" id="edit_kompetensi" class="form-control" required>
                        @foreach($kompetensi as $k)
                        <option value="{{ $k->id_kompetensi_keahlian }}">{{ $k->nama_kompetensi_keahlian }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const editBtns = document.querySelectorAll('.edit-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const kompetensi = this.dataset.kompetensi;

            const form = document.getElementById('editForm');
            form.action = `/kelas/${id}`;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_kompetensi').value = kompetensi;
        });
    });
</script>
@endsection

//kelola-siswa ---------------------------------------------------------
@extends('layouts.app')

@section('content')
<h2 class="mb-4">Kelola Data Siswa</h2>
<hr>
<!-- Tombol Tambah Siswa -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle"></i> Tambah Siswa
</button>

<!-- Tabel Siswa -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NISN</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Alamat</th>
                    <th>No. Telp</th>
                    <th>User</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $index => $s)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $s->nisn }}</td>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $s->alamat }}</td>
                    <td>{{ $s->no_telp }}</td>
                    <td>{{ $s->user->name ?? '-' }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $s->nisn }}">Edit</button>
                        <form action="{{ route('kelola-siswa.destroy', $s->nisn) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $s->nisn }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('kelola-siswa.update', $s->nisn) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Siswa</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @include('admin.siswa-form', ['s' => $s])
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('kelola-siswa.store') }}">
            @csrf
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.siswa-form', ['s' => null])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

//kompetensi-keahlian -----------------------------------------------------
@extends('layouts.app')

@section('content')
<h2 class="mb-4">Data Kompetensi Keahlian</h2>
<hr>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">
    <i class="bi bi-plus-circle"></i> Tambah Kompetensi
</button>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-dark table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Kompetensi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->nama_kompetensi_keahlian }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn"
                            data-id="{{ $item->id_kompetensi_keahlian }}"
                            data-nama="{{ $item->nama_kompetensi_keahlian }}"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal">
                            Edit
                        </button>
                        <form action="{{ route('kompetensi-keahlian.destroy', $item->id_kompetensi_keahlian) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <form action="{{ route('kompetensi-keahlian.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kompetensi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Kompetensi</label>
                    <input type="text" name="nama_kompetensi_keahlian" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kompetensi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Kompetensi</label>
                    <input type="text" name="nama_kompetensi_keahlian" id="edit_nama" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const form = document.getElementById('editForm');
            form.action = `/kompetensi-keahlian/${id}`;
            document.getElementById('edit_nama').value = nama;
        });
    });
</script>
@endsection

//management ------------------------------------------------------------------
@extends('layouts.app')

@section('content')
<h2 class="mb-4">Manajemen User</h2>
<hr>

<!-- Tombol Tambah -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    Tambah User
</button>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Tabel Data -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username ?? '-' }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $user->id }}">
                            Edit
                        </button>

                        <!-- Form Hapus -->
                        <form action="{{ route('management.destroy', $user->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('management.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit User</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Username (Kosongkan untuk siswa)</label>
                                        <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Password (Kosongkan jika tidak ingin diganti)</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Role</label>
                                        <select name="role" class="form-select" required>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('management.store') }}">
            @csrf
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Username (kosongkan jika siswa)</label>
                        <input type="text" name="username" class="form-control">
                    </div>
                    <div class="form-group mt-2">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

//pembayaran-form -----------------------------------------------------
<!-- Pilih Nama Siswa -->
<div class="mb-3">
    <label for="nisn" class="form-label">Nama Siswa</label>
    <select name="nisn" class="form-select select-siswa" required>
        <option value="">-- Pilih Siswa --</option>
        @foreach ($siswa as $s)
            <option value="{{ $s->nisn }}"
                    data-iduser="{{ $s->id_user }}"
                    {{ isset($p) && $p->nisn == $s->nisn ? 'selected' : '' }}>
                {{ $s->nama }}
            </option>
        @endforeach
    </select>
</div>

<!-- NISN Otomatis -->
<div class="mb-3">
    <label for="inputNisn" class="form-label">NISN</label>
    <input type="text" class="form-control input-nisn" id="inputNisn" readonly>
</div>

<!-- Hidden input id_user -->
<input type="hidden" name="id_user" class="input-id-user">

<!-- Tanggal Bayar -->
<div class="mb-3">
    <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
    <input type="date" name="tgl_bayar" class="form-control" value="{{ $p->tgl_bayar ?? '' }}" required>
</div>

<!-- Bulan Bayar -->
<div class="mb-3">
    <label for="bulan_bayar" class="form-label">Bulan Bayar</label>
    <input type="text" name="bulan_bayar" class="form-control" value="{{ $p->bulan_bayar ?? '' }}" required>
</div>

<!-- Tahun Bayar -->
<div class="mb-3">
    <label for="tahun_bayar" class="form-label">Tahun Bayar</label>
    <input type="text" name="tahun_bayar" class="form-control" value="{{ $p->tahun_bayar ?? '' }}" required>
</div>

<!-- SPP -->
<div class="mb-3">
    <label for="id_spp" class="form-label">SPP</label>
    <select name="id_spp" class="form-select" required>
        @foreach ($spp as $s)
            <option value="{{ $s->id_spp }}"
                {{ isset($p) && $p->id_spp == $s->id_spp ? 'selected' : '' }}>
                {{ $s->tahun }} - Rp{{ number_format($s->nominal, 0, ',', '.') }}
            </option>
        @endforeach
    </select>
</div>

<!-- Jumlah Bayar -->
<div class="mb-3">
    <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
    <input type="number" name="jumlah_bayar" class="form-control" value="{{ $p->jumlah_bayar ?? '' }}" required>
</div>

//pembayaran ----------------------------------------------------------
@extends('layouts.app')

@section('content')
<h2 class="mb-4">Data Pembayaran</h2>
<hr>
<!-- Tombol Tambah -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle mr-1"></i> Tambah Pembayaran
</button>
<a href="{{ route('pembayaran.export') }}" class="btn btn-success mb-3">
    <i class="bi bi-file-earmark-excel"></i> Cetak Laporan
</a>

<!-- Tabel Pembayaran -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Tanggal</th>
                    <th>Bulan/Tahun</th>
                    <th>SPP</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->nisn }}</td>
                    <td>{{ $p->siswa->nama ?? '-' }}</td>
                    <td>{{ $p->tgl_bayar }}</td>
                    <td>{{ $p->bulan_bayar }}/{{ $p->tahun_bayar }}</td>
                    <td>{{ $p->spp->tahun ?? '-' }} - Rp{{ number_format($p->spp->nominal ?? 0, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id_pembayaran }}">Edit</button>
                        <form action="{{ route('pembayaran.destroy', $p->id_pembayaran) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $p->id_pembayaran }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('pembayaran.update', $p->id_pembayaran) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Pembayaran</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @include('admin.pembayaran-form', ['p' => $p, 'siswa' => $siswa, 'spp' => $spp])
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('pembayaran.store') }}">
            @csrf
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.pembayaran-form', ['p' => null, 'siswa' => $siswa, 'spp' => $spp])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk ambil NISN otomatis -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.select-siswa').forEach(function(select) {
            const container = select.closest('.modal-body');
            const inputNisn = container.querySelector('.input-nisn');
            const inputIdUser = container.querySelector('.input-id-user');
    
            function updateInputs() {
                const selected = select.options[select.selectedIndex];
                if (selected) {
                    if (inputNisn) inputNisn.value = selected.value;
                    if (inputIdUser) inputIdUser.value = selected.getAttribute('data-iduser');
                }
            }
    
            select.addEventListener('change', updateInputs);
            updateInputs(); // jalanin saat pertama (untuk edit modal)
        });
    });
    </script>
    
    

@endsection

//siswa-form -----------------------------------------------------------------------
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="nisn" class="form-label">NISN</label>
        <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $s->nisn ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="nis" class="form-label">NIS</label>
        <input type="text" name="nis" class="form-control" value="{{ old('nis', $s->nis ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $s->nama ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="id_kelas" class="form-label">Kelas</label>
        <select name="id_kelas" class="form-select" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach ($kelas as $k)
                <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $s->id_kelas ?? '') == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" required>{{ old('alamat', $s->alamat ?? '') }}</textarea>
    </div>

    <div class="col-md-6 mb-3">
        <label for="no_telp" class="form-label">No. Telepon</label>
        <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $s->no_telp ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="id_spp" class="form-label">SPP</label>
        <select name="id_spp" class="form-select" required>
            <option value="">-- Pilih SPP --</option>
            @foreach ($spp as $sp)
                <option value="{{ $sp->id_spp }}" {{ old('id_spp', $s->id_spp ?? '') == $sp->id_spp ? 'selected' : '' }}>
                    {{ $sp->tahun }} - Rp{{ number_format($sp->nominal) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 mb-3">
        <label for="id_user" class="form-label">Tautkan ke User</label>
        <select name="id_user" class="form-select">
            <option value="">-- Kosongkan jika belum ada --</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" {{ old('id_user', $s->id_user ?? '') == $u->id ? 'selected' : '' }}>
                    {{ $u->name }} ({{ $u->email }})
                </option>
            @endforeach
        </select>
    </div>
</div>

//spp -------------------------------------------------------------
@extends('layouts.app')

@section('content')
<h2 class="mb-4">Data SPP</h2>
<hr>

<!-- Tombol Tambah -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    Tambah SPP
</button>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Tabel Data -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tahun</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($spp as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id_spp }}">
                            Edit
                        </button>

                        <!-- Form Hapus -->
                        <form action="{{ route('spp.destroy', $item->id_spp) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $item->id_spp }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('spp.update', $item->id_spp) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit SPP</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <input type="number" name="tahun" class="form-control" value="{{ $item->tahun }}" required>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Nominal</label>
                                        <input type="number" name="nominal" class="form-control" value="{{ $item->nominal }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('spp.store') }}">
            @csrf
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah SPP</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Nominal</label>
                        <input type="number" name="nominal" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

//siswa.riwayat-pembayaran ---------------------------------------------------------
@extends('layouts.app')

@section('content')
<h2 class="mb-4">Riwayat Pembayaran SPP</h2>
<hr>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Tanggal Bayar</th>
                    <th>Bulan / Tahun</th>
                    <th>SPP (Tahun)</th>
                    <th>Nominal SPP</th>
                    <th>Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayaran as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->nisn }}</td>
                    <td>{{ $p->siswa->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->format('d-m-Y') }}</td>
                    <td>{{ $p->bulan_bayar }} / {{ $p->tahun_bayar }}</td>
                    <td>{{ $p->spp->tahun ?? '-' }}</td>
                    <td>Rp{{ number_format($p->spp->nominal ?? 0, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada riwayat pembayaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

//Exports.PembayaranExport.php -------------------------------------------------------
<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return Pembayaran::with(['siswa', 'spp'])->get();
        } else {
            return Pembayaran::with(['siswa', 'spp'])
                ->where('id_user', $user->id)
                ->get();
        }
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Tanggal Bayar',
            'Bulan Bayar',
            'Tahun Bayar',
            'SPP Tahun',
            'Jumlah Bayar',
        ];
    }

    public function map($pembayaran): array
    {
        return [
            $pembayaran->nisn,
            $pembayaran->siswa->nama ?? '-',
            date('d/m/Y', strtotime($pembayaran->tgl_bayar)),
            $pembayaran->bulan_bayar,
            $pembayaran->tahun_bayar,
            $pembayaran->spp->tahun ?? '-',
            $pembayaran->jumlah_bayar,
        ];
    }
}
