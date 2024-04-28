@extends('layout.main')
@section('content')
<div class="p-4">
    <h2 class="text-center">Tambah Data</h2>

    <div class="col-md-4 mx-auto mt-3">
        <div class="card">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-12">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title">
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
                    <div class="col-12">
                        <button type="button" class="btn btn-primary float-end" onclick="store()">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    async function store() {

        const title = $('#title').val()
        const deskripsi = $('#deskripsi').val()
        const total = $('#total').val()
        const date = $('#date').val()

        const data = {
            title: title,
            description: deskripsi,
            total: total,
            date: date
        }

        await axios.post('/api/expense/create', data, {
            headers: {
                'Authorization': `Bearer ${$.cookie('token')}`
            }
        }).then(({
            data
        }) => {
            Swal.fire({
                icon: "success",
                text: data.message,
            });
            setTimeout(() => {
                window.location.href = '/home'
            }, 1500);
        })
    }
</script>

@endsection()