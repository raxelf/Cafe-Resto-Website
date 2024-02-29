@extends('layout.app')

@section('title', 'Laporan')

@section('content')
    <div class="container mb-5">
        <h4 style="margin-top:70px;color: #1e1e1e;font-size: 28px;font-weight: 400;">Laporan</h4>
    
        <div class="mt-4">
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
        </div>

        <div class="mt-4 h-100">
            <div id="kategori-table" class="table-responsive">
                <table class="w-100">
                    <thead class="text-center">
                        <tr>
                            <th style="border-radius: 20px 0 0 0">No</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah Order</th>
                            <th>Pendapatan</th>
                            <th style="border-radius: 0 20px 0 0">Total Qty</th>
                        </tr>
                    </thead>
                    <tbody style="border: 1px solid #1e1e1e;">
                        
                    </tbody>
                </table>
            </div>
            <div class="w-100" style="border-radius: 0 0 20px 20px;background: #FFF; border-left: 1px solid #1e1e1e;border-right: 1px solid #1e1e1e;border-bottom: 1px solid #1e1e1e;">
                <div class="w-100 d-flex justify-content-center gap-3 p-4 align-items-center pagination">
                    
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            function rupiah(angka) {
                const format = angka.toString().split('').reverse().join('');
                const convert = format.match(/\d{1,3}/g);
                return 'Rp ' + convert.join('.').split('').reverse().join('');
            }

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

            $(function(){
                const token = localStorage.getItem('token');
                
                const defaultDateFilter = 'mingguIni';

                function loadData(dateFilter) {
                    const dateParams = generateDateParams(dateFilter);
                    let apiUrl = `/api/reports?dari=${dateParams.dari}&sampai=${dateParams.sampai}`;

                    $.ajax({
                        url: apiUrl,
                        headers: {
                            "Authorization": "Bearer " + token
                        },
                        success: function({ data, current_page, per_page, total, last_page }) {
                            let rows = '';

                            if (data.length === 0) {
                                row = `
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: #1e1e1e; font-size: 18px; font-weight: 400;">
                                            Belum ada laporan.
                                        </td>
                                    </tr>
                                `;
                            } else {
                                data.forEach(function(val, index) {
                                    rows += `
                                        <tr>
                                            <td>${index+1}</td>    
                                            <td>${val.nama_barang}</td>    
                                            <td>${rupiah(val.harga)}</td>    
                                            <td>${val.jumlah_dibeli}</td>    
                                            <td>${rupiah(val.pendapatan)}</td>    
                                            <td>${val.total_qty}</td>    
                                        </tr>
                                    `;
                                });

                                $('tbody').append(rows);

                                $('.pagination').html(`
                                    <div class="w-100 d-flex justify-content-center gap-3 align-items-center">
                                        <button type="button" class="btn" onclick="loadPage(${current_page - 1}, '${dateFilter}')">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                        <span style="color: #1E1E1E;font-size: 14px;font-weight: 400;">
                                            Page ${current_page} of ${last_page}
                                        </span>
                                        <button type="button" class="btn" onclick="loadPage(${current_page + 1}, '${dateFilter}')">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>
                                `);
                            }
                        }
                    });
                }

                loadData(defaultDateFilter);

                $('input[name=dateFilter]').change(function() {
                    const dateFilter = $(this).val();
                    $('tbody').empty();
                    loadData(dateFilter);
                });
            });

            function loadPage(page, dateFilter) {
                const token = localStorage.getItem('token');

                const dateParams = generateDateParams(dateFilter);
                $.ajax({
                    url: `/api/reports?dari=${dateParams.dari}&sampai=${dateParams.sampai}&page=` + page,
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    success: function ({ data, current_page, per_page, total, last_page }) {
                        if (page < 1 || page > last_page) {
                            return;
                        }
                        let row;

                        if (data.length === 0) {
                            row = `
                            <tr>
                                <td colspan="6" style="text-align: center; color: #1e1e1e; font-size: 18px; font-weight: 400;">
                                    Belum ada laporan.
                                </td>
                            </tr>`;
                        } else {
                            data.forEach(function(val, index) {
                                row += `
                                    <tr>
                                        <td>${(page - 1) * per_page + index + 1}</td>    
                                        <td>${val.nama_barang}</td>    
                                        <td>${rupiah(val.harga)}</td>    
                                        <td>${val.jumlah_dibeli}</td>    
                                        <td>${rupiah(val.pendapatan)}</td>    
                                        <td>${val.total_qty}</td>    
                                    </tr>
                                `;
                            });
                        }

                        $('tbody').html(row);

                        $('.pagination').html(`
                            <div class="w-100 d-flex justify-content-center gap-3 align-items-center">
                                <button type="button" class="btn" onclick="loadPage(${page - 1}, '${dateFilter}')">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <span style="color: #1E1E1E;font-size: 14px;font-weight: 400;">
                                    Page ${page} of ${last_page}
                                </span>
                                <button type="button" class="btn" onclick="loadPage(${page + 1}, '${dateFilter}')">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        `);
                    }
                });
            }

            document.querySelectorAll('input[name="dateFilter"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    document.querySelectorAll('.dateFilter label').forEach(function (label) {
                        label.classList.remove('checked-label');
                    });

                    if (this.checked) {
                        this.nextElementSibling.classList.add('checked-label');
                    }
                });
            });
        </script>
    @endpush
@endsection