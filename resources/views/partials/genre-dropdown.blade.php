@php
    $genres = [
        'Fiction',
        'Non-Fiction',
        'Science Fiction',
        'Fantasy',
        'Biography',
        'History',
        'Mystery',
        'Romance',
        'Thriller',
        'Self-Help',
        'Children',
        'Technology',
        'Other'
    ];
@endphp

<select name="book_genre" class="form-control" required>
    <option value="">Select Genre</option>
    @foreach($genres as $genre)
        <option value="{{ $genre }}"
            @if(old('book_genre', $selectedGenre ?? '') == $genre) selected @endif>
            {{ $genre }}
        </option>
    @endforeach
</select>
