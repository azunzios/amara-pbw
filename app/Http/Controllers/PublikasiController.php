<?php

namespace App\Http\Controllers;

use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublikasiController extends Controller
{
    /** Folder penyimpanan sampul (sama seperti folder "asset" di webbps PHP). */
    private const FOLDER_SAMPUL = 'asset';

    /** READ: daftar publikasi */
    public function index()
    {
        $publikasi = Publikasi::orderBy('id')->get();

        return view('publikasi.index', compact('publikasi'));
    }

    /** CREATE: tampilkan form */
    public function create()
    {
        return view('publikasi.create');
    }

    /** CREATE: simpan data baru */
    public function store(Request $request)
    {
        $data = $request->validate($this->aturan(sampulWajib: true), $this->pesan());

        $data['sampul'] = $this->simpanSampul($request->file('sampul'));
        Publikasi::create($data);

        return redirect()->route('publikasi.index')->with('sukses', 'Data Berhasil Ditambahkan');
    }

    /** UPDATE: tampilkan form edit */
    public function edit(Publikasi $publikasi)
    {
        return view('publikasi.edit', compact('publikasi'));
    }

    /** UPDATE: simpan perubahan */
    public function update(Request $request, Publikasi $publikasi)
    {
        $data = $request->validate($this->aturan(sampulWajib: false), $this->pesan());

        if ($request->hasFile('sampul')) {
            $this->hapusSampul($publikasi->sampul);
            $data['sampul'] = $this->simpanSampul($request->file('sampul'));
        } else {
            unset($data['sampul']); // kosong = sampul lama dipertahankan
        }

        $publikasi->update($data);

        return redirect()->route('publikasi.index')->with('sukses', 'Data Berhasil Diubah');
    }

    /** DELETE */
    public function destroy(Publikasi $publikasi)
    {
        $this->hapusSampul($publikasi->sampul);
        $publikasi->delete();

        return redirect()->route('publikasi.index')->with('sukses', 'Data Berhasil Dihapus');
    }

    /** Saran judul (pengganti page11A_gethint.php), mengembalikan JSON */
    public function cari(Request $request)
    {
        $keyword = trim((string) $request->query('keyword', ''));

        if ($keyword === '') {
            return response()->json([]);
        }

        $judul = Publikasi::where('judul', 'like', '%'.addcslashes($keyword, '%_\\').'%')
            ->orderBy('judul')
            ->pluck('judul');

        return response()->json($judul);
    }

    private function aturan(bool $sampulWajib): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'tanggal_rilis' => ['required', 'date'],
            'sampul' => [$sampulWajib ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'abstraksi' => ['required', 'string'],
        ];
    }

    private function pesan(): array
    {
        return [
            'judul.required' => 'Judul tidak boleh kosong.',
            'judul.max' => 'Judul maksimal 255 karakter.',
            'tanggal_rilis.required' => 'Tanggal rilis tidak boleh kosong.',
            'tanggal_rilis.date' => 'Tanggal rilis tidak valid.',
            'sampul.required' => 'Sampul wajib dipilih.',
            'sampul.image' => 'Sampul harus berupa gambar.',
            'sampul.mimes' => 'Sampul harus berformat JPG, PNG, atau WEBP.',
            'sampul.max' => 'Ukuran sampul maksimal 2 MB.',
            'abstraksi.required' => 'Abstraksi tidak boleh kosong.',
        ];
    }

    private function simpanSampul($file): string
    {
        $nama = 'upload-'.time().'-'.Str::random(6).'.'.$file->extension();
        $file->move(public_path(self::FOLDER_SAMPUL), $nama);

        return $nama;
    }

    /** Hanya menghapus file hasil upload; cover bawaan (Cover1.webp, dst.) tidak disentuh. */
    private function hapusSampul(?string $nama): void
    {
        if ($nama && str_starts_with($nama, 'upload-')) {
            $path = public_path(self::FOLDER_SAMPUL.'/'.$nama);
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
