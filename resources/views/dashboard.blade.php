@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mb-5">
        <h4 style="margin-top:70px;color: #1e1e1e;font-size: 28px;font-weight: 400;">Dashboard</h4>
        <div class="mt-4">
            <h5 style="color: #1E1E1E;font-size: 24px;font-weight: 400;">Grafik Penjualan OPPA BOX</h5>
            <form id="dateFilterForm" class="d-flex flex-column flex-md-row gap-3" style="height: 50px">
                <div class="dateFilter btn-group d-flex align-items-center" role="group" aria-label="Date Filter">
                    <input type="radio" id="mingguIni" name="dateFilter" value="mingguIni" class="btn-check" autocomplete="off" checked>
                    <label class="btn btn-outline-warning checked-label d-flex align-items-center" style="color: #1e1e1e;height:50px;" for="mingguIni">Minggu Ini</label>
        
                    <input type="radio" id="bulanIni" name="dateFilter" value="bulanIni" class="btn-check" autocomplete="off">
                    <label class="btn btn-outline-warning d-flex align-items-center" style="color: #1e1e1e;height:50px;" for="bulanIni">Bulan Ini</label>
        
                    <input type="radio" id="semua" name="dateFilter" value="semua" class="btn-check" autocomplete="off">
                    <label class="btn btn-outline-warning d-flex align-items-center" style="color: #1e1e1e;height:50px;" for="semua">Semua</label>
                </div>
            </form>
            <canvas class="mt-2" id="myNewChart" width="400" height="150"></canvas>
        </div>
        <div class="mt-5 d-flex flex-wrap gap-4">
            <a href="/admin/pesanan" class="btn d-flex flex-column justify-content-center align-items-center" style="height: 150px;width:100%; max-width:300px;border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                <i class="bi bi-clipboard" style="font-size: 48px"></i>
                <span class="text-center" style="color: #1E1E1E;font-size: 20px;font-weight: 400;">Lihat Pesanan</span>
            </a>
            <a href="/admin/laporan" class="btn d-flex flex-column justify-content-center align-items-center" style="height: 150px;width:100%; max-width:300px;border-radius: 20px;background: #FFF;box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.25);">
                <i class="bi bi-journal-bookmark" style="font-size: 48px"></i>
                <span class="text-center" style="color: #1E1E1E;font-size: 20px;font-weight: 400;">Lihat Laporan</span>
            </a>
        </div>
    </div>

    @push('js')
        <script>
            $(function () {
                const token = localStorage.getItem('token');
                const defaultDateFilter = 'mingguIni';
                const ctx = document.getElementById('myNewChart').getContext('2d');
                let myNewChart;

                function generateDateParams(dateFilter) {
                    const today = new Date();
                    let dari, sampai;

                    if (dateFilter === 'mingguIni') {
                        const dayOfWeek = today.getDay();
                        const daysUntilMonday = (dayOfWeek === 0 ? 6 : dayOfWeek - 1);
                        dari = new Date(today);
                        dari.setDate(today.getDate() - daysUntilMonday); 
                        sampai = new Date(today);
                        sampai.setDate(dari.getDate() + 6);
                    } else if (dateFilter === 'bulanIni') {
                        dari = new Date(today.getFullYear(), today.getMonth(), 1);
                        sampai = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    } else if (dateFilter === 'semua') {
                        dari = new Date(today.getFullYear(), 0, 1);
                        sampai = new Date(today.getFullYear(), 11, 31);
                    }

                    return { dari: dari.toISOString().split('T')[0], sampai: sampai.toISOString().split('T')[0] };
                }

                function loadData(dateFilter) {
                    const dateParams = generateDateParams(dateFilter);
                    const apiUrl = `/api/reports?dari=${dateParams.dari}&sampai=${dateParams.sampai}`;

                    $.ajax({
                        url: apiUrl,
                        headers: {
                            "Authorization": "Bearer " + token
                        },
                        success: function ({ data, current_page, per_page, total, last_page }) {
                            const chartData = {
                                labels: data.map(item => item.nama_barang),
                                values: data.map(item => item.pendapatan),
                            };
                            if (!myNewChart) {
                                myNewChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: chartData.labels,
                                        datasets: [{
                                            label: 'Pendapatan',
                                            data: chartData.values,
                                            backgroundColor: 'rgba(255, 223, 0, 0.2)',
                                            borderColor: 'rgba(255, 223, 0, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        },
                                        responsive: true,
                                    }
                                });
                            } else {
                                myNewChart.data.labels = chartData.labels;
                                myNewChart.data.datasets[0].data = chartData.values;
                                myNewChart.update();
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                }

                function handleDateFilterChange() {
                    const dateFilter = $(this).val();
                    $('tbody').empty();
                    loadData(dateFilter);
                }

                loadData(defaultDateFilter);

                $('input[name=dateFilter]').change(handleDateFilterChange);

                $('.dateFilter').on('change', 'input[name="dateFilter"]', function () {
                    $('.dateFilter label').removeClass('checked-label');
                    if (this.checked) {
                        this.nextElementSibling.classList.add('checked-label');
                    }
                });
            });
        </script>
    @endpush
@endsection