<!-- <div
    x-show="status === '4'"
    x-transition.duration.300ms
    class="border rounded-3 p-3 mt-4 bg-light"
> -->
    <h5 class="font-weight-bolder mb-4">
        <i class="fas fa-graduation-cap me-2 text-primary"></i>
        Pertanyaan Lanjutan (Melanjutkan Pendidikan)
    </h5>
    <p class="text-sm text-muted mb-4">
        Mohon isi detail pendidikan lanjutan Anda pada kolom yang telah
        disediakan di bawah ini.
    </p>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label for="f18a" class="form-label">Sumber Biaya</label>
                <div class="input-group">
                    <span class="input-group-text"
                        ><i class="fas fa-wallet"></i
                    ></span>
                    <input
                        type="text"
                        id="f18a"
                        name="f18a"
                        value="{{ old('f18a', $answer->f18a ?? '') }}"
                        class="form-control"
                    />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label for="f18b" class="form-label">Perguruan Tinggi</label>
                <div class="input-group">
                    <span class="input-group-text"
                        ><i class="fas fa-university"></i
                    ></span>
                    <input
                        type="text"
                        id="f18b"
                        name="f18b"
                        value="{{ old('f18b', $answer->f18b ?? '') }}"
                        class="form-control"
                    />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label for="f18c" class="form-label">Program Studi</label>
                <div class="input-group">
                    <span class="input-group-text"
                        ><i class="fas fa-book-open"></i
                    ></span>
                    <input
                        type="text"
                        id="f18c"
                        name="f18c"
                        value="{{ old('f18c', $answer->f18c ?? '') }}"
                        class="form-control"
                    />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label for="f18d" class="form-label">Tanggal Masuk</label>
                <div class="input-group">
                    <span class="input-group-text"
                        ><i class="fas fa-calendar-alt"></i
                    ></span>
                    <input
                        type="date"
                        id="f18d"
                        name="f18d"
                        value="{{ old('f18d', $answer->f18d ?? '') }}"
                        class="form-control"
                    />
                </div>
            </div>
        </div>
    </div>
</div>
