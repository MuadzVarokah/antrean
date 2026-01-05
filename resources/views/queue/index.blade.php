<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <title>Sistem Antrian</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
          integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

     <style>
          body {
               background: #EBF9FF;
               min-height: 100vh;
               font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          }

          .current-queue {
               font-size: 7rem;
               font-weight: bold;
               text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
               animation: pulse 2s infinite;
          }

          .last-queue {
               font-size: 7rem;
               font-weight: bold;
               text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
          }

          @keyframes pulse {

               0%,
               100% {
                    transform: scale(1);
               }

               50% {
                    transform: scale(1.05);
               }
          }

          .queue-list {
               min-height: 29.5rem;
               max-height: 31.4rem;
               overflow-y: auto;
          }

          .queue-item {
               padding: 1rem;
               margin: 0.5rem 0;
               border-radius: 10px;
               transition: all 0.3s;
          }

          .queue-item.waiting {
               background: #f8f9fa;
               border-left: 4px solid #ffc107;
          }

          .queue-item.active {
               background: #d4edda;
               border-left: 4px solid #28a745;
               font-weight: bold;
          }

          .queue-item.completed {
               background: #e2e3e5;
               border-left: 4px solid #6c757d;
               opacity: 0.7;
          }

          .queue-item.skipped {
               background: #f8d7da;
               border-left: 4px solid #dc3545;
               opacity: 0.7;
          }
     </style>
</head>

