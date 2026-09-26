<?php

namespace Tests\Feature;

use App\Models\Curhat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SahabatAmanTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_create_curhat_and_view_history(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'identifier' => '2024001',
        ]);

        $guru = User::factory()->create(['role' => 'guru', 'identifier' => 'guru-1']);

        $this->actingAs($student);

        $response = $this->post('/curhat', [
            'penerima_id' => $guru->id,
            'kategori' => 'pribadi',
            'pesan' => 'Saya merasa cemas saat ujian.',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('curhats', [
            'siswa_id' => $student->id,
            'penerima_id' => $guru->id,
            'tujuan' => 'guru',
            'status' => 'terkirim',
        ]);

        $curhat = Curhat::where('siswa_id', $student->id)->first();
        $this->assertDatabaseHas('balasans', [
            'curhat_id' => $curhat->id,
            'pengirim_id' => $student->id,
            'pengirim_role' => 'siswa',
            'isi' => 'Saya merasa cemas saat ujian.',
        ]);

        $this->get('/dashboard')->assertOk()->assertSee('Saya merasa cemas saat ujian.');
    }

    public function test_guru_can_access_admin_panel(): void
    {
        $student = User::factory()->create(['role' => 'siswa', 'identifier' => '2024002']);
        $guru = User::factory()->create(['role' => 'guru', 'identifier' => 'guru-2']);

        $curhat = Curhat::create([
            'siswa_id' => $student->id,
            'penerima_id' => $guru->id,
            'tujuan' => 'guru',
            'kategori' => 'sosial',
            'status' => 'terkirim',
        ]);

        $this->actingAs($guru);

        $this->get('/admin')->assertOk()->assertSee('Ikhtisar Konseling');
        $this->get('/admin/curhat/'.$curhat->id)->assertOk()->assertSee('Detail Curhat');
    }

    public function test_siswa_cannot_access_admin_panel(): void
    {
        $student = User::factory()->create(['role' => 'siswa', 'identifier' => '2024005']);

        $this->actingAs($student);

        $this->get('/admin')->assertForbidden();
    }

    public function test_siswa_cannot_reply_to_curhat(): void
    {
        $sender = User::factory()->create(['role' => 'siswa', 'identifier' => '2024003']);
        $recipient = User::factory()->create(['role' => 'siswa', 'identifier' => '2024004']);

        $curhat = Curhat::create([
            'siswa_id' => $sender->id,
            'penerima_id' => $recipient->id,
            'tujuan' => 'siswa',
            'kategori' => 'belajar',
            'status' => 'terkirim',
        ]);

        $this->actingAs($recipient);

        $this->post('/dashboard/curhat/'.$curhat->id.'/balas', [
            'isi' => 'Coba konsultasikan ke guru BK ya.',
        ])->assertForbidden();
    }

    public function test_guru_can_reply_and_close_curhat(): void
    {
        $student = User::factory()->create(['role' => 'siswa', 'identifier' => '2024009']);
        $guru = User::factory()->create(['role' => 'guru', 'identifier' => 'guru-3']);

        $curhat = Curhat::create([
            'siswa_id' => $student->id,
            'penerima_id' => $guru->id,
            'tujuan' => 'guru',
            'kategori' => 'belajar',
            'status' => 'terkirim',
        ]);

        $this->actingAs($guru);

        $this->post('/dashboard/curhat/'.$curhat->id.'/balas', [
            'isi' => 'Mari kita cari solusi bersama.',
        ])->assertRedirect();

        $curhat->refresh();
        $this->assertSame('dibalas', $curhat->status);

        $this->post('/dashboard/curhat/'.$curhat->id.'/tangani')->assertRedirect();

        $this->assertDatabaseMissing('curhats', ['id' => $curhat->id]);
        $this->assertDatabaseMissing('balasans', ['curhat_id' => $curhat->id]);
    }

    public function test_student_can_send_curhat_to_peer_counselor(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'identifier' => '2024006',
        ]);

        $konselor = User::factory()->konselorSebaya()->create();

        $this->actingAs($student);

        $this->post('/curhat', [
            'penerima_id' => $konselor->id,
            'kategori' => 'pribadi',
            'pesan' => 'Saya merasa tidak nyaman di kelas akhir-akhir ini.',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('curhats', [
            'siswa_id' => $student->id,
            'penerima_id' => $konselor->id,
            'tujuan' => 'konselor sebaya',
            'status' => 'terkirim',
        ]);
    }

    public function test_peer_counselor_and_guru_cannot_send_curhat(): void
    {
        $konselor = User::factory()->konselorSebaya()->create();
        $guru = User::factory()->create(['role' => 'guru', 'identifier' => 'guru-4']);
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024011']);

        $this->actingAs($konselor);

        $this->get('/curhat')->assertForbidden();
        $this->post('/curhat', [
            'penerima_id' => $guru->id,
            'kategori' => 'pribadi',
            'pesan' => 'Ini tidak seharusnya bisa dikirim oleh konselor.',
        ])->assertForbidden();

        $this->actingAs($guru);

        $this->get('/curhat')->assertForbidden();
        $this->post('/curhat', [
            'penerima_id' => $siswa->id,
            'kategori' => 'pribadi',
            'pesan' => 'Ini tidak seharusnya bisa dikirim oleh guru.',
        ])->assertForbidden();
    }

    public function test_peer_counselor_can_reply_and_close_curhat(): void
    {
        $sender = User::factory()->create(['role' => 'siswa', 'identifier' => '2024007']);
        $konselor = User::factory()->konselorSebaya()->create();

        $curhat = Curhat::create([
            'siswa_id' => $sender->id,
            'penerima_id' => $konselor->id,
            'tujuan' => 'konselor sebaya',
            'kategori' => 'sosial',
            'status' => 'terkirim',
        ]);

        $this->actingAs($konselor);

        $this->get('/dashboard')->assertOk()->assertSee('Konselor Sebaya');

        $this->post('/dashboard/curhat/'.$curhat->id.'/balas', [
            'isi' => 'Kamu tidak sendiri, ceritakan lebih lanjut ya.',
        ])->assertRedirect();

        $curhat->refresh();
        $this->assertSame('dibalas', $curhat->status);
        $this->assertDatabaseHas('balasans', [
            'curhat_id' => $curhat->id,
            'pengirim_id' => $konselor->id,
            'pengirim_role' => 'konselor sebaya',
        ]);

        $this->post('/dashboard/curhat/'.$curhat->id.'/tangani')->assertRedirect();

        $this->assertDatabaseMissing('curhats', ['id' => $curhat->id]);
        $this->assertDatabaseMissing('balasans', ['curhat_id' => $curhat->id]);
    }

    public function test_peer_counselor_cannot_access_admin_panel(): void
    {
        $konselor = User::factory()->konselorSebaya()->create();

        $this->actingAs($konselor);

        $this->get('/admin')->assertForbidden();
    }

    public function test_peer_counselor_can_register_and_lands_on_inbox(): void
    {
        $this->post('/register', [
            'name' => 'Konselor Sebaya Tingkat 7',
            'email' => 'konselor7@test.local',
            'identifier' => 'KON-700',
            'password' => 'secret99',
            'password_confirmation' => 'secret99',
            'role' => 'konselor sebaya',
        ])->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', [
            'email' => 'konselor7@test.local',
            'role' => 'konselor sebaya',
        ]);
    }

    public function test_guest_trying_to_open_restricted_page_sets_intended_url()
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_peer_counselor_login_ignores_stale_intended_url()
    {
        $konselor = User::factory()->konselorSebaya()->create([
            'password' => 'password123',
        ]);

        $this->withSession(['url.intended' => '/curhat'])
            ->post('/login', [
                'email' => $konselor->email,
                'password' => 'password123',
            ])
            ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($konselor);
    }

    public function test_guru_login_lands_on_dashboard()
    {
        $guru = User::factory()->create(['role' => 'guru', 'identifier' => 'guru-5']);

        $this->post('/login', [
            'email' => $guru->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($guru);
    }

    public function test_student_login_lands_on_curhat()
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024012']);

        $this->post('/login', [
            'email' => $siswa->email,
            'password' => 'password',
        ])->assertRedirect('/curhat');

        $this->assertAuthenticatedAs($siswa);
    }

    public function test_student_can_reply_in_ongoing_curhat(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024013']);
        $konselor = User::factory()->konselorSebaya()->create();

        $curhat = Curhat::create([
            'siswa_id' => $siswa->id,
            'penerima_id' => $konselor->id,
            'tujuan' => 'konselor sebaya',
            'kategori' => 'pribadi',
            'status' => 'dibalas',
        ]);

        $this->actingAs($siswa);

        $this->post('/dashboard/curhat/'.$curhat->id.'/balas', [
            'isi' => 'Terima kasih sudah mau mendengar.',
        ])->assertRedirect();

        $this->assertDatabaseHas('balasans', [
            'curhat_id' => $curhat->id,
            'pengirim_id' => $siswa->id,
            'pengirim_role' => 'siswa',
            'isi' => 'Terima kasih sudah mau mendengar.',
        ]);

        $curhat->refresh();
        $this->assertSame('dibalas', $curhat->status);
    }

    public function test_student_can_reply_back_and_forth_on_dashboard_page(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024014']);
        $guru = User::factory()->create(['role' => 'guru', 'identifier' => 'guru-6']);

        $curhat = Curhat::create([
            'siswa_id' => $siswa->id,
            'penerima_id' => $guru->id,
            'tujuan' => 'guru',
            'kategori' => 'belajar',
            'status' => 'dibalas',
        ]);

        $this->actingAs($siswa);

        $this->get('/dashboard/'.$curhat->id)
            ->assertOk()
            ->assertSee('Kirim Balasan');
    }

    public function test_student_cannot_reply_after_curhat_handled(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024015']);
        $guru = User::factory()->create(['role' => 'guru', 'identifier' => 'guru-7']);

        $curhat = Curhat::create([
            'siswa_id' => $siswa->id,
            'penerima_id' => $guru->id,
            'tujuan' => 'guru',
            'kategori' => 'karier',
            'status' => 'terkirim',
        ]);

        $this->actingAs($guru);

        $this->post('/dashboard/curhat/'.$curhat->id.'/tangani')->assertRedirect();

        $this->assertDatabaseMissing('curhats', ['id' => $curhat->id]);

        $this->actingAs($siswa);

        $this->get('/dashboard/'.$curhat->id)->assertRedirect(route('dashboard'));

        $this->post('/dashboard/curhat/'.$curhat->id.'/balas', [
            'isi' => 'Boleh satu pesan lagi?',
        ])->assertRedirect(route('dashboard'));
    }

    public function test_guru_handled_curhat_is_deleted_from_list(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024016']);
        $guru = User::factory()->create(['role' => 'guru', 'identifier' => 'guru-8']);

        $curhat = Curhat::create([
            'siswa_id' => $siswa->id,
            'penerima_id' => $guru->id,
            'tujuan' => 'guru',
            'kategori' => 'sosial',
            'status' => 'terkirim',
        ]);

        $this->actingAs($guru);

        $this->post('/dashboard/curhat/'.$curhat->id.'/tangani')->assertRedirect();

        $this->assertDatabaseMissing('curhats', ['id' => $curhat->id]);

        $this->get('/admin')
            ->assertOk()
            ->assertDontSee('Saya merasa canggung saat presentasi di depan kelas.');

        $this->get('/admin/curhat/'.$curhat->id)->assertRedirect(route('dashboard'));
    }

    public function test_peer_counselor_handled_curhat_is_deleted_from_dashboard(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024017']);
        $konselor = User::factory()->konselorSebaya()->create();

        $curhat = Curhat::create([
            'siswa_id' => $siswa->id,
            'penerima_id' => $konselor->id,
            'tujuan' => 'konselor sebaya',
            'kategori' => 'pribadi',
            'status' => 'terkirim',
        ]);

        $this->actingAs($konselor);

        $this->post('/dashboard/curhat/'.$curhat->id.'/tangani')->assertRedirect();

        $this->assertDatabaseMissing('curhats', ['id' => $curhat->id]);

        $this->get('/dashboard')
            ->assertOk()
            ->assertDontSee('Saya ingin membicarakan tekanan dari keluarga.');

        $this->get('/dashboard/'.$curhat->id)->assertRedirect(route('dashboard'));
    }

    public function test_user_auto_logged_out_after_10_minutes_idle(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024018']);

        $this->actingAs($siswa);

        $this->get('/dashboard')->assertOk();

        $this->withSession(['last_activity_at' => now()->subMinutes(11)->timestamp]);

        $this->get('/dashboard')
            ->assertRedirect('/login')
            ->assertSessionHas('warning');

        $this->assertGuest();
    }
}
