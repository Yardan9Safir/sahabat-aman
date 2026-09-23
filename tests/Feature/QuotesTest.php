<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotesTest extends TestCase
{
    use RefreshDatabase;

    protected function makeGuru(): User
    {
        return User::factory()->create(['role' => 'guru', 'identifier' => 'guru-quote-1']);
    }

    protected function makeQuote(User $guru, array $overrides = []): Quote
    {
        return Quote::create(array_merge([
            'id_pembuat' => $guru->id,
            'judul_quotes' => 'Judul Quotes',
            'category_quotes' => 'motivasi',
            'isi_quotes' => 'Kamu lebih kuat dari yang kamu kira hari ini.',
            'is_daily' => false,
        ], $overrides));
    }

    public function test_guru_can_create_quote(): void
    {
        $guru = $this->makeGuru();

        $response = $this->actingAs($guru)->post('/admin/quotes', [
            'judul_quotes' => 'Tetap Semangat',
            'category_quotes' => 'belajar',
            'isi_quotes' => 'Setiap langkah kecil membawamu lebih dekat ke tujuan.',
            'is_daily' => '1',
        ]);

        $response->assertRedirect('/admin/quotes');

        $this->assertDatabaseHas('quotes', [
            'judul_quotes' => 'Tetap Semangat',
            'category_quotes' => 'belajar',
            'id_pembuat' => $guru->id,
            'is_daily' => 1,
        ]);
    }

    public function test_siswa_cannot_access_quotes_crud(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024999']);

        $this->actingAs($siswa)->get('/admin/quotes')->assertForbidden();
        $this->actingAs($siswa)->post('/admin/quotes', [
            'judul_quotes' => 'Pencurian Hak',
            'category_quotes' => 'motivasi',
            'isi_quotes' => 'Siswa seharusnya tidak bisa membuat quotes.',
        ])->assertForbidden();
    }

    public function test_guru_can_update_and_delete_quote(): void
    {
        $guru = $this->makeGuru();
        $quote = $this->makeQuote($guru);

        $this->actingAs($guru)
            ->put('/admin/quotes/'.$quote->id_quotes, [
                'judul_quotes' => 'Judul Baru',
                'category_quotes' => 'karier',
                'isi_quotes' => 'Isi quotes yang sudah diperbarui sepenuhnya.',
            ])
            ->assertRedirect('/admin/quotes');

        $this->assertDatabaseHas('quotes', [
            'id_quotes' => $quote->id_quotes,
            'judul_quotes' => 'Judul Baru',
            'category_quotes' => 'karier',
        ]);

        $this->actingAs($guru)
            ->delete('/admin/quotes/'.$quote->id_quotes)
            ->assertRedirect('/admin/quotes');

        $this->assertDatabaseMissing('quotes', ['id_quotes' => $quote->id_quotes]);
    }

    public function test_selecting_daily_quote_unsets_previous_one(): void
    {
        $guru = $this->makeGuru();
        $first = $this->makeQuote($guru, ['is_daily' => true]);
        $second = $this->makeQuote($guru, ['judul_quotes' => 'Quote Kedua']);

        $this->actingAs($guru)
            ->post('/admin/quotes/'.$second->id_quotes.'/harian')
            ->assertRedirect('/admin/quotes');

        $this->assertFalse($first->fresh()->is_daily);
        $this->assertTrue($second->fresh()->is_daily);
    }

    public function test_daily_quote_shows_on_landing_and_login_page(): void
    {
        $guru = $this->makeGuru();
        $this->makeQuote($guru, [
            'judul_quotes' => 'Kata Hari Ini',
            'isi_quotes' => 'Bercerita itu berani, bukan lemah.',
            'is_daily' => true,
        ]);

        $this->get('/')->assertOk()->assertSee('Bercerita itu berani, bukan lemah.', false);
        $this->get('/login')->assertOk()->assertSee('Bercerita itu berani, bukan lemah.', false);
    }

    public function test_guru_can_view_quotes_grid_with_category_filter(): void
    {
        $guru = $this->makeGuru();
        $this->makeQuote($guru, ['judul_quotes' => 'Quote Motivasi']);
        $this->makeQuote($guru, ['judul_quotes' => 'Quote Karier', 'category_quotes' => 'karier']);

        $this->actingAs($guru)->get('/admin/quotes')
            ->assertOk()
            ->assertSee('Quotes & Kata-Kata Hari Ini', false)
            ->assertSee('Quote Motivasi')
            ->assertSee('Quote Karier');

        $this->actingAs($guru)->get('/admin/quotes?category=karier')
            ->assertOk()
            ->assertSee('Quote Karier')
            ->assertDontSee('Quote Motivasi');
    }
}