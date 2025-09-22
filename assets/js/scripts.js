// Student search
document.addEventListener('DOMContentLoaded', () => {
  const studentSearch = document.getElementById('studentSearch');
  const studentRows = document.querySelectorAll('#studentTable tbody tr');

  if (studentSearch) {
    studentSearch.addEventListener('keyup', () => {
      const searchTerm = studentSearch.value.toLowerCase();
      studentRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });
  }

  // Fee search & filter
  const feeSearch = document.getElementById('feeSearch');
  const statusFilter = document.getElementById('statusFilter');
  const feeRows = document.querySelectorAll('#feeTable tbody tr');

  function filterFees() {
    const search = feeSearch.value.toLowerCase();
    const status = statusFilter.value;

    feeRows.forEach(row => {
      const rowText = row.textContent.toLowerCase();
      const rowStatus = row.cells[4].textContent.trim();
      const matchSearch = rowText.includes(search);
      const matchStatus = !status || rowStatus === status;

      row.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
  }

  if (feeSearch && statusFilter) {
    feeSearch.addEventListener('keyup', filterFees);
    statusFilter.addEventListener('change', filterFees);
  }
});
