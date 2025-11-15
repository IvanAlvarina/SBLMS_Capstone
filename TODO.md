# TODO: Add Super Admin Management for E-books, News & Magazines, and OER

## Overview
Add full CRUD functionality for super admin to manage URL links for e-books, news & magazines, and OER, similar to existing e-journals management.

## Tasks

### 1. Update Controllers
- [ ] Update EbooksController.php: Add list(), getEbookData(), addEbook(), store(), edit(), update(), destroy() methods
- [ ] Update NewsAndMagazineController.php: Add list(), getNewsData(), addNews(), store(), edit(), update(), destroy() methods
- [ ] Update OERController.php: Add list(), getOerData(), addOer(), store(), edit(), update(), destroy() methods

### 2. Create Views
- [ ] Create resources/views/Ebooks/EbooksListView.blade.php (management list with DataTables)
- [ ] Create resources/views/Ebooks/EbooksCreateView.blade.php (add/edit form)
- [ ] Create resources/views/NewsAndMagazine/NewsAndMagazineListView.blade.php
- [ ] Create resources/views/NewsAndMagazine/NewsAndMagazineCreateView.blade.php
- [ ] Create resources/views/OER/OERListView.blade.php
- [ ] Create resources/views/OER/OERCreateView.blade.php

### 3. Update Routes
- [ ] Add CRUD routes for ebooks, news&magazine, and OER in routes/web.php

### 4. Update Dashboard
- [ ] Add "Resource Management" section in super-admin dashboard with links to manage each resource type

### 5. Testing
- [ ] Test all CRUD operations for each resource type
- [ ] Verify super admin access and permissions
- [ ] Check DataTables functionality and search/filter
