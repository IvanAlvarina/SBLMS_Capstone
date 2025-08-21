@extends('Layouts.vuexy')

@section('title', 'Dashboard')

@section('content')

<div class="row">
    @php
        $cards = [
            ['id' => 'total', 'title' => 'Total Books', 'count' => $totalBooks, 'color' => 'primary', 'icon' => 'bi-book'],
            ['id' => 'available', 'title' => 'Available Books', 'count' => $availableBooks, 'color' => 'success', 'icon' => 'bi-check-circle'],
            ['id' => 'borrowed', 'title' => 'Borrowed Books', 'count' => $borrowedBooks, 'color' => 'warning', 'icon' => 'bi-arrow-left-circle'],
            ['id' => 'reserved', 'title' => 'Reserved Books', 'count' => $reservedBooks, 'color' => 'info', 'icon' => 'bi-clock'],
            ['id' => 'removed', 'title' => 'Removed Books', 'count' => $removedBooks, 'color' => 'secondary', 'icon' => 'bi-trash'],
        ];
    @endphp

    @foreach ($cards as $card)
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card text-center clickable-card" data-type="{{ $card['id'] }}" style="cursor:pointer;">
            <div class="card-body">
                <h5 class="card-title">{{ $card['title'] }}</h5>
                <h2 class="text-{{ $card['color'] }}">
                    <span class="badge bg-{{ $card['color'] }} me-2">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </span>
                    {{ $card['count'] }}
                </h2>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailsModalLabel">Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="modalContent">
          <p class="text-center">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('vendor-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
@endpush

@push('vendor-scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@endpush

@push('page-scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    const modalTitle = document.getElementById('detailsModalLabel');
    const modalContent = document.getElementById('modalContent');

    document.querySelectorAll('.clickable-card').forEach(card => {
        card.addEventListener('click', function () {
            const type = this.getAttribute('data-type');
            modalTitle.textContent = this.querySelector('.card-title').textContent;
            modalContent.innerHTML = '<p class="text-center">Loading...</p>';

            modal.show();

            fetch(`/dashboard/books-data?type=${type}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if(data.books.length === 0){
                            modalContent.innerHTML = '<p class="text-center">No records found.</p>';
                            return;
                        }

                        let html = `
                            <table id="modalBooksTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Book ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                        data.books.forEach(book => {
                            html += `
                                <tr>
                                    <td>${book.book_id}</td>
                                    <td>${book.book_title}</td>
                                    <td>${book.book_author}</td>
                                    <td>${book.book_status}</td>
                                </tr>`;
                        });

                        html += '</tbody></table>';
                        modalContent.innerHTML = html;

                        // Initialize DataTable
                        $('#modalBooksTable').DataTable({
                            pageLength: 10,
                            lengthChange: false,
                            searching: false,
                            ordering: false
                        });

                    } else {
                        modalContent.innerHTML = '<p class="text-danger text-center">Failed to load data.</p>';
                    }
                })
                .catch(() => {
                    modalContent.innerHTML = '<p class="text-danger text-center">Error fetching data.</p>';
                });
        });
    });
});
</script>
@endpush
