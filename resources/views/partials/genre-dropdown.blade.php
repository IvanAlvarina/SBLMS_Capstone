@php
    $genres = [
        'Filipiniana',
        'Fiction',
        'General Reference',
        'Periodical'
    ];

    // Check if user is super-admin
    $isSuperAdmin = auth()->check() && auth()->user()->hasRole('super-admin');
@endphp

<div class="input-group">
    <select name="book_genre" class="form-control" id="book_genre_select" required>
        <option value="">Select Genre</option>
        @foreach($genres as $genre)
            <option value="{{ $genre }}"
                @if(old('book_genre', $selectedGenre ?? '') == $genre) selected @endif>
                {{ $genre }}
            </option>
        @endforeach
    </select>
    @if($isSuperAdmin)
        <button type="button" class="btn btn-outline-primary" id="add-genre-btn" title="Add New Genre">
            <i class="ti ti-plus"></i>
        </button>
    @endif
</div>

@if($isSuperAdmin)
<!-- Add Genre Modal -->
<div class="modal fade" id="addGenreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Genre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="new_genre" class="form-label">Genre Name</label>
          <input type="text" class="form-control" id="new_genre" placeholder="Enter new genre">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="save-genre-btn">Add Genre</button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('add-genre-btn').addEventListener('click', function() {
    new bootstrap.Modal(document.getElementById('addGenreModal')).show();
});

document.getElementById('save-genre-btn').addEventListener('click', function() {
    const newGenre = document.getElementById('new_genre').value.trim();
    if (!newGenre) {
        Swal.fire('Error', 'Please enter a genre name.', 'error');
        return;
    }

    // Add to select options
    const select = document.getElementById('book_genre_select');
    const option = document.createElement('option');
    option.value = newGenre;
    option.text = newGenre;
    option.selected = true;
    select.appendChild(option);

    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('addGenreModal')).hide();
    document.getElementById('new_genre').value = '';

    Swal.fire('Success', 'Genre added successfully!', 'success');
});
</script>
@endif
