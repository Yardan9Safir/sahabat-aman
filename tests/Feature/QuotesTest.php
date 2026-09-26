<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\Setting;
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

    protected function makeKonselor(): User
    {
        return User::factory()->konselorSebaya()->create();
    }

    protected function makeQuote(User $pembuat, array $overrides = []): Quote
    {
        return Quote::create(array_merge([
            'id_pembuat' => $pembuat->id,
            'judul_quotes' => 'Judul Quotes',
            'category_quotes' => 'motivasi',
            'isi_quotes' => 'Kamu lebih kuat dari yang kamu kira hari ini.',
            'is_daily' => false,
        ], $overrides));
    }

    public function test_siswa_can_create_quote(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024999']);

        $this->actingAs($siswa)->post('/quotes', [
            'judul_quotes' => 'Pelan Itu Oke',
            'category_quotes' => 'pribadi',
            'isi_quotes' => 'Tidak apa-apa pelan-pelan, yang penting jangan berhenti.',
        ])->assertRedirect('/quotes');

        $this->assertDatabaseHas('quotes', [
            'judul_quotes' => 'Pelan Itu Oke',
            'category_quotes' => 'pribadi',
            'id_pembuat' => $siswa->id,
            'is_daily' => 0,
        ]);
    }

    public function test_konselor_sebaya_can_create_quote(): void
    {
        $konselor = $this->makeKonselor();

        $this->actingAs($konselor)->post('/quotes', [
            'judul_quotes' => 'Amanutely Saja',
            'category_quotes' => 'sosial',
            'isi_quotes' => 'Ceritakan yang kamu rasa, tidak ada yang akan menghakimi.',
        ])->assertRedirect('/quotes');

        $this->assertDatabaseHas('quotes', [
            'judul_quotes' => 'Amanutely Saja',
            'id_pembuat' => $konselor->id,
        ]);
    }

    public function test_guru_can_create_quote_and_set_daily(): void
    {
        $guru = $this->makeGuru();

        $this->actingAs($guru)->post('/quotes', [
            'judul_quotes' => 'Tetap Semangat',
            'category_quotes' => 'belajar',
            'isi_quotes' => 'Setiap langkah kecil membawamu lebih dekat ke tujuan.',
            'is_daily' => '1',
        ])->assertRedirect('/quotes');

        $this->assertDatabaseHas('quotes', [
            'judul_quotes' => 'Tetap Semangat',
            'category_quotes' => 'belajar',
            'id_pembuat' => $guru->id,
            'is_daily' => 1,
        ]);
    }

    public function test_siswa_cannot_make_quote_daily_by_sending_is_daily(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024888']);

        $this->actingAs($siswa)->post('/quotes', [
            'judul_quotes' => 'Mau Jadi Hari Ini',
            'category_quotes' => 'motivasi',
            'isi_quotes' => 'Siswa tidak boleh mengatur kata-kata hari ini sendiri.',
            'is_daily' => '1',
        ])->assertRedirect('/quotes');

        $this->assertDatabaseHas('quotes', [
            'judul_quotes' => 'Mau Jadi Hari Ini',
            'is_daily' => 0,
        ]);
    }

    public function test_siswa_can_edit_and_delete_own_quote_only(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024777']);
        $lain = User::factory()->create(['role' => 'siswa', 'identifier' => '2024666']);

        $milik = $this->makeQuote($siswa);
        $milikOrangLain = $this->makeQuote($lain, ['judul_quotes' => 'Quote Orang Lain']);

        $this->actingAs($siswa)
            ->get('/quotes/'.$milikOrangLain->id_quotes.'/edit')
            ->assertForbidden();

        $this->actingAs($siswa)
            ->put('/quotes/'.$milikOrangLain->id_quotes, [
                'judul_quotes' => 'Dibajak',
                'category_quotes' => 'motivasi',
                'isi_quotes' => 'Percobaan membajak quote orang lain.',
            ])
            ->assertForbidden();

        $this->actingAs($siswa)
            ->delete('/quotes/'.$milikOrangLain->id_quotes)
            ->assertForbidden();

        $this->assertDatabaseHas('quotes', ['id_quotes' => $milikOrangLain->id_quotes, 'judul_quotes' => 'Quote Orang Lain']);

        $this->actingAs($siswa)
            ->put('/quotes/'.$milik->id_quotes, [
                'judul_quotes' => 'Judul Baru',
                'category_quotes' => 'karier',
                'isi_quotes' => 'Isi quotes yang sudah diperbarui sepenuhnya.',
            ])
            ->assertRedirect('/quotes');

        $this->assertDatabaseHas('quotes', [
            'id_quotes' => $milik->id_quotes,
            'judul_quotes' => 'Judul Baru',
            'category_quotes' => 'karier',
        ]);

        $this->actingAs($siswa)
            ->delete('/quotes/'.$milik->id_quotes)
            ->assertRedirect('/quotes');

        $this->assertDatabaseMissing('quotes', ['id_quotes' => $milik->id_quotes]);
    }

    public function test_guru_can_manage_anyones_quote(): void
    {
        $guru = $this->makeGuru();
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024555']);
        $quote = $this->makeQuote($siswa);

        $this->actingAs($guru)->get('/quotes/'.$quote->id_quotes.'/edit')->assertOk();

        $this->actingAs($guru)
            ->delete('/quotes/'.$quote->id_quotes)
            ->assertRedirect('/quotes');

        $this->assertDatabaseMissing('quotes', ['id_quotes' => $quote->id_quotes]);
    }

    public function test_only_guru_can_set_daily_quote(): void
    {
        $guru = $this->makeGuru();
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024444']);
        $konselor = $this->makeKonselor();
        $quote = $this->makeQuote($siswa);

        $this->actingAs($siswa)->post('/admin/quotes/'.$quote->id_quotes.'/harian')->assertForbidden();
        $this->actingAs($konselor)->post('/admin/quotes/'.$quote->id_quotes.'/harian')->assertForbidden();
        $this->assertFalse($quote->fresh()->is_daily);

        $this->actingAs($guru)
            ->post('/admin/quotes/'.$quote->id_quotes.'/harian')
            ->assertRedirect('/quotes');

        $this->assertTrue($quote->fresh()->is_daily);
    }

    public function test_selecting_daily_quote_unsets_previous_one(): void
    {
        $guru = $this->makeGuru();
        $first = $this->makeQuote($guru, ['is_daily' => true]);
        $second = $this->makeQuote($guru, ['judul_quotes' => 'Quote Kedua']);

        $this->actingAs($guru)
            ->post('/admin/quotes/'.$second->id_quotes.'/harian')
            ->assertRedirect('/quotes');

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

    public function test_all_roles_can_open_quotes_index_with_category_filter(): void
    {
        $guru = $this->makeGuru();
        $konselor = $this->makeKonselor();
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024333']);

        $this->makeQuote($guru, ['judul_quotes' => 'Quote Motivasi']);
        $this->makeQuote($konselor, ['judul_quotes' => 'Quote Karier', 'category_quotes' => 'karier']);
        $this->makeQuote($siswa, ['judul_quotes' => 'Quote Siswa', 'category_quotes' => 'sosial']);

        foreach ([$guru, $konselor, $siswa] as $user) {
            $this->actingAs($user)->get('/quotes')
                ->assertOk()
                ->assertSee('Quotes & Kata-Kata Hari Ini', false)
                ->assertSee('Quote Motivasi')
                ->assertSee('Quote Karier')
                ->assertSee('Quote Siswa');

            $this->actingAs($user)->get('/quotes?category=karier')
                ->assertOk()
                ->assertSee('Quote Karier')
                ->assertDontSee('Quote Motivasi');
        }
    }

    public function test_quotes_index_can_filter_own_quotes(): void
    {
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024222']);
        $this->makeQuote($siswa, ['judul_quotes' => 'Quote Milik Saya']);
        $this->makeQuote(User::factory()->create(['role' => 'siswa', 'identifier' => '2024111']), [
            'judul_quotes' => 'Quote Orang Lain',
        ]);

        $this->actingAs($siswa)->get('/quotes?mine=1')
            ->assertOk()
            ->assertSee('Quote Milik Saya')
            ->assertDontSee('Quote Orang Lain');
    }

    public function test_only_guru_can_manage_login_display(): void
    {
        $guru = $this->makeGuru();
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2024000']);
        $konselor = $this->makeKonselor();
        $quote = $this->makeQuote($siswa);

        $this->actingAs($siswa)->get('/admin/tampilan-login')->assertForbidden();
        $this->actingAs($konselor)->get('/admin/tampilan-login')->assertForbidden();

        $this->actingAs($siswa)->put('/admin/tampilan-login', [
            'enabled' => '1',
            'pilihan' => [$quote->id_quotes],
        ])->assertForbidden();

        $this->assertFalse($quote->fresh()->tampil_di_login);

        $this->actingAs($guru)->get('/admin/tampilan-login')->assertOk();
    }

    public function test_guru_can_choose_up_to_three_quotes_for_login_page(): void
    {
        $guru = $this->makeGuru();
        $siswa = User::factory()->create(['role' => 'siswa', 'identifier' => '2023888']);

        $satu = $this->makeQuote($guru, ['isi_quotes' => 'Quote pertama untuk halaman login.']);
        $dua = $this->makeQuote($siswa, ['isi_quotes' => 'Quote kedua dari siswa.']);
        $tiga = $this->makeQuote($guru, ['isi_quotes' => 'Quote ketiga dari guru.']);
        $empat = $this->makeQuote($siswa, ['isi_quotes' => 'Quote keempat tidak boleh tampil.']);

        $this->actingAs($guru)->put('/admin/tampilan-login', [
            'enabled' => '1',
            'pilihan' => [$tiga->id_quotes, $satu->id_quotes, $dua->id_quotes],
            'urutan' => [
                $satu->id_quotes => 2,
                $dua->id_quotes => 3,
                $tiga->id_quotes => 1,
            ],
        ])->assertRedirect('/admin/tampilan-login');

        $this->assertSame(1, $tiga->fresh()->urutan_login);
        $this->assertSame(2, $satu->fresh()->urutan_login);
        $this->assertSame(3, $dua->fresh()->urutan_login);
        $this->assertFalse($empat->fresh()->tampil_di_login);

        $this->post('/logout');

        $response = $this->get('/login');
        $response->assertOk()
            ->assertSee('Quote ketiga dari guru.', false)
            ->assertSee('Quote pertama untuk halaman login.', false)
            ->assertSee('Quote kedua dari siswa.', false)
            ->assertDontSee('Quote keempat tidak boleh tampil.', false);

        $this->assertSame(
            ['Quote ketiga dari guru.', 'Quote pertama untuk halaman login.', 'Quote kedua dari siswa.'],
            $this->loginQuoteTexts($response->getContent())
        );
    }

    public function test_login_display_rejects_more_than_three_quotes(): void
    {
        $guru = $this->makeGuru();
        $ids = collect(range(1, 4))->map(fn () => $this->makeQuote($guru)->id_quotes)->all();

        $this->actingAs($guru)
            ->put('/admin/tampilan-login', ['enabled' => '1', 'pilihan' => $ids])
            ->assertSessionHasErrors('pilihan');

        $this->assertSame(0, Quote::where('tampil_di_login', true)->count());
    }

    public function test_login_display_toggle_hides_quotes_on_login_page(): void
    {
        $guru = $this->makeGuru();
        $this->makeQuote($guru, [
            'isi_quotes' => 'Quote yang harusnya disembunyikan.',
            'is_daily' => true,
            'tampil_di_login' => true,
            'urutan_login' => 1,
        ]);

        Setting::put(Setting::LOGIN_QUOTE_ENABLED, '0');

        $this->get('/login')
            ->assertOk()
            ->assertDontSee('Quote yang harusnya disembunyikan.', false);
    }

    /**
     * Urut teks quote sesuai urutan slide di HTML halaman login.
     */
    protected function loginQuoteTexts(string $html): array
    {
        preg_match_all('/data-quote-slide="\d+".*?&ldquo;(.*?)&rdquo;/s', $html, $matches);

        return $matches[1];
    }
}
