<?php

namespace Tests\Feature;

use App\Models\Publikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PublikasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    protected function tearDown(): void
    {
        foreach (glob(public_path('asset/upload-*')) ?: [] as $f) {
            @unlink($f);
        }
        parent::tearDown();
    }

    private function masuk(): User
    {
        $user = User::factory()->create(['username' => 'admin', 'password' => 'rahasia123']);
        $this->actingAs($user);

        return $user;
    }

    public function test_tamu_bisa_melihat_publikasi_dan_galeri_tapi_cud_harus_login(): void
    {
        // Pertama kali mengunjungi / diarahkan ke /publikasi
        $this->get('/')->assertRedirect('/publikasi');

        // Tamu bisa melihat daftar publikasi dan galeri tanpa login (Read)
        $this->get('/publikasi')->assertOk()->assertSee('Daftar Publikasi');
        $this->get('/galeri')->assertOk()->assertSee('Galeri Kegiatan');

        // Tambah publikasi dan CUD lainnya harus login dan ada notifikasi "Anda perlu login"
        $this->get('/publikasi/create')
            ->assertRedirect('/login')
            ->assertSessionHas('warning', 'Anda perlu login');

        // Mengikuti redirect ke /login menampilkan teks notifikasi "Anda perlu login"
        $this->followingRedirects()
            ->get('/publikasi/create')
            ->assertOk()
            ->assertSee('Anda perlu login');

        $this->post('/publikasi', [])
            ->assertRedirect('/login')
            ->assertSessionHas('warning', 'Anda perlu login');

        $p = Publikasi::create(['judul' => 'Uji Tamu', 'tanggal_rilis' => '2025-01-01', 'sampul' => 'Cover1.webp', 'abstraksi' => 'test']);

        $this->get(route('publikasi.edit', $p))
            ->assertRedirect('/login')
            ->assertSessionHas('warning', 'Anda perlu login');

        $this->followingRedirects()
            ->get(route('publikasi.edit', $p))
            ->assertOk()
            ->assertSee('Anda perlu login');

        $this->put(route('publikasi.update', $p), ['judul' => 'Ubah'])
            ->assertRedirect('/login')
            ->assertSessionHas('warning', 'Anda perlu login');

        $this->delete(route('publikasi.destroy', $p))
            ->assertRedirect('/login')
            ->assertSessionHas('warning', 'Anda perlu login');

        $this->get('/login')
            ->assertOk()
            ->assertSee('Silakan masuk');
    }

    public function test_login_benar_dan_salah(): void
    {
        User::factory()->create(['username' => 'admin', 'password' => 'rahasia123']);

        $this->post('/login', ['username' => 'admin', 'password' => 'salah'])
            ->assertRedirect()->assertSessionHas('error', 'Username/Password salah');
        $this->assertGuest();

        $this->post('/login', ['username' => 'admin', 'password' => 'rahasia123'])
            ->assertRedirect(route('publikasi.index'));
        $this->assertAuthenticated();
    }

    public function test_logout(): void
    {
        $this->masuk();
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_daftar_publikasi_tampil(): void
    {
        $this->masuk();
        Publikasi::create(['judul' => 'Statistik Uji', 'tanggal_rilis' => '2025-01-02', 'sampul' => 'Cover1.webp', 'abstraksi' => 'abc']);

        $this->get('/publikasi')->assertOk()->assertSee('Statistik Uji')->assertSee('2025-01-02');
    }

    public function test_tambah_publikasi_dengan_upload(): void
    {
        $this->masuk();
        $this->get('/publikasi/create')->assertOk();

        $this->post('/publikasi', [
            'judul' => 'Publikasi Baru',
            'tanggal_rilis' => '2026-03-04',
            'abstraksi' => 'Isi abstraksi',
            'sampul' => UploadedFile::fake()->image('c.jpg'),
        ])->assertRedirect(route('publikasi.index'))->assertSessionHas('sukses');

        $p = Publikasi::firstOrFail();
        $this->assertSame('Publikasi Baru', $p->judul);
        $this->assertFileExists(public_path('asset/'.$p->sampul));
    }

    public function test_tambah_publikasi_validasi(): void
    {
        $this->masuk();
        $this->post('/publikasi', ['judul' => ''])
            ->assertSessionHasErrors(['judul', 'tanggal_rilis', 'sampul', 'abstraksi']);
        $this->assertDatabaseCount('publikasis', 0);
    }

    public function test_edit_tanpa_ganti_sampul_dan_dengan_ganti_sampul(): void
    {
        $this->masuk();
        $p = Publikasi::create(['judul' => 'Lama', 'tanggal_rilis' => '2025-01-01', 'sampul' => 'Cover1.webp', 'abstraksi' => 'x']);

        $this->get(route('publikasi.edit', $p))->assertOk()->assertSee('Lama');

        $this->put(route('publikasi.update', $p), ['judul' => 'Baru', 'tanggal_rilis' => '2025-02-02', 'abstraksi' => 'y'])
            ->assertRedirect(route('publikasi.index'));
        $p->refresh();
        $this->assertSame('Baru', $p->judul);
        $this->assertSame('Cover1.webp', $p->sampul);

        $this->put(route('publikasi.update', $p), [
            'judul' => 'Baru', 'tanggal_rilis' => '2025-02-02', 'abstraksi' => 'y',
            'sampul' => UploadedFile::fake()->image('n.png'),
        ]);
        $p->refresh();
        $this->assertStringStartsWith('upload-', $p->sampul);
        $this->assertFileExists(public_path('asset/'.$p->sampul));
        $this->assertFileExists(public_path('asset/Cover1.webp')); // cover bawaan tidak terhapus
    }

    public function test_hapus_publikasi_dan_file_upload(): void
    {
        $this->masuk();
        $this->post('/publikasi', [
            'judul' => 'Akan Dihapus', 'tanggal_rilis' => '2026-01-01', 'abstraksi' => 'z',
            'sampul' => UploadedFile::fake()->image('c.jpg'),
        ]);
        $p = Publikasi::firstOrFail();
        $path = public_path('asset/'.$p->sampul);

        $this->delete(route('publikasi.destroy', $p))->assertRedirect(route('publikasi.index'));
        $this->assertDatabaseCount('publikasis', 0);
        $this->assertFileDoesNotExist($path);
    }

    public function test_cari_judul(): void
    {
        $this->masuk();
        Publikasi::create(['judul' => 'Statistik Pendidikan', 'tanggal_rilis' => '2025-01-01', 'abstraksi' => 'a']);
        Publikasi::create(['judul' => 'Keadaan Angkatan Kerja', 'tanggal_rilis' => '2025-01-01', 'abstraksi' => 'a']);

        $this->getJson('/publikasi/cari?keyword=pendidikan')->assertOk()->assertExactJson(['Statistik Pendidikan']);
        $this->getJson('/publikasi/cari?keyword=')->assertOk()->assertExactJson([]);
    }

    public function test_galeri_dan_seeder(): void
    {
        $this->masuk();
        $this->get('/galeri')->assertOk()->assertSee('Galeri Kegiatan');

        $this->seed();
        $this->assertDatabaseCount('publikasis', 5);
        $this->assertDatabaseHas('users', ['username' => 'admin']);
    }

    public function test_register_berhasil_dan_langsung_login(): void
    {
        $this->get('/register')->assertOk()->assertSee('Konfirmasi Password');

        $this->post('/register', [
            'name' => 'Amara', 'username' => 'amara_01',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('publikasi.index'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['username' => 'amara_01']);
        $this->assertNotSame('rahasia123', User::where('username', 'amara_01')->value('password'));
    }

    public function test_register_validasi(): void
    {
        User::factory()->create(['username' => 'admin']);

        $this->post('/register', [
            'name' => '', 'username' => 'admin', 'password' => '123', 'password_confirmation' => 'beda',
        ])->assertSessionHasErrors(['name', 'username', 'password']);
        $this->assertGuest();
    }

    public function test_remember_me_membuat_cookie_ingat(): void
    {
        User::factory()->create(['username' => 'admin', 'password' => 'rahasia123']);

        $res = $this->post('/login', ['username' => 'admin', 'password' => 'rahasia123', 'remember' => '1']);
        $res->assertRedirect();
        $this->assertNotEmpty(User::where('username', 'admin')->value('remember_token'));
        $this->assertTrue(collect($res->headers->getCookies())->contains(fn ($c) => str_starts_with($c->getName(), 'remember_web_')));
    }
}
