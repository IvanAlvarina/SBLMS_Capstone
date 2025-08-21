@extends('Layouts.vuexy')

@section('title', 'Dashboard')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Users Stats</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Faculty -->
                    <div class="col-md-6 mb-3">
                        <div class="card text-center clickable-card" data-type="faculty" style="cursor:pointer;">
                            <div class="card-body">
                                <h5 class="card-title">Faculty</h5>
                                <h2 class="text-primary">
                                    <span class="badge bg-primary me-2">
                                        <i class="bi bi-people-fill"></i>
                                    </span>
                                    {{ $facultyCount }}
                                </h2>
                            </div>
                        </div>
                    </div>

                    <!-- Students -->
                    <div class="col-md-6 mb-3">
                        <div class="card text-center clickable-card" data-type="student" style="cursor:pointer;">
                            <div class="card-body">
                                <h5 class="card-title">Students</h5>
                                <h2 class="text-success">
                                    <span class="badge bg-success me-2">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    {{ $studentCount }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Book Stats</h5>
            </div>
            <div class="card-body">
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
                    <div class="col-md-4 col-sm-6 mb-3">
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
            </div>
        </div>
    </div>
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
<style>
.clickable-card:hover {
    box-shadow: 0 0 12px rgba(0, 123, 255, 0.5);
    transform: translateY(-4px);
    transition: all 0.3s ease;
}
</style>
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

            let url;
            let isUser = false;

            // Determine which endpoint to call based on the type clicked
            if (['faculty', 'student'].includes(type)) {
                url = `/dashboard/users-data?type=${type}`;
                isUser = true;
            } else {
                url = `/dashboard/books-data?type=${type}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if(data.data.length === 0){
                            modalContent.innerHTML = '<p class="text-center">No records found.</p>';
                            return;
                        }

                        let html = '';

                        if (isUser) {
                            // User table
                            html = `
                                <table id="modalTable" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;

                            data.data.forEach(user => {
                                html += `
                                    <tr>
                                        <td>${user.id}</td>
                                        <td>${user.fullname}</td>
                                        <td>${user.email}</td>
                                        <td>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                                    </tr>
                                `;
                            });

                            html += '</tbody></table>';

                        } else {
                            // Book table
                            html = `
                                <table id="modalTable" class="table table-striped" style="width:100%">
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

                            data.data.forEach(book => {
                                html += `
                                    <tr>
                                        <td>${book.book_id}</td>
                                        <td>${book.book_title}</td>
                                        <td>${book.book_author}</td>
                                        <td>${book.book_status}</td>
                                    </tr>
                                `;
                            });

                            html += '</tbody></table>';
                        }

                        modalContent.innerHTML = html;

                        // Initialize DataTable
                        $('#modalTable').DataTable({
                            pageLength: 10,
                            lengthChange: false,
                            searching: false,
                            ordering: false,
                            destroy: true // allow re-init
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
