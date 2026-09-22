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

        $response->assertRedirect('/riwayat');
        $this->assertDatabaseHas('curhats', [
            'siswa_id' => $student->id,
            'penerima_id' => $guru->id,
            'tujuan' => 'guru',
            'status' => 'terkirim',
        ]);

        $this->get('/riwayat')->assertOk();
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
            'pesan' => 'Saya sedang kesulitan bergaul di kelas.',
            'status' => 'terkirim',
        ]);

        $this->actingAs($guru);

        $this->get('/admin')->assertOk()->assertSee('Kotak Masuk Curhat Siswa');
        $this->get('/admin/curhat/'.$curhat->id)->assertOk()->assertSee('Detail Curhat');
        $this->get('/admin/siswa')->assertNotFound();
    }

    public function test_siswa_cannot_access_admin_panel(): void
    {
        $student = User::factory()->create(['role' => 'siswa', 'identifier' => '2024005']);

        $this->actingAs($student);

        $this->get('/admin')->assertForbidden();
    }

    public function test_siswa_recipient_can_reply(): void
    {
        $sender = User::factory()->create(['role' => 'siswa', 'identifier' => '2024003']);
        $recipient = User::factory()->create(['role' => 'siswa', 'identifier' => '2024004']);

        $curhat = Curhat::create([
            'siswa_id' => $sender->id,
            'penerima_id' => $recipient->id,
            'tujuan' => 'siswa',
            'kategori' => 'belajar',
            'pesan' => 'Saya bingung memilih jurusan kuliah.',
            'status' => 'terkirim',
        ]);

        $this->actingAs($recipient);

        $this->post('/dashboard/curhat/'.$curhat->id.'/balas', [
            'isi' => 'Coba konsultasikan ke guru BK ya.',
        ])->assertRedirect();

        $curhat->refresh();
        $this->assertSame('dibalas', $curhat->status);
    }

    public function test_konselor_can_access_inbox_but_not_admin_panel(): void
    {
        $student = User::factory()->create(['role' => 'siswa', 'identifier' => '2024006', 'kelas' => '7A']);
        $konselor = User::factory()->create(['role' => 'konselor', 'identifier' => 'KSL-7A-A', 'kelas' => '7A', 'anon_label' => 'A']);

        $curhat = Curhat::create([
            'siswa_id' => $student->id,
            'penerima_id' => $konselor->id,
            'tujuan' => 'konselor',
            'kategori' => 'sosial',
            'pesan' => 'Aku merasa kesepian di sekolah.',
            'status' => 'terkirim',
        ]);

        $this->actingAs($konselor);

        $this->get('/dashboard')->assertOk()->assertSee('Aku merasa kesepian di sekolah.');
        $this->get('/dashboard/'.$curhat->id)->assertOk();
        $this->get('/admin')->assertForbidden();
    }

    public function test_konselor_is_anonymous_to_students(): void
    {
        $student = User::factory()->create(['role' => 'siswa', 'identifier' => '2024007', 'kelas' => '7A']);
        $konselor = User::factory()->create(['role' => 'konselor', 'identifier' => 'KSL-7A-B', 'kelas' => '7A', 'anon_label' => 'B', 'name' => 'Rahasia Konselor']);

        $this->actingAs($student);

        $response = $this->get('/curhat');
        $response->assertOk();

        $response->assertSee('Konselor Sebaya B');
        $response->assertDontSee('Rahasia Konselor');

        $this->post('/curhat', [
            'penerima_id' => $konselor->id,
            'kategori' => 'pribadi',
            'pesan' => 'Saya ingin bercerita secara anonim.',
        ])->assertRedirect('/riwayat');

        $this->assertDatabaseHas('curhats', [
            'tujuan' => 'konselor',
            'nama_penerima' => 'Konselor Sebaya B',
        ]);
    }

    public function test_student_only_sees_konselor_from_own_class(): void
    {
        $student7A = User::factory()->create(['role' => 'siswa', 'identifier' => '2024008', 'kelas' => '7A']);
        $konselor7A = User::factory()->create(['role' => 'konselor', 'kelas' => '7A', 'anon_label' => 'A']);
        $konselor7B = User::factory()->create(['role' => 'konselor', 'kelas' => '7B', 'anon_label' => 'A']);

        $this->actingAs($student7A);

        $response = $this->get('/curhat')->assertOk();

        $response->assertSee('Konselor Sebaya A — Kelas 7A');
        $response->assertDontSee('Konselor Sebaya A — Kelas 7B');
        $this->assertTrue($konselor7B->kelas !== $student7A->kelas);
    }

    public function test_admin_and_dashboard_status_filters(): void
    {
        $student = User::factory()->create(['role' => 'siswa', 'identifier' => '2024009']);
        $guru = User::factory()->create(['role' => 'guru']);
        $konselor = User::factory()->create(['role' => 'konselor', 'kelas' => '7A', 'anon_label' => 'A']);

        Curhat::create(['siswa_id' => $student->id, 'penerima_id' => $guru->id, 'tujuan' => 'guru', 'kategori' => 'pribadi', 'pesan' => 'Pesan belum dibalas guru.', 'status' => 'terkirim']);
        Curhat::create(['siswa_id' => $student->id, 'penerima_id' => $konselor->id, 'tujuan' => 'konselor', 'kategori' => 'sosial', 'pesan' => 'Pesan on-going konselor.', 'status' => 'dibalas']);
        Curhat::create(['siswa_id' => $student->id, 'penerima_id' => $konselor->id, 'tujuan' => 'konselor', 'kategori' => 'belajar', 'pesan' => 'Pesan selesai konselor.', 'status' => 'ditangani']);

        $this->actingAs($guru);
        $this->get('/admin?status=belum_dibalas')->assertOk()->assertSee('Pesan belum dibalas guru.')
            ->assertDontSee('Pesan on-going konselor.');

        $this->actingAs($konselor);
        $this->get('/dashboard?status=ongoing')->assertOk()->assertSee('Pesan on-going konselor.')
            ->assertDontSee('Pesan belum dibalas guru.');
        $this->get('/dashboard?status=selesai')->assertOk()->assertSee('Pesan selesai konselor.');
    }
}
