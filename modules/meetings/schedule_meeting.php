
<style>
/* ====== PAGE HEADER ====== */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 2px solid #e9ecef;
}

.page-title {
  display: flex;
  align-items: center;
  gap: 12px;
}

.page-title i {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #9c27b0, #e91e63);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 20px;
}

.page-title h2 {
  font-size: 24px;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 4px;
}

.page-title p {
  font-size: 14px;
  color: #6c757d;
}

/* ====== STATS CARDS ====== */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  padding: 20px;
  border-radius: 12px;
  border: 1px solid #e9ecef;
  display: flex;
  align-items: center;
  gap: 16px;
  transition: all 0.3s;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: white;
}

.stat-icon.blue { background: #4285f4; }
.stat-icon.green { background: #34a853; }
.stat-icon.orange { background: #fbbc04; }
.stat-icon.red { background: #ea4335; }

.stat-content h3 {
  font-size: 28px;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 4px;
}

.stat-content p {
  font-size: 13px;
  color: #6c757d;
}

/* ====== FILTER SECTION ====== */
.filters {
  background: white;
  padding: 20px;
  border-radius: 12px;
  border: 1px solid #e9ecef;
  margin-bottom: 24px;
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
  align-items: center;
}

.filters label {
  font-size: 14px;
  font-weight: 600;
  color: #2c3e50;
}

.form-control {
  padding: 10px 16px;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  font-size: 14px;
}

/* ====== BUTTONS ====== */
.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-primary {
  background: linear-gradient(135deg, #4285f4, #34a853);
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-sm {
  padding: 6px 12px;
  font-size: 13px;
}

/* ====== TABLES ====== */
.report-card {
  background: white;
  border-radius: 12px;
  border: 1px solid #e9ecef;
  overflow: hidden;
  margin-bottom: 24px;
}

.card-header {
  padding: 20px 24px;
  background: #f8f9fa;
  border-bottom: 1px solid #e9ecef;
}

.card-title {
  font-size: 18px;
  font-weight: 600;
  color: #2c3e50;
  display: flex;
  align-items: center;
  gap: 10px;
}

.report-table {
  width: 100%;
  border-collapse: collapse;
}

.report-table thead {
  background: #f8f9fa;
}

.report-table th {
  padding: 16px 24px;
  text-align: left;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  color: #6c757d;
  border-bottom: 2px solid #e9ecef;
}

.report-table td {
  padding: 16px 24px;
  border-bottom: 1px solid #f8f9fa;
  font-size: 14px;
}

.report-table tbody tr:hover {
  background: #f8f9fa;
}

/* ====== BADGES ====== */
.badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.badge-success { background: #d4edda; color: #155724; }
.badge-warning { background: #fff3cd; color: #856404; }
.badge-danger { background: #f8d7da; color: #721c24; }

/* ====== TABS ====== */
.tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}

.tab-btn {
  padding: 12px 24px;
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  color: #6c757d;
}

.tab-btn.active {
  background: #4285f4;
  color: white;
  border-color: #4285f4;
}

.tab-content {
  display: none;
}

.tab-content.active {
  display: block;
}

/* ====== MODAL (For Schedule Meeting Button) ====== */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: white;
  padding: 24px;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e9ecef;
  padding-bottom: 10px;
  margin-bottom: 16px;
}

.modal-header h3 {
  font-size: 20px;
  font-weight: 700;
  color: #2c3e50;
}

.close-btn {
  font-size: 20px;
  cursor: pointer;
  color: #6c757d;
}

.modal-body label {
  display: block;
  margin-top: 12px;
  font-weight: 600;
  font-size: 14px;
  color: #2c3e50;
}

.modal-body input,
.modal-body select,
.modal-body textarea {
  width: 100%;
  margin-top: 8px;
  padding: 10px 14px;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  font-size: 14px;
}

/* ====== RESPONSIVE ====== */
@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }

  .filters {
    flex-direction: column;
    align-items: stretch;
  }

  .filters > * {
    width: 100%;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
<div class="page-header">
  <div class="page-title">
    <i class="fas fa-video"></i>
    <div>
      <h2>Parent–Teacher Meetings</h2>
      <p>Schedule, manage, and track parent-teacher meetings</p>
    </div>
  </div>
  <button class="btn btn-primary">
    <i class="fas fa-plus"></i> Schedule Meeting
  </button>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
    <div class="stat-content">
      <h3>12</h3>
      <p>Upcoming Meetings</p>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon green"><i class="fas fa-users"></i></div>
    <div class="stat-content">
      <h3>45</h3>
      <p>Total Meetings</p>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
    <div class="stat-content">
      <h3>5</h3>
      <p>Pending Approvals</p>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="filters">
  <label for="filter-date">Date:</label>
  <input type="date" id="filter-date" class="form-control">

  <label for="filter-status">Status:</label>
  <select id="filter-status" class="form-control">
    <option value="">All</option>
    <option value="scheduled">Scheduled</option>
    <option value="completed">Completed</option>
    <option value="cancelled">Cancelled</option>
  </select>

  <button class="btn btn-primary"><i class="fas fa-filter"></i> Apply Filter</button>
</div>

<!-- Tabs -->
<div class="tabs">
  <button class="tab-btn active" onclick="showTab('upcoming')">Upcoming</button>
  <button class="tab-btn" onclick="showTab('completed')">Completed</button>
  <button class="tab-btn" onclick="showTab('cancelled')">Cancelled</button>
</div>

<!-- Upcoming Meetings Table -->
<div id="upcoming" class="tab-content active">
  <div class="report-card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-calendar-day"></i> Upcoming Meetings</div>
    </div>
    <table class="report-table">
      <thead>
        <tr>
          <th>Parent Name</th>
          <th>Student</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Mrs. Ali</td>
          <td>Ayaan Ali</td>
          <td>2025-10-15</td>
          <td>10:00 AM</td>
          <td><span class="badge badge-success">Confirmed</span></td>
          <td><button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</button></td>
        </tr>
        <tr>
          <td>Mr. Khan</td>
          <td>Hina Khan</td>
          <td>2025-10-16</td>
          <td>11:30 AM</td>
          <td><span class="badge badge-warning">Pending</span></td>
          <td><button class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</button></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Completed Meetings -->
<div id="completed" class="tab-content">
  <div class="report-card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-check-circle"></i> Completed Meetings</div>
    </div>
    <table class="report-table">
      <thead>
        <tr>
          <th>Parent Name</th>
          <th>Student</th>
          <th>Date</th>
          <th>Time</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Mrs. Ahmed</td>
          <td>Bilal Ahmed</td>
          <td>2025-10-10</td>
          <td>9:30 AM</td>
          <td>Excellent progress</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Cancelled Meetings -->
<div id="cancelled" class="tab-content">
  <div class="report-card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-ban"></i> Cancelled Meetings</div>
    </div>
    <table class="report-table">
      <thead>
        <tr>
          <th>Parent Name</th>
          <th>Student</th>
          <th>Date</th>
          <th>Reason</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Mr. Farooq</td>
          <td>Sana Farooq</td>
          <td>2025-10-08</td>
          <td>Parent unavailable</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script>
function showTab(tabId) {
  document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
  document.querySelector(`[onclick="showTab('${tabId}')"]`).classList.add('active');
  document.getElementById(tabId).classList.add('active');
}
</script>
