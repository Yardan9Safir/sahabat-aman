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

        $this->get('/admin')->assertOk()->assertSee('Data Curhat');
        $this->get('/admin/curhat/'.$curhat->id)->assertOk()->assertSee('Detail Curhat');
        $this->get('/admin/siswa')->assertOk()->assertSee($student->name);
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
}
