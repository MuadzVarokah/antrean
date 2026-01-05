<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <title>Admin Dashboard - Sistem Antrian</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
          integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

     <style>
          body {
               background-color: #EBF9FF;
               min-height: 100vh;
               font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          }

          .current-queue {
               font-size: 8rem;
               font-weight: bold;
               text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
               animation: pulse 2s infinite;
          }

          @keyframes pulse {
               0%,
               100% {transform: scale(1);}
               50% {transform: scale(1.05);}
          }

          .stat-icon {
               width: 50px;
               height: 50px;
               border-radius: 50%;
               display: flex;
               align-items: center;
               justify-content: center;
               font-size: 1.5rem;
          }

          .queue-table {
               max-height: calc(100vh - 10rem);
               overflow-y: auto;
          }
     </style>
</head>

<body>
     <!-- Navbar -->
     <nav class="navbar navbar-expand-lg" style="background-color: #EBF9FF">
          <div class="container-fluid d-flex justify-content-between">
               <a class="navbar-brand mb-0 h1 text-info" href="#"><i class="fas fa-hospital"></i>&nbsp;&nbsp;Sistem Antrian</a>
               <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-dark" type="button">Logout <i class="fa-solid fa-arrow-right-from-bracket"></i></button>
               </form>
          </div>
     </nav>

     <div class="container-fluid mt-2">
          <div class="row">
               <div class="col-lg-6 mb-4">
                    <div class="card card-body bg-white border-0 rounded-4 shadow p-3">
                         <div class="queue-display text-center">
                              <h3 class="text-secondary mb-3">Nomor Antrian Aktif</h3>
                              <div class="current-queue text-info" id="currentQueue">A001</div>
                              <div class="row mt-4">
                                   <div class="col-6">
                                        <button class="btn btn-lg btn-info text-white btn-take-queue shadow w-100" id="btnPrev">
                                             <i class="fa-solid fa-backward-step"></i>&nbsp;&nbsp;<span style="font-weight: 600">Antrian Sebelumnya</span>
                                        </button>
                                   </div>
                                   <div class="col-6">
                                        <button class="btn btn-lg btn-info text-white btn-take-queue shadow w-100" id="btnNext">
                                             <i class="fa-solid fa-forward-step"></i>&nbsp;&nbsp;<span style="font-weight: 600">Antrian Selanjutnya</span>
                                        </button>
                                   </div>
                              </div>
                              <hr>
                              <div class="row">
                                   <div class="col-6">
                                        <button class="btn btn-lg btn-danger btn-take-queue shadow w-100" id="btnSkip">
                                             <i class="fa-solid fa-forward-fast"></i>&nbsp;&nbsp;<span style="font-weight: 600">Lewati Antrian</span>
                                        </button>
                                   </div>
                                   <div class="col-6">
                                        <button class="btn btn-lg btn-success btn-take-queue shadow w-100" id="btnComplete">
                                             <i class="fa-solid fa-circle-check"></i>&nbsp;&nbsp;<span style="font-weight: 600">Selesaikan Antrian</span>
                                        </button>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <div class="col-sm-4 col-6 mt-4">
                              <div class="card card-body bg-white border-0 rounded-4 shadow p-3">
                                   <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-warning bg-opacity-25 text-warning me-3">
                                             <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                             <h6 class="mb-0 text-muted">Menunggu</h6>
                                             <h4 class="mb-0" id="waitingCount">0</h4>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-sm-4 col-6 mt-4">
                              <div class="card card-body bg-white border-0 rounded-4 shadow p-3">
                                   <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-success bg-opacity-25 text-success me-3">
                                             <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div>
                                             <h6 class="mb-0 text-muted">Selesai</h6>
                                             <h4 class="mb-0" id="completedCount">0</h4>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-sm-4 col-6 mt-4">
                              <div class="card card-body bg-white border-0 rounded-4 shadow p-3">
                                   <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-danger bg-opacity-25 text-danger me-3">
                                             <i class="fa-solid fa-circle-xmark"></i>
                                        </div>
                                        <div>
                                             <h6 class="mb-0 text-muted">Dilewati</h6>
                                             <h4 class="mb-0" id="skippedCount">0</h4>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="col-lg-6 mb-4">
                    <div class="card card-body bg-white border-0 rounded-4 shadow p-3">
                         <h5 class="card-title mb-3">
                              <i class="fas fa-list"></i> Daftar Antrian Hari Ini
                         </h5>
                         <div class="queue-table">
                              <table class="table table-hover">
                                   <thead class="table-light sticky-top">
                                        <tr>
                                             <th>#</th>
                                             <th>Nomor Antrian</th>
                                             <th>Waktu</th>
                                             <th>Status</th>
                                             <th>Aksi</th>
                                        </tr>
                                   </thead>
                                   <tbody id="queueTableBody">
                                        <tr>
                                             <td colspan="4" class="text-center py-4">
                                                  <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                                                  <p class="mt-2 text-muted">Memuat data...</p>
                                             </td>
                                        </tr>
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
     <script>
          const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

          // Load queue data
          async function loadQueueData() {
               if (document.querySelector('.modal.show')) {
                    return;
               }

               try {
                    const response = await fetch('/admin/queue/data');
                    const data = await response.json();

                    if (data.success) {
                         // Update current queue
                         const currentQueueEl = document.getElementById('currentQueue');
                         if (data.active_queue) {
                         currentQueueEl.textContent = data.active_queue.queue_number;
                         } else {
                         currentQueueEl.textContent = '-';
                         }

                         // Update stats
                         document.getElementById('waitingCount').textContent = data.waiting_count;
                         document.getElementById('completedCount').textContent = data.completed_count;
                         document.getElementById('skippedCount').textContent = data.skipped_count;

                         // Update table
                         const tbody = document.getElementById('queueTableBody');
                         if (data.queues.length > 0) {
                         tbody.innerHTML = data.queues.map((q, index) => {
                              let badgeClass = '';
                              let badgeText = '';

                              if (q.status === 'waiting') {
                                   badgeClass = 'bg-warning text-dark';
                                   badgeText = 'Menunggu';
                                   button = '';
                              } else if (q.status === 'active') {
                                   badgeClass = 'bg-success';
                                   badgeText = 'Sedang Dilayani';
                                   button = '';
                              } else if (q.status === 'skipped') {
                                   badgeClass = 'bg-danger';
                                   badgeText = 'Dilewati';
                                   button = `
                                        <button class="btn btn-sm btn-info text-white"
                                             data-bs-toggle="modal"
                                             data-bs-target="#btnJump${q.id}">
                                             <i class="fa-solid fa-arrow-rotate-left"></i>
                                        </button>

                                        <div class="modal fade" id="btnJump${q.id}" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                                             <div class="modal-dialog modal-dialog-centered">
                                                  <div class="modal-content">
                                                       <div class="modal-body text-center pt-4">
                                                            <i class="fa-solid fa-circle-question fa-5x text-info mb-3"></i>
                                                            <h3 class="text-secondary mb-3">Status antrian saat ini?</h3>

                                                            <div class="row">
                                                                 <div class="col-6">
                                                                      <button class="btn btn-danger btn-lg w-100 mt-2"
                                                                           onclick="jumpQueue(${q.id}, 'skipped')">
                                                                           <i class="fa-solid fa-forward-fast"></i> Lewati
                                                                      </button>
                                                                 </div>

                                                                 <div class="col-6">
                                                                      <button class="btn btn-success btn-lg w-100 mt-2"
                                                                           onclick="jumpQueue(${q.id}, 'completed')">
                                                                           <i class="fa-solid fa-circle-check"></i> Selesaikan
                                                                      </button>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        `;
                              } else {
                                   badgeClass = 'bg-secondary';
                                   badgeText = 'Selesai';
                                   button = '';
                              }
     
                              const time = new Date(q.created_at).toLocaleTimeString('id-ID');

                              return `
                                   <tr class="${q.status === 'active' ? 'table-success' : ''}">
                                        <td>${index + 1}</td>
                                        <td><strong>${q.queue_number}</strong></td>
                                        <td>${time.replace(/\./g, ':')}</td>
                                        <td><span class="badge ${badgeClass} badge-lg">${badgeText}</span></td>
                                        <td>${button}</td>
                                   </tr>
                              `;
                         }).join('');
                         } else {
                         tbody.innerHTML = '<tr><td colspan="4" class="text-center py-3">Belum ada antrian</td></tr>';
                         }
                    }
               } catch (error) {
                    console.error('Error loading queue data:', error);
               }
          }

          // Next queue
          document.getElementById('btnNext').addEventListener('click', async function() {
               this.disabled = true;
               try {
                    const response = await fetch('/admin/queue/next', {
                         method: 'POST',
                         headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': csrfToken
                         }
                    });
                    const data = await response.json();
                    alert(data.message);
                    loadQueueData();
               } catch (error) {
                    alert('Error: ' + error.message);
               } finally {
                    this.disabled = false;
               }
          });

          // Previous queue
          document.getElementById('btnPrev').addEventListener('click', async function() {
               this.disabled = true;
               try {
                    const response = await fetch('/admin/queue/prev', {
                         method: 'POST',
                         headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': csrfToken
                         }
                    });
                    const data = await response.json();
                    alert(data.message);
                    loadQueueData();
               } catch (error) {
                    alert('Error: ' + error.message);
               } finally {
                    this.disabled = false;
               }
          });

          // Complete queue
          document.getElementById('btnComplete').addEventListener('click', async function() {
               this.disabled = true;
               try {
                    const response = await fetch('/admin/queue/complete', {
                         method: 'POST',
                         headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': csrfToken
                         }
                    });
                    const data = await response.json();
                    alert(data.message);
                    loadQueueData();
               } catch (error) {
                    alert('Error: ' + error.message);
               } finally {
                    this.disabled = false;
               }
          });

          // Skip queue
          document.getElementById('btnSkip').addEventListener('click', async function() {
               this.disabled = true;
               try {
                    const response = await fetch('/admin/queue/skip', {
                         method: 'POST',
                         headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': csrfToken
                         }
                    });
                    const data = await response.json();
                    alert(data.message);
                    loadQueueData();
               } catch (error) {
                    alert('Error: ' + error.message);
               } finally {
                    this.disabled = false;
               }
          });

          function jumpQueue(id, prevStatus) {
               fetch('/admin/queue/jump', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': csrfToken,
                         'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id, prevStatus })
               })
               .then(res => {
                    if (!res.ok) throw new Error('Network error');
                    return res.json();
               })
               .then(() => {
                    document.activeElement.blur();

                    const modalEl = document.getElementById(`btnJump${id}`);
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();

                    loadQueueData();
               })
               .catch(err => console.error(err));
          }

          // Auto refresh every 3 seconds
          loadQueueData();
          setInterval(loadQueueData, 3000);
     </script>
</body>

</html>