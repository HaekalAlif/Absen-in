<div class="container mt-4">
    <h2>Buat Absensi</h2>

    <form action="{{ route('dosen.absen.store') }}" method="POST">
        @csrf
        
        <!-- Pilihan Kelas -->
        <div class="form-group">
            <label for="classroom">Pilih Kelas:</label>
            <select id="classroom" name="classroom_id" class="form-control">
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Pilihan Mahasiswa -->
        <div class="form-group">
            <label for="user">Pilih Mahasiswa:</label>
            <select id="user" name="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tanggal -->
        <div class="form-group">
            <label for="date">Tanggal:</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <!-- Jam Mulai -->
        <div class="form-group">
            <label for="start_time">Jam Mulai:</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
        </div>

        <!-- Jam Selesai -->
        <div class="form-group">
            <label for="end_time">Jam Selesai:</label>
            <input type="time" name="end_time" id="end_time" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Absensi</button>
    </form>
</div>