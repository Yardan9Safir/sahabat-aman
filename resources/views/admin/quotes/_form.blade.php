@php
    $quote = $quote ?? null;
@endphp

<div class="bg-surface-container-lowest p-6 rounded-3xl soft-shadow border border-surface-variant/40">
    <form method="POST" action="{{ $action }}" class="flex flex-col gap-5">
        @csrf
        @method($method)

        <div>
            <label for="judul_quotes" class="text-label-lg font-bold text-on-surface mb-2 block">Judul Quotes</label>
            <input type="text" name="judul_quotes" id="judul_quotes" value="{{ old('judul_quotes', $quote?->judul_quotes) }}" required
                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none"
                placeholder="cth. Temukan Kekuatan di Balik Rasa Lelah">
            @error('judul_quotes')
                <p class="mt-2 text-xs text-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category_quotes" class="text-label-lg font-bold text-on-surface mb-2 block">Kategori</label>
            <select name="category_quotes" id="category_quotes" required
                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none cursor-pointer">
                @foreach(\App\Models\Quote::CATEGORIES as $category)
                <option value="{{ $category }}" {{ old('category_quotes', $quote?->category_quotes) === $category ? 'selected' : '' }}>
                    {{ ucfirst($category) }}
                </option>
                @endforeach
            </select>
            @error('category_quotes')
                <p class="mt-2 text-xs text-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="isi_quotes" class="text-label-lg font-bold text-on-surface mb-2 block">Isi Quotes</label>
            <textarea name="isi_quotes" id="isi_quotes" rows="5" required
                class="w-full bg-surface-container-lowest border-1.5 border-outline-variant rounded-2xl p-4 text-on-surface placeholder:text-outline/70 focus:border-primary-container focus:ring-4 focus:ring-primary/10 transition-all outline-none resize-y"
                placeholder="Tulis kata-kata bijak untuk dijadikan penyemangat hari ini...">{{ old('isi_quotes', $quote?->isi_quotes) }}</textarea>
            @error('isi_quotes')
                <p class="mt-2 text-xs text-error">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-3 p-4 rounded-2xl border border-outline-variant/50 bg-surface-container-low/60 cursor-pointer select-none">
            <input type="checkbox" name="is_daily" value="1" id="is_daily"
                class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary/30"
                {{ old('is_daily', $quote?->is_daily) ? 'checked' : '' }}>
            <span class="flex flex-col">
                <span class="text-label-lg font-semibold text-on-surface">Jadikan sebagai kata-kata hari ini</span>
                <span class="text-label-sm text-on-surface-variant">Quotes ini akan tampil di halaman utama dan halaman login.</span>
            </span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <a href="{{ route('admin.quotes.index') }}" class="px-5 py-3 rounded-2xl bg-surface-container text-primary text-label-lg font-bold hover:bg-surface-variant transition-colors">Batal</a>
            <button type="submit" class="px-7 py-3.5 rounded-2xl bg-primary text-white text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">{{ $submitIcon ?? 'save' }}</span>
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</div>