document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('/students/chart-data'); 
        if (!response.ok) throw new Error('Failed to fetch chart data.');

        const data = await response.json();

        // Gender Distribution
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: data.gender.map(g => g.gender),
                datasets: [{
                    label: 'Gender Distribution',
                    data: data.gender.map(g => g.count),
                    backgroundColor: ['#007bff', '#dc3545']
                }]
            }
        });

        // Program Distribution
        const programCtx = document.getElementById('programChart').getContext('2d');
        new Chart(programCtx, {
            type: 'polarArea',
            data: {
                labels: data.program.map(p => p.program_code),
                datasets: [{
                    label: 'Program Distribution',
                    data: data.program.map(p => p.count),
                    backgroundColor: ['#ffc107', '#28a745', '#17a2b8']
                }]
            }
        });

        // Year Level Breakdown
        const yearCtx = document.getElementById('yearLevelChart').getContext('2d');
        new Chart(yearCtx, {
            type: 'doughnut',
            data: {
                labels: data.yearLevel.map(y => y.year_level + ' Year'),
                datasets: [{
                    label: 'Year Level Breakdown',
                    data: data.yearLevel.map(y => y.count),
                    backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107']
                }]
            }
        });

    } catch (error) {
        console.error('Error fetching chart data:', error);
        alert('Failed to load chart data.');
    }
    
});