<body style="background-color: #EBF9FF">
     <!-- Navbar -->
     <nav class="navbar navbar-expand-lg" style="background-color: #EBF9FF">
          <div class="container-fluid d-flex justify-content-between">
               <a class="navbar-brand mb-0 h1 text-info" href="#"><i class="fas fa-hospital"></i>&nbsp;&nbsp;Sistem
                    Antrian</a>
               <a href="{{ route('admin.login') }}" class="btn btn-sm btn-info text-white" type="button">Login <i
                         class="fa-solid fa-arrow-right-to-bracket"></i></a>
          </div>
     </nav>

     <!-- Container -->
     <div class="container pt-4">
          {{-- <div class="text-center mb-5">
               <h1 class="text-muted fw-bold mb-2"><i class="fas fa-hospital"></i> Sistem Antrian</h1>
               <h5 class="text-muted">Ambil nomor antrian Anda dengan mudah</h5>
          </div> --}}

          <div class="row">
               <div class="col-lg-8 mb-4">
                    <div class="card card-body bg-white border-0 rounded-4 shadow p-3 mb-4">
                         <div class="queue-display text-center">
                              <h3 class="text-secondary mb-3">Nomor Antrian Aktif</h3>
                              <div class="current-queue text-info" id="currentQueue">-</div>
                              {{-- <h3 class="text-secondary mb-3">&nbsp;</h3> --}}
                         </div>
                    </div>

                    <div class="card card-body bg-white border-0 rounded-4 shadow p-3">
                         <div class="queue-display text-center">
                              <h3 class="text-secondary mb-3">Antrian Terakhir</h3>
                              <div class="last-queue text-muted" id="lastQueue">-</div>
                              <button class="btn btn-lg btn-info text-white btn-take-queue shadow w-100 mt-4" id="btnTakeQueue">
                                   <i class="fas fa-ticket"></i>&nbsp;&nbsp;<span style="font-weight: 600">Ambil Antrian</span>
                              </button>
                         </div>
                    </div>
               </div>

               <div class="col-lg-4 mb-4">
                    <div class="card card-body bg-white border-0 rounded-4 shadow p-3 h-100 mb-3">
                         <h4 class="text-secondary mb-3"><i class="fas fa-list"></i>&nbsp;&nbsp;Daftar Antrian Hari Ini</h4>
                         <div class="queue-list h-100" id="queueList">
                              <div class="text-center text-muted py-5">
                                   <i class="fas fa-spinner fa-spin fa-2x"></i>
                                   <p class="mt-2">Memuat data...</p>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <!-- Modal Success -->
     <div class="modal fade" id="successModal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                    <div class="modal-body text-center pt-4">
                         <i class="fas fa-check-circle fa-5x text-info mb-3"></i>
                         <h3 class="text-secondary mb-3">Nomor Antrian Anda</h3>
                         <div class="display-1 fw-bold text-info my-3" id="modalQueueNumber">-</div>
                         <p class="text-muted">Silakan tunggu panggilan antrian Anda</p>
                         <button type="button" class="btn btn-info text-white btn-lg w-100 mt-2" data-bs-dismiss="modal"><i class="fa-regular fa-thumbs-up"></i>&nbsp;&nbsp;OK</button>
                    </div>
               </div>
          </div>
     </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
     <script>
          const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

          // Take queue
          document.getElementById('btnTakeQueue').addEventListener('click', async function() {
               this.disabled = true;
               this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

               try {
                    const response = await fetch('/queue/take', {
                         method: 'POST',
                         headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': csrfToken
                         }
                    });

                    const data = await response.json();

                    if (data.success) {
                         document.getElementById('modalQueueNumber').textContent = data.queue_number;
                         new bootstrap.Modal(document.getElementById('successModal')).show();
                         loadQueueData();
                    }
               } catch (error) {
                    alert('Terjadi kesalahan: ' + error.message);
               } finally {
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-ticket"></i> Ambil Antrian';
               }
          });

          // Load queue data
          async function loadQueueData() {
               try {
                    const [currentRes, lastRes, listRes] = await Promise.all([
                         fetch('/queue/current'),
                         fetch('/queue/last'),
                         fetch('/queue/list')
                    ]);

                    const currentData = await currentRes.json();
                    const lastData = await lastRes.json();
                    const listData = await listRes.json();

                    // Update current queue
                    const currentQueueEl = document.getElementById('currentQueue');
                    if (currentData.queue) {
                         currentQueueEl.textContent = currentData.queue.queue_number;
                    } else {
                         currentQueueEl.textContent = '-';
                    }

                    // Update last queue
                    const lastQueueEl = document.getElementById('lastQueue');
                    if (lastData.queue) {
                         lastQueueEl.textContent = lastData.queue.queue_number;
                    } else {
                         lastQueueEl.textContent = '-';
                    }

                    // Update queue list
                    const queueListEl = document.getElementById('queueList');
                    if (listData.queues && listData.queues.length > 0) {
                         queueListEl.innerHTML = listData.queues.map(q => {
                         let statusBadge = '';
                         let statusText = '';

                         if (q.status === 'waiting') {
                              statusBadge = '<span class="badge bg-warning text-dark badge-status">Menunggu</span>';
                              statusText = 'waiting';
                         } else if (q.status === 'active') {
                              statusBadge = '<span class="badge bg-success badge-status">Sedang Dilayani</span>';
                              statusText = 'active';
                         } else if (q.status === 'skipped') {
                              statusBadge = '<span class="badge bg-danger badge-status">Dilewati</span>';
                              statusText = 'skipped';
                         } else {
                              statusBadge = '<span class="badge bg-secondary badge-status">Selesai</span>';
                              statusText = 'completed';
                         }

                         return `
                              <div class="queue-item ${statusText}">
                                   <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                             <h5 class="mb-1">${q.queue_number}</h5>
                                        </div>
                                        ${statusBadge}
                                   </div>
                              </div>
                         `;
                         }).join('');
                    } else {
                         queueListEl.innerHTML = '<div class="text-center text-muted py-3">Belum ada antrian</div>';
                    }
               } catch (error) {
                    console.error('Error loading queue data:', error);
               }
          }

          // Auto refresh every 3 seconds
          loadQueueData();
          setInterval(loadQueueData, 3000);
     </script>
</body>

</html>