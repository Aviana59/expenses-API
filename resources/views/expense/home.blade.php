@extends('layout.main')
@section('content')
<div class="p-4">
    <h1>Catatan Pengeluaran Saya</h1>
    <div class="mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-sm">start</span>
                            <input type="date" id="start" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                        </div>

                    </div>
                    <div class="col-md-5">
                        <div class="input-group input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-sm">end</span>
                            <input type="date" id="end" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                        </div>


                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary" onclick="filter()">Filter <i class="fa-solid fa-filter"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-4"><a href="/add" class="btn btn-success float-end mb-2">Tambah data <i class="fa-solid fa-plus"></i></a></div>
        </div>

        <table class="table table-bordered table-hover">
            <thead class="table-secondary text-center">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3">
                    <div class="col-md-12">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title">
                        <input type="hidden" class="form-control" id="id">
                    </div>
                    <div class="col-md-12">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" placeholder="Masukan Deskripsi disini" name="deskripsi" id="deskripsi" cols="20" rows="5"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="total" class="form-label">Total</label>
                        <input type="text" class="form-control" id="total">
                    </div>
                    <div class="col-12">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="date">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="save()">Save changes</button>
                    </div>
            </div>
        </div>
    </div>

    <script>
        async function loadData(start = null, end = null) {
            let str = ''
            let tableBody = $('#tableBody')
            const {
                data
            } = (start === null && end === null) ? await axios.post('/api/expense'): await axios.post('/api/expense', {
                start: start,
                end: end
            })

            const items = data.data
            if (data.data.length > 0) {
                for (let i = 0; i < items.length; i++) {
                    str += '<tr>'
                    str += `<td class="text-center">${i+1}</td>` +
                        `<td class="text-center">${items[i].title}</td>` +
                        `<td class="text-center">${items[i].description}</td>` +
                        `<td class="text-center">${items[i].total}</td>` +
                        `<td class="text-center">${items[i].date}</td>` +
                        `<td class="text-center">
                        <button class="btn btn-sm btn-primary"><i class="fa-solid fa-pencil" onclick="show(${items[i].id})"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can" onclick="remove(${items[i].id})"></i></button>
                    </td>`
                }

                tableBody.html(str)
            } else {
                tableBody.html('')
            }
        }

        async function show(id) {
            const myModal = new bootstrap.Modal('#myModal', {
                keyboard: false
            })
            myModal.show()

            const {
                data
            } = await axios.get(`/api/expense/show/${id}`, {
                headers: {
                    'Authorization': `Bearer ${$.cookie('token')}`
                }
            })
            if (data.data.length) {
                const items = data.data[0]
                $('#title').val(items.title)
                $('#deskripsi').html(items.description)
                $('#total').val(items.total)
                $('#date').val(items.date)
                $('#id').val(items.id)

            }

        }


        async function save() {
            const title = $('#title').val()
            const description = $('#deskripsi').val()
            const total = $('#total').val()
            const date = $('#date').val()
            const id = $('#id').val()

            const body = {
                title,
                description,
                total,
                date
            }

            const {
                data
            } = await axios.put(`/api/expense/update/${id}`, body, {
                headers: {
                    'Authorization': `Bearer ${$.cookie('token')}`
                }
            })

            if (data.message === 'success') {
                Swal.fire({
                    title: data.message,
                    icon: 'success',
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: "Oke",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        loadData()
                    }
                });

            }
        }

        async function remove(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ini akan menghapus data ini secara permanen',
                icon: 'question',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: "Hapus!",
            }).then(async (result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    const {
                        data
                    } = await axios.delete(`/api/expense/delete/${id}`, {
                        headers: {
                            'Authorization': `Bearer ${$.cookie('token')}`
                        }
                    })

                    if (data.message === 'success') {
                        Swal.fire({
                            title: data.message,
                            icon: 'success',
                            showDenyButton: false,
                            showCancelButton: false,
                            confirmButtonText: "Oke",
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                loadData()
                            }
                        });
                    }
                }
            });

        }

        function filter() {
            const start = $('#start').val()
            const end = $('#end').val()
            loadData(start, end)
        }

        $(document).ready(async function() {
            loadData();
        })
    </script>
    @endsection()