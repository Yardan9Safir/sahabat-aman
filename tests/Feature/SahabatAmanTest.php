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

        $this->actingAs($student);

        $response = $this->post('/curhat', [
            'tujuan' => 'teman',
            'kategori' => 'pribadi',
            'pesan' => 'Saya merasa cemas saat ujian.',
            'anonim' => true,
        ]);

        $response->assertRedirect('/riwayat');
        $this->assertDatabaseHas('curhats', [
            'siswa_id' => $student->id,
            'tujuan' => 'teman',
            'status' => 'terkirim',
        ]);

        $this->get('/riwayat')->assertOk();
    }

    public function test_peer_counselor_can_reply_and_mark_seen(): void
    {
        $student = User::factory()->create(['role' => 'siswa', 'identifier' => '2024002']);
        $peer = User::factory()->create(['role' => 'teman', 'identifier' => 'peer-1']);

        $curhat = Curhat::create([
            'siswa_id' => $student->id,
            'tujuan' => 'teman',
            'kategori' => 'sosial',
            'pesan' => 'Saya sedang kesulitan bergaul di kelas.',
            'anonim' => true,
            'status' => 'terkirim',
        ]);

        $this->actingAs($peer);

        $this->post('/dashboard/curhat/' . $curhat->id . '/balas', [
            'isi' => 'Kamu tidak sendiri, ayo ceritakan lebih detail.',
        ])->assertRedirect('/dashboard');

        $curhat->refresh();
        $this->assertSame('dibalas', $curhat->status);
        $this->assertDatabaseHas('balasans', [
            'curhat_id' => $curhat->id,
            'pengirim_id' => $peer->id,
        ]);
    }
}
